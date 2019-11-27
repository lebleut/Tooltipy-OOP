<?php
namespace Tooltipy;

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.linkedin.com/in/jamel-eddine-zarga-56336485
 * @since      4.0.0
 *
 * @package    Tooltipy
 * @subpackage Tooltipy/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Tooltipy
 * @subpackage Tooltipy/public
 * @author     Jamel Eddine Zarga <jamel.zarga@gmail.com>
 */

class Tooltipy_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    4.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    4.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    4.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		add_filter( 'the_content', array($this, 'filter_content') );
	}

	/**
	 * Renders the required tooltips to be related to the keyword
	 */
	public function tooltips_section(){
		global $post_type;

		if( Tooltipy::get_plugin_name() == $post_type ){
			return false;
		}
		// Tooltipy settings
		$tooltip_mode = tooltipy_get_option( 'tooltip_mode' );

		// Don't load popups if 'title' or 'link' tooltip mode are picked from the settings
		// Load only for 'standard' & 'icon' modes
		if( in_array( $tooltip_mode, array( 'title', 'link' ) ) ){
			return false;
		}
		
		// Current post meta data
		$exclude_me 		= get_post_meta( get_the_id(), 'tltpy_exclude_me', true );
		$matched_tooltips 	= get_post_meta( get_the_id(), 'tltpy_matched_tooltips', true );
		$exclude_tooltips	= get_post_meta( get_the_id(), 'tltpy_exclude_tooltips', true );

		$exclude_tooltips = explode( ',', $exclude_tooltips );
		$exclude_tooltips = array_map( 'trim', $exclude_tooltips );
		$exclude_tooltips = array_map( 'strtolower', $exclude_tooltips );

		if( empty( $matched_tooltips ) || $exclude_me ){
			return false;
		}
		foreach ( $matched_tooltips as $key => $tooltip ) {
			if( in_array( strtolower($tooltip['tooltip_title']), $exclude_tooltips ) ){
				unset( $matched_tooltips[$key] );
			}
		}

		if( empty( $matched_tooltips ) ){
			return false;
		}
		?>
		<div id="tooltipy-popups-wrapper" style="display:none;">
		<?php
		// HTML section
		$matched_ids = array_map( function( $elem ){
			return intval($elem['tooltip_id']);
		}, $matched_tooltips );

		$get_from_post_types = Tooltipy::get_from_post_types();

		$query = new \WP_Query(
			array(
			'post_type' => $get_from_post_types,
			'post__in' => $matched_ids
			)
		);
		if ( $query->have_posts() ) :
			while ( $query->have_posts() ) : $query->the_post();
				// Tooltips meta data
				$tt_synonyms			= get_post_meta( get_the_ID(), 'tltpy_synonyms', true);
				$tt_is_prefix			= get_post_meta( get_the_ID(), 'tltpy_is_prefix', true);
				$tt_is_case_sensitive	= get_post_meta( get_the_ID(), 'tltpy_case_sensitive', true);
				$tt_youtube_id		= get_post_meta( get_the_ID(), 'tltpy_youtube_id', true);
				
				$popup_classes = array(
					'tooltipy-pop',
					'tooltipy-pop-' . get_the_ID()
				);
				$tooltip_categories = wp_get_post_terms( get_the_ID(), Tooltipy::get_taxonomy(), array( "fields" => "ids" ) );
				
				foreach ($tooltip_categories as $key => $value) {
					array_push( $popup_classes, "tooltipy-pop-cat-".$value );
				}
				if($tt_is_case_sensitive){
					array_push( $popup_classes, 'tooltipy-pop-case-sensitive' );
				}

				if( !empty( trim( $tt_youtube_id ) ) ){
					array_push( $popup_classes, 'tooltipy-pop-youtube' );
				}
				if( $tt_is_prefix ){
					array_push( $popup_classes, 'tooltipy-pop-prefix' );
				}
				$popup_classes = apply_filters( 'tltpy_popup_classes', $popup_classes, get_the_ID() );
				?>
				<div id="tooltipy-pop-<?php echo get_the_ID(); ?>">
					<div class="<?php echo implode( ' ', $popup_classes ); ?>">
						<?php
						/**
						 * @Hook : tltpy_popup_sections
						 * The popup_sections stack action hook by which you can add any content to the tooltip popup template
						 * 
						 * Hooked :
						 * tltpy_popup_add_video_section	- 10
						 * tltpy_popup_add_main_section 	- 10
						 * tltpy_popup_add_synonyms_section - 10
						 */

						do_action( 'tltpy_popup_sections' );

						?>
					</div>
				</div>
				<?php
			endwhile;
			wp_reset_postdata();
		endif;
		?>
		</div>
		<?php
	}

	/**
	 *  The main filtering content of Tooltipy
	 */
	public function filter_content( $content ){
		global $post_type, $post;

		// Don't filter Tooltipy post types them selves
		if( Tooltipy::get_plugin_name() == $post_type ){
			return $content;
		}

		// Current post meta data
		$exclude_me 		= get_post_meta( get_the_id(), 'tltpy_exclude_me', true );
		$matched_tooltips 	= get_post_meta( get_the_id(), 'tltpy_matched_tooltips', true );
		$exclude_tooltips	= get_post_meta( get_the_id(), 'tltpy_exclude_tooltips', true );

		if( empty( $matched_tooltips || $exclude_me  ) ){
			return $content;
		}

		$exclude_tooltips = explode( ',', $exclude_tooltips );
		$exclude_tooltips = array_map( 'trim', $exclude_tooltips );
		$exclude_tooltips = array_map( 'strtolower', $exclude_tooltips );

		$patterns = array();
		$replacements = array();

		foreach ($matched_tooltips as $num => $tooltip) {
			if( in_array( strtolower($tooltip['tooltip_title']), $exclude_tooltips ) ){
				unset( $matched_tooltips[$num]);
			}
		}

		if( empty( $matched_tooltips ) ){
			return $content;
		}

		foreach ($matched_tooltips as $num => $tooltip) {
			$case_sensitive_modifier = 'i';

			$keyword_classes = array(
				'tooltipy-kw',
				'tooltipy-kw-'. $tooltip['tooltip_id'],
			);

			$tooltip_categories = wp_get_post_terms( $tooltip['tooltip_id'], Tooltipy::get_taxonomy(), array("fields" => "ids") );
			
			// Categories classes
			foreach ($tooltip_categories as $key => $value) {
				array_push( $keyword_classes, "tooltipy-kw-cat-".$value );
			}

			// custom classes
			$custom_classes = tooltipy_get_option( 'keyword_css_classes' );
			if( $custom_classes && trim($custom_classes) != '' ){
				array_push( $keyword_classes, trim( $custom_classes ) );
			}

			// Tooltipy settings
			$tooltip_mode = tooltipy_get_option( 'tooltip_mode' );

			// Tooltips meta data
			$tt_synonyms			= get_post_meta( $tooltip['tooltip_id'], 'tltpy_synonyms', true);
			$tt_is_prefix			= get_post_meta( $tooltip['tooltip_id'], 'tltpy_is_prefix', true);
			$tt_is_case_sensitive	= get_post_meta( $tooltip['tooltip_id'], 'tltpy_case_sensitive', true);
			$tt_youtube_id			= get_post_meta( $tooltip['tooltip_id'], 'tltpy_youtube_id', true);

			$tt_synonyms_arr = explode( '|', $tt_synonyms );
			$tt_synonyms_arr = array_map( 'trim', $tt_synonyms_arr );
			
			// Add main keyword to synonyms array
			array_push( $tt_synonyms_arr, $tooltip['tooltip_title']);

			if($tt_is_case_sensitive){
				$case_sensitive_modifier = '';
				
				array_push( $keyword_classes, 'tooltipy-kw-case-sensitive' );
			}

			if( !empty( trim( $tt_youtube_id ) ) ){
				array_push( $keyword_classes, 'tooltipy-kw-youtube' );
			}

			$tooltip_post = get_post($tooltip['tooltip_id']);

			$before = '(^|\s|\W)'; // Group 1 in regex $1
			$after = '($|\s|\W)'; // Group 3 in regex $3
			$inner_after = '';

			// If is prefix
			if( $tt_is_prefix ){
				$inner_after = '\w*';

				array_push( $keyword_classes, 'tooltipy-kw-prefix' );
			}

			$keyword_classes = apply_filters( 'tltpy_keyword_classes', $keyword_classes, $tooltip[ 'tooltip_id' ] );
			
			// Consider the main keyword and synonyms
			foreach ($tt_synonyms_arr as $synonym) {
				if( !empty( $synonym ) ){

					$data_tooltip_attr 	= 'data-tooltip="'.$tooltip['tooltip_id'].'"';

					array_push($patterns, '/' . $before . '('.$synonym . $inner_after . ')' . $after . '/'.$case_sensitive_modifier);

					// init replacement
					$replacement = '$1$2$3';

					switch ($tooltip_mode) {
						case 'standard':
							if( !in_array( 'tltpy_mode_standard', $keyword_classes ) ){
								$keyword_classes[] = 'tltpy_mode_standard';
							}

							$classes_attr = 'class="' . implode( ' ', $keyword_classes) . '"';
							$tooltip_attributes = array( $classes_attr, $data_tooltip_attr );
							$replacement = '$1<span ' . implode( ' ', $tooltip_attributes ) . '>$2</span>$3';
							break;
					
						case 'icon':
							if( !in_array( 'tltpy_mode_icon', $keyword_classes ) ){
								$keyword_classes[] = 'tltpy_mode_icon';
							}

							$classes_attr = 'class="' . implode( ' ', $keyword_classes) . '"';
							$tooltip_attributes = array( $classes_attr, $data_tooltip_attr );
							
							$bg_color = tooltipy_get_option( 'icon_background_color', false );
							if( empty( $bg_color ) ){
								$bg_color = "#27ae60";
							}
							
							$txt_color = tooltipy_get_option( 'icon_text_color', false );
							if( empty( $txt_color ) ){
								$txt_color = "#ffffff";
							}
							
							$icon = '<svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px"
								width="25"
								height="25"
								viewBox="0 0 252 252"
								style="fill:#000000;">

								<g fill="none" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal">
									<path d="M0,252v-252h252v252z" fill="none">
									</path>
									<g id="Layer_1">
										<g id="surface1_33_">
											<path fill="' . $bg_color . '" d="M231,126c0,57.981 -47.019,105 -105,105c-57.981,0 -105,-47.019 -105,-105c0,-57.981 47.019,-105 105,-105c57.981,0 105,47.019 105,105z">
											</path>
											<path  fill="' . $txt_color . '" d="M124.7295,163.51125c-3.81675,0 -6.972,1.2285 -9.43425,3.73275c-2.46225,2.48325 -3.69075,5.6385 -3.69075,9.47625c0,3.77475 1.2075,6.89325 3.612,9.35025c2.42025,2.46225 5.58075,3.69075 9.534,3.69075c3.9375,0 7.098,-1.2285 9.51825,-3.69075c2.39925,-2.46225 3.612,-5.58075 3.612,-9.35025c0,-3.8325 -1.2285,-6.993 -3.69075,-9.47625c-2.4465,-2.46225 -5.67,-3.73275 -9.4605,-3.73275z">
											</path>
											<path fill="' . $txt_color . '" d="M153.216,76.5765c-6.37875,-5.55975 -15.3195,-8.3265 -26.8485,-8.3265c-11.424,0 -20.44875,2.93475 -27.048,8.778c-6.25275,5.53875 -9.576,13.146 -9.9855,22.722h-0.084v5.25h23.625l0.042,-3.69075c0.105,-4.326 1.3755,-7.73325 3.79575,-10.19025c2.39925,-2.48325 5.6385,-3.71175 9.66,-3.71175c8.48925,0 12.7155,4.57275 12.7155,13.73925c0,3.0345 -0.798,5.92725 -2.44125,8.652c-1.62225,2.73 -4.9035,6.27375 -9.82275,10.64175c-4.9245,4.347 -8.3055,8.778 -10.1535,13.26675c-1.86375,4.48875 -2.78775,10.9515 -2.78775,18.53775h20.895l0.33075,-5.37075c0.59325,-5.313 2.95575,-9.92775 7.098,-13.88625l6.6255,-6.25275c5.145,-4.98225 8.778,-9.51825 10.83075,-13.5975c2.07375,-4.10025 3.1185,-8.44725 3.1185,-13.041c-0.0315,-10.12725 -3.20775,-17.96025 -9.5655,-23.52z">
											</path>
										</g>
									</g>
								</g>
							</svg>';
							
							$replacement = '$1<span ' . implode( ' ', $tooltip_attributes ) . '>' . $icon . '</span>$2$3';
							break;
					
						case 'title':
							// Tooltip content formatted for the title attrib
							$title_attr_content = $tooltip_post->post_content;
							$title_attr_content = esc_attr( wp_strip_all_tags( $title_attr_content ) );
							$title_attr = 'title="' . $title_attr_content . '"';

							if( !in_array( 'tltpy_mode_title', $keyword_classes ) ){
								$keyword_classes[] = 'tltpy_mode_title';
							}

							$classes_attr = 'class="' . implode( ' ', $keyword_classes) . '"';
							$tooltip_attributes = array( $classes_attr, $data_tooltip_attr, $title_attr );
							$replacement = '$1<span ' . implode( ' ', $tooltip_attributes ) . '>$2</span>$3';
							break;
					
						case 'link':
							if( !in_array( 'tltpy_mode_link', $keyword_classes ) ){
								$keyword_classes[] = 'tltpy_mode_link';
							}

							$classes_attr = 'class="' . implode( ' ', $keyword_classes) . '"';
							$tooltip_attributes = array( $classes_attr, $data_tooltip_attr );
							$replacement = '$1<a href="' . get_post_permalink( $tooltip_post->ID ) . '" ' . implode( ' ', $tooltip_attributes ) . '>$2</a>$3';
							break;

						case 'footnote':
							if( !in_array( 'tltpy_mode_footnote', $keyword_classes ) ){
								$keyword_classes[] = 'tltpy_mode_footnote';
							}

							$classes_attr = 'class="' . implode( ' ', $keyword_classes) . '"';
							$tooltip_attributes = array( $classes_attr, $data_tooltip_attr );
							$replacement = '$1$2<sup>[<a href="#tltpy-footnote-' . $tooltip['tooltip_id'] . '" ' . implode( ' ', $tooltip_attributes ) . '>' . ($num+1) . '</a>]</sup>$3';
							break;
					
						default:
							break;
					}

					array_push($replacements, $replacement );
				}
			}
		}

		$limit = tooltipy_get_option( 'match_all_occurrences',false) ? -1 : 1;

		$content = $this->text_nodes_replace( $patterns, $replacements, $content, $limit );

		return $content;
	}

	/**
	 * text_nodes_replace : execute preg_replace just for text html dom nodes
	 * that means that it doesn't affect HTML tags
	 */
	public function text_nodes_replace( $patterns, $replacements, $content, $limit ){
		include_once( TOOLTIPY_BASE_DIR . '/includes/libraries/simple-html-dom/simple_html_dom.php');

		foreach( $patterns as $key => $pat ){
			$html_obj = str_get_html( $content );
			$text_nodes = $html_obj->find('text');

			foreach($text_nodes as $line) {
				$line->innertext = preg_replace( $patterns[$key], $replacements[$key], $line->innertext, $limit, $count);

				if( $limit == 1 && $count > 0){
					break;
				}
			}
			$content = $html_obj;
		}				
		return $content;
	}
	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    4.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name . '-animate', plugin_dir_url( __FILE__ ) . 'css/animate.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/tooltipy-public.css', array(), $this->version, 'all' );

		$tooltip_mode = tooltipy_get_option( 'tooltip_mode' );

		if( in_array( $tooltip_mode, array( 'standard', 'icon' ) ) ){
			// Tippy library style CDN version
			wp_enqueue_style( 'tippy-style', 'https://unpkg.com/tippy.js@5/dist/backdrop.css', array(), $this->version, 'all' );
		}

		$this->inline_style();
	}

	public function inline_style(){
		// Options
		$desc_font_size = tooltipy_get_option( 'description_font_size' );
		$tooltip_cursor = tooltipy_get_option( 'tooltip_cursor' );
		
		?>
		<!-- Tooltipy inline style -->
		<style>
		<?php
		if( $desc_font_size && intval( $desc_font_size ) > 0 ){
			?>
			.tooltipy-pop__content{
				font-size: <?php echo $desc_font_size; ?>px;
			}
			<?php
		}

		if( $tooltip_cursor && $tooltip_cursor != 'auto' ){
			?>
			.tooltipy-kw{
				cursor: <?php echo $tooltip_cursor; ?>;
			}
			<?php
		}
		?>
		</style>
		<?php

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    4.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/tooltipy-public.js', array( 'jquery' ), $this->version, true );

		// Tippy library script
		$tooltip_mode = tooltipy_get_option( 'tooltip_mode' );

		if( in_array( $tooltip_mode, array( 'standard', 'icon' ) ) ){
			
			//Tippy CDN version (require popper)
			wp_enqueue_script( 'popper-script', 'https://unpkg.com/popper.js@1', array(), $this->version, true );
			wp_enqueue_script( 'tippy-script', 'https://unpkg.com/tippy.js@5', array('popper-script'), $this->version, true );

			wp_enqueue_script( 'tippy-handler', TOOLTIPY_PLUGIN_URL . 'public/js/tippy-handler.js', array(), $this->version, true );

			$options = array();
			
			foreach( Settings::get_fields() as $field ){
				$options[$field['uid']] = tooltipy_get_option( $field['uid'] );
			}
			
			wp_localize_script(
				'tippy-handler',
				'wpTooltipy',
				$options
			); 
		}
	}

	// Register Tooltipy Post Type
	public function tooltipy_post_type() {

		$labels = array(
			'name'                  => _x( 'Tooltips', 'Post Type General Name', 'tooltipy-lang' ),
			'singular_name'         => _x( 'Tooltip', 'Post Type Singular Name', 'tooltipy-lang' ),
			'menu_name'             => __tooltipy( 'Tooltipy OOP' ),
			'name_admin_bar'        => __tooltipy( 'Tooltip' ),
			'archives'              => __tooltipy( 'Tooltips archive' ),
			'parent_item_colon'     => __( 'Parent tooltip:' ),
			'all_items'             => __tooltipy( 'All Tooltips' ),
			'add_new_item'          => __tooltipy( 'Add New Tooltip' ),
			'add_new'               => __tooltipy( 'Add New' ),
			'new_item'              => __tooltipy( 'New Tooltip' ),
			'edit_item'             => __tooltipy( 'Edit Tooltip' ),
			'update_item'           => __tooltipy( 'Update Tooltip' ),
			'view_item'             => __tooltipy( 'View Tooltip' ),
			'search_items'          => __tooltipy( 'Search Tooltip' ),
			'not_found'             => __tooltipy( 'No Tooltips found' ),
			'not_found_in_trash'    => __tooltipy( 'No Tooltips found in Trash' ),
			'featured_image'        => __tooltipy( 'Featured Image' ),
			'set_featured_image'    => __tooltipy( 'Set featured image' ),
			'remove_featured_image' => __tooltipy( 'Remove featured image' ),
			'use_featured_image'    => __tooltipy( 'Use as featured image' ),
			'insert_into_item'      => __tooltipy( 'Insert into Tooltip' ),
			'uploaded_to_this_item' => __tooltipy( 'Uploaded to this Tooltip' ),
			'items_list'            => __tooltipy( 'Tooltips list' ),
			'items_list_navigation' => __tooltipy( 'Tooltips list navigation' ),
			'filter_items_list'     => __tooltipy( 'Filter Tooltips list' ),
		);

		/*$capabilities = array(
			'edit_post'             => 'manage_options',
			'read_post'             => 'manage_options',
			'delete_post'           => 'manage_options',
			'edit_posts'            => 'manage_options',
			'edit_others_posts'     => 'manage_options',
			'publish_posts'         => 'manage_options',
			'read_private_posts'    => 'manage_options',
		);*/

		$args = array(
			'label'                 => __tooltipy( 'Tooltip' ),
			'description'           => __( 'Post type to create keywords to generate tooltips in the frontend.' ),
			'labels'                => $labels,
			'supports'              => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'revisions', 'page-attributes', ),
			'taxonomies'            => array( 'tooltip_cat' ),
			'hierarchical'          => false,
			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 5,
			'menu_icon'             => TOOLTIPY_PLUGIN_URL.'assets/menu_icon.png',
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'can_export'            => true,
			'has_archive'           => true,		
			'exclude_from_search'   => false,
			'publicly_queryable'    => true,
			//'capabilities'          => $capabilities,
			'show_in_rest'          => true,
		);

		register_post_type( Tooltipy::get_plugin_name(), $args );

		// Tooltips category taxonomy
		$cat_args = array(
			'labels' => array(
				'name' => __tooltipy( 'Categories' )
			),
			'hierarchical' => true,			
    		'show_ui' => 'radio',
			'show_admin_column' => true,
		);

		register_taxonomy(
			Tooltipy::get_taxonomy(),
			Tooltipy::get_plugin_name(),
			$cat_args
		);
		
		// Flush permalinks to consider new tooltipy post type rewrite rule if activated now
		if( tooltipy_get_option( 'activated_just_now',false ) ){
			flush_rewrite_rules();
			delete_option( 'tltpy_activated_just_now');
		}
	}

	public function debug_mode(){
		global $post_type;

		$tooltipy_debug_mode = tooltipy_get_option( 'debug_mode' );

		if( !$tooltipy_debug_mode || !current_user_can( 'administrator' ) ){
			return false;
		}
		?>
		<div id="tooltipy-debug" style="background:white; padding: 50px;">
			<?php
			if( Tooltipy::get_plugin_name() == $post_type):		
				$this->debug_tooltip_meta();
			else:
				$this->debug_posts_meta();
			endif;

			$this->debug_settings();
			?>
		</div>
		<?php		
	}

	public function debug_settings(){
		$settings = new Settings();
		$all_settings = $settings->get_settings();

		?>
		<h2>Tooltipy settings :</h2>
		<ul>
		<?php
			foreach($all_settings as $setting){
				$setting_id = $setting['uid'];
				$setting_vals = get_option( $setting_id );
				$setting_vals = is_array($setting_vals) ? implode(', ',$setting_vals) : $setting_vals;
				
				if( true === $setting_vals ){
					$setting_vals = '<span style="color:green;">--TRUE--</span>';
				}else if( false === $setting_vals ){
					$setting_vals = '<span style="color:red;">--FALSE--</span>';
				}else if( empty($setting_vals) ){
					$setting_vals = '<span style="color:orange;">--EMPTY--</span>';
				}else{
					$setting_vals = '<span style="color:blue;">'.$setting_vals.'</span>';
				}
				?>
					<li>
						<b><?php echo($setting_id); ?></b>
						<span>( <?php echo( $setting_vals ); ?> )</span>
					</li>
				<?php
			}
		?>
		</ul>
		<?php
	}

	public function debug_tooltip_meta(){
		?>
			<h2>Current Tooltip metadata :</h2>
			<ul>
				<?php
					$tooltip_metabox_fields = Tooltip_Metaboxes::get_metabox_fields();
					foreach ($tooltip_metabox_fields as $field) {
						?>
						<li>
							<b><?php echo($field['meta_field_id']); ?></b>
							<span>( <?php echo( get_post_meta(get_the_ID(), $field['meta_field_id'], true ) ); ?> )</span>
						</li>
						<?php
					}
				?>
			</ul>
		<?php
	}

	public function debug_posts_meta(){
		?>
		<h2>Current post metadata :</h2>
		<ul>
		<?php
			$posts_metabox_fields = Posts_Metaboxes::get_metabox_fields();
			foreach ($posts_metabox_fields as $field) {
				$meta_val = get_post_meta(get_the_ID(), $field['meta_field_id'], true );

				$meta_str = '';
				if( is_array($meta_val) ){
					$meta_str = array();
					foreach ($meta_val as $val) {
						 array_push( $meta_str, $val['tooltip_title'].' ('.$val['tooltip_id'].')' );
					}
					$meta_str = implode( ', ', $meta_str );
				}else{
					$meta_str = $meta_val;
				}
				?>
				<li>
					<b><?php echo($field['meta_field_id']); ?></b>
					<span>( <?php echo( $meta_str ); ?> )</span>
				</li>
				<?php
			}
		?>
		</ul>
		<?php
	}
	
	public function tooltip_single_template( $single ) {

		global $wp_query, $post;

		/* Checks for single template by post type */
		if ( $post->post_type == Tooltipy::get_plugin_name() ) {
			return TOOLTIPY_PLUGIN_DIR . 'public/single.php';
		}

		return $single;

	}
	
	public function glossary_template( $page_template ){
		global $wp_query, $post;
		$tooltipy_glossary_page_id = tooltipy_get_option( 'glossary_page' );
		if( is_array($tooltipy_glossary_page_id) ){
			$tooltipy_glossary_page_id = $tooltipy_glossary_page_id[0];
		}

		if( $tooltipy_glossary_page_id == get_the_ID() ){
			return TOOLTIPY_PLUGIN_DIR . 'public/glossary.php';
		}
		return $page_template;
	}

	public function footnote_section( $content ){
		global $post_type;

		$tooltip_mode = tooltipy_get_option( 'tooltip_mode' );
		
		if( $tooltip_mode != 'footnote' || Tooltipy::get_plugin_name() == $post_type ){
			return $content;
		}
		
		$matched_tooltips 	= get_post_meta( get_the_id(), 'tltpy_matched_tooltips', true );
		$exclude_me			= get_post_meta( get_the_id(), 'tltpy_exclude_me', true );
		$exclude_tooltips	= get_post_meta( get_the_id(), 'tltpy_exclude_tooltips', true );

		$exclude_tooltips = explode( ',', $exclude_tooltips );
		$exclude_tooltips = array_map( 'trim', $exclude_tooltips );
		$exclude_tooltips = array_map( 'strtolower', $exclude_tooltips );

		foreach ($matched_tooltips as $num => $tooltip) {
			if( in_array( strtolower($tooltip['tooltip_title']), $exclude_tooltips ) ){
				unset( $matched_tooltips[$num]);
			}
		}

		if( empty( $matched_tooltips ) || $exclude_me ){
			return $content;
		}
		
		$notes = array();

		foreach ($matched_tooltips as $num => $tooltip) {
			$tooltip_post = get_post($tooltip['tooltip_id']);

			$note_html = '<li id="tltpy-footnote-' . $tooltip['tooltip_id'] . '" class="tltpy-footnote">' . $tooltip_post->post_content . '</li>';
			array_push( $notes, $note_html );
		}

		$footnote_section = '<div id="tltpy-footnotes">
		<hr>
		<ol>' . implode( '', $notes ) . '</ol>
		</div>';
		
		return $content . $footnote_section;
	}

	public function rewrite_rules() {
		// Consider the letter query var for glossary pages
		add_rewrite_rule( '([^/]+)/letter/([^/])', 'index.php?pagename=$matches[1]&letter=$matches[2]', 'top' );
	}

	public function register_query_var( $vars ) {
		$vars[] = 'letter';

		return $vars;
	}

}

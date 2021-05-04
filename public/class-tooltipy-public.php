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
		$this->version = time(); // TODO to be switched to the var : $version
		add_filter( 'the_content', array($this, 'filter_content') );
		add_action( 'wp_ajax_tltpy_load_glossary', array( $this, 'ajax_load_glossary' ) );
		add_action( 'wp_ajax_nopriv_tltpy_load_glossary', array( $this, 'ajax_load_glossary' ) );
		add_action( 'init', array( $this, 'rewrite_rules' ) );
		add_filter( 'query_vars', array( $this, 'add_query_vars' ) );
	}

	public static function get_active_matched_tooltips( $post_id = null ){
		global $post, $post_type;
		
		if( !isset($post->ID) ){
			return false;
		}

		$post_id = $post_id === null ? $post->ID : $post_id;

		if( Tooltipy::get_plugin_name() == $post_type ){
			return false;
		}
		
		// Current post meta data
		$exclude_me 		= get_post_meta( $post_id, 'tltpy_exclude_me', true );
		$matched_tooltips 	= get_post_meta( $post_id, 'tltpy_matched_tooltips', true );
		$exclude_tooltips	= get_post_meta( $post_id, 'tltpy_exclude_tooltips', true );
		$exclude_cats		= get_post_meta( $post_id, 'tltpy_exclude_cats', true );

		$exclude_tooltips = explode( ',', $exclude_tooltips );
		$exclude_tooltips = array_map( 'trim', $exclude_tooltips );
		$exclude_tooltips = array_map( 'strtolower', $exclude_tooltips );

		if( empty( $matched_tooltips ) || $exclude_me ){
			return false;
		}
		foreach ( $matched_tooltips as $key => $tooltip ) {
			if(
				in_array( strtolower($tooltip['tooltip_title']), $exclude_tooltips )
				||
				( !empty($exclude_cats) && has_term( $exclude_cats, Tooltipy::get_taxonomy(), $tooltip['tooltip_id'] ) )
			){
				unset( $matched_tooltips[$key] );
			}
		}

		return $matched_tooltips;
	}

	/**
	 * Renders the required tooltips to be related to the keyword
	 */
	public function tooltips_section(){

		// Tooltipy settings
		$tooltip_mode = tooltipy_get_option( 'tooltip_mode' );

		// Don't load popups if 'title' or 'link' tooltip mode are picked from the settings
		// Load only for 'standard' & 'icon' modes
		if( in_array( $tooltip_mode, array( 'title', 'link' ) ) ){
			return false;
		}

		$matched_tooltips = self::get_active_matched_tooltips();

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
					
					$popup_classes = [];
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
					<div
						data-tooltipy-id="<?php echo get_the_ID(); ?>"
						class="<?php echo implode(' ', $popup_classes) ?>"
					>
						<div class="tooltipy-inner">
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
		global $post_type;

		if( Tooltipy::get_plugin_name() == get_post_type() ){
			return $content;
		}

		$matched_tooltips = self::get_active_matched_tooltips();

		$patterns = array();
		$replacements = array();

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
			$tt_is_wiki				= get_post_meta( $tooltip['tooltip_id'], 'tltpy_is_wiki', true);
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
			// If is wiki
			if( $tt_is_wiki ){
				array_push( $keyword_classes, 'tooltipy-kw-wiki' );
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
							
							$icon_img = tooltipy_get_option( 'icon_image', false );

							$bg_color = tooltipy_get_option( 'icon_background_color', false );
							if( empty( $bg_color ) ){
								$bg_color = "auto";
							}
							
							$txt_color = tooltipy_get_option( 'icon_text_color', false );
							if( empty( $txt_color ) ){
								$txt_color = "auto";
							}

							$icon = '<span class="dashicons '.$icon_img.'" style="color: '.$txt_color.'; background-color: '.$bg_color.';"></span>';
							
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

		$html_obj = str_get_html( $content );
		$text_nodes = $html_obj->find('text');
		
		foreach( $patterns as $key => $pat ){

			foreach($text_nodes as $line) {
				// Exclude classes
				if( self::is_node_excluded( $line ) ){
					continue;
				}

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
	 * Checks whether if an HTML node is excluded from being matched
	 *
	 * @param  object $html_node
	 *
	 * @return void
	 */
	public static function is_node_excluded( $html_node ){
		// classes
		$exclude_class_name = tooltipy_get_option( 'exclude_classes', false );
		if( $exclude_class_name ){
			if( '' != $exclude_class_name && self::parents_has_class( $html_node, $exclude_class_name ) ){
				return true;
			}
		}

		// tag names ( a, h1 ... h6 , strong, b, abr, ...)
		$exclude_links 			= tooltipy_get_option( 'exclude_links', false, true );
		$exclude_heading_tags 	= tooltipy_get_option( 'exclude_heading_tags', false, false );
		$exclude_common_tags 	= tooltipy_get_option( 'exclude_common_tags', false, false );

		$exclude_tags = array();

		if( $exclude_links && 'yes' == $exclude_links ){
			array_push( $exclude_tags, 'a' );
		}

		if( $exclude_heading_tags && is_array( $exclude_heading_tags ) ){
			$exclude_tags = array_merge( $exclude_tags, $exclude_heading_tags );
		}

		if( $exclude_common_tags && is_array( $exclude_common_tags ) ){
			$exclude_tags = array_merge( $exclude_tags, $exclude_common_tags );
		}

		if( count($exclude_tags) ){
			if( self::is_node_in_tag( $html_node, $exclude_tags ) ){
				return true;
			}
		}

		return false;
	}

	/**
	 * Checks whether if a node is a child of another node having tag_names in argument
	 *
	 * @param  object $html_node
	 * @param  mixed $tag_names
	 *
	 * @return void
	 */
	public static function is_node_in_tag( $html_node, $tag_names ){
		$parent = $html_node->parentNode();

		if( $parent ){		
			if(  in_array( $parent->tag, $tag_names ) ){
				return true;
			}

			return self::is_node_in_tag( $parent, $tag_names );

		}else if( !$parent ){
			return false;
		}
	}

	/**
	 * Checks whether if a node is a child of another node having the class name in argument
	 *
	 * @param  mixed $html_node
	 * @param  mixed $class_name
	 *
	 * @return void
	 */
	public static function parents_has_class( $html_node, $class_name ){
		$parent = $html_node->parentNode();
		if( $parent ){
			$classes = explode( ' ', $parent->getAttribute( 'class' ) );
		
			$class_name = trim($class_name);
			$arr_cls = explode( ' ', $class_name );
			$arr_cls = array_map( 'trim', $arr_cls );
			$arr_cls = array_filter( $arr_cls, function($w){ return '' != $w; } );
			$class_name = implode( ' ', $arr_cls );

			foreach( explode( ' ', $class_name ) as $cls ){
				if(  in_array( $cls, $classes ) ){
					return true;
				}
			}

			return self::parents_has_class( $parent, $class_name );

		}else if( !$parent ){
			return false;
		}
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
			wp_enqueue_style( 'tippy-style', 'https://unpkg.com/tippy.js@6/dist/backdrop.css', array(), $this->version, 'all' );
			wp_enqueue_style( 'tippy-style-light', 'https://unpkg.com/tippy.js@6/themes/light.css', array(), $this->version, 'all' );
			wp_enqueue_style( 'tippy-style-light-border', 'https://unpkg.com/tippy.js@6/themes/light-border.css', array(), $this->version, 'all' );
			wp_enqueue_style( 'tippy-style-material', 'https://unpkg.com/tippy.js@6/themes/material.css', array(), $this->version, 'all' );
			wp_enqueue_style( 'tippy-style-translucent', 'https://unpkg.com/tippy.js@6/themes/translucent.css', array(), $this->version, 'all' );
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
		global $post_type;

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
			wp_enqueue_script( 'popper-script', 'https://unpkg.com/@popperjs/core@2', array(), $this->version, true );
			wp_enqueue_script( 'tippy-script', 'https://unpkg.com/tippy.js@6', array('popper-script'), $this->version, true );

			wp_enqueue_script( 'tippy-handler', TOOLTIPY_PLUGIN_URL . 'public/js/tippy-handler.js', array(), $this->version, true );

			$options = array();
			
			foreach( Settings::get_fields() as $field ){
				$options[$field['uid']] = tooltipy_get_option( $field['uid'] );
			}

			// Wiki language
			$options['wikipedia_lang'] = tooltipy_get_option( 'wikipedia_lang', 'en' );

			// Add related tooltips to options
			global $post;

			if( !isset($post->ID) ){
				return;
			}

			$matched_tooltips = Tooltipy_Public::get_active_matched_tooltips( $post->ID );
			$options['keywords'] = [];
			if( is_array($matched_tooltips) ){
				foreach( $matched_tooltips as $tooltip ){
					$tooltip_id = $tooltip['tooltip_id'];
					$options['keywords'][] = [
						'id' 				=> $tooltip_id,
						'title' 			=> $tooltip['tooltip_title'],
						'offset' 			=> $tooltip['tooltip_offset'],
						'synonyms' 			=> get_post_meta( $tooltip_id, 'tltpy_synonyms', true ),
						'case_sensitive' 	=> get_post_meta( $tooltip_id, 'tltpy_case_sensitive', true ),
						'is_prefix' 		=> get_post_meta( $tooltip_id, 'tltpy_is_prefix', true ),
						'is_wiki' 			=> get_post_meta( $tooltip_id, 'tltpy_is_wiki', true ),
						'wiki_term'			=> get_post_meta( $tooltip_id, 'tltpy_wiki_term', true ),
						'youtube_id' 		=> get_post_meta( $tooltip_id, 'tltpy_youtube_id', true ),
					];
				}
			}

			if( Tooltipy::get_plugin_name() == $post_type ){
				$tooltip_metabox_fields = Tooltip_Metaboxes::get_metabox_fields();
				$options['meta'] = [];

				foreach ($tooltip_metabox_fields as $field) {
					$options['meta'][$field['meta_field_id']] = get_post_meta(get_the_ID(), $field['meta_field_id'], true );
				}
			}

		}
		$options['ajaxurl'] = admin_url( 'admin-ajax.php' );

		wp_localize_script(
			$this->plugin_name,
			'wpTooltipy',
			$options
		); 
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
			'supports'              => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'revisions', 'page-attributes', 'comments' ),
			'taxonomies'            => array( Tooltipy::get_taxonomy() ),
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

		$rewrite_tooltip = tooltipy_get_option( 'rewrite_tooltip', false );
		$rewrite_tooltip = trim( $rewrite_tooltip );

		if( !empty($rewrite_tooltip) ){
			$args['rewrite'] = [ 'slug' => $rewrite_tooltip ];
		}

		register_post_type( Tooltipy::get_plugin_name(), $args );

		$rewrite_tooltip = tooltipy_get_option( 'rewrite_cat', false );
		// Tooltips category taxonomy
		$cat_args = array(
			'labels' => array(
				'name' => __tooltipy( 'Categories' )
			),
			'hierarchical' => true,
			'show_in_rest' => true,
    		'show_ui' => 'radio',
			'show_admin_column' => true,
			'rewrite' => [
				'slug' => $rewrite_tooltip
			]
		);

		register_taxonomy(
			Tooltipy::get_taxonomy(),
			Tooltipy::get_plugin_name(),
			$cat_args
		);
		
		// Flush permalinks to consider new tooltipy post type rewrite rule if activated now
		if( tooltipy_get_option( 'flush_rewrite_rules',false, true, false ) ){
			flush_rewrite_rules();
			delete_option( 'tltpy_flush_rewrite_rules');
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
				if( 'tltpy_matched_tooltips' == $field['meta_field_id'] && is_array($meta_val) ){
					$meta_str = array();
					foreach ($meta_val as $val) {
						 array_push( $meta_str, $val['tooltip_title'].' ('.$val['tooltip_id'].')' );
					}
					$meta_str = implode( ', ', $meta_str );
				}elseif( 'tltpy_exclude_cats' == $field['meta_field_id'] && is_array($meta_val) ){
					$meta_val = array_map(function($term_id){
						$term = get_term( $term_id );
						return $term->name;
					}, $meta_val);

					$meta_str = implode( ', ', $meta_val );
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

		$matched_tooltips = self::get_active_matched_tooltips();

		$tooltip_mode = tooltipy_get_option( 'tooltip_mode' );
		
		if( $tooltip_mode != 'footnote' || Tooltipy::get_plugin_name() == $post_type ){
			return $content;
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
		$glossary_page_id = tooltipy_get_option( 'glossary_page' );

		if( !empty($glossary_page_id) ){
			$glossary_page = get_post( $glossary_page_id );
			$slug = $glossary_page->post_name;
			
			// Consider the letter query var for glossary pages
			add_rewrite_rule(
				'^'.$slug.'/letter/([^/]+)$',
				'index.php?page_id='.$glossary_page_id.'&letter=$matches[1]',
				'top'
			);
		}

	}
	function add_query_vars( $vars )
	{
		$vars[] = 'letter';
		return $vars;
	}
	public function register_widgets(){
		register_widget( Widgets\PostKeywords::class );
	}

	public function ajax_load_glossary(){
		$glossary_first_letter = isset($_POST['letter']) ? $_POST['letter'] : '';
		
		ob_start();
			tooltipy_main_glossary_template( $glossary_first_letter );
		$html = ob_get_clean();

		$data = [
			'message' => 'good',
			'html' => $html
		];

		echo json_encode( $data );
		die();
	}
}

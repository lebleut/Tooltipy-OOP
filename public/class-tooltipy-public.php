<?php

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

	// The main filtering content of Tooltipy
	function filter_content($content){
		global $post_type;

		// Don't filter Tooltipy post types them selves
		if( Tooltipy::get_plugin_name() == $post_type ){
			return $content;
		}

		$matched_tooltips = get_post_meta( get_the_id(), 'tltpy_matched_tooltips', true );

		if( empty( $matched_tooltips ) ){
			return $content;
		}

		$patterns = array();
		$replacements = array();

		foreach ($matched_tooltips as $tooltip) {
			$case_sensitive_modifier = 'i';
			$is_case_sensitive = get_post_meta( $tooltip['tooltip_id'], 'tltpy_case_sensitive', true);
			if($is_case_sensitive){
				$case_sensitive_modifier = '';
			}

			$tooltip_post = get_post($tooltip['tooltip_id']);

			// Tooltip content formatted for the title attrib
			$tooltip_content = $tooltip_post->post_content;
			$tooltip_content = esc_attr( wp_strip_all_tags( $tooltip_content ) );

			array_push($patterns, '/('.$tooltip['tooltip_title'].')/'.$case_sensitive_modifier);
			array_push($replacements, '<span style="color:green;" tooltip-id="'.$tooltip['tooltip_id'].'" title="' . $tooltip_content . '">$1</span>');
		}

		$limit = get_option('tltpy_match_all_occurrences',false) ? -1 : 1;
		$content = preg_replace( $patterns, $replacements, $content, $limit);

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
		 * defined in Tooltipy_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Tooltipy_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/tooltipy-public.css', array(), $this->version, 'all' );

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
		 * defined in Tooltipy_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Tooltipy_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/tooltipy-public.js', array( 'jquery' ), $this->version, false );

	}

	// Register Tooltipy Post Type
	function tooltipy_post_type() {

		$labels = array(
			'name'                  => _x( 'Tooltips', 'Post Type General Name', 'tooltipy-lang' ),
			'singular_name'         => _x( 'Tooltip', 'Post Type Singular Name', 'tooltipy-lang' ),
			'menu_name'             => __( 'Tooltipy OOP', 'tooltipy-lang' ),
			'name_admin_bar'        => __( 'Tooltip', 'tooltipy-lang' ),
			'archives'              => __( 'Tooltips archive', 'tooltipy-lang' ),
			'parent_item_colon'     => __( 'Parent tooltip:', 'tooltipy-lang' ),
			'all_items'             => __( 'All Tooltips', 'tooltipy-lang' ),
			'add_new_item'          => __( 'Add New Tooltip', 'tooltipy-lang' ),
			'add_new'               => __( 'Add New', 'tooltipy-lang' ),
			'new_item'              => __( 'New Tooltip', 'tooltipy-lang' ),
			'edit_item'             => __( 'Edit Tooltip', 'tooltipy-lang' ),
			'update_item'           => __( 'Update Tooltip', 'tooltipy-lang' ),
			'view_item'             => __( 'View Tooltip', 'tooltipy-lang' ),
			'search_items'          => __( 'Search Tooltip', 'tooltipy-lang' ),
			'not_found'             => __( 'No Tooltips found', 'tooltipy-lang' ),
			'not_found_in_trash'    => __( 'No Tooltips found in Trash', 'tooltipy-lang' ),
			'featured_image'        => __( 'Featured Image', 'tooltipy-lang' ),
			'set_featured_image'    => __( 'Set featured image', 'tooltipy-lang' ),
			'remove_featured_image' => __( 'Remove featured image', 'tooltipy-lang' ),
			'use_featured_image'    => __( 'Use as featured image', 'tooltipy-lang' ),
			'insert_into_item'      => __( 'Insert into Tooltip', 'tooltipy-lang' ),
			'uploaded_to_this_item' => __( 'Uploaded to this Tooltip', 'tooltipy-lang' ),
			'items_list'            => __( 'Tooltips list', 'tooltipy-lang' ),
			'items_list_navigation' => __( 'Tooltips list navigation', 'tooltipy-lang' ),
			'filter_items_list'     => __( 'Filter Tooltips list', 'tooltipy-lang' ),
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
			'label'                 => __( 'Tooltip', 'tooltipy-lang' ),
			'description'           => __( 'Post type to create keywords to generate tooltips in the frontend.', 'tooltipy-lang' ),
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
				'name' => __( 'Categories', 'tooltipy-lang' )
			),
			'hierarchical' => true,			
    		'show_ui' => 'radio',
			'show_admin_column' => true,
		);

		register_taxonomy(
			'tooltip_cat',
			Tooltipy::get_plugin_name(),
			$cat_args
		);
		
		// Flush permalinks to consider new tooltipy post type rewrite rule if activated now
		if( get_option( 'tooltipy_activated_just_now',false ) ){
			flush_rewrite_rules();
			delete_option( 'tooltipy_activated_just_now');
		}
	}

	function debug_mode(){
		global $post_type;

		$tooltipy_debug_mode = get_option( 'tltpy_debug_mode' );

		if( !$tooltipy_debug_mode || !current_user_can( 'administrator' ) ){
			return false;
		}
		?>
		<div id="tooltipy-debug" style="background:white; padding: 50px;">
			<?php
			if( 'tooltipy' == $post_type):		
				$this->debug_tooltip_meta();
			else:
				$this->debug_posts_meta();
			endif;

			$this->debug_settings();
			?>
		</div>
		<?php		
	}

	function debug_settings(){
		$settings = new Tooltipy_Settings();
		$all_settings = $settings->get_settings();

		?>
		<h2>Tooltipy settings :</h2>
		<ul>
		<?php
			foreach($all_settings as $setting){
				$setting_id = $setting['uid'];
				$setting_vals = get_option($setting_id);
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
	function debug_tooltip_meta(){
		?>
			<h2>Current Tooltip metadata :</h2>
			<ul>
				<?php
					$tooltip_metabox_fields = Tooltipy_Tooltip_Metaboxes::get_metabox_fields();
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
	function debug_posts_meta(){
		?>
		<h2>Current post metadata :</h2>
		<ul>
		<?php
			$posts_metabox_fields = Tooltipy_Posts_Metaboxes::get_metabox_fields();
			foreach ($posts_metabox_fields as $field) {
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
}

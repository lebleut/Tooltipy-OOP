<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.linkedin.com/in/jamel-eddine-zarga-56336485
 * @since      4.0.0
 *
 * @package    Tooltipy_Oop
 * @subpackage Tooltipy_Oop/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Tooltipy_Oop
 * @subpackage Tooltipy_Oop/public
 * @author     Jamel Eddine Zarga <jamel.zarga@gmail.com>
 */
class Tooltipy_Oop_Public {

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
		 * defined in Tooltipy_Oop_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Tooltipy_Oop_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/tooltipy-oop-public.css', array(), $this->version, 'all' );

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
		 * defined in Tooltipy_Oop_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Tooltipy_Oop_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/tooltipy-oop-public.js', array( 'jquery' ), $this->version, false );

	}

	// Register Tooltipy Post Type
	function tooltipy_post_type() {

		$labels = array(
			'name'                  => _x( 'Tooltips', 'Post Type General Name', 'tooltipy-oop' ),
			'singular_name'         => _x( 'Tooltip', 'Post Type Singular Name', 'tooltipy-oop' ),
			'menu_name'             => __( 'Tooltipy OOP', 'tooltipy-oop' ),
			'name_admin_bar'        => __( 'Tooltip', 'tooltipy-oop' ),
			'archives'              => __( 'Tooltips archive', 'tooltipy-oop' ),
			'parent_item_colon'     => __( 'Parent tooltip:', 'tooltipy-oop' ),
			'all_items'             => __( 'All Tooltips', 'tooltipy-oop' ),
			'add_new_item'          => __( 'Add New Tooltip', 'tooltipy-oop' ),
			'add_new'               => __( 'Add New', 'tooltipy-oop' ),
			'new_item'              => __( 'New Tooltip', 'tooltipy-oop' ),
			'edit_item'             => __( 'Edit Tooltip', 'tooltipy-oop' ),
			'update_item'           => __( 'Update Tooltip', 'tooltipy-oop' ),
			'view_item'             => __( 'View Tooltip', 'tooltipy-oop' ),
			'search_items'          => __( 'Search Tooltip', 'tooltipy-oop' ),
			'not_found'             => __( 'No Tooltips found', 'tooltipy-oop' ),
			'not_found_in_trash'    => __( 'No Tooltips found in Trash', 'tooltipy-oop' ),
			'featured_image'        => __( 'Featured Image', 'tooltipy-oop' ),
			'set_featured_image'    => __( 'Set featured image', 'tooltipy-oop' ),
			'remove_featured_image' => __( 'Remove featured image', 'tooltipy-oop' ),
			'use_featured_image'    => __( 'Use as featured image', 'tooltipy-oop' ),
			'insert_into_item'      => __( 'Insert into Tooltip', 'tooltipy-oop' ),
			'uploaded_to_this_item' => __( 'Uploaded to this Tooltip', 'tooltipy-oop' ),
			'items_list'            => __( 'Tooltips list', 'tooltipy-oop' ),
			'items_list_navigation' => __( 'Tooltips list navigation', 'tooltipy-oop' ),
			'filter_items_list'     => __( 'Filter Tooltips list', 'tooltipy-oop' ),
		);

		$capabilities = array(
			'edit_post'             => 'manage_options',
			'read_post'             => 'manage_options',
			'delete_post'           => 'manage_options',
			'edit_posts'            => 'manage_options',
			'edit_others_posts'     => 'manage_options',
			'publish_posts'         => 'manage_options',
			'read_private_posts'    => 'manage_options',
		);

		$args = array(
			'label'                 => __( 'Tooltip', 'tooltipy-oop' ),
			'description'           => __( 'Post type to create keywords to generate tooltips in the frontend.', 'tooltipy-oop' ),
			'labels'                => $labels,
			'supports'              => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'revisions', 'custom-fields', 'page-attributes', ),
			'taxonomies'            => array( 'tooltip_cat' ),
			'hierarchical'          => false,
			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 5,
			'menu_icon'             => TOOLTIPY_PLUGIN_URL.'/admin/css/menu_icon.png',
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'can_export'            => true,
			'has_archive'           => true,		
			'exclude_from_search'   => false,
			'publicly_queryable'    => true,
			'capabilities'          => $capabilities,
			'show_in_rest'          => true,
		);

		register_post_type( 'tooltipy', $args );
	}
}

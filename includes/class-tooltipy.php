<?php
namespace Tooltipy;

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://www.linkedin.com/in/jamel-eddine-zarga-56336485
 * @since      4.0.0
 *
 * @package    Tooltipy
 * @subpackage Tooltipy/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      4.0.0
 * @package    Tooltipy
 * @subpackage Tooltipy/includes
 * @author     Jamel Eddine Zarga <jamel.zarga@gmail.com>
 */
class Tooltipy {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    4.0.0
	 * @access   protected
	 * @var      Plugin_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    4.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected static $plugin_name = 'tooltipy';

	/**
	 * The current version of the plugin.
	 *
	 * @since    4.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    4.0.0
	 */
	public function __construct() {

		$this->version = '4.0.0';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Plugin_Loader. Orchestrates the hooks of the plugin.
	 * - Plugin_i18n. Defines internationalization functionality.
	 * - Tooltipy\Admin. Defines all hooks for the admin area.
	 * - Tooltipy_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    4.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-plugin-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-plugin-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-tooltipy-public.php';

		$this->loader = new Plugin_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Plugin_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    4.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Plugin_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    4.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {
		
		$plugin_admin = new Admin( self::get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		// Show log from ../wp-conetnt/debug.log
		$this->loader->add_action( 'tltpy_tab_after_settings', $plugin_admin, 'log_content' );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    4.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Tooltipy_Public( self::get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'init',  $plugin_public, 'tooltipy_post_type' );	
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		// Tooltips HTML section
		$this->loader->add_action( 'wp_footer', $plugin_public, 'tooltips_section' );

		// Debug mode
		$this->loader->add_action( 'wp_footer', $plugin_public, 'debug_mode' );

		// Filter the single_template with our custom function for Tooltipy post_type
		$this->loader->add_filter( 'single_template', $plugin_public, 'tooltip_single_template');

		// Filter the page_template to show the glossary content
		$this->loader->add_filter( 'page_template', $plugin_public, 'glossary_template');

		// if footnote mode load the footnotes section under the content
		$this->loader->add_filter( 'the_content', $plugin_public, 'footnote_section' );

		// Rewrite rules
		$this->loader->add_action( 'init', $plugin_public, 'rewrite_rules' );
		$this->loader->add_filter( 'query_vars', $plugin_public, 'register_query_var' );
	}
	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    4.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     4.0.0
	 * @return    string    The name of the plugin.
	 */
	public static function get_plugin_name() {
		return self::$plugin_name;
	}

	public static function get_related_post_types(){
		$post_types = get_post_types();

		// Remove Tooltipy from related post_types
		foreach ($post_types as $key => $pt) {
			if( $pt == self::get_plugin_name() ){
				unset( $post_types[$key] );
			}
		}
		$post_types = apply_filters( 'tltpy_related_post_types', $post_types );

		return $post_types;
	}

	public static function get_taxonomy(){
		$taxonomy = 'tooltip_cat';
		
		$taxonomy = apply_filters( 'tltpy_taxonomy', $taxonomy );

		return $taxonomy;
	}
	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     4.0.0
	 * @return    Plugin_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     4.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	public function get_tooltips(){
		$args = array(
			'posts_per_page' => -1,
			'post_type'   =>  self::get_from_post_types()
		  );
		   
		$tooltips = get_posts( $args );
		
		return $tooltips;
	}

	/**
	 * The list of post type slugs to calculate tooltip keywords from
	 *
	 * @return array
	 */
	public static function get_from_post_types(){
		$get_from_opt = tooltipy_get_option( 'get_from_post_types' );
		
		if( empty($get_from_opt) ){
			$pts = array( Tooltipy::get_plugin_name() );
		}else{
			$pts = $get_from_opt;
		}

		return $pts;
	}

	/**
	 * Print a custom message in the ../wp-content/debug.log file if the debug_mode option is activated
	 * Note : you should set the 'WP_DEBUG_LOG' constant to true in the wp-config.php file :
	 * define( 'WP_DEBUG_LOG', true );
	 */
	public static function log( $msg ){
		$debug_mode_setting = tooltipy_get_option( 'debug_mode' );

		if( !$debug_mode_setting ){
			return false;
		}
		
		$backtrace = debug_backtrace();
		$caller = array_shift( $backtrace );

		$caller_file = preg_replace( '/.*\/wp-content\//', '.../wp-content/', $caller['file'] );
		$caller_line = $caller['line'];

		error_log( '--- TOOLTIPY ---' );
		error_log( ' * File: ' .$caller_file );
		error_log( ' * line : ' .$caller_line);

		error_log( $msg );

		error_log( '--------' );
	}

	public static function get_glossary_page_id(){
		$glossary_id = tooltipy_get_option( 'glossary_page' );
		if( is_array( $glossary_id ) ){
			$glossary_id = $glossary_id[0];
		}

		return $glossary_id;
	}

	public static function get_glossary_page_link(){
		$glossary_id = self::get_glossary_page_id();

		if( $glossary_id ){
			return get_the_permalink( $glossary_id );
		}else{
			return false;
		}
	}
}

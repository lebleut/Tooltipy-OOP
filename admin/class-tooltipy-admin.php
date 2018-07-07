<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.linkedin.com/in/jamel-eddine-zarga-56336485
 * @since      4.0.0
 *
 * @package    Tooltipy
 * @subpackage Tooltipy/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Tooltipy
 * @subpackage Tooltipy/admin
 * @author     Jamel Eddine Zarga <jamel.zarga@gmail.com>
 */
class Tooltipy_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		
		// Settings
		require_once TOOLTIPY_PLUGIN_DIR . 'admin/class-tooltipy-settings.php';
		new Tooltipy_Settings();

		// Meta boxe
		require_once TOOLTIPY_PLUGIN_DIR . 'admin/class-tooltipy-metaboxes.php';
		new Tooltipy_Metaboxes();
	}

	/**
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/tooltipy-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/tooltipy-admin.js', array( 'jquery' ), $this->version, false );

	}
}

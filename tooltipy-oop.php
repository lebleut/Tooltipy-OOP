<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.linkedin.com/in/jamel-eddine-zarga-56336485
 * @since             4.0.0
 * @package           Tooltipy_Oop
 *
 * @wordpress-plugin
 * Plugin Name:       Tooltipy OOP
 * Plugin URI:        www.tooltipy.com
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           4.0.0
 * Author:            Jamel Eddine Zarga
 * Author URI:        https://www.linkedin.com/in/jamel-eddine-zarga-56336485
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       tooltipy-oop
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Define constants
if ( ! defined( 'TOOLTIPY_BASE_FILE' ) )
    define( 'TOOLTIPY_BASE_FILE', __FILE__ );

if ( ! defined( 'TOOLTIPY_BASE_DIR' ) )
    define( 'TOOLTIPY_BASE_DIR', dirname( TOOLTIPY_BASE_FILE ) );

if ( ! defined( 'TOOLTIPY_PLUGIN_URL' ) )
    define( 'TOOLTIPY_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

if ( ! defined( 'TOOLTIPY_PLUGIN_DIR' ) )
    define( 'TOOLTIPY_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-tooltipy-oop-activator.php
 */
function activate_tooltipy_oop() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-tooltipy-oop-activator.php';
	Tooltipy_Oop_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-tooltipy-oop-deactivator.php
 */
function deactivate_tooltipy_oop() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-tooltipy-oop-deactivator.php';
	Tooltipy_Oop_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_tooltipy_oop' );
register_deactivation_hook( __FILE__, 'deactivate_tooltipy_oop' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-tooltipy-oop.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    4.0.0
 */
function run_tooltipy_oop() {

	$plugin = new Tooltipy_Oop();
	$plugin->run();

}

run_tooltipy_oop();

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
 * @since             6.0.0
 * @package           Tooltipy
 *
 * @wordpress-plugin
 * Plugin Name:       Tooltipy OOP
 * Plugin URI:        www.tooltipy.com
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           6.0.0
 * Author:            Jamel Eddine Zarga
 * Author URI:        https://www.linkedin.com/in/jamel-eddine-zarga-56336485
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       tooltipy-lang
 * Domain Path:       /languages
 */

// Use namespaces
use Tooltipy\Tooltipy;
use Tooltipy\Plugin_Activator;
use Tooltipy\Plugin_Deactivator;

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

// Global tools and functions
include TOOLTIPY_PLUGIN_DIR . 'includes/functions.php';

// Template functions
include TOOLTIPY_PLUGIN_DIR . 'includes/template-functions.php';

// Template hooks
include TOOLTIPY_PLUGIN_DIR . 'includes/template-hooks.php';

// Widgets
include TOOLTIPY_PLUGIN_DIR . 'includes/widgets/class-post-keywords.php';

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-plugin-activator.php
 */
function activate_tooltipy() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-plugin-activator.php';
	Plugin_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-plugin-deactivator.php
 */
function deactivate_tooltipy() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-plugin-deactivator.php';
	Plugin_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_tooltipy' );
register_deactivation_hook( __FILE__, 'deactivate_tooltipy' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-tooltipy.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    4.0.0
 */
$tooltipy_obj = false;

function run_tooltipy() {
	global $tooltipy_obj;

	$tooltipy_obj = new Tooltipy();
	$tooltipy_obj->run();

}

run_tooltipy();

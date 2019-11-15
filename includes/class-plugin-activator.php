<?php
namespace Tooltipy;

/**
 * Fired during plugin activation
 *
 * @link       https://www.linkedin.com/in/jamel-eddine-zarga-56336485
 * @since      4.0.0
 *
 * @package    Tooltipy
 * @subpackage Tooltipy/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      4.0.0
 * @package    Tooltipy
 * @subpackage Tooltipy/includes
 * @author     Jamel Eddine Zarga <jamel.zarga@gmail.com>
 */
class Plugin_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    4.0.0
	 */
	public static function activate() {

		// Make Tooltipy aware of activation (use it after registering the new post type)
		if( !tooltipy_get_option( 'activated_just_now',false ) ){
			tooltipy_add_option( 'activated_just_now',true );
		}else{
			tooltipy_update_option( 'activated_just_now',true );
		}

		// flush_rewrite_rules to consider the tooltipy new rewrite rules (letter, ...)
		flush_rewrite_rules();
	}

}

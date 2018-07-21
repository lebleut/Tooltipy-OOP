<?php

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
class Tooltipy_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    4.0.0
	 */
	public static function activate() {

		// Make Tooltipy aware of activation (use it after registering the new post type)
		if( !get_option( 'tooltipy_activated_just_now',false ) ){
			add_option('tooltipy_activated_just_now',true);
		}else{
			update_option('tooltipy_activated_just_now',true);
		}

		// flush_rewrite_rules to consider the tooltipy new rewrite rules (letter, ...)
		flush_rewrite_rules();
	}

}

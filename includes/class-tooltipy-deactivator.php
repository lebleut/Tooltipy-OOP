<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://www.linkedin.com/in/jamel-eddine-zarga-56336485
 * @since      4.0.0
 *
 * @package    Tooltipy
 * @subpackage Tooltipy/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      4.0.0
 * @package    Tooltipy
 * @subpackage Tooltipy/includes
 * @author     Jamel Eddine Zarga <jamel.zarga@gmail.com>
 */
class Tooltipy_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    4.0.0
	 */
	public static function deactivate() {

		// flush_rewrite_rules to remove the tooltipy rewrite rules (letter, ...)
		flush_rewrite_rules();
	}

}

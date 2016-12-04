<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://www.linkedin.com/in/jamel-eddine-zarga-56336485
 * @since      1.0.0
 *
 * @package    Tooltipy_Oop
 * @subpackage Tooltipy_Oop/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Tooltipy_Oop
 * @subpackage Tooltipy_Oop/includes
 * @author     Jamel Eddine Zarga <jamel.zarga@gmail.com>
 */
class Tooltipy_Oop_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'tooltipy-oop',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}

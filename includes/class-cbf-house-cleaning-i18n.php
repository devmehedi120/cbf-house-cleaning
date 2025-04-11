<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://www.fiverr.com/wpdevmehedi
 * @since      1.0.0
 *
 * @package    Cbf_House_Cleaning
 * @subpackage Cbf_House_Cleaning/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Cbf_House_Cleaning
 * @subpackage Cbf_House_Cleaning/includes
 * @author     Mehedi Hasan  <wpdevmehedi@gmail.com>
 */
class Cbf_House_Cleaning_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'cbf-house-cleaning',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}

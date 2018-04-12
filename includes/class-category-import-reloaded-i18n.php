<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://www.niroma.net/
 * @since      1.0.0
 *
 * @package    Category_Import_Reloaded
 * @subpackage Category_Import_Reloaded/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Category_Import_Reloaded
 * @subpackage Category_Import_Reloaded/includes
 * @author     NiRoMa <info@niroma.net>
 */
class Category_Import_Reloaded_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'category-import-reloaded',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}

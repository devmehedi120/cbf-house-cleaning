<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.fiverr.com/wpdevmehedi
 * @since             1.0.0
 * @package           Cbf_House_Cleaning
 *
 * @wordpress-plugin
 * Plugin Name:       Custom Booking For House Cleaning
 * Plugin URI:        https://https://www.fiverr.com/wpdevmehedi
 * Description:       House Cleaning service Booking plugin
 * Version:           1.0.0
 * Author:            Mehedi Hasan 
 * Author URI:        https://www.fiverr.com/wpdevmehedi/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       cbf-house-cleaning
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'CBF_HOUSE_CLEANING_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-cbf-house-cleaning-activator.php
 */
function activate_cbf_house_cleaning() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-cbf-house-cleaning-activator.php';
	Cbf_House_Cleaning_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-cbf-house-cleaning-deactivator.php
 */
function deactivate_cbf_house_cleaning() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-cbf-house-cleaning-deactivator.php';
	Cbf_House_Cleaning_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_cbf_house_cleaning' );
register_deactivation_hook( __FILE__, 'deactivate_cbf_house_cleaning' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-cbf-house-cleaning.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_cbf_house_cleaning() {

	$plugin = new Cbf_House_Cleaning();
	$plugin->run();

}
run_cbf_house_cleaning();




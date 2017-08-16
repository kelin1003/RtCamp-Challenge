<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              sociallyawkward.in
 * @since             1.0.0
 * @package           Rtchallenge
 *
 * @wordpress-plugin
 * Plugin Name:       RtChallenge-A
 * Plugin URI:        pixelmatters.in
 * Description:       RtCamp's Wordpress Challenge Assignment 2-A
 * Version:           1.0.0
 * Author:            Kelin Chauhan
 * Author URI:        sociallyawkward.in
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       rtchallenge
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-rtchallenge-activator.php
 */
function activate_rtchallenge() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-rtchallenge-activator.php';
	Rtchallenge_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-rtchallenge-deactivator.php
 */
function deactivate_rtchallenge() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-rtchallenge-deactivator.php';
	Rtchallenge_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_rtchallenge' );
register_deactivation_hook( __FILE__, 'deactivate_rtchallenge' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-rtchallenge.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_rtchallenge() {

	$plugin = new Rtchallenge();
	$plugin->run();

}
run_rtchallenge();

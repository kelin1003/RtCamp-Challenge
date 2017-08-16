<?php

/**
 * 
 *
 * 
 *
 * @link              sociallyawkward.in
 * @since             1.0.0
 * @package           Rtcontributors
 *
 * @wordpress-plugin
 * Plugin Name:       RtContributors
 * Plugin URI:        pixelmatters.in
 * Description:       RtCamp's Challenge Assignment 2 Part B -- Lets you add more 	  * Authors and Contributors to a post.
 * Version:           1.0.0
 * Author:            Kelin Chauhan
 * Author URI:        sociallyawkward.in
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       rtcontributors
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-rtcontributors-activator.php
 */
function activate_rtcontributors() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-rtcontributors-activator.php';
	Rtcontributors_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-rtcontributors-deactivator.php
 */
function deactivate_rtcontributors() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-rtcontributors-deactivator.php';
	Rtcontributors_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_rtcontributors' );
register_deactivation_hook( __FILE__, 'deactivate_rtcontributors' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-rtcontributors.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_rtcontributors() {

	$plugin = new Rtcontributors();
	$plugin->run();

}
run_rtcontributors();

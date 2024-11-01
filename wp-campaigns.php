<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://wpcampaigns.dev
 * @since             1.0.0
 * @package           WpCampaigns

 * @wordpress-plugin
 * Plugin Name:       WP Campaigns
 * Plugin URI:        http://wpcampaigns.dev
 * Description:       Create awesome newsletter with your data in your site and send to third party providers to spread the news.
 * Version:           1.0.22
 * Requires at least: 4.7
 * Tested up to: 5.1
 * Author:            WEwp - Gilad Takoni & Ronen abutbul
 * Author URI:        http://wpcampaigns.dev
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-campaigns
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
define( 'WP_CAMPAIGNS_VERSION', '1.0.22' );

define( 'WP_CAMPAIGNS_PLUGIN_NAME', 'wp-campaigns' );


define('DEVELOPMENT_MODE',false);

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wpex-campaigns-activator.php
 */

function activate_wp_campaigns() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wpex-campaigns-activator.php';
	WpexCampaigns_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wpex-campaigns-deactivator.php
 */
function deactivate_wp_campaigns() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wpex-campaigns-deactivator.php';
	WpexCampaigns_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wp_campaigns' );
register_deactivation_hook( __FILE__, 'deactivate_wp_campaigns' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wpex-campaigns.php';

/**
 * Load mailchimp SDK
 */
require plugin_dir_path( __FILE__ ) . 'includes/Mailchimp.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wp_campaigns() {

	$plugin = new WpexCampaigns();
	$plugin->run();

}

run_wp_campaigns();

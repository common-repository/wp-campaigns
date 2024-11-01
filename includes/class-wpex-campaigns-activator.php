<?php

/**
 * Fired during plugin activation
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    WpexCampaigns
 * @subpackage WpexCampaigns/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    WpexCampaigns
 * @subpackage WpexCampaigns/includes
 * @author     Your Name <email@example.com>
 */
class WpexCampaigns_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		global $wpdb;
		$sql = "CREATE TABLE `{$wpdb->prefix}wpex_campaigns` ( `id` int(11) NOT NULL AUTO_INCREMENT, `json` mediumtext, `post_id` int(11) DEFAULT NULL, `status` varchar(45) DEFAULT NULL, `mailchimp_web_id` varchar(45) DEFAULT NULL, PRIMARY KEY (`id`), UNIQUE KEY `id_UNIQUE` (`id`) ) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;";
		$wpdb->query( $sql );
		$a = 1;
	}

}

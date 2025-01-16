<?php

/**
 * Fired during plugin activation
 *
 * @link       https://https://https://wbcomdesigns.com/
 * @since      1.0.0
 *
 * @package    Advanced_Username_Manager
 * @subpackage Advanced_Username_Manager/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Advanced_Username_Manager
 * @subpackage Advanced_Username_Manager/includes
 * @author     Wbcom Designs <info@wbcomdesign.com>
 */
class Advanced_Username_Manager_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		global $wpdb;
		
		$table_name 		= $wpdb->prefix . 'username_change_logs';
		$charset_collate 	= $wpdb->get_charset_collate();
		
		if ( $wpdb->get_var( "SHOW TABLES LIKE '$table_name'" ) != $table_name ) {
			$bpht_sql = "CREATE TABLE $table_name (
				id int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
				user_id  bigint(20),				
				old_username varchar(255),
				new_username  varchar(255),
				created_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
				PRIMARY KEY (id)) {$charset_collate};";
			require_once ABSPATH . 'wp-admin/includes/upgrade.php';
			dbDelta( $bpht_sql );
		}

	}

}

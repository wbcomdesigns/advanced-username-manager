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
		global $wpdb, $wp_roles;
		
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
		
		
		
		$general_settings = get_option( 'advanced_username_manager_general_settings' );		
		if( empty( $general_settings ) ) {
			
			$roles            = $wp_roles->get_names();
			
			$general_settings['enable_username'] 	= 'yes';
			$general_settings['limit_days'] 		= '7';
			$general_settings['user_roles'] 		= array_keys($roles);
			$general_settings['min_username_length']= '5';
			$general_settings['max_username_length']= '12';
			$general_settings['allowed_characters'] = '';
			$general_settings['prohibited_words'] 	= '';			
			update_option('advanced_username_manager_general_settings', $general_settings);
		}
		
	}

}

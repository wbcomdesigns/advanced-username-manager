<?php

function advanced_username_manager_update_repair_member_slug() {
	global $wpdb;

	// phpcs:ignore WordPress.DB.DirectDatabaseQuery, WordPress.DB.PreparedSQL.NotPrepared
	$user_ids = $wpdb->get_col(
		$wpdb->prepare(
			"SELECT u.ID FROM `{$wpdb->users}` AS u LEFT JOIN `{$wpdb->usermeta}` AS um ON ( u.ID = um.user_id AND um.meta_key = %s ) WHERE ( um.user_id IS NULL OR LENGTH(meta_value) = %d ) ORDER BY u.ID ASC",
			'aum_profile_slug',
			40,			
		)
	);	
	advanced_username_manager_set_bulk_user_profile_slug( $user_ids );
}
/**
 * Setup the user profile hash to the user meta. 
 *
 * @param array $user_ids User IDs.
 */
function advanced_username_manager_set_bulk_user_profile_slug( $user_ids ) {
	global $wpdb ;

	if ( empty( $user_ids ) ) {
		return;
	}
	
	foreach ( $user_ids as $key => $user_id ) {
		// removed old user meta which have value length 40.
		$wpdb->query( $wpdb->prepare(
					"DELETE FROM {$wpdb->usermeta} WHERE meta_key LIKE %s AND user_id = %d AND LENGTH(meta_value) = %d",
					'aum_profile_slug',
					$user_id,
					40
				) );

		// fetch user slug if already exists.
		$user_slug = advanced_username_manager_core_get_user_slug( $user_id );
		if ( ! empty( $user_slug ) ) {
			
			// Unset user if already setup.
			unset( $user_ids[$key] );
		}
	}
	
	$prefix = apply_filters('advanced_username_manager_profile_slug_prefix', 'aum');
	$start	= 0;
	$length	= 12;
	$bps_sql_data = array();
	foreach ( $user_ids as $key => $user_id ) {			
		$uuid = strtolower( substr( $prefix . sha1( $user_id.wp_generate_password( 40 ) ), $start, $length ) );
		$bps_sql_data[] = "({$user_id}, 'aum_profile_slug', '{$uuid}')";	
	}

	// Insert 'aum_profile_slug' metakey.
	if ( ! empty( $bps_sql_data ) ) {
		$bps_sql = "INSERT INTO {$wpdb->usermeta} (user_id, meta_key, meta_value) VALUES " . implode( ', ', $bps_sql_data ); // Remove the trailing comma and space.	
		$wpdb->query( $bps_sql ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
	}
	// Flush WP cache.
	wp_cache_flush();
} 

/**
 * Get the profile slug based on the user ID. 
 *
 * @param int $user_id User ID to check.
 *
 * @return string
 */
function advanced_username_manager_core_get_user_slug( int $user_id ) {

	if ( empty( $user_id ) ) {
		return '';
	}

	$profile_slug = get_user_meta( $user_id, 'aum_profile_slug', true );

	/**
	 * Filters the profile slug based on originally provided user ID.	 
	 *
	 * @param string $profile_slug User profile slug.
	 * @param int    $user_id User ID.
	 */
	return apply_filters( 'advanced_username_manager_core_get_user_slug', $profile_slug, $user_id );
}


add_filter( 'bp_members_get_user_slug', 'advanced_username_manager_get_user_slug', 10, 2 );
function advanced_username_manager_get_user_slug( $slug, $user_id  ) {

	global $aum_general_settings;	
	if( isset( $aum_general_settings['bp_profile_slug_format']) && $aum_general_settings['bp_profile_slug_format'] == 'username' ) {		
		return $slug;
	}
	
	$user_slug = advanced_username_manager_core_get_user_slug( $user_id );
	if( !empty( $user_slug) ) {
		return $user_slug;
	}
	return $slug;
}


// Replace member slug from unique indetifire.
add_filter( 'bp_core_set_uri_globals_member_slug', 'advanced_username_manager_set_uri_globals_member_slug', 20 );
function advanced_username_manager_set_uri_globals_member_slug( $member_slug ) {
	
	global $aum_general_settings, $wpdb;
	
	$user_id = $wpdb->get_var(
		$wpdb->prepare(
			"SELECT user_id FROM `{$wpdb->usermeta}` WHERE meta_key= %s AND  meta_value = %s  ORDER BY user_id ASC LIMIT %d",
			'aum_profile_slug',
			$member_slug,
			1			
		)
	);	
	if( isset( $aum_general_settings['bp_profile_slug_format']) && $aum_general_settings['bp_profile_slug_format'] == 'username' ) {
		if( !empty($user_id) ) {
			return get_user_by( 'id', $user_id )->user_login;
		}
		return $member_slug;
	}	
	
	
	if( !empty( $user_id) ) {	
		return get_user_by( 'id', $user_id )->user_login;		
	}
	
	return $member_slug;
}
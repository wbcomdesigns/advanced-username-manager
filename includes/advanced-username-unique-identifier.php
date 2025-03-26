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

	if (empty($user_ids)) {
        return;
    }
    
    // Process in batches of 50 to prevent memory issues
    $batch_size = 50;
    $batches = array_chunk($user_ids, $batch_size);
    
    foreach ($batches as $batch) {
        $bps_sql_data = array();
        $prefix = apply_filters('advanced_username_manager_profile_slug_prefix', 'aum');
        $start = 0;
        $length = 12;
        
        foreach ($batch as $user_id) {
            // Skip processing if user already has a valid slug
            $existing_slug = wp_cache_get('aum_user_profile_slug_' . $user_id, 'aum');
            if (false === $existing_slug) {
                $existing_slug = advanced_username_manager_core_get_user_slug( $user_id );
                if (!empty($existing_slug)) {
                    wp_cache_set('aum_user_profile_slug_' . $user_id, $existing_slug, 'aum', 3600);
                    continue;
                }
            } elseif (!empty($existing_slug)) {
                continue;
            }
            
            // Generate a unique slug
            $uuid = strtolower(substr($prefix . sha1($user_id . wp_generate_password(40)), $start, $length));
            $bps_sql_data[] = $wpdb->prepare("(%d, %s, %s)", $user_id, 'aum_profile_slug', $uuid); // Remove the trailing comma and space.
            
            // Update the cache with the new slug
            wp_cache_set('aum_user_profile_slug_' . $user_id, $uuid, 'aum', 3600);
        }
        
        // Insert all slugs in a single query for this batch
        if (!empty($bps_sql_data)) {
            $sql = "INSERT INTO {$wpdb->usermeta} (user_id, meta_key, meta_value) VALUES " . implode(', ', $bps_sql_data);
            $wpdb->query($sql); // phpcs:ignore
        }
    }
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
    
    // Use object cache for frequently accessed data
    $cache_key = 'aum_user_profile_slug_' . $user_id;
    $profile_slug = wp_cache_get($cache_key, 'aum');
    
    if (false === $profile_slug) {
        $profile_slug = get_user_meta($user_id, 'aum_profile_slug', true);
        wp_cache_set($cache_key, $profile_slug, 'aum', 3600); // Cache for 1 hour
    }

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
	
	// Use cache for performance
    $cache_key = 'aum_member_slug_' . md5($member_slug);
    $user_id   = wp_cache_get($cache_key, 'aum');

	if (false === $user_id) {
        $user_id = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT user_id FROM `{$wpdb->usermeta}` WHERE meta_key = %s AND meta_value = %s LIMIT 1",
                'aum_profile_slug',
                $member_slug
            )
        );
        
        // Cache the result for future lookups
        wp_cache_set($cache_key, $user_id, 'aum', 3600); // Cache for 1 hour
    }

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
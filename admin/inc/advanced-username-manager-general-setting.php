<?php
/**
 * This file is used for rendering and saving plugin general settings.
 *
 * @link       www.wbcomdesigns.com
 * @since      1.0.0
 *
 * @package    bp-business-Advanced_Username_Manager
 * @subpackage Advanced_Username_Manager/admin/general-setitngs
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
global $wp_roles;
$roles            = $wp_roles->get_names();
$general_settings = get_option( 'advanced_username_manager_general_settings' );
$limit_options    = [
						'7'=> esc_html__('7 Days', 'advanced-username-manager' ), 
						'15'=> esc_html__('15 Days', 'advanced-username-manager' ), 
						'30'=> esc_html__('30 Days', 'advanced-username-manager' ), 
					];
					
$enable_username 		= ( isset( $general_settings['enable_username'] ) ) ? $general_settings['enable_username'] : '';
$enable_wc_username 	= ( isset( $general_settings['enable_wc_username'] ) ) ? $general_settings['enable_wc_username'] : '';
$user_roles 			= ( isset( $general_settings['user_roles'] ) ) ? $general_settings['user_roles'] : '';
$limit_days 			= ( isset( $general_settings['limit_days'] ) ) ? $general_settings['limit_days'] : '7';
$min_username_length	= ( isset( $general_settings['min_username_length'] ) ) ? $general_settings['min_username_length'] : '';
$max_username_length	= ( isset( $general_settings['max_username_length'] ) ) ? $general_settings['max_username_length'] : '';
$bp_profile_slug_format	= ( isset( $general_settings['bp_profile_slug_format'] ) ) ? $general_settings['bp_profile_slug_format'] : '';
?>
<div class="wbcom-tab-content">
	<div class="wbcom-welcome-main-wrapper">
		<div class="wbcom-admin-title-section">
			<h3 class="wbcom-welcome-title"><?php esc_html_e( 'General Settings', 'advanced-username-manager' ); ?></h3>			
		</div><!-- .wbcom-welcome-head -->
		<div class="wbcom-business-profile-wrapper">
			<div class="wbcom-admin-option-wrap wbcom-admin-option-wrap-view">	
				<form method="post" action="options.php">
					<?php
					settings_fields( 'advanced_username_manager_general_settings' );
					do_settings_sections( 'advanced_username_manager_general_settings' );					
					?>
					<div class="form-table">
						<?php if( class_exists( 'Buddypress' )  ){	?>	
							<div class="wbcom-settings-section-wrap">
								<div class="wbcom-settings-section-options-heading">
									<label><?php esc_html_e( 'Enable Username Change in Profile', 'advanced-username-manager' ); ?></label>
									<p class="description"><?php esc_html_e( 'Adds a tab in the Profile Settings for BuddyPress and BuddyBoss, allowing users to change their username from the front-end.', 'advanced-username-manager' ); ?></p>
								</div>
								<div class="wbcom-settings-section-options">
									<label class="wb-switch">
										<input name="advanced_username_manager_general_settings[enable_username]" type="checkbox" class="regular-text" value="yes" <?php checked( $enable_username, 'yes' ); ?> >
										<div class="wb-slider wb-round"></div>
									</label>
								</div>
							</div>
						<?php } ?>	
						<?php if( class_exists( 'WooCommerce' )  ){	?>		
							<div class="wbcom-settings-section-wrap">
								<div class="wbcom-settings-section-options-heading">
									<label><?php esc_html_e( 'Enable Username Change in Woocommerce', 'advanced-username-manager' ); ?></label>
									<p class="description"><?php esc_html_e( 'Adds a tab in the My Account for WooCommerce, allowing users to change their username from the front-end.', 'advanced-username-manager' ); ?></p>
								</div>
								<div class="wbcom-settings-section-options">
									<label class="wb-switch">
										<input name="advanced_username_manager_general_settings[enable_wc_username]" type="checkbox" class="regular-text" value="yes" <?php checked( $enable_wc_username, 'yes' ); ?> >
										<div class="wb-slider wb-round"></div>
									</label>
								</div>
							</div>	
						<?php } ?>
						<div class="wbcom-settings-section-wrap wbcom-select-user-role-section">
							<div class="wbcom-settings-section-options-heading">
								<label><?php esc_html_e( 'Select User Role(s)', 'advanced-username-manager' ); ?></label>
								<p class="description"><?php esc_html_e( 'Choose the user roles that are allowed to modify their username.', 'advanced-username-manager' ); ?></p>
							</div>
							<div class="wbcom-settings-section-options">
								<select class="aum-select" name="advanced_username_manager_general_settings[user_roles][]" multiple>
									<?php
									foreach ( $roles as $role => $role_name ) {
										if( $role == 'administrator' ) {
											continue;
										}
										$selected = ( ! empty( $general_settings['user_roles'] ) && in_array( $role, $general_settings['user_roles'] ) ) ? 'selected' : '';
										?>
									<option value="<?php echo esc_attr( $role ); ?>" <?php echo esc_attr( $selected ); ?>><?php echo esc_html( $role_name ); ?></option>
									<?php } ?>
								</select>
							</div>
						</div>
						
						<div class="wbcom-settings-section-wrap">
							<div class="wbcom-settings-section-options-heading">
								<label><?php esc_html_e( 'Username Change Limit', 'advanced-username-manager' ); ?></label>
								<p class="description"><?php esc_html_e( 'Set the number of days after which users can change their username again.', 'advanced-username-manager' ); ?></p>
							</div>
							<div class="wbcom-settings-section-options">
								<select name="advanced_username_manager_general_settings[limit_days]">
									<?php
									foreach ( $limit_options as $key => $value ) {?>
										<option value="<?php echo esc_attr( $key ); ?>" <?php selected($key, $limit_days) ?>><?php echo esc_html( $value ); ?></option>
									<?php } ?>
								</select>
							</div>
						</div>
						
						<div class="wbcom-settings-section-wrap">
							<div class="wbcom-settings-section-options-heading">
								<label><?php esc_html_e( 'Minimum Username Length', 'advanced-username-manager' ); ?></label>
								<p class="description"><?php esc_html_e( 'Set the minimum number of characters required for a username.', 'advanced-username-manager' ); ?></p>
							</div>
							<div class="wbcom-settings-section-options">								
								<input name="advanced_username_manager_general_settings[min_username_length]" type="text" class="regular-text" value="<?php echo esc_attr($min_username_length);?>" id="min_username_length">								
							</div>
						</div>
						
						<div class="wbcom-settings-section-wrap">
							<div class="wbcom-settings-section-options-heading">
								<label><?php esc_html_e( 'Maximum Username Length', 'advanced-username-manager' ); ?></label>
								<p class="description"><?php esc_html_e( 'Set the maximum number of characters allowed for a username.', 'advanced-username-manager' ); ?></p>
							</div>
							<div class="wbcom-settings-section-options">
								<input name="advanced_username_manager_general_settings[max_username_length]" type="text" class="regular-text" value="<?php echo esc_attr($max_username_length);?>" id="max_username_length">
							</div>
						</div>
						
						<?php if( class_exists( 'BuddyPress' ) && !buddypress()->buddyboss ) : ?>
							<div class="wbcom-settings-section-wrap">
								<div class="wbcom-settings-section-options-heading">
									<label><?php esc_html_e( 'User Profile Link Format', 'advanced-username-manager' ); ?></label>
									<p class="description"><?php esc_html_e( 'Choose the format for member profile links (e.g., /members/username). Both formats will work, ensuring previously shared links remain accessible.', 'advanced-username-manager' ); ?></p>
								</div>
								<div class="wbcom-settings-section-options">
									<select name="advanced_username_manager_general_settings[bp_profile_slug_format]">
										<option value="username" <?php selected('username', $bp_profile_slug_format) ?>><?php esc_html_e( 'Username', 'advanced-username-manager' )?></option>
										<option value="unique_identifier" <?php selected('unique_identifier', $bp_profile_slug_format) ?>><?php esc_html_e( 'Unique Identifier', 'advanced-username-manager' )?></option>
									</select>
								</div>
							</div>
						<?php else : ?>
							<input type="hidden" name="advanced_username_manager_general_settings[bp_profile_slug_format]" value="username" >
						<?php endif;?>

						<?php do_action( 'advanced_username_manager_add_general_setting_options' ); ?>
					</div>
					<?php submit_button(); ?>
				</form>
			</div>
		</div>
	</div>
</div>


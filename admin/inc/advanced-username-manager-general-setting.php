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
					
$enable_username 	= ( isset( $general_settings['enable_username'] ) ) ? $general_settings['enable_username'] : '';
$user_roles 		= ( isset( $general_settings['user_roles'] ) ) ? $general_settings['user_roles'] : '';
$limit_days 		= ( isset( $general_settings['limit_days'] ) ) ? $general_settings['limit_days'] : '7';
$min_username_length	= ( isset( $general_settings['min_username_length'] ) ) ? $general_settings['min_username_length'] : '';
$max_username_length	= ( isset( $general_settings['max_username_length'] ) ) ? $general_settings['max_username_length'] : '';
$allowed_characters	= ( isset( $general_settings['allowed_characters'] ) ) ? $general_settings['allowed_characters'] : '';
$prohibited_words	= ( isset( $general_settings['prohibited_words'] ) ) ? $general_settings['prohibited_words'] : '';
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
						
						<div class="wbcom-settings-section-wrap">
							<div class="wbcom-settings-section-options-heading">
								<label><?php esc_html_e( 'Enable username', 'advanced-username-manager' ); ?></label>
								<p class="description"><?php esc_html_e( 'Manage to change/update username', 'advanced-username-manager' ); ?></p>
							</div>
							<div class="wbcom-settings-section-options">
								<label class="wb-switch">
									<input name="advanced_username_manager_general_settings[enable_username]" type="checkbox" class="regular-text" value="yes" <?php checked( $enable_username, 'yes' ); ?> >
									<div class="wb-slider wb-round"></div>
								</label>
							</div>
						</div>		

						<div class="wbcom-settings-section-wrap">
							<div class="wbcom-settings-section-options-heading">
								<label><?php esc_html_e( 'Select User role(s)', 'woo-sell-services' ); ?></label>
								<p class="description"><?php esc_html_e( 'Select user roles base allow to modify username change', 'woo-sell-services' ); ?></p>
							</div>
							<div class="wbcom-settings-section-options">
								<select class="aum-select" name="advanced_username_manager_general_settings[user_roles][]" multiple>
									<?php
									foreach ( $roles as $role => $role_name ) {
										$selected = ( ! empty( $general_settings['user_roles'] ) && in_array( $role, $general_settings['user_roles'] ) ) ? 'selected' : '';
										?>
									<option value="<?php echo esc_attr( $role ); ?>" <?php echo esc_attr( $selected ); ?>><?php echo esc_html( $role_name ); ?></option>
									<?php } ?>
								</select>
							</div>
						</div>
						
						<div class="wbcom-settings-section-wrap">
							<div class="wbcom-settings-section-options-heading">
								<label><?php esc_html_e( 'Limit Days', 'woo-sell-services' ); ?></label>
								<p class="description"><?php esc_html_e( 'User can change the username base on selected period', 'woo-sell-services' ); ?></p>
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
								<label><?php esc_html_e( 'Minimum username length', 'advanced-username-manager' ); ?></label>
								<p class="description"><?php esc_html_e( 'User can set minumu username length', 'advanced-username-manager' ); ?></p>
							</div>
							<div class="wbcom-settings-section-options">								
								<input name="advanced_username_manager_general_settings[min_username_length]" type="text" class="regular-text" value="<?php echo esc_attr($min_username_length);?>" id="min_username_length">								
							</div>
						</div>
						
						<div class="wbcom-settings-section-wrap">
							<div class="wbcom-settings-section-options-heading">
								<label><?php esc_html_e( 'Maximum username length', 'advanced-username-manager' ); ?></label>
								<p class="description"><?php esc_html_e( 'User can set maximum username length', 'advanced-username-manager' ); ?></p>
							</div>
							<div class="wbcom-settings-section-options">
								<input name="advanced_username_manager_general_settings[max_username_length]" type="text" class="regular-text" value="<?php echo esc_attr($max_username_length);?>" id="max_username_length">
							</div>
						</div>
						
						<div class="wbcom-settings-section-wrap">
							<div class="wbcom-settings-section-options-heading">
								<label><?php esc_html_e( 'Allowed characters.', 'advanced-username-manager' ); ?></label>
								<p class="description"><?php esc_html_e( 'Only Allowed characters', 'advanced-username-manager' ); ?></p>
							</div>
							<div class="wbcom-settings-section-options">
								<input name="advanced_username_manager_general_settings[allowed_characters]" type="text" class="regular-text" value="<?php echo esc_attr($allowed_characters);?>" id="allowed_characters">
							</div>
						</div>
						
						<div class="wbcom-settings-section-wrap">
							<div class="wbcom-settings-section-options-heading">
								<label><?php esc_html_e( 'Prohibited words or patterns.', 'advanced-username-manager' ); ?></label>
								<p class="description"><?php esc_html_e( 'Don\'t allowed prohibited words or patterns ', 'advanced-username-manager' ); ?></p>
							</div>
							<div class="wbcom-settings-section-options">
								<input name="advanced_username_manager_general_settings[prohibited_words]" type="text" class="regular-text" value="<?php echo esc_attr($prohibited_words);?>" id="prohibited_words">
							</div>
						</div>
						
						

						<?php do_action( 'bp_business_profile_add_general_setting_options' ); ?>
					</div>
					<?php submit_button(); ?>
				</form>
			</div>
		</div>
	</div>
</div>


<?php
// this is the URL our updater / license checker pings. This should be the URL of the site with EDD installed
if ( ! defined( 'ADVANCED_USERNAME_MANAGER_STORE_URL' ) ) {
	define( 'ADVANCED_USERNAME_MANAGER_STORE_URL', 'https://wbcomdesigns.com/' ); // you should use your own CONSTANT name, and be sure to replace it throughout this file
}

// the name of your product. This should match the download name in EDD exactly
if ( ! defined( 'ADVANCED_USERNAME_MANAGER_ITEM_NAME' ) ) {
	define( 'ADVANCED_USERNAME_MANAGER_ITEM_NAME', 'Advanced Username Manager' ); // you should use your own CONSTANT name, and be sure to replace it throughout this file
}

// the name of the settings page for the license input to be displayed
if ( ! defined( 'ADVANCED_USERNAME_MANAGER_PLUGIN_LICENSE_PAGE' ) ) {
	define( 'ADVANCED_USERNAME_MANAGER_PLUGIN_LICENSE_PAGE', 'wbcom-license-page' );
}

if ( ! class_exists( 'ADVANCED_USERNAME_MANAGER_PLUGIN_UPDATER' ) ) {
	// load our custom updater.
	include dirname( __FILE__ ) . '/edd_plugin_updater.php';
}

function advanced_username_manager_plugin_updater() {
	// retrieve our license key from the DB.
	$license_key = trim( get_option( 'edd_wbcom_advanced_username_manager_license_key' ) );

	// setup the updater
	$edd_updater = new ADVANCED_USERNAME_MANAGER_PLUGIN_UPDATER(
		ADVANCED_USERNAME_MANAGER_STORE_URL,
		ADVANCED_USERNAME_MANAGER_FILE,
		array(
			'version'   => ADVANCED_USERNAME_MANAGER_VERSION,             // current version number.
			'license'   => $license_key,        // license key (used get_option above to retrieve from DB).
			'item_name' => ADVANCED_USERNAME_MANAGER_ITEM_NAME,  // name of this plugin.
			'author'    => 'wbcomdesigns',  // author of this plugin.
			'url'       => home_url(),
		)
	);
}
add_action( 'admin_init', 'advanced_username_manager_plugin_updater', 0 );


/************************************
 * the code below is just a standard
 * options page. Substitute with
 * your own.
 */
function edd_wbcom_advanced_username_manager_register_option() {
	// creates our settings in the options table
	register_setting( 'edd_wbcom_advanced_username_manager_license', 'edd_wbcom_advanced_username_manager_license_key', 'advanced_username_manager_sanitize_license' );
}
add_action( 'admin_init', 'edd_wbcom_advanced_username_manager_register_option' );

function advanced_username_manager_sanitize_license( $new ) {
	$old = get_option( 'edd_wbcom_advanced_username_manager_license_key' );
	if ( $old && $old != $new ) {
		delete_option( 'edd_wbcom_advanced_username_manager_license_status' ); // new license has been entered, so must reactivate
	}
	return $new;
}



/************************************
 * this illustrates how to activate
 * a license key
 *************************************/

function edd_wbcom_advanced_username_manager_activate_license() {
	// listen for our activate button to be clicked
	if ( isset( $_POST['ADVANCED_USERNAME_MANAGER_license_activate'] ) ) {
		// run a quick security check
		if ( ! check_admin_referer( 'edd_wbcom_advanced_username_manager_nonce', 'edd_wbcom_advanced_username_manager_nonce' ) ) {
			return; // get out if we didn't click the Activate button
		}

		// retrieve the license from the database
		$license = isset( $_POST['edd_wbcom_advanced_username_manager_license_key'] ) ? sanitize_text_field( wp_unslash( $_POST['edd_wbcom_advanced_username_manager_license_key'] ) ) : '';

		// data to send in our API request
		$api_params = array(
			'edd_action' => 'activate_license',
			'license'    => $license,
			'item_name'  => urlencode( ADVANCED_USERNAME_MANAGER_ITEM_NAME ), // the name of our product in EDD
			'url'        => home_url(),
		);

		// Call the custom API.
		$response = wp_remote_post(
			ADVANCED_USERNAME_MANAGER_STORE_URL,
			array(
				'timeout'   => 15,
				'sslverify' => false,
				'body'      => $api_params,
			)
		);

		// make sure the response came back okay
		if ( is_wp_error( $response ) || 200 != wp_remote_retrieve_response_code( $response ) ) {
			if ( is_wp_error( $response ) ) {
				$message = $response->get_error_message();
			} else {
				$message = esc_html__( 'An error occurred, please try again.', 'advanced-username-manager' );
			}
		} else {
			$license_data = json_decode( wp_remote_retrieve_body( $response ) );

			if ( false == $license_data->success ) {
				switch ( $license_data->error ) {
					case 'expired':
						/* translators: %s is the license expiration date */
						$message = sprintf( esc_html__( 'Your license key expired on %s.', 'advanced-username-manager' ),
							date_i18n( get_option( 'date_format' ), strtotime( $license_data->expires, current_time( 'timestamp' ) ) )
						);
						break;

					case 'revoked':
						$message = esc_html__( 'Your license key has been disabled.', 'advanced-username-manager' );
						break;

					case 'missing':
						$message = esc_html__( 'Invalid license.', 'advanced-username-manager' );
						break;

					case 'invalid':
					case 'site_inactive':
						$message = esc_html__( 'Your license is not active for this URL.', 'advanced-username-manager' );
						break;

					case 'item_name_mismatch':
						/* translators: %s is the plugin mame */
						$message = sprintf( esc_html__( 'This appears to be an invalid license key for %s.', 'advanced-username-manager' ), ADVANCED_USERNAME_MANAGER_ITEM_NAME );
						break;

					case 'no_activations_left':
						$message = esc_html__( 'Your license key has reached its activation limit.', 'advanced-username-manager' );
						break;

					default:
						$message = esc_html__( 'An error occurred, please try again.', 'advanced-username-manager' );
						break;
				}
			} else {
				set_transient( 'edd_wbcom_advanced_username_manager_license_key_data', $license_data, 12 * HOUR_IN_SECONDS );
			}
		}

		// Check if anything passed on a message constituting a failure
		if ( ! empty( $message ) ) {
			$base_url = admin_url( 'admin.php?page=' . ADVANCED_USERNAME_MANAGER_PLUGIN_LICENSE_PAGE );
			$redirect = add_query_arg(
				array(
					'bp_business_profile_activation' => 'false',
					'message'                        => urlencode( $message ),
				),
				$base_url
			);
			$license  = trim( $license );
			update_option( 'edd_wbcom_advanced_username_manager_license_key', $license );
			if ( ! empty( $license_data ) ) {
				update_option( 'edd_wbcom_advanced_username_manager_license_status', $license_data->license );
			}
			wp_redirect( $redirect );
			exit();
		}

		// $license_data->license will be either "valid" or "invalid"
		$license = trim( $license );
		update_option( 'edd_wbcom_advanced_username_manager_license_key', $license );
		if ( ! empty( $license_data ) ) {
			update_option( 'edd_wbcom_advanced_username_manager_license_status', $license_data->license );
		}
		wp_redirect( admin_url( 'admin.php?page=' . ADVANCED_USERNAME_MANAGER_PLUGIN_LICENSE_PAGE ) );
		exit();
	}
}
add_action( 'admin_init', 'edd_wbcom_advanced_username_manager_activate_license' );


/***********************************************
 * Illustrates how to deactivate a license key.
 * This will decrease the site count
 ***********************************************/

function edd_wbcom_advanced_username_manager_deactivate_license() {
	// listen for our activate button to be clicked
	if ( isset( $_POST['ADVANCED_USERNAME_MANAGER_license_deactivate'] ) ) {
		// run a quick security check
		if ( ! check_admin_referer( 'edd_wbcom_advanced_username_manager_nonce', 'edd_wbcom_advanced_username_manager_nonce' ) ) {
			return; // get out if we didn't click the Activate button
		}

		// retrieve the license from the database
		$license = trim( get_option( 'edd_wbcom_advanced_username_manager_license_key' ) );

		// data to send in our API request
		$api_params = array(
			'edd_action' => 'deactivate_license',
			'license'    => $license,
			'item_name'  => urlencode( ADVANCED_USERNAME_MANAGER_ITEM_NAME ), // the name of our product in EDD
			'url'        => home_url(),
		);

		// Call the custom API.
		$response = wp_remote_post(
			ADVANCED_USERNAME_MANAGER_STORE_URL,
			array(
				'timeout'   => 15,
				'sslverify' => false,
				'body'      => $api_params,
			)
		);

		// make sure the response came back okay
		if ( is_wp_error( $response ) || 200 != wp_remote_retrieve_response_code( $response ) ) {
			if ( is_wp_error( $response ) ) {
				$message = $response->get_error_message();
			} else {
				$message = esc_html__( 'An error occurred, please try again.', 'advanced-username-manager' );
			}

			$base_url = admin_url( 'admin.php?page=' . ADVANCED_USERNAME_MANAGER_PLUGIN_LICENSE_PAGE );
			$redirect = add_query_arg(
				array(
					'bp_business_profile_activation' => 'false',
					'message'                        => urlencode( $message ),
				),
				$base_url
			);

			wp_redirect( $redirect );
			exit();
		}

		// decode the license data
		$license_data = json_decode( wp_remote_retrieve_body( $response ) );
		delete_transient( 'edd_wbcom_advanced_username_manager_license_key_data' );

		// $license_data->license will be either "deactivated" or "failed"
		if ( $license_data->license == 'deactivated' || 'failed' === $license_data->license ) {
			delete_option( 'edd_wbcom_advanced_username_manager_license_status' );
		}

		wp_redirect( admin_url( 'admin.php?page=' . ADVANCED_USERNAME_MANAGER_PLUGIN_LICENSE_PAGE ) );
		exit();
	}
}
add_action( 'admin_init', 'edd_wbcom_advanced_username_manager_deactivate_license' );


/************************************
 * this illustrates how to check if
 * a license key is still valid
 * the updater does this for you,
 * so this is only needed if you
 * want to do something custom
 *************************************/
add_action( 'admin_init', 'edd_wbcom_advanced_username_manager_check_license' );
function edd_wbcom_advanced_username_manager_check_license() {
	global $wp_version, $pagenow;

	if ( $pagenow === 'plugins.php' || $pagenow === 'index.php' || ( isset( $_GET['page'] ) && $_GET['page'] === 'wbcom-license-page' ) ) {

		$license_data = get_transient( 'edd_wbcom_advanced_username_manager_license_key_data' );
		$license      = trim( get_option( 'edd_wbcom_advanced_username_manager_license_key' ) );

		if ( empty( $license_data ) && $license != '' ) {

			$api_params = array(
				'edd_action' => 'check_license',
				'license'    => $license,
				'item_name'  => urlencode( ADVANCED_USERNAME_MANAGER_ITEM_NAME ),
				'url'        => home_url(),
			);

			// Call the custom API.
			$response = wp_remote_post(
				ADVANCED_USERNAME_MANAGER_STORE_URL,
				array(
					'timeout'   => 15,
					'sslverify' => false,
					'body'      => $api_params,
				)
			);

			if ( is_wp_error( $response ) ) {
				return false;
			}

			$license_data = json_decode( wp_remote_retrieve_body( $response ) );

			if ( ! empty( $license_data ) ) {
				set_transient( 'edd_wbcom_advanced_username_manager_license_key_data', $license_data, 12 * HOUR_IN_SECONDS );
			}
		}
	}
}

/**
 * This is a means of catching errors from the activation method above and displaying it to the customer
 */
function edd_wbcom_advanced_username_manager_admin_notices() {

	$license_activation = filter_input( INPUT_GET, 'bp_business_profile_activation' ) ? filter_input( INPUT_GET, 'bp_business_profile_activation' ) : '';
	$error_message      = filter_input( INPUT_GET, 'message' ) ? filter_input( INPUT_GET, 'message' ) : '';
	$license_data       = get_transient( 'edd_wbcom_advanced_username_manager_license_key_data' );
	$license            = trim( get_option( 'edd_wbcom_advanced_username_manager_license_key' ) );

	if ( isset( $license_activation ) && ! empty( $error_message ) || ( ! empty( $license_data ) && $license_data->license == 'expired' ) ) {
		if ( $license_activation === '' && !empty( $license_data) ) {
			$license_activation = $license_data->license;
		}
		switch ( $license_activation ) {
			case 'expired':
				?>
				<div class="notice notice-error is-dismissible">
				<p>
				<?php
				echo sprintf(
					/* translators: %1$s: Expire Time*/
					esc_html__( 'Your  Wbcom Designs - BuddyPress Business Profile plugin license key expired on %s.', 'advanced-username-manager' ),
					esc_html( date_i18n( get_option( 'date_format' ), strtotime( esc_html( $license_data->expires ), current_time( 'timestamp' ) ) ) )
				);
				?>
				</p>
				</div>
				<?php

				break;
			case 'false':
				$message = urldecode( $error_message );
				?>
				<div class="error">
					<p><?php echo esc_html( $message ); ?></p>
				</div>
				<?php
				break;

			case 'true':
			default:
				// Developers can put a custom success message here for when activation is successful if they way.
				break;
		}
	}

	if ( $license === '' ) {
		?>
		<div class="notice notice-error is-dismissible">
			<p>
			<?php
			echo esc_html__( 'Please activate your  Wbcom Designs - BuddyPress Business Profile plugin license key.', 'advanced-username-manager' );
			?>
			</p>			
		</div>
		<?php
	}

}
add_action( 'admin_notices', 'edd_wbcom_advanced_username_manager_admin_notices' );

add_action( 'wbcom_add_plugin_license_code', 'wbcom_advanced_username_manager_render_license_section' );
function wbcom_advanced_username_manager_render_license_section() {

	$license = get_option( 'edd_wbcom_advanced_username_manager_license_key', true );
	$status  = get_option( 'edd_wbcom_advanced_username_manager_license_status' );

	$license_output = advanced_username_manager_active_license_message();

	if ( false !== $status && 'valid' === $status && isset( $license_output['license_data'] ) && ! empty( $license_output['license_data'] ) && $license_output['license_data']->license == 'valid' ) {
		$status_class = 'active';
		$status_text  = 'Active';
	} elseif ( isset( $license_output['license_data'] ) && ! empty( $license_output['license_data'] ) && $license_output['license_data']->license == 'expired' ) {
		$status_class = 'expired';
		$status_text  = ucfirst( str_replace( '_', ' ', $license_output['license_data']->license ) );

	} elseif ( isset( $license_output['license_data'] ) && ! empty( $license_output['license_data'] ) && $license_output['license_data']->license == 'invalid' ) {
		$status_class = 'invalid';
		$status_text  = ucfirst( str_replace( '_', ' ', $license_output['license_data']->license ) );

	} else {
		$status_class = 'inactive';
		$status_text  = 'Inactive';
	}
	?>
	<table class="form-table wb-license-form-table mobile-license-headings">
		<thead>
			<tr>
				<th class="wb-product-th"><?php esc_html_e( 'Product', 'advanced-username-manager' ); ?></th>
				<th class="wb-version-th"><?php esc_html_e( 'Version', 'advanced-username-manager' ); ?></th>
				<th class="wb-key-th"><?php esc_html_e( 'Key', 'advanced-username-manager' ); ?></th>
				<th class="wb-status-th"><?php esc_html_e( 'Status', 'advanced-username-manager' ); ?></th>
				<th class="wb-action-th"><?php esc_html_e( 'Action', 'advanced-username-manager' ); ?></th>
				<th></th>
			</tr>
		</thead>
	</table>
	<form method="post" action="options.php">
		<?php settings_fields( 'edd_wbcom_advanced_username_manager_license' ); ?>
		<table class="form-table wb-license-form-table">
			<tr>
				<td class="wb-plugin-name"><?php echo esc_html( ADVANCED_USERNAME_MANAGER_ITEM_NAME ); ?></td>
				<td class="wb-plugin-version"><?php echo esc_html( ADVANCED_USERNAME_MANAGER_VERSION ); ?></td>
				<td class="wb-plugin-license-key">
					<input id="edd_wbcom_advanced_username_manager_license_key" name="edd_wbcom_advanced_username_manager_license_key" type="text" class="regular-text" value="<?php echo esc_attr( $license ); ?>" />
					<p><?php echo esc_html( $license_output['message'] ); ?></p>
				</td>
				<td class="wb-license-status <?php echo esc_attr( $status_class ); ?>"><?php echo esc_html( $status_text ); ?></td>
				<td class="wb-license-action">
					<?php
					if ( $status != false && $status == 'valid' ) {
						wp_nonce_field( 'edd_wbcom_advanced_username_manager_nonce', 'edd_wbcom_advanced_username_manager_nonce' );
						?>
						<input type="submit" class="button-secondary" name="ADVANCED_USERNAME_MANAGER_license_deactivate" value="<?php esc_html_e( 'Deactivate License', 'advanced-username-manager' ); ?>"/>
						<?php
					} else {
						wp_nonce_field( 'edd_wbcom_advanced_username_manager_nonce', 'edd_wbcom_advanced_username_manager_nonce' );
						?>
						<input type="submit" class="button-secondary" name="ADVANCED_USERNAME_MANAGER_license_activate" value="<?php esc_html_e( 'Activate License', 'advanced-username-manager' ); ?>"/>
					<?php } ?>
				</td>
			</tr>
		</table>
	</form>
	<?php
}

/**
 * License activation message
 *
 * @return array $output store license data.
 */
function advanced_username_manager_active_license_message() {
	global $wp_version, $pagenow;

	if ( $pagenow === 'plugins.php' || $pagenow === 'index.php' || ( isset( $_GET['page'] ) && $_GET['page'] === 'wbcom-license-page' ) ) {

		$license_data = get_transient( 'edd_wbcom_advanced_username_manager_license_key_data' );
		$license      = trim( get_option( 'edd_wbcom_advanced_username_manager_license_key' ) );

			$api_params = array(
				'edd_action' => 'check_license',
				'license'    => $license,
				'item_name'  => urlencode( ADVANCED_USERNAME_MANAGER_ITEM_NAME ),
				'url'        => home_url(),
			);

			// Call the custom API.
			$response = wp_remote_post(
				ADVANCED_USERNAME_MANAGER_STORE_URL,
				array(
					'timeout'   => 15,
					'sslverify' => false,
					'body'      => $api_params,
				)
			);

		if ( is_wp_error( $response ) ) {
			return false;
		}

			$output                 = array();
			$output['license_data'] = json_decode( wp_remote_retrieve_body( $response ) );
			$message                = '';
			// make sure the response came back okay
		if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {

			if ( is_wp_error( $response ) ) {
				$message = $response->get_error_message();
			} else {
				$message = __( 'An error occurred, please try again.', 'advanced-username-manager' );
			}
		} else {
			$license_data = json_decode( wp_remote_retrieve_body( $response ) );
			// Get expire date
			$expires = false;
			if ( isset( $license_data->expires ) && 'lifetime' != $license_data->expires ) {
				$expires = date_i18n( get_option( 'date_format' ), strtotime( $license_data->expires, current_time( 'timestamp' ) ) );
			} elseif ( isset( $license_data->expires ) && 'lifetime' == $license_data->expires ) {
				$expires = 'lifetime';
			}

			if ( $license_data->license == 'valid' ) {
				// Get site counts
				$site_count    = $license_data->site_count;
				$license_limit = $license_data->license_limit;
				$message       = 'License key is active.';
				if ( isset( $expires ) && 'lifetime' != $expires ) {
					/* translators: %s is the license expiration date */
					$message .= sprintf( __( ' Expires %s.', 'advanced-username-manager' ), $expires ) . ' ';
				}
				if ( $license_limit ) {
					/* translators: 1: activated sites count, 2: total site license limit */
					$message .= sprintf( __( 'You have %1$s/%2$s-sites activated.', 'advanced-username-manager' ), $site_count, $license_limit );
				}
			}
		}
			$output['message'] = $message;
			return $output;
	}
}

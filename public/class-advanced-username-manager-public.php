<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://https://https://wbcomdesigns.com/
 * @since      1.0.0
 *
 * @package    Advanced_Username_Manager
 * @subpackage Advanced_Username_Manager/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Advanced_Username_Manager
 * @subpackage Advanced_Username_Manager/public
 * @author     Wbcom Designs <info@wbcomdesign.com>
 */
class Advanced_Username_Manager_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		global $aum_general_settings;
		$this->plugin_name = $plugin_name;
		$this->version = $version;		
		add_shortcode('username_manager', [$this, 'advanced_username_manager_change_username_func'] );
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Advanced_Username_Manager_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Advanced_Username_Manager_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) {
			$extension = '.css';
			$path      = is_rtl() ? '/rtl' : '';
		} else {
			$extension = '.min.css';
			$path      = is_rtl() ? '/rtl' : '/min';
		}

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css' . $path . '/advanced-username-manager-public' . $extension, array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Advanced_Username_Manager_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Advanced_Username_Manager_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		global $aum_general_settings;

		if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) {
			$extension = '.js';
			$path      = '';
		} else {
			$extension = '.min.js';
			$path      = '/min';
		}

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js' . $path . '/advanced-username-manager-public' . $extension, array( 'jquery' ), $this->version, false );

		$min_username_length	= ( isset( $aum_general_settings['min_username_length'] ) && $aum_general_settings['min_username_length'] != ''  )? $aum_general_settings['min_username_length'] : 5;
		$max_username_length	= ( isset( $aum_general_settings['max_username_length'] ) && $aum_general_settings['max_username_length'] != ''  )? $aum_general_settings['max_username_length'] : 12;		
		$js_object = array(
			'ajaxurl'                           => admin_url( 'admin-ajax.php' ),
			'ajax_nonce'                        => wp_create_nonce( 'advanced-username-change' ),			
			'limit_days'				=> ( isset( $aum_general_settings['limit_days'] ) && $aum_general_settings['limit_days'] != ''  ) ? $aum_general_settings['limit_days'] : 7,
			'min_username_length'				=> $min_username_length,
			'max_username_length'				=> $max_username_length,						
			'min_username_error'			=> sprintf(esc_html__('Username must be at least %s characters long.', 'advanced-username-manager' ), esc_attr($min_username_length) ),
			'max_username_error'			=> sprintf(esc_html__('Username must not exceed %s characters.', 'advanced-username-manager' ), esc_attr($max_username_length)),			
		);
		wp_localize_script( $this->plugin_name, 'aum_options', $js_object );

	}
	
	
	/**
	 * Call the "username_manager" shortcode callback function
	 *
	 * @since    1.0.0
	 */
	
	public function advanced_username_manager_change_username_func( $atts, $content ) {
		global $aum_general_settings;		
		
		
		ob_start();
		if( !isset($aum_general_settings['enable_username']) ) {
			?>
			<div class="aum-error">
				<?php echo  esc_html__( 'Username changes are temporarily disabled. Please try again later or contact support for assistance.', 'advanced-username-manager' ); ?>
			</div>
			<?php
			return ob_get_clean();
		}
		
		if( !is_user_logged_in() || ( isset($aum_general_settings['enable_username']) && $aum_general_settings['enable_username']!= 'yes' ) ) {
			?>
			<div class="aum-error">
				<?php echo esc_html__( 'To change your username, please log in first.', 'advanced-username-manager' ); ?>
			</div>
			<?php
			return ob_get_clean();
		}
		
		if (  function_exists( 'bp_is_user') && bp_is_user() ) {
			$current_user 		= get_userdata(bp_displayed_user_id());
		} else {
			$current_user 		= wp_get_current_user();
		}
		
		$current_user_roles	= $current_user->roles;
		if( is_array($aum_general_settings['user_roles']) ) {
			$aum_general_settings['user_roles'] = array_merge( $aum_general_settings['user_roles'], ['administrator']);
		}
		if( is_array($aum_general_settings['user_roles']) && empty( array_intersect( $current_user_roles, $aum_general_settings['user_roles']) ) ) {
			?>
			<div class="aum-error">
				<?php echo esc_html__( 'You are not allowed to change the username. Please contact the administrator for assistance.', 'advanced-username-manager' ); ?>
			</div>
			<?php
			return ob_get_clean();
			
		}
		
		?>
			<form name="advanced_username_change" method="post" class="aum-standard-form">
				<div class="aum-input-field">
					<label for="current_user_name"><?php esc_html_e( 'Current Username', 'advanced-username-manager' ) ?></label>
					<input type="text" name="current_user_name" id="aum_current_user_name" value="<?php echo esc_attr( $current_user->user_login ); ?>" class="settings-input" disabled="disabled"/>
				</div>
				<div id="aum_new_user_input_field" class="aum-input-field">
					<label for="new_user_name"><?php esc_html_e( 'New Username', 'advanced-username-manager' ) ?></label>
					<input type="text" name="new_user_name" id="aum_new_user_name" value="" class="settings-input"/>
					<ul id="aum-autocomplete-suggestions"></ul>
				</div>				
				
				<?php wp_nonce_field( 'advanced-username-change' ); ?>

				<p class="submit">
					<input type="hidden" id="aum_user_id" name="user_id" value="<?php echo esc_attr($current_user->ID); ?>" />
					<input type="submit" id="username_change_submit" name="username_change_submit" class="button" value="<?php esc_html_e( 'Save Changes', 'advanced-username-manager' ) ?>" disabled/>
				</p>
				<input type="hidden" id="bp_is_my_profile" name="bp_is_my_profile" value="<?php echo esc_attr( ( function_exists( 'bp_is_user' ) ) ? bp_is_user() : '' ); ?>" />
			</form>
		<?php
		return ob_get_clean();
	}
	
	/**
	 * update username using ajax
	 *
	 * @since    1.0.0
	 */
	public function advanced_username_manager_update_username() {
		
		check_ajax_referer( 'advanced-username-change', 'nonce' );
		
		global $wpdb, $aum_general_settings;
		$table_name 	= $wpdb->prefix . 'username_change_logs';	
		$loggedin_user_id		= get_current_user_id();		
		$user_id		= sanitize_text_field( wp_unslash( $_POST['aum_user_id'] ) );
		$limit_days		= ( isset( $aum_general_settings['limit_days'] ) && $aum_general_settings['limit_days'] != ''  )? $aum_general_settings['limit_days'] : '7';
		$limit_days_ago = date( 'Y-m-d', strtotime( '-'. $limit_days .' days' ) );		
		
		// Get the results		
		$results = $wpdb->get_var( $wpdb->prepare( "SELECT created_date FROM {$table_name} WHERE created_date >= %s and user_id=%d order by id DESC LIMIT 1", 
									$limit_days_ago, 
									$user_id ) 
								);		
		if( $results != '' ){
			$next_date = date_i18n('Y-m-d', strtotime( $results .' +'. $limit_days .' days' ) );
		 	$retval = array(			
				'error_message'	=> sprintf(esc_html__( 'You already updated your username. You can update your username after %s', 'advanced-username-manager' ) , esc_html($next_date)),
			);
			wp_send_json_error( $retval );
		}
		
		
		$new_user_name		= ( isset($_POST['new_user_name']) && !empty($_POST['new_user_name'])) ? sanitize_text_field( wp_unslash( $_POST['new_user_name'] ) ) : '';
		$current_user_name	= ( isset($_POST['current_user_name']) && !empty($_POST['current_user_name'])) ? sanitize_text_field( wp_unslash( $_POST['current_user_name'] ) ) : '';
		$bp_is_my_profile	= ( isset($_POST['bp_is_my_profile']) && !empty($_POST['bp_is_my_profile'])) ? sanitize_text_field( wp_unslash( $_POST['bp_is_my_profile'] ) ) : '';
		if( $current_user_name == '' ) {
			$current_user 		= wp_get_current_user();
			$current_user_name 	= $current_user->user_login;
		}
		
		
		if ( empty( $new_user_name ) || ! validate_username( $new_user_name ) ) {
			
			$retval = array(				
				'error_message'	=> esc_html__( 'Sorry, this username is invalid because it uses illegal characters. Please enter a valid username.', 'advanced-username-manager' ),
			);
			wp_send_json_error( $retval );
		} 
		
		
		if ( $current_user_name == $new_user_name ) {
			$retval = array(			
				'error_message'	=> esc_html__( 'Please enter a different Username!', 'advanced-username-manager' ),
			);
			wp_send_json_error( $retval );
		}
		
		if ( username_exists( $new_user_name ) ) {			
			
			$retval = array(				
				'error_message'	=> esc_html__( 'Sorry, this username is already in use. Please try another one.', 'advanced-username-manager' ),
			);
			wp_send_json_error( $retval );
		}		
		
		// if it is multisite, before change the username, revoke the admin capability.
		if ( is_multisite() && is_super_admin( $user_id ) ) {

			if ( ! function_exists( 'revoke_super_admin' ) ) {
				require_once( ABSPATH . 'wp-admin/includes/ms.php' );
			}

			$is_super_admin = true;
			revoke_super_admin( $user_id );
		}
		
		// wp_update_user() doesn't update user_login when updating a user... sucks!
		wp_update_user( array(
			'ID'            => $user_id,
			'user_login'    => $new_user_name,
			'user_nicename' => sanitize_title( $new_user_name ),
			'display_name' => sanitize_title( $new_user_name ),
		) );
		
		// manually update user_login.
		$wpdb->update( $wpdb->users, array( 'user_login' => $new_user_name ), array( 'ID' => $user_id ), array( '%s' ), array( '%d' ) );
		
		// if multisite and the user was super admin, mark him back as super admin.
		if ( is_multisite() && $is_super_admin ) {
			grant_super_admin( $user_id );
		}
		
		$user = new WP_User( $user_id );
		
		$wpdb->insert(
						$table_name,
						array(
							'user_id'       => $user_id,
							'old_username'  => $current_user_name,
							'new_username'  => $new_user_name,
							'created_date'	=> date_i18n( 'Y-m-d H:i:s' ),
						)
					);
					
		$this->advanced_username_manager_send_mail( $user_id, $new_user_name );
		
		/* Auto Login after change the username */
		// delete object cache.
		clean_user_cache( $user_id );
		wp_cache_delete( $user_id, 'users' );
		if( class_exists( 'BuddyPress' ) ) {
			wp_cache_delete( 'bp_core_userdata_' . $user_id, 'bp' );
			wp_cache_delete( 'bp_user_username_' . $user_id, 'bp' );
			wp_cache_delete( 'bp_user_domain_' . $user_id, 'bp' );		
		}
		
		
		
		if( (int)$loggedin_user_id === (int)$user_id) {
			// Here we calculate the expiration length of the current auth cookie and compare it to the default expiration.
			wp_clear_auth_cookie();
			
			// Here we calculate the expiration length of the current auth cookie and compare it to the default expiration.
			// If it's greater than this, then we know the user checked 'Remember Me' when they logged in.
			$logged_in_cookie = wp_parse_auth_cookie( '', 'logged_in' );

			/** This filter is documented in wp-includes/pluggable.php */
			$default_cookie_life = apply_filters( 'auth_cookie_expiration', ( 2 * DAY_IN_SECONDS ), $user_id, false );
			$remember            = ( ( $logged_in_cookie['expiration'] - time() ) > $default_cookie_life );

			wp_set_auth_cookie( $user_id, $remember );
		}
		
		
		// hook for plugins.
		do_action( 'advanced_username_changed', $new_user_name, $user );
		
		$result['success_message'] = esc_html__( 'Username has beed changed Successfully!', 'advanced-username-manager' );
		
		$redirect_url = '';		
		if ( function_exists( 'bp_is_my_profile' ) && $bp_is_my_profile ) {
			if ( function_exists( 'bp_members_get_user_url' ) ) {
				$bp = buddypress();
				// Updating because of bp_members_get_user_slug function.
				$bp->loggedin_user->userdata->user_nicename = $user->user_nicename;
				$bp->loggedin_user->userdata->user_login    = $user->user_login;

				$redirect_url = bp_members_get_user_url( $user->ID, array(
					'single_item_component' => 'settings',
					'single_item_action'    => 'username-change',
				) );
			} else {
				$redirect_url = bp_core_get_user_domain( $user_id, $user->user_nicename, $user->user_login ) . $bp->settings->slug . '/username-change/';
			}
		}
		$result['redirect_url'] = $redirect_url;
		
		wp_send_json_success( $result );
	}
	
	
	/**
	 * send the email to the user about the  change the username
	 *
	 * @since    1.0.0
	 */
	public function advanced_username_manager_send_mail( $user_id, $new_user_name ) {
		$user 			= get_userdata( $user_id );
		$site_name     	= get_bloginfo( 'name' );
		$user_email   	= $user->user_email;
		$email_subject 	= apply_filters( 'advanced_username_manager_email_subject', sprintf( esc_html__('%s Your Username Has Been Successfully Updated', 'advanced-username-manager'), $site_name ) );
		$email_content 	= "<p>Hello {$user->display_name},</p>

<p>We're writing to let you know that your username on MyAwesomeSite has been successfully updated.</p>

<p>Your new username is: <strong>{$new_user_name}</strong></p>

<p>If you did not request this change, please contact our support team immediately to secure your account.</p>

<p>Thank you for being part of {$site_name}!</p>

<p>Best regards, <br />
The {$site_name} Team</p>";
		$email_content = apply_filters( 'bp_business_review_email_content', $email_content );

		$headers = array( 'Content-Type: text/html; charset=UTF-8' );
		wp_mail( $user_email, $email_subject, $email_content, $headers );		
	}
	
	/**
	 * Check the user name availability
	 *
	 * @since    1.0.0
	 */
	public function aum_check_username_availability() {
		// Check nonce for security

		check_ajax_referer( 'advanced-username-change', 'nonce' );

		$username = ( isset($_POST['username']) && !empty($_POST['username']) ) ? sanitize_text_field($_POST['username']) : '';

		if (strlen($username) < 3) {

			wp_send_json_error(['message' => esc_html__('Username must be at least 3 characters long.', 'advanced-username-manager')]);

		}

		// Check if username exists in the database

		if ( username_exists($username) ) {

			$suggestions = [];
			// Add suffixes
			$suggestions[] = $username . '123';
			$suggestions[] = $username . date('Y');
			$suggestions[] = $username . date('d');
			// Add prefixes
			$suggestions[] = 'my' . ucfirst($username);
			$suggestions[] = 'the' . ucfirst($username);
			$suggestions[] = 'best' . ucfirst($username);
			$suggestions[] = $username . rand(100, 999);			
			wp_send_json_success(['available' => false, 'suggestions' => array_unique($suggestions), 'message' => esc_html__( 'Sorry, this username is already in use. you can select from following suggestion.', 'advanced-username-manager')]);
		} else {

			wp_send_json_success(['available' => true,'message'=> esc_html__( 'Great! This username is available.', 'advanced-username-manager')]);

		}
	}
	
	/**
	 * set up member subnav for change the username in buddypress
	 *
	 * @since    1.0.0
	 */
	public function advanced_username_manager_bp_nav_setup() {
		
		// only add if settings component is enabled.
		if ( ! bp_is_active( 'settings' ) ) {
			return;
		}
		
		$settings_link = bp_displayed_user_domain() . bp_get_settings_slug() . '/';

		bp_core_new_subnav_item( array(
			'name'            => esc_html__( 'Username Change', 'advanced-username-manager' ),
			'slug'            => 'username-change',
			'parent_url'      => $settings_link,
			'parent_slug'     => buddypress()->settings->slug,
			'screen_function' => array( $this, 'advanced_username_manager_change_settings_screen' ),
			'position'        => 30,
			'user_has_access' => apply_filters( 'bp_username_changer_user_has_access', $this->aum_user_can_update_username() ),
		) );
	}
	
	/**
	 * Can the current user update the user name for the displayed user?
	 *
	 * @return bool
	 */
	public function aum_user_can_update_username() {
		return apply_filters( 'aum_username_changer_user_can_update', bp_is_my_profile() || is_super_admin() );
	}
	
	/**
	 * Show/Update Username
	 */
	public function advanced_username_manager_change_settings_screen() {
		
		if ( ! $this->aum_user_can_update_username() ) {
			return;
		}
		// show title &form.
		add_action( 'bp_template_title', array( $this, 'advanced_username_manager_print_title' ) );
		add_action( 'bp_template_content', array( $this, 'advanced_username_manager_print_form' ) );

		bp_core_load_template( apply_filters( 'aum_bp_username_changer_template_settings', 'members/single/plugins' ) );
	}
	
	/**
	 * Settings content title
	 */
	public function advanced_username_manager_print_title() {
		 esc_html_e( 'Change Username', 'advanced-username-manager' );
	}
	
	/**
	 * call the shortcode
	 *
	 * @since    1.0.0
	 */
	public function advanced_username_manager_print_form() {		
		echo do_shortcode( '[username_manager]');
	}
	
	/**
	 * rewrite endpoint
	 *
	 * @since    1.0.0
	 */
	public function advanced_username_manager_add_wss_endpoint() {
		add_rewrite_endpoint( 'change-username', EP_PAGES );
	}
	
	/**
	 * add change username in WooCommece account menu item
	 *
	 * @since    1.0.0
	 */
	public function advanced_username_manager_woocommerce_account_menu_items( $items ) {		
		$position = array_search('edit-account', array_keys($items)) + 1;		
		$items = array_slice($items, 0, $position, true) 
			+ array( 'change-username' => esc_html__( 'Change Username', 'advanced-username-manager' ) ) 
			+ array_slice($items, $position, null, true);
		
		
		return $items;
	}
	
	/**
	 * call the shortcode
	 *
	 * @since    1.0.0
	 */
	public function advanced_username_manager_woocommece_print_form() {
		
		?>
		<h3><?php esc_html_e( 'Change Username', 'advanced-username-manager' );?></h3>
		<?php
		$this->advanced_username_manager_print_form();
	}
}

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
		add_shortcode('username_manager', [$this, 'advanced_username_manager_func'] );
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/advanced-username-manager-public.css', array(), $this->version, 'all' );

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
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/advanced-username-manager-public.js', array( 'jquery' ), $this->version, false );
		
		$min_username_length	= ( isset( $aum_general_settings['min_username_length'] ) && $aum_general_settings['min_username_length'] != ''  )? $aum_general_settings['min_username_length'] : 5;
		$max_username_length	= ( isset( $aum_general_settings['max_username_length'] ) && $aum_general_settings['max_username_length'] != ''  )? $aum_general_settings['max_username_length'] : 12;
		$allowed_characters		= ( isset( $aum_general_settings['allowed_characters'] ) && $aum_general_settings['allowed_characters'] != ''  )? $aum_general_settings['allowed_characters'] : '';
		$js_object = array(
			'ajaxurl'                           => admin_url( 'admin-ajax.php' ),
			'ajax_nonce'                        => wp_create_nonce( 'advanced-username-change' ),			
			'limit_days'				=> ( isset( $aum_general_settings['limit_days'] ) && $aum_general_settings['limit_days'] != ''  ) ? $aum_general_settings['limit_days'] : 7,
			'min_username_length'				=> $min_username_length,
			'max_username_length'				=> $max_username_length,
			'allowed_characters'				=> $allowed_characters,
			'prohibited_words'				=> ( isset( $aum_general_settings['prohibited_words'] ) && $aum_general_settings['prohibited_words'] != ''  )? $aum_general_settings['prohibited_words'] : '',
			'min_username_error'			=> sprintf(esc_html__('Username must be at least %s characters long.', 'advanced-username-change' ), esc_attr($min_username_length) ),
			'max_username_error'			=> sprintf(esc_html__('Username must not exceed %s characters.', 'advanced-username-change' ), esc_attr($max_username_length)),
			'allowed_characters_error'			=> sprintf(esc_html__('Username can only contain letters, numbers, and the characters %s.', 'advanced-username-change' ), esc_attr($allowed_characters)),
		);
		wp_localize_script( $this->plugin_name, 'aum_options', $js_object );

	}
	
	public function advanced_username_manager_func( $atts, $content ) {
		global $aum_general_settings;
		
		if( !is_user_logged_in() || ( isset($aum_general_settings['enable_username']) && $aum_general_settings['enable_username']!= 'yes' ) ) {
			return '';
		}
		
		$current_user 		= wp_get_current_user();
		$current_user_roles	= $current_user->roles;
		if( empty( array_intersect( $current_user_roles, $aum_general_settings['user_roles']) ) ) {
			return esc_html__( 'you are not allow to change the username', 'advanced-username-manager' );
		}
		
		ob_start();
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
					<input type="submit" id="username_change_submit" name="username_change_submit" class="button" value="<?php esc_html_e( 'Save Changes', 'advanced-username-manager' ) ?>" disabled/>
				</p>
			</form>
		<?php
		return ob_get_clean();
	}
	
	public function advanced_username_manager_update_username() {
		
		check_ajax_referer( 'advanced-username-change', 'nonce' );
		
		global $wpdb, $aum_general_settings;
		$table_name 	= $wpdb->prefix . 'username_change_logs';
		$user_id		= get_current_user_id();
		$limit_days		= ( isset( $aum_general_settings['limit_days'] ) && $aum_general_settings['limit_days'] != ''  )? $aum_general_settings['limit_days'] : '7';
		$limit_days_ago = date( 'Y-m-d', strtotime( '-'. $limit_days .' days' ) );
		
		$query = "SELECT created_date FROM {$table_name} WHERE created_date >= %s and user_id=%d order by id DESC LIMIT 1";
		// Get the results		
		$results = $wpdb->get_var( $wpdb->prepare( $query, $limit_days_ago, $user_id ) );		
		if( $results != '' ){
			$next_date = date_i18n('Y-m-d H:i:s', strtotime( $results .' +'. $limit_days .' days' ) );
		 	$retval = array(			
				'error_message'	=> sprintf(esc_html__( 'You already updated your username. You can update your username after %s', 'advanced-username-manager' ) , esc_html($next_date)),
			);
			wp_send_json_error( $retval );
		}
		
		
		$new_user_name		= sanitize_text_field( wp_unslash( $_POST['new_user_name'] ) );
		$current_user_name	= sanitize_text_field( wp_unslash( $_POST['current_user_name'] ) );
		if( $current_user_name == '' ) {
			$current_user 		= wp_get_current_user();
			$current_user_name 	= $current_user->user_login;
		}
		
		
		if ( empty( $new_user_name ) || ! validate_username( $new_user_name ) ) {
			
			$retval = array(				
				'error_message'	=> esc_html__( 'Please enter a valid Username!', 'advanced-username-manager' ),
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
		
		// wp_update_user() doesn't update user_login when updating a user... sucks!
		wp_update_user( array(
			'ID'            => $user_id,
			'user_login'    => $new_user_name,
			'user_nicename' => sanitize_title( $new_user_name ),
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
		wp_clear_auth_cookie();
		// Here we calculate the expiration length of the current auth cookie and compare it to the default expiration.
		// If it's greater than this, then we know the user checked 'Remember Me' when they logged in.
		$logged_in_cookie = wp_parse_auth_cookie( '', 'logged_in' );

		/** This filter is documented in wp-includes/pluggable.php */
		$default_cookie_life = apply_filters( 'aum_auth_cookie_expiration', ( 2 * DAY_IN_SECONDS ), $user_id, false );
		$remember            = ( ( $logged_in_cookie['expiration'] - time() ) > $default_cookie_life );

		wp_set_auth_cookie( $user_id, $remember );
		
		// hook for plugins.
		do_action( 'advanced_username_changed', $new_user_name, $user );
		
		$result['success_message'] = esc_html__( 'Username has beed changed Successfully!', 'advanced-username-manager' );

		wp_send_json_success( $result );
	}
	
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
	
	public function aum_check_username_availability() {
		// Check nonce for security

		check_ajax_referer( 'advanced-username-change', 'nonce' );

		$username = sanitize_text_field($_POST['username']);

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
			wp_send_json_success(['available' => false, 'suggestions' => array_unique($suggestions)]);
		} else {

			wp_send_json_success(['available' => true,'message'=> esc_html__( 'Great! This username is available.', 'advanced-username-manager')]);

		}
	}

}

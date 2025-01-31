<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://https://https://wbcomdesigns.com/
 * @since      1.0.0
 *
 * @package    Advanced_Username_Manager
 * @subpackage Advanced_Username_Manager/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Advanced_Username_Manager
 * @subpackage Advanced_Username_Manager/admin
 * @author     Wbcom Designs <info@wbcomdesign.com>
 */
class Advanced_Username_Manager_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		
		if( !isset($_GET['page']) || ( isset($_GET['page']) && $_GET['page'] != 'advanced-username-manager' ) ) {
			return;
		}

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

		if ( ! wp_style_is( 'selectize-css', 'enqueued' ) ) {
			wp_enqueue_style( 'selectize-css', plugin_dir_url( __FILE__ ) . 'css/vendor/selectize.min.css', array(), $this->version, 'all' );
		}

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css' . $path . '/advanced-username-manager-admin' . $extension, array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		if( !isset($_GET['page']) || ( isset($_GET['page']) && $_GET['page'] != 'advanced-username-manager' ) ) {
			return;
		}
		
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
			$extension = '.js';
			$path      = '';
		} else {
			$extension = '.min.js';
			$path      = '/min';
		}

		if ( ! wp_script_is( 'selectize-js', 'enqueued' ) ) {
			wp_enqueue_script( 'selectize-js', plugin_dir_url( __FILE__ ) . 'js/vendor/selectize.min.js', array( 'jquery' ), $this->version, false );
		}

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js' . $path . '/advanced-username-manager-admin' . $extension, array( 'jquery' ), $this->version, false );

	}

	/**
	 * Register admin menu for plugin.
	 *
	 * @since    1.0.0
	 */
	public function advanced_username_manager_add_admin_menu() {
		
		if ( empty( $GLOBALS['admin_page_hooks']['wbcomplugins'] ) ) {

			add_menu_page( esc_html__( 'WB Plugins', 'advanced-username-manager' ), esc_html__( 'WB Plugins', 'advanced-username-manager' ), 'manage_options', 'wbcomplugins', array( $this, 'advanced_username_manager_settings_page' ), 'dashicons-lightbulb', 59 );

			add_submenu_page( 'wbcomplugins', esc_html__( 'General', 'advanced-username-manager' ), esc_html__( 'General', 'advanced-username-manager' ), 'manage_options', 'wbcomplugins' );
		}
		add_submenu_page( 'wbcomplugins', esc_html__( 'Advanced Username Manager', 'advanced-username-manager' ), esc_html__( 'Advanced Username Manager', 'advanced-username-manager' ), 'manage_options', 'advanced-username-manager', array( $this, 'advanced_username_manager_settings_page' ) );

		
	}
	
	/**
	 * BP Business Profile Admin Setting.
	 */
	public function advanced_username_manager_settings_page() {

		$current = isset( $_GET['tab'] ) ? sanitize_text_field( wp_unslash( $_GET['tab'] ) ) : 'welcome'; //phpcs:ignore

		if ( $current === 'shortcode-generator' ) {
			$current = 'my-shortcodes';
		}

		$advanced_username_manager_tabs = array(
			'welcome'         => esc_html__( 'Welcome', 'advanced-username-manager' ),
			'general-setting' => esc_html__( 'General Settings', 'advanced-username-manager' ),			
		);		
		
		?>

		<div class="wrap">
			<div class="wbcom-bb-plugins-offer-wrapper">
				<div id="wb_admin_logo">
					<a href="https://wbcomdesigns.com/downloads/buddypress-community-bundle/?utm_source=pluginoffernotice&utm_medium=community_banner" target="_blank">
						<img src="<?php echo esc_url( ADVANCED_USERNAME_MANAGER_URL ) . 'admin/wbcom/assets/imgs/wbcom-offer-notice.png'; ?>">
					</a>
				</div>
			</div>

			<div class="wbcom-wrap">
				<div class="blpro-header">
					<div class="wbcom_admin_header-wrapper">
						<div id="wb_admin_plugin_name">
							<?php esc_html_e( 'Advanced Username Manager', 'advanced-username-manager' ); ?>
							<?php /* translators: %s: */ ?>
							<span><?php printf( esc_html__( 'Version %s', 'advanced-username-manager' ), esc_attr( ADVANCED_USERNAME_MANAGER_VERSION ) ); ?></span>
						</div>
						<?php echo do_shortcode( '[wbcom_admin_setting_header]' ); ?>
					</div>
				</div>
				<div class="wbcom-admin-settings-page">
					<div class="wbcom-tabs-section">
						<div class="nav-tab-wrapper">
							<div class="wb-responsive-menu">
								<span><?php echo esc_html( 'Menu' ); ?></span>
								<input class="wb-toggle-btn" type="checkbox" id="wb-toggle-btn">
								<label class="wb-toggle-icon" for="wb-toggle-btn"><span class="wb-icon-bars"></span></label>
							</div>
							<ul>
							<?php
							foreach ( $advanced_username_manager_tabs as $advanced_username_manager_tab => $advanced_username_manager_name ) :
								$class = ( $advanced_username_manager_tab === $current ) ? 'nav-tab-active' : '';
								?>
								<li class="<?php echo esc_attr( $advanced_username_manager_tab ); ?>">
									<a class="nav-tab <?php echo esc_attr( $class ); ?>" href="admin.php?page=advanced-username-manager&tab=<?php echo esc_attr( $advanced_username_manager_tab ); ?>"><?php echo esc_html( $advanced_username_manager_name ); ?></a>
								</li>
							<?php endforeach; ?>
							</ul>
						</div>
					</div>
					<?php include 'inc/advanced-username-manager-tabs-options.php'; ?>

				</div>
			</div>
		</div>
		<?php
	}
	
	public function advanced_username_manager_sanitize_recursive( $input ) {
		if ( is_array( $input ) ) {
			foreach ( $input as $key => $value ) {
				$input[$key] = $this->advanced_username_manager_sanitize_recursive( $value );
			}
		} else {
			$input = sanitize_text_field( $input );
		}
		return $input;
	}

	
	/**
	 * Save plugin admin options
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function advanced_username_manager_save_options() {
		/* General Settings Saved */
		if ( isset( $_POST['advanced_username_manager_general_settings'] ) && ! defined( 'DOING_AJAX' ) ) {
			
			check_admin_referer( 'advanced_username_manager_general_settings-options' );
			
			$general_settings = $this->advanced_username_manager_sanitize_recursive( wp_unslash( $_POST['advanced_username_manager_general_settings'] ) );
			
			if( isset($general_settings['bp_profile_slug_format']) && $general_settings['bp_profile_slug_format'] == 'unique_identifier' && class_exists( 'BuddyPress' ) && !buddypress()->buddyboss ) {
				/* generate unique identifier */
				advanced_username_manager_update_repair_member_slug();
			} 
			
			update_option( 'advanced_username_manager_general_settings', $general_settings );
			wp_redirect( esc_url_raw( wp_unslash( $_POST['_wp_http_referer'] ) ) );
			exit;
		}		
		
	}	

}

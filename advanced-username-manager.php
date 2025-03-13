<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://wbcomdesigns.com/
 * @since             1.0.0
 * @package           Advanced_Username_Manager
 *
 * @wordpress-plugin
 * Plugin Name:       Advanced Username Manager
 * Plugin URI:        https://wbcomdesigns.com/downloads/advanced-username-manager
 * Description:       This is a description of the plugin.
 * Version:           1.0.0
 * Author:            Wbcom Designs
 * Author URI:        https://wbcomdesigns.com//
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       advanced-username-manager
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'ADVANCED_USERNAME_MANAGER_VERSION', '1.0.0' );
define( 'ADVANCED_USERNAME_MANAGER_URL', plugin_dir_url( __FILE__ ) );
define( 'ADVANCED_USERNAME_MANAGER_DIR', dirname( __FILE__ ) );
define( 'ADVANCED_USERNAME_MANAGER_FILE', __FILE__ );
define( 'ADVANCED_USERNAME_MANAGER_PATH', plugin_dir_path( __FILE__ ) );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-advanced-username-manager-activator.php
 */
function activate_advanced_username_manager() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-advanced-username-manager-activator.php';
	Advanced_Username_Manager_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-advanced-username-manager-deactivator.php
 */
function deactivate_advanced_username_manager() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-advanced-username-manager-deactivator.php';
	Advanced_Username_Manager_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_advanced_username_manager' );
register_deactivation_hook( __FILE__, 'deactivate_advanced_username_manager' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-advanced-username-manager.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_advanced_username_manager() {

	$plugin = new Advanced_Username_Manager();
	$plugin->run();

}
run_advanced_username_manager();


/*
**
 * redirect to plugin settings page after activated
 */

add_action( 'activated_plugin', 'advanced_username_manager_activation_redirect_settings' );
function advanced_username_manager_activation_redirect_settings( $plugin ) {
		
	if ( $plugin === plugin_basename( __FILE__ ) && isset( $_REQUEST['action'] ) && $_REQUEST['action'] === 'activate' && isset( $_REQUEST['plugin'] ) && $_REQUEST['plugin'] === plugin_basename( __FILE__ ) ) { //phpcs:ignore		
		wp_redirect( admin_url( 'admin.php?page=advanced-username-manager&redirects=1' ) );
		exit;
	}

}

/*
** Added plugin setting link
*/
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'advanced_username_manager_settings_link' );
function advanced_username_manager_settings_link( $settings ) {
	$settings[] = '<a href="' . esc_url( admin_url( 'admin.php?page=advanced-username-manager&tab=general-setting' ) ) . '">Settings</a>';
	return $settings;
}
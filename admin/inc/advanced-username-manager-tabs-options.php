<?php
/**
 * This template file is used for fetching desired options page file at admin settings end.
 *
 * @link       https://wbcomdesigns.com/
 * @since      1.0.0
 *
 * @package    Buddypress_Quotes
 * @subpackage Buddypress_Quotes/admin
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( isset( $_GET['tab'] ) ) { //phpcs:ignore
	$tab = sanitize_text_field( $_GET['tab'] ); //phpcs:ignore
} else {
	$tab = 'welcome';
}
switch ( $tab ) {
	case 'welcome':
		include 'advanced-username-manager-welcome-page.php';
		break;
	case 'general-setting':
		include 'advanced-username-manager-general-setting.php';
		break;
	case 'faq':
		include 'advanced-username-manager-faq-setting.php';
		break;	
	default:
		include 'advanced-username-manager-welcome-page.php';
		break;
}


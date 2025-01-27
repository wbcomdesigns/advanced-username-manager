<?php
/**
 * This file is used for rendering and saving plugin welcome settings.
 *
 * @link       https://wbcomdesigns.com/
 * @since      1.0.0
 *
 * @package    Advanced_Username_Manager
 * @subpackage Advanced_Username_Manager/admin/inc
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>

<div class="wbcom-tab-content">
	<div class="wbcom-welcome-main-wrapper">
		<div class="wbcom-welcome-head">
			<p class="wbcom-welcome-description"><?php esc_html_e( 'Advanced Username Manager plugin allows users to change their username(the name with which they login). It is simple and useful if you want to give your users the freedom to change their username.', 'advanced-username-manager' ); ?></p>
		</div><!-- .wbcom-welcome-head -->

		<div class="wbcom-welcome-content">
			<div class="wbcom-welcome-support-info">
				<h3><?php esc_html_e( 'Help &amp; Support Resources', 'advanced-username-manager' ); ?></h3>
				<p><?php esc_html_e( 'Here are all the resources you may need to get help from us. Documentation is usually the best place to start. Should you require help anytime, our customer care team is available to assist you at the support center.', 'advanced-username-manager' ); ?></p>

				<div class="wbcom-support-info-wrap">
					<div class="wbcom-support-info-widgets">
						<div class="wbcom-support-inner">
						<h3><span class="dashicons dashicons-book"></span><?php esc_html_e( 'Documentation', 'advanced-username-manager' ); ?></h3>
						<p><?php esc_html_e( 'We have prepared an extensive guide on Advanced Username Manager to learn all aspects of the plugin. You will find most of your answers here.', 'advanced-username-manager' ); ?></p>
						<a href="<?php echo esc_url( 'https://docs.wbcomdesigns.com/doc_category/advanced-username-manager/' ); ?>" class="button button-primary button-welcome-support" target="_blank"><?php esc_html_e( 'Read Documentation', 'advanced-username-manager' ); ?></a>
						</div>
					</div>

					<div class="wbcom-support-info-widgets">
						<div class="wbcom-support-inner">
						<h3><span class="dashicons dashicons-sos"></span><?php esc_html_e( 'Support Center', 'advanced-username-manager' ); ?></h3>
						<p><?php esc_html_e( 'We strive to offer the best customer care via our support center. Once your plugin is activated, you can ask us for help anytime.', 'advanced-username-manager' ); ?></p>
						<a href="<?php echo esc_url( 'https://wbcomdesigns.com/support/' ); ?>" class="button button-primary button-welcome-support" target="_blank"><?php esc_html_e( 'Get Support', 'advanced-username-manager' ); ?></a>
					</div>
					</div>
					<div class="wbcom-support-info-widgets">
						<div class="wbcom-support-inner">
						<h3><span class="dashicons dashicons-admin-comments"></span><?php esc_html_e( 'Got Feedback?', 'advanced-username-manager' ); ?></h3>
						<p><?php esc_html_e( 'We want to hear about your experience with the plugin. We would also love to hear any suggestions you may for future updates.', 'advanced-username-manager' ); ?></p>
						<a href="<?php echo esc_url( 'https://wbcomdesigns.com/submit-review/' ); ?>" class="button button-primary button-welcome-support" target="_blank"><?php esc_html_e( 'Send Feedback', 'advanced-username-manager' ); ?></a>
					</div>
					</div>
				</div>
			</div>
		</div>

	</div><!-- .wbcom-welcome-content -->
</div><!-- .wbcom-welcome-main-wrapper -->

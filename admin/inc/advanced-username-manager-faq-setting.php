<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://wbcomdesigns.com
 * @since      1.0.0
 *
 * @package    Buddypress_Edit_Activities
 * @subpackage Buddypress_Edit_Activities/admin/partials
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="wbcom-tab-content">      
<div class="wbcom-faq-adming-setting">
	<div class="wbcom-admin-title-section">
		<h3><?php esc_html_e( 'Have some questions?', 'advanced-username-manager' ); ?></h3>
	</div>
	<div class="wbcom-faq-admin-settings-block">
		<div id="wbcom-faq-settings-section" class="wbcom-faq-table">
			<div class="wbcom-faq-section-row">
				<div class="wbcom-faq-admin-row">
					<button class="wbcom-faq-accordion">
						<?php esc_html_e( 'What is Advanced Username Manager?', 'advanced-username-manager' ); ?>
					</button>
					<div class="wbcom-faq-panel">
						<p> 
							<?php esc_html_e( 'Advanced Username Manager is a WordPress plugin that allows users to change their usernames directly from the frontend while providing administrators with control over permissions, restrictions, and formatting options. It enhances user experience by making username management seamless and secure.', 'advanced-username-manager' ); ?>
						</p>
					</div>
				</div>
			</div>

			<div class="wbcom-faq-section-row">
				<div class="wbcom-faq-admin-row">
					<button class="wbcom-faq-accordion">
						<?php esc_html_e( 'Can users change their usernames from the frontend?', 'advanced-username-manager' ); ?>
					</button>
					<div class="wbcom-faq-panel">
						<p> 
							<?php esc_html_e( 'Yes, users can update their usernames directly from the frontend without needing administrator approval. This makes the process more user-friendly and efficient.', 'advanced-username-manager' ); ?>
						</p>
					</div>
				</div>
			</div>

			<div class="wbcom-faq-section-row">
				<div class="wbcom-faq-admin-row">
					<button class="wbcom-faq-accordion">
						<?php esc_html_e( 'Can administrators restrict who can change their usernames?', 'advanced-username-manager' ); ?>
					</button>
					<div class="wbcom-faq-panel">
						<p> 
							<?php esc_html_e( 'Yes, administrators can enable or disable username changes and specify which user roles, such as Editors, Authors, Contributors, or Subscribers, are allowed to modify their usernames.', 'advanced-username-manager' ); ?>
						</p>
					</div>
				</div>
			</div>

			<div class="wbcom-faq-section-row">
				<div class="wbcom-faq-admin-row">
					<button class="wbcom-faq-accordion">
						<?php esc_html_e( 'Is there a limit to how often a user can change their username?', 'advanced-username-manager' ); ?>
					</button>
					<div class="wbcom-faq-panel">
						<p> 
							<?php esc_html_e( 'Yes, administrators can set a waiting period before a user can change their username again. Options include 7 Days, 15 Days, or 30 Days, ensuring controlled and structured username updates.', 'advanced-username-manager' ); ?>
						</p>
					</div>
				</div>
			</div>

			<div class="wbcom-faq-section-row">
				<div class="wbcom-faq-admin-row">
					<button class="wbcom-faq-accordion">
						<?php esc_html_e( 'Does the plugin check username availability in real time?', 'advanced-username-manager' ); ?>
					</button>
					<div class="wbcom-faq-panel">
						<p> 
							<?php esc_html_e( 'Yes, the plugin provides a real-time username availability check, preventing duplicate usernames and suggesting alternative options if the desired username is already taken.', 'advanced-username-manager' ); ?>
						</p>
					</div>
				</div>
			</div>


			<div class="wbcom-faq-section-row">
				<div class="wbcom-faq-admin-row">
					<button class="wbcom-faq-accordion">
						<?php esc_html_e( 'What is the Unique Identifier feature?', 'advanced-username-manager' ); ?>
					</button>
					<div class="wbcom-faq-panel">
						<p> 
							<?php esc_html_e( 'The Unique Identifier feature allows administrators to generate secure, random profile URLs for users instead of using their usernames. This enhances privacy by ensuring that profile URLs remain functional even after a username change.', 'advanced-username-manager' ); ?>
						</p>
					</div>
				</div>
			</div>

			<div class="wbcom-faq-section-row">
				<div class="wbcom-faq-admin-row">
					<button class="wbcom-faq-accordion">
						<?php esc_html_e( 'How do users change their username using the shortcode?', 'advanced-username-manager' ); ?>
					</button>
					<div class="wbcom-faq-panel">
						<p> 
							<?php esc_html_e( 'Users can change their username by navigating to a page where the administrator has placed the [username_manager] shortcode. This will display a simple form for users to update their username directly from the frontend.', 'advanced-username-manager' ); ?>
						</p>
					</div>
				</div>
			</div>

		</div>
	</div>
</div>
</div>
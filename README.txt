=== Advanced Username Manager ===
Contributors: wbcomdesigns
Tags: username, user management, username change, BuddyPress, WooCommerce
Requires at least: 5.0
Tested up to: 7.2.0
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

The Advanced Username Manager plugin allows users to change their WordPress usernames while offering administrators robust control over access and restrictions. It's seamless, secure, and compatible with BuddyPress, WooCommerce, and other third-party plugins.
== Description ==

The **Advanced Username Manager** plugin enables WordPress users to change their usernames securely while giving administrators full control over permissions and restrictions.

**Features:**

* **Username Change Functionality:** Update usernames, first name, last name, and nickname from the frontend.
* **Random Username Generator:** Generate anonymous usernames.
* **Real-Time Username Availability Check:** Verify username availability instantly.

**Administrative Features:**

* **Role-Based Access Control:** Restrict username changes based on user roles.
* **Frequency Limits:** Control how often users can change their usernames.
* **Customizable Username Rules:** Set character limits and formatting rules.
* **Audit Logs for Username Changes:** Track all username changes for security and compliance.


**Security Features:**

* **Input Validation & Sanitization:** Ensure usernames meet security and formatting requirements.
* **Nonce Verification for Secure Submissions:** Prevent unauthorized username changes.
* **Server-Side Validation:** Reinforce security by verifying all changes on the server.


**Shortcode Support:**

* Use `[username_manager]` anywhere: pages, posts, BuddyPress, WooCommerce, etc.

== Installation ==

1. Download the plugin from the GitHub repository.
2. Upload the plugin folder to `/wp-content/plugins/`.
3. Activate through the "Plugins" menu in WordPress.
4. Go to **Settings > Advanced Username Manager** to configure.

== Frequently Asked Questions ==

= How do I allow specific roles to change usernames? =

Go to **Settings > Advanced Username Manager**, and configure role-based access.

= Can users generate random usernames? =

Yes, the form includes an option to generate a random username.

= Is there a limit to how often a user can change their username?

Yes, administrators can set a waiting period before a user can change their username again. Available options include **7 days, 15 days, or 30 days**, ensuring controlled and structured username updates.

= What is the Unique Identifier feature?

The Unique Identifier feature allows administrators to generate secure, random profile URLs for users instead of using their usernames. This enhances privacy by ensuring that profile URLs remain functional even after a username change.


== Screenshots ==

1. Username change form on the frontend.
2. Admin settings panel for managing username rules.

== Changelog ==

= 1.0.0 =
* Initial release with core features: username changes, admin controls, and security settings.

== Upgrade Notice ==

= 1.0.0 =
First stable release. Upgrade for secure username management and enhanced control features.

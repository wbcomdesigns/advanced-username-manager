# Advanced Username Manager Plugin

## Overview
The **Advanced Username Manager** plugin allows users to change their WordPress usernames while offering administrators robust control over access and restrictions. This plugin ensures a seamless and secure experience for username management and can be used with any third-party plugin or WordPress core functionality via a shortcode.

## Features

### Core Features
- **Username Change Functionality**: Allows users to update their WordPress usernames from the frontend. Users can also update their first name, last name, and nickname.
- **Random Username Generator**: Users can generate a random username for anonymity.
- **Real-Time Username Availability Check**: Verifies if the desired username is available and provides suggestions for unavailable usernames.

### Administrative Features
- **Role-Based Access Control**: Restrict username changes to specific user roles (e.g., Subscriber, Contributor) and optionally prevent changes for critical roles like Admin.
- **Frequency Limits**: Set limits on how often users can change their usernames (e.g., once every 30 days).
- **Customizable Rules**: Define rules for acceptable usernames, such as:
  - Minimum and maximum length.
  - Allowed/disallowed characters.
  - Prohibited words or patterns.
- **Audit Logs**: Keep a record of all username changes, including old username, new username, user ID, and timestamp.

### Shortcode Support
- Use the shortcode `[username_manager]` to embed the username change form anywhere:
  - WordPress pages or posts.
  - BuddyPress profile pages.
  - WooCommerce account sections.
- Fully compatible with third-party plugins, ensuring broader usability.

### Security Features
- **Input Validation and Sanitization**: Prevent SQL injection, XSS, and other vulnerabilities.
- **Nonce Verification**: Secure form submissions with nonce tokens to protect against CSRF attacks.
- **Server-Side Validation**: Validate username availability and compliance with custom rules on the server side.

### Notifications and History
- **Email Notifications**: Notify users via email after a successful username change.
- **Username History**: Maintain a history of previous usernames for user reference and admin tracking.

### Multilingual Support
- Compatible with multilingual plugins for translation of all frontend and backend messages.

## Installation
1. Download the plugin from the GitHub repository.
2. Upload the plugin folder to the `/wp-content/plugins/` directory.
3. Activate the plugin through the "Plugins" menu in WordPress.
4. Navigate to **Settings > Advanced Username Manager** to configure options.

## Usage

### For Administrators
1. Go to **Settings > Advanced Username Manager**.
2. Configure:
   - Role-based access control.
   - Frequency limits for username changes.
   - Validation rules for acceptable usernames.

### For Users
1. Navigate to the page where the `[username_manager]` shortcode is embedded.
2. Update your username using the intuitive form:
   - Check availability in real-time.
   - Optionally generate a random username.

## Shortcode Documentation
- **Basic Usage**:  
  `[username_manager]`  
  Renders the username change form with default settings.

- **Example with BuddyPress**:  
  Add the shortcode to BuddyPress profile pages to enable username changes directly.

- **Example with WooCommerce**:  
  Embed the shortcode in the "My Account" section for seamless integration.

## Development and Contribution
- **GitHub Repository**: [Add your repository link here]  
- Contributions are welcome! Fork the repository and submit pull requests.  
- **Issues and Bugs**: Report issues via the GitHub repository.

## Testing and Compatibility
- Tested with:
  - WordPress Core
  - BuddyPress
  - WooCommerce
  - Popular themes and plugins

## Future Enhancements
- Integration with social login platforms for username suggestions.
- Advanced username availability checks with customizable patterns.
- Enhanced compatibility with additional community and membership plugins.

## License
This plugin is open-source and licensed under the MIT License.

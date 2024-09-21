<?php

/**
 * Plugin Name:       WP Call Button
 * Plugin URI:        https://www.wpbeginner.com/
 * Description:       This plugin enables visitors on your website to call your business phone number by adding a call button at a prominent location on your website.
 * Version:           1.4.1
 * Author:            Syed Balkhi
 * Author URI:        https://www.wpbeginner.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-call-button
 * Domain Path:       /languages
 * Requires at least: 4.9
 * Requires PHP:      5.6
 *
 * @package WpCallButton
 */

// Exit if accessed directly.
if ( ! defined( 'WPINC' ) ) {
	exit;
}

define( 'WP_CALL_BUTTON_VERSION', '1.4.1' );
define( 'WP_CALL_BUTTON_FILE', __FILE__ );

// Include the PHP-FIG PSR-4 Compliant class loader.
require plugin_dir_path( WP_CALL_BUTTON_FILE ) . 'autoload-php-fig-psr4.php';

// Load plugin namespace classes.
use WpCallButton\Plugin\WpCallButtonPlugin;
use WpCallButton\Plugin\WpCallButtonUninstall;
use WpCallButton\Notices\Review;

/**
 * Fired when the plugin is uninstalled.
 */
function wp_call_button_uninstall_hook() {
	WpCallButtonUninstall::run_uninstall_tasks( 'wp-call-button' );
}
register_uninstall_hook( WP_CALL_BUTTON_FILE, 'wp_call_button_uninstall_hook' );

// Initialize the plugin.
new WpCallButtonPlugin( 'WP Call Button', 'wp-call-button' );
( new Review() )->load_hooks();

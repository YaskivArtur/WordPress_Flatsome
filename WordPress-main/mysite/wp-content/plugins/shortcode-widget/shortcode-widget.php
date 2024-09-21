<?php
/**
 * Plugin Name: Shortcode Widget
 * Plugin URI:  https://wordpress.org/plugins/shortcode-widget/
 * Description: Adds a text-like widget that allows you to write shortcode in it. (Just whats missing in the default text widget)
 * Version:     1.5.3
 * Author:      Gagan Deep Singh
 * Author URI:  https://gagan0123.com
 * License:     GPLv2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: shortcode-widget
 * Domain Path: /languages
 *
 * @package     Shortcode_Widget
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

if ( ! defined( 'SHORTCODE_WIDGET_PATH' ) ) {
	/**
	 * Absolute path of this plugin
	 *
	 * @since 1.5
	 */
	define( 'SHORTCODE_WIDGET_PATH', trailingslashit( plugin_dir_path( __FILE__ ) ) );
}

/** Loading the core plugin class */
require_once SHORTCODE_WIDGET_PATH . 'includes/class-shortcode-widget-plugin.php';

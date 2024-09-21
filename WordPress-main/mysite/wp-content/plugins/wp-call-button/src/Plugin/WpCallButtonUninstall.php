<?php
namespace WpCallButton\Plugin;

/**
 * Fired during plugin Uninstallation.
 *
 * This class defines all code necessary to run during the plugin's uninstallation.
 *
 * @since      1.0.0
 * @package    WpCallButton
 * @subpackage WpCallButton/Plugin
 */
class WpCallButtonUninstall {

	/**
	 * Delete all the options stored in the DB
	 *
	 * @since    1.0.0
	 */
	public static function run_uninstall_tasks( $plugin_slug ) {
		if ( ! empty( $plugin_slug ) ) {
			delete_option( $plugin_slug . '-settings' );
			delete_option( $plugin_slug . '_welcome' );
			delete_option( 'widget_' . $plugin_slug . '-widget-main-a' );
		}
	}

}

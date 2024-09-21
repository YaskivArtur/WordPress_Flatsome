<?php
/**
 * A Helper class for the plugin.
 *
 * @link       http://wpbeginner.com
 * @since      1.0.0
 *
 * @package    WpCallButton
 * @subpackage WpCallButton/Plugin
 */
namespace WpCallButton\Plugin;

class WpCallButtonHelpers {

	/**
	 * Function that generates the phone icon image
	 *
	 * @param String Fill color
	 *
	 * @returns String
	 */

	public static function get_phone_image( $fill = 'white' ) {
		$phone_image = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="459px" height="459px" viewBox="0 0 459 459" style="enable-background:new 0 0 459 459;" xml:space="preserve"><g><g id="call"><path style="fill: ' . esc_attr( $fill ) . ';" d="M91.8,198.9c35.7,71.4,96.9,130.05,168.3,168.3L316.2,311.1c7.649-7.649,17.85-10.199,25.5-5.1c28.05,10.2,58.649,15.3,91.8,15.3c15.3,0,25.5,10.2,25.5,25.5v86.7c0,15.3-10.2,25.5-25.5,25.5C193.8,459,0,265.2,0,25.5C0,10.2,10.2,0,25.5,0h89.25c15.3,0,25.5,10.2,25.5,25.5c0,30.6,5.1,61.2,15.3,91.8c2.55,7.65,0,17.85-5.1,25.5L91.8,198.9z"/></g></g></svg>';
		// phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode
		return 'data:image/svg+xml;base64,' . base64_encode( $phone_image );
	}

	/**
	 * Function that determines whether or not the call button can be printed on current page.
	 *
	 * @param Array Array of the settings
	 *
	 * @returns Array
	 */
	public static function get_call_button( $settings = [] ) {
		// Get the settings for the Easy Call Now Button for WordPress plugin.
		if ( empty( $settings ) || ! is_array( $settings ) ) {
			return [];
		}

		// Call button will not show.
		$show_call_button = false;

		// If the call button is enabled and a phone number has been entered.
		if ( isset( $settings['wpcallbtn_button_enabled'] ) && $settings['wpcallbtn_button_enabled'] === 'yes' &&
				isset( $settings['wpcallbtn_phone_num'] ) && ! empty( $settings['wpcallbtn_phone_num'] ) ) {

			// call button will always show
			$show_call_button = true;

			// Check call button visibility settings.
			if ( isset( $settings['wpcallbtn_button_filter_type'] ) ) {

				// Proceed only if the filter types are show or hide.
				if ( $settings['wpcallbtn_button_filter_type'] === 'show' || $settings['wpcallbtn_button_filter_type'] === 'hide' ) {

					// Set the filter IDs based on show or hide
					$settings_wpcallbtn_button_filter_ids = [];
					if ( $settings['wpcallbtn_button_filter_type'] === 'show' ) {
						$settings_wpcallbtn_button_filter_ids = $settings['wpcallbtn_button_filter_ids_show'];
					} elseif ( $settings['wpcallbtn_button_filter_type'] === 'hide' ) {
						$settings_wpcallbtn_button_filter_ids = $settings['wpcallbtn_button_filter_ids_hide'];
					}

					// Check if the filter post ids are set.
					if ( ! empty( $settings_wpcallbtn_button_filter_ids ) ) {

						// Get filtered post IDs in an array.
						$filter_ids = [];
						if ( ! empty( $settings_wpcallbtn_button_filter_ids ) ) {
							// Get the filter Post Ids.
							$filter_ids = array_map(
								function ( $option ) {
									$option_parts = explode( '____', $option );
									return $option_parts[0];
								},
								$settings_wpcallbtn_button_filter_ids
							);

							// Filter out post IDs that are invalid.
							$filter_ids = array_filter(
								$filter_ids,
								function ( $id ) {
									return is_numeric( $id );
								}
							);

						}

						if ( $settings['wpcallbtn_button_filter_type'] === 'show' ) {
							// The filter type is set to show button on Filter ID pages.
							// Check if the filtered ids match current page / post.
							if ( is_single( $filter_ids ) || is_page( $filter_ids ) ) {
								$show_call_button = true;
							} else {
								$show_call_button = false;
							}
						} elseif ( $settings['wpcallbtn_button_filter_type'] === 'hide' ) {
							// The filter type is set to hide on filter ID pages.
							// Check if the filtered ids match current page / post.
							if ( is_single( $filter_ids ) || is_page( $filter_ids ) ) {
								$show_call_button = false;
							} else {
								$show_call_button = true;
							}
						}
					}
				}
			}
		}

		// Return the details.
		return [
			'show_call_button' => $show_call_button,
			'settings'         => $settings,
			'tracking'         => '',
		];
	}

	/**
	 * Enqueque all the Admin styles.
	 *
	 * @returns void
	 */
	public static function enqueue_admin_styles() {
		// Minicolors styles.
		wp_enqueue_style( 'wp-call-button-minicolors', plugins_url( '/assets/css/jquery.minicolors.css', WP_CALL_BUTTON_FILE ), [], '2.2.6' );

		// intl-tel-input styles.
		wp_enqueue_style( 'wp-call-button-intltellinput', plugins_url( '/assets/css/intlTelInput.min.css', WP_CALL_BUTTON_FILE ), [], '16.0.0' );

		// select2 styles.
		wp_enqueue_style( 'wp-call-button-select2', plugins_url( '/assets/css/select2.min.css', WP_CALL_BUTTON_FILE ), [], '4.0.7' );

		// Admin Plugin styles.
		wp_register_style( 'wp-call-button-admin', plugins_url( '/assets/css/custom_admin.css', WP_CALL_BUTTON_FILE ), [], WP_CALL_BUTTON_VERSION );
		wp_enqueue_style( 'wp-call-button-admin' );
	}

	/**
	 * Enqueque all the Admin scripts.
	 *
	 * @returns void
	 */
	public static function enqueue_admin_scripts( $load_in_header = false ) {
		// Matching height.
		wp_enqueue_script( 'wp-call-button-matching-js', plugins_url( 'assets/js/jquery.matchHeight.min.js', WP_CALL_BUTTON_FILE ), [ 'jquery' ], '2.2.6', false );

		// Minicolors (color picker).
		wp_enqueue_script( 'wp-call-button-minicolors-js', plugins_url( 'assets/js/jquery.minicolors.min.js', WP_CALL_BUTTON_FILE ), [ 'jquery' ], '2.2.6', false );

		// Select2 JS.
		wp_enqueue_script( 'wp-call-button-select2-js', plugins_url( 'assets/js/select2.min.js', WP_CALL_BUTTON_FILE ), [ 'jquery' ], '4.0.7', false );

		// Clipboard JS.
		wp_enqueue_script( 'wp-call-button-clipboard-js', plugins_url( 'assets/js/clipboard.min.js', WP_CALL_BUTTON_FILE ), [ 'jquery' ], '2.0.4', false );

		// intl-tel-input.
		wp_enqueue_script( 'wp-call-button-intltellinput-js', plugins_url( 'assets/js/intlTelInput.min.js', WP_CALL_BUTTON_FILE ), [ 'wp-call-button-intltellinput-utils-js' ], '16.0.0', false );
		wp_enqueue_script( 'wp-call-button-intltellinput-utils-js', plugins_url( 'assets/js/utils.js', WP_CALL_BUTTON_FILE ), [], '16.0.0', false );

		// Admin scripts.
		wp_enqueue_script( 'wp-call-button-admin-js', plugins_url( 'assets/js/admin_scripts.js', WP_CALL_BUTTON_FILE ), [ 'wp-call-button-minicolors-js', 'wp-call-button-intltellinput-js', 'wp-call-button-select2-js', 'wp-call-button-clipboard-js', 'jquery' ], WP_CALL_BUTTON_VERSION, false );
		wp_localize_script(
			'wp-call-button-admin-js',
			'wpcallbtn_ajaxvars',
			[
				'ajaxurl'               => admin_url( 'admin-ajax.php' ),
				'nonce'                 => wp_create_nonce( 'wp-call-button-nonce' ),
				'placeholder'           => esc_attr__( 'Search for a post', 'wp-call-button' ),
				'phone_validate_errors' => [
					esc_attr__( 'Invalid number', 'wp-call-button' ),
					esc_attr__( 'Invalid country code', 'wp-call-button' ),
					esc_attr__( 'Too short', 'wp-call-button' ),
					esc_attr__( 'Too long', 'wp-call-button' ),
					esc_attr__( 'Invalid number', 'wp-call-button' ),
				],
			]
		);

		// Admin About page scripts.
		wp_enqueue_script( 'wp-call-button-admin-about-js', plugins_url( 'assets/js/admin_about_scripts.js', WP_CALL_BUTTON_FILE ), [ 'jquery', 'wp-call-button-matching-js' ], WP_CALL_BUTTON_VERSION, false );
		wp_localize_script(
			'wp-call-button-admin-about-js',
			'wpcallbtn_about_ajaxvars',
			[
				'ajax_url'                    => admin_url( 'admin-ajax.php' ),
				'nonce'                       => wp_create_nonce( 'wp-call-button-about' ),
				// Strings.
				'plugin_activate'             => esc_html__( 'Activate', 'wp-call-button' ),
				'plugin_activated'            => esc_html__( 'Activated', 'wp-call-button' ),
				'plugin_active'               => esc_html__( 'Active', 'wp-call-button' ),
				'plugin_inactive'             => esc_html__( 'Inactive', 'wp-call-button' ),
				'plugin_processing'           => esc_html__( 'Processing...', 'wp-call-button' ),
				'plugin_install_error'        => esc_html__( 'Could not install a plugin. Please download from WordPress.org and install manually.', 'wp-call-button' ),
				'plugin_install_activate_btn' => esc_html__( 'Install and Activate', 'wp-call-button' ),
				'plugin_activate_btn'         => esc_html__( 'Activate', 'wp-call-button' ),
			]
		);
	}

	/**
	 * Retrieve the plugin settings.
	 *
	 * @returns $data Array Mixed
	 */
	public static function get_settings() {
		// Get the settings from store.
		$settings = get_option( 'wp-call-button-settings' );
		if ( $settings && is_array( $settings ) ) {
			return $settings;
		}

		// Return default settings.
		return [
			'wpcallbtn_phone_num'              => '',
			'wpcallbtn_button_color'           => '#269041',
			'wpcallbtn_button_position'        => 'bottom-right',
			'wpcallbtn_button_enabled'         => 'no',
			'wpcallbtn_button_text'            => esc_attr__( 'Call Us', 'wp-call-button' ),
			'wpcallbtn_button_click_ga'        => '',
			'wpcallbtn_button_size'            => '',
			'wpcallbtn_button_order'           => '',
			'wpcallbtn_button_filter_ids_show' => '',
			'wpcallbtn_button_filter_ids_hide' => '',
			'wpcallbtn_button_filter_type'     => 'none',
			'wpcallbtn_button_mobile_only'     => 'yes',
		];
	}
}

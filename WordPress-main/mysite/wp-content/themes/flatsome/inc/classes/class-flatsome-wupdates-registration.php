<?php
/**
 * Flatsome_Registration class.
 *
 * @package Flatsome
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * The Flatsome registration.
 */
final class Flatsome_WUpdates_Registration extends Flatsome_Base_Registration {

	/**
	 * Setup instance.
	 *
	 * @param UxThemes_API $api The UX Themes API instance.
	 */
	public function __construct( UxThemes_API $api ) {
		parent::__construct( $api, 'flatsome_wupdates' );

		add_action( 'flatsome_scheduled_registration', array( $this, 'migrate_registration' ) );
	}

	/**
	 * Registers Flatsome.
	 *
	 * @param string $code Purchase code.
	 * @return array|WP_error
	 */
	public function register( $code ) {
		$registration = new Flatsome_Registration( $this->api );
		$result       = $registration->register( $code );

		if ( is_wp_error( $result ) ) {
			return $this->api->get_error( $result, 'wupdates-register' );
		}

		if ( empty( $registration->get_code() ) ) {
			return $result;
		}

		$this->delete_options();

		return $result;
	}

	/**
	 * Unregisters theme.
	 *
	 * @return array|WP_error
	 */
	public function unregister() {
		$this->delete_options();
		return array();
	}

	/**
	 * Get latest Flatsome version.
	 *
	 * @return string|WP_error
	 */
	public function get_latest_version() {
		$code = $this->get_code();

		if ( empty( $code ) ) {
			return new WP_Error( 'missing-purchase-code', __( 'Missing purchase code.', 'flatsome' ) );
		}

		$result = $this->api->send_request( "/legacy/license/$code/latest-version", 'wupdates-latest-version' );

		if ( is_wp_error( $result ) ) {
			$statuses = array( 400, 403, 404, 409, 410, 423 );
			if ( in_array( (int) $result->get_error_code(), $statuses, true ) ) {
				$this->set_errors( array( $result->get_error_message() ) );
			}
			return $result;
		} else {
			wp_clear_scheduled_hook( 'flatsome_scheduled_registration' );
			wp_schedule_single_event( time() + HOUR_IN_SECONDS, 'flatsome_scheduled_registration' );
			$this->set_errors( array() );
		}

		if ( empty( $result['version'] ) ) {
			return new WP_Error( 'missing-version', __( 'No version received.', 'flatsome' ) );
		}

		if ( ! is_string( $result['version'] ) ) {
			return new WP_Error( 'invalid-version', __( 'Invalid version received.', 'flatsome' ) );
		}

		return $result['version'];
	}

	/**
	 * Get a temporary download URL.
	 *
	 * @param string $version Version number to download.
	 * @return string|WP_error
	 */
	public function get_download_url( $version ) {
		$code = $this->get_code();

		if ( empty( $code ) ) {
			return new WP_Error( 'missing-purchase-code', __( 'Missing purchase code.', 'flatsome' ) );
		}

		$result = $this->api->send_request( "/legacy/license/$code/download-url/$version", 'download-url' );

		if ( is_wp_error( $result ) ) {
			return $result;
		}

		if ( empty( $result['url'] ) ) {
			return new WP_Error( 'missing-url', __( 'No URL received.', 'flatsome' ) );
		}

		if ( ! is_string( $result['url'] ) ) {
			return new WP_Error( 'invalid-url', __( 'Invalid URL received.', 'flatsome' ) );
		}

		return $result['url'];
	}

	/**
	 * Checks whether Flatsome is registered or not.
	 *
	 * @return boolean
	 */
	public function is_registered() {
		return $this->get_code() !== '';
	}

	/**
	 * Checks whether the registration has been verified by Envato.
	 *
	 * @return boolean
	 */
	public function is_verified() {
		return true;
	}

	/**
	 * Delete options.
	 */
	public function delete_options() {
		$slug = flatsome_theme_key();

		delete_option( $slug . '_wup_buyer' );
		delete_option( $slug . '_wup_sold_at' );
		delete_option( $slug . '_wup_purchase_code' );
		delete_option( $slug . '_wup_supported_until' );
		delete_option( $slug . '_wup_errors' );
		delete_option( $slug . '_wup_attempts' );

		parent::delete_options();
	}

	/**
	 * Checks whether the purchase code was registered with WPUpdates.
	 *
	 * @return boolean
	 */
	public function get_code() {
		return get_option( flatsome_theme_key() . '_wup_purchase_code', '' );
	}

	/**
	 * Checks if the purchase code has been verified and attempts to register the site.
	 */
	public function migrate_registration() {
		$code = $this->get_code();

		if ( empty( $code ) ) {
			return;
		}

		$license = $this->api->send_request( "/v1/license/$code", 'wupdates' );

		if ( is_wp_error( $license ) ) {
			return;
		}

		if ( empty( $license['status'] ) ) {
			return; // Wait for a verified license.
		}

		$result = ( new Flatsome_Registration( $this->api ) )->register( $code );

		if ( is_wp_error( $result ) ) {
			$error = $this->api->get_error( $result, 'wupdates' );

			if ( in_array( (int) $error->get_error_code(), array( 400, 403, 409, 423 ), true ) ) {
				$this->set_errors( array( $error->get_error_message() ) );
			}
		} else {
			$this->delete_options();
		}
	}
}

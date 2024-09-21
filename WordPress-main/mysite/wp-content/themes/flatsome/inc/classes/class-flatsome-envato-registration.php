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
final class Flatsome_Envato_Registration extends Flatsome_Base_Registration {

	/**
	 * Setup instance.
	 *
	 * @param UxThemes_API $api The UX Themes API instance.
	 */
	public function __construct( UxThemes_API $api ) {
		parent::__construct( $api, 'flatsome_envato' );
	}

	/**
	 * Register with a purchase ID or code.
	 *
	 * @param string $purchase_id Purchase ID or code.
	 * @return array|WP_error
	 */
	public function register( $purchase_id ) {
		$token = $this->get_token();

		if ( empty( $token ) ) {
			return new WP_Error( 400, __( 'Missing token.', 'flatsome' ) );
		} elseif ( empty( $purchase_id ) ) {
			return new WP_Error( 400, __( 'No purchase code provided.', 'flatsome' ) );
		}

		$result = $this->api->send_request( '/v1/token/register', 'envato-register', array(
			'method'  => 'POST',
			'headers' => array(
				'authorization' => "Bearer $token",
			),
			'body'    => array(
				'purchase_id' => $purchase_id,
			),
		) );

		if ( ! is_wp_error( $result ) ) {
			$registration = new Flatsome_Registration( $this->api );
			$registration->set_options( $result );
			$this->delete_options();
		}

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
		$token = $this->get_token();

		if ( empty( $token ) ) {
			return '';
		}

		$result = $this->api->send_request( '/v1/token/latest-version', 'latest-version', array(
			'headers' => array(
				'authorization' => "Bearer $token",
			),
		) );

		if ( is_wp_error( $result ) ) {
			$statuses = array( 403, 404, 409, 410, 423 );
			if ( in_array( (int) $result->get_error_code(), $statuses, true ) ) {
				$this->set_errors( array( $result->get_error_message() ) );
			}
			return $result;
		} else {
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
		$token = $this->get_token();

		if ( empty( $token ) ) {
			return '';
		}

		$result = $this->api->send_request( "/v1/token/download-url/$version", 'download-url', array(
			'headers' => array(
				'authorization' => "Bearer $token",
			),
		) );

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
	 * Returns available purchase codes.
	 *
	 * @return array|WP_error
	 */
	public function get_purchase_codes() {
		$token = $this->get_token();

		if ( empty( $token ) ) {
			return array( 'available' => array() );
		}

		return $this->api->send_request( '/v1/token/purchase-codes', null, array(
			'headers' => array(
				'authorization' => "Bearer $token",
			),
		) );
	}

	/**
	 * Checks whether Flatsome is registered or not.
	 *
	 * @return boolean
	 */
	public function is_registered() {
		return $this->get_token() !== '';
	}

	/**
	 * Checks whether the registration has been verified by Envato.
	 *
	 * @return boolean
	 */
	public function is_verified() {
		return ! empty( $this->get_option( 'is_valid' ) );
	}

	/**
	 * Returns the personal Envato token this site was registered with.
	 *
	 * @return boolean
	 */
	public function get_token() {
		$options = $this->get_options();
		$token   = isset( $options['token'] ) ? $options['token'] : '';
		$valid   = isset( $options['is_valid'] ) ? $options['is_valid'] : false;

		return $valid ? $token : '';
	}
}

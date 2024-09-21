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
 * The UX Themes API.
 */
final class UxThemes_API {

	/**
	 * Setup instance.
	 */
	public function __construct() {
		add_filter( 'http_headers_useragent', array( $this, 'http_headers_useragent' ), 10, 2 );
	}

	/**
	 * Filters the user agent value sent with an HTTP request.
	 *
	 * @param string $useragent WordPress user agent string.
	 * @param string $url The request URL.
	 * @return string
	 */
	public function http_headers_useragent( $useragent, $url = '' ) {
		if ( strpos( $url, UXTHEMES_API_URL ) !== false ) {
			$theme = wp_get_theme( get_template() );
			return 'Flatsome/' . $theme->get( 'Version' ) . '; ' . $useragent;
		}
		return $useragent;
	}

	/**
	 * Sends a request to the Flatsome Account API.
	 *
	 * @param string $path    REST API path.
	 * @param string $context REST API path.
	 * @param array  $args    Request arguments.
	 * @return array|WP_error
	 */
	public function send_request( $path, $context = null, $args = array() ) {
		$args = array_merge_recursive( $args, array(
			'timeout' => 60,
			'headers' => array(
				'Referer' => $this->get_site_url(),
			),
		) );

		$url      = esc_url_raw( UXTHEMES_API_URL . $path );
		$response = wp_remote_request( $url, $args );
		$status   = wp_remote_retrieve_response_code( $response );
		$headers  = wp_remote_retrieve_headers( $response );
		$body     = wp_remote_retrieve_body( $response );
		$data     = (array) json_decode( $body, true );

		if ( is_wp_error( $response ) ) {
			return $this->get_error( $response, $context, $data );
		}

		if ( $status === 429 ) {
			if ( isset( $headers['x-ratelimit-reset'] ) ) {
				$data['retry-after'] = (int) $headers['x-ratelimit-reset'];
			} elseif ( isset( $headers['retry-after'] ) ) {
				$data['retry-after'] = time() + ( (int) $headers['retry-after'] );
			}
		}

		if ( $status !== 200 ) {
			$error = isset( $data['message'] )
				? new WP_Error( $status, $data['message'], $data )
				// translators: %d: The status code.
				: new WP_Error( $status, sprintf( __( 'Sorry, an error occurred while accessing the API. Error %d', 'flatsome' ), $status ), $data );

			return $this->get_error( $error, $context, $data );
		}

		return $data;
	}

	/**
	 * Returns the raw site URL.
	 *
	 * @return string
	 */
	protected function get_site_url() {
		global $wpdb;

		$row = $wpdb->get_row( "SELECT option_value FROM $wpdb->options WHERE option_name = 'siteurl' LIMIT 1" );

		if ( is_object( $row ) ) {
			return $row->option_value;
		}

		return '';
	}

	/**
	 * Returns a proper error for a HTTP status code.
	 *
	 * @param WP_Error $error   The original error.
	 * @param string   $context A context.
	 * @param array    $data    Optional data.
	 * @return WP_Error
	 */
	public function get_error( $error, $context = null, $data = array() ) {
		$status        = (int) $error->get_error_code();
		$account_attrs = ' href="' . esc_url_raw( UXTHEMES_ACCOUNT_URL ) . '" target="_blank" rel="noopener noreferrer"';

		switch ( $status ) {
			case 400:
				if ( $context === 'register' ) {
					return new WP_Error( $status, __( 'Your purchase code is malformed.', 'flatsome' ), $data );
				}
				if ( $context === 'envato-register' ) {
					return new WP_Error( $status, __( 'Sorry, an error occurred. Please try again.', 'flatsome' ), $data );
				}
				if ( $context === 'latest-version' ) {
					return new WP_Error( $status, __( 'Flatsome was unable to get the latest version. Your site might have changed domain after you registered it.', 'flatsome' ), $data );
				}
				return $error;
			case 403:
				if ( $context === 'latest-version' ) {
					return new WP_Error( $status, __( 'Flatsome was unable to get the latest version because the purchase code has not been verified yet. Please re-register it in order to receive updates.', 'flatsome' ), $data );
				}
				return $error;
			case 404:
				if ( $context === 'register' || $context === 'envato-register' || $context === 'wupdates-register' ) {
					return new WP_Error( $status, __( 'The purchase code is malformed or does not belong to a Flatsome sale.', 'flatsome' ), $data );
				}
				if ( $context === 'unregister' ) {
					// translators: %s: License manager link attributes.
					return new WP_Error( $status, sprintf( __( 'The registration was not found for <a%s>your account</a>. It was only deleted on this site.', 'flatsome' ), $account_attrs ), $data );
				}
				if ( $context === 'latest-version' ) {
					// translators: %s: License manager link attributes.
					return new WP_Error( $status, sprintf( __( 'Flatsome was unable to get the latest version. Your registration might have been deleted from <a%s>your account</a>.', 'flatsome' ), $account_attrs ), $data );
				}
				if ( $context === 'wupdates-latest-version' ) {
					return new WP_Error( $status, __( 'Flatsome was unable to get the latest version. Your purchase code is malformed.', 'flatsome' ), $data );
				}
				return $error;
			case 409:
				if ( $context === 'wupdates' ) {
					// translators: %s: License manager link attributes.
					return new WP_Error( $status, sprintf( __( 'Your purchase code has been used on too many sites. Please go to <a%s>your account</a> and manage your licenses.', 'flatsome' ), $account_attrs ), $data );
				}
				// translators: %s: License manager link attributes.
				return new WP_Error( $status, sprintf( __( 'The purchase code is already registered on another site. Please go to <a%s>your account</a> and manage your licenses.', 'flatsome' ), $account_attrs ), $data );
			case 410:
				if ( $context === 'register' || $context === 'envato-register' || $context === 'latest-version' ) {
					return new WP_Error( $status, __( 'Your purchase code has been blocked. Please contact support to resolve the issue.', 'flatsome' ), $data );
				}
				if ( $context === 'wupdates-register' ) {
					return new WP_Error( $status, __( 'The purchase code does not belong to a Flatsome sale.', 'flatsome' ), $data );
				}
				if ( $context === 'wupdates-latest-version' ) {
					return new WP_Error( $status, __( 'Flatsome was unable to get the latest version. The purchase code does not belong to a Flatsome sale.', 'flatsome' ), $data );
				}
				return new WP_Error( $status, __( 'The requested resource no longer exists.', 'flatsome' ), $data );
			case 417:
				return new WP_Error( $status, __( 'No domain was sent with the request.', 'flatsome' ), $data );
			case 422:
				return new WP_Error( $status, __( 'Unable to parse the domain for your site.', 'flatsome' ), $data );
			case 423:
				if ( $context === 'register' || $context === 'envato-register' || $context === 'latest-version' || $context === 'wupdates-latest-version' || $context === 'wupdates' ) {
					return new WP_Error( $status, __( 'Your purchase code has been locked. Please contact support to resolve the issue.', 'flatsome' ), $data );
				}
				return new WP_Error( $status, __( 'The requested resource has been locked.', 'flatsome' ), $data );
			case 429:
				return new WP_Error( $status, __( 'Sorry, the API is overloaded.', 'flatsome' ), $data );
			case 503:
				return new WP_Error( $status, __( 'Sorry, the API is unavailable at the moment.', 'flatsome' ), $data );
			default:
				return $error;
		}
	}
}

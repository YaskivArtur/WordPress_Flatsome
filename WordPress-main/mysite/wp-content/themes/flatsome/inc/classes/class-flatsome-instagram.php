<?php
/**
 * Flatsome_Instagram class.
 *
 * @package Flatsome
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Flatsome_Instagram class.
 */
class Flatsome_Instagram {

	/**
	 * The scheduled event name.
	 *
	 * @var string
	 */
	protected $event = 'flatsome_instagram_refresh_access_tokens';

	/**
	 * The single class instance.
	 *
	 * @var Flatsome_Instagram|null
	 */
	protected static $instance = null;

	/**
	 * Setup instance.
	 */
	private function __construct() {
		add_action( 'admin_init', array( $this, 'schedule_event' ) );
		add_action( 'switch_theme', array( $this, 'deschedule_event' ) );
		add_action( $this->event, array( $this, 'refresh_access_tokens' ) );
	}

	/**
	 * Schedule a weekly event that refreshes the access tokens.
	 */
	public function schedule_event() {
		if ( ! wp_next_scheduled( $this->event ) ) {
			wp_schedule_event( time(), 'weekly', $this->event );
		}
	}

	/**
	 * Deschedule the event when switching themes.
	 */
	public function deschedule_event() {
		if ( wp_next_scheduled( $this->event ) ) {
			wp_clear_scheduled_hook( $this->event );
		}
	}

	/**
	 * Refresh Instagram access tokens if they are about to expire.
	 */
	public function refresh_access_tokens() {
		$accounts = flatsome_facebook_accounts();

		if ( empty( $accounts ) ) {
			return;
		}

		foreach ( $accounts as &$account ) {
			if ( ! isset( $account['expires_at'] ) ) continue;

			$expires_in = $account['expires_at'] - time();

			if ( $expires_in <= MONTH_IN_SECONDS ) {
				$data = $this->refresh_access_token( $account['access_token'] );

				if ( isset( $account['error'] ) ) {
					unset( $account['error'] );
				}

				if ( is_wp_error( $data ) ) {
					$account['error'] = $data->get_error_message();
				} else {
					$account['access_token'] = $data['access_token'];
					$account['expires_at']   = time() + (int) $data['expires_in'];
				}
			}
		}

		set_theme_mod( 'facebook_accounts', $accounts );
	}

	/**
	 * Refresh an access token.
	 *
	 * @param string $access_token The access token.
	 */
	protected function refresh_access_token( $access_token ) {
		$response = wp_remote_get(
			add_query_arg(
				array(
					'grant_type'   => 'ig_refresh_token',
					'access_token' => $access_token,
				),
				'https://graph.instagram.com/refresh_access_token'
			),
			array(
				'timeout' => 30,
			)
		);

		if ( is_wp_error( $response ) ) {
			return $response;
		} elseif ( wp_remote_retrieve_response_code( $response ) !== 200 ) {
			return new WP_Error( 'invalid_response_code', 'Refresh endpoint returned an invalid response code' );
		}

		$body = wp_remote_retrieve_body( $response );
		$data = json_decode( $body, true );

		if ( ! isset( $data['access_token'] ) ) {
			return new WP_Error( 'missing_access_token', 'Refresh endpoint did not return an access token' );
		}

		return $data;
	}

	/**
	 * Main Flatsome_Instagram instance
	 *
	 * @return Flatsome_Instagram
	 */
	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
}

Flatsome_Instagram::get_instance();

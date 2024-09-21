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
 * Base Flatsome registration.
 */
class Flatsome_Base_Registration {

	/**
	 * The UX Themes API instance.
	 *
	 * @var UxThemes_API
	 */
	protected $api;

	/**
	 * The option name.
	 *
	 * @var string
	 */
	protected $option_name;

	/**
	 * Setup instance.
	 *
	 * @param string $api         The UX Themes API instance.
	 * @param string $option_name The option name.
	 */
	public function __construct( $api, $option_name ) {
		$this->api         = $api;
		$this->option_name = $option_name;
	}

	/**
	 * Register theme.
	 *
	 * @param string $code The purchase code.
	 * @return array|WP_error
	 */
	public function register( $code ) {
		return new WP_Error( 500, __( 'Not allowed.', 'flatsome' ) );
	}

	/**
	 * Unregister theme.
	 *
	 * @return array|WP_error
	 */
	public function unregister() {
		return new WP_Error( 500, __( 'Not allowed.', 'flatsome' ) );
	}

	/**
	 * Check latest version.
	 *
	 * @return array|WP_error
	 */
	public function get_latest_version() {
		return new WP_Error( 500, __( 'Not allowed.', 'flatsome' ) );
	}

	/**
	 * Get a download URL.
	 *
	 * @param string $version Version number to download.
	 * @return array|WP_error
	 */
	public function get_download_url( $version ) {
		return new WP_Error( 500, __( 'Not allowed.', 'flatsome' ) );
	}

	/**
	 * Checks whether Flatsome is registered or not.
	 *
	 * @return boolean
	 */
	public function is_registered() {
		return false;
	}

	/**
	 * Checks whether the registration has been verified by Envato.
	 *
	 * @return boolean
	 */
	public function is_verified() {
		return false;
	}

	/**
	 * Checks whether registration is public or local.
	 *
	 * @return boolean
	 */
	public function is_public() {
		return true;
	}

	/**
	 * Returns the registered purchase code.
	 *
	 * @return string
	 */
	public function get_code() {
		return '';
	}

	/**
	 * Return the options array.
	 */
	public function get_options() {
		return get_option( $this->option_name, array() );
	}

	/**
	 * Updates the options array.
	 *
	 * @param array $data New data.
	 */
	public function set_options( $data ) {
		update_option( $this->option_name, $data );
	}

	/**
	 * Delete the options array.
	 */
	public function delete_options() {
		delete_option( $this->option_name );
	}

	/**
	 * Return a value from the option settings array.
	 *
	 * @param string $name Option name.
	 * @param mixed  $default The default value if nothing is set.
	 * @return mixed
	 */
	public function get_option( $name, $default = null ) {
		$options = $this->get_options();
		return isset( $options[ $name ] ) ? $options[ $name ] : $default;
	}

	/**
	 * Set option value.
	 *
	 * @param string $name Option name.
	 * @param mixed  $option Option data.
	 */
	public function set_option( $name, $option ) {
		$options          = $this->get_options();
		$options[ $name ] = wp_unslash( $option );

		$this->set_options( $options );
	}

	/**
	 * Deletes an option.
	 *
	 * @param string $name Option name.
	 */
	public function delete_option( $name ) {
		$options = $this->get_options();

		if ( isset( $options[ $name ] ) ) {
			unset( $options[ $name ] );
		}

		$this->set_options( $options );
	}

	/**
	 * Set registration errors.
	 *
	 * @param string[] $errors The error messages.
	 * @return void
	 */
	public function set_errors( array $errors ) {
		$errors = array_filter( $errors );
		$this->set_option( 'errors', $errors );
		$this->set_option( 'show_notice', ! empty( $errors ) );
	}

	/**
	 * Get registration errors.
	 *
	 * @return string[]
	 */
	public function get_errors() {
		return array_filter( $this->get_option( 'errors', array() ) );
	}

	/**
	 * Clears errors to hide admin notices etc.
	 */
	public function dismiss_notice() {
		$this->delete_option( 'show_notice' );
	}
}

<?php
/**
 * Flatsome_Envato_Admin class.
 *
 * @package Flatsome
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * The Flatsome Envato.
 */
final class Flatsome_Envato_Admin {

	/**
	 * The single class instance.
	 *
	 * @var object
	 */
	private static $instance = null;

	/**
	 * Main Flatsome_Envato_Admin instance
	 *
	 * @return Flatsome_Envato_Admin.
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * The Flatsome_Registration instance.
	 *
	 * @var Flatsome_Registration
	 */
	private $registration = null;

	/**
	 * Setup instance properties
	 *
	 * @param Flatsome_Envato $registration The Flatsome_Envato instance.
	 */
	public function __construct( $registration ) {
		$this->registration = $registration;

		add_action( 'admin_menu', array( $this, 'add_pages' ) );
		add_action( 'current_screen', array( $this, 'render_version_info_iframe' ) );
		add_action( 'admin_post_flatsome_envato_register', array( $this, 'save_registration_form' ) );
		add_action( 'wp_ajax_flatsome_registration_dismiss_notice', array( $registration, 'dismiss_notice' ) );
	}

	/**
	 * Add necessary admin pages.
	 */
	public function add_pages() {
		add_submenu_page( '', '', '', 'manage_options', 'flatsome-version-info', '__return_empty_string' );
	}

	/**
	 * Renders the update modal iframe.
	 *
	 * @param WP_Screen $screen WordPress admin screen.
	 */
	public function render_version_info_iframe( $screen ) {
		if ( $screen->base === 'admin_page_flatsome-version-info' ) {
			$version = isset( $_GET['version'] ) ? wp_unslash( $_GET['version'] ) : '';
			include get_template_directory() . '/template-parts/admin/envato/version-info-iframe.php';
			die;
		}
	}

	/**
	 * Renders a message for sites with a purchase code.
	 *
	 * @return string
	 */
	public function render_message_form() {
		ob_start();
		include get_template_directory() . '/template-parts/admin/envato/message-form.php';
		return ob_get_clean();
	}

	/**
	 * Renders a warning about unusual theme directory name.
	 *
	 * @return string
	 */
	public function render_directory_warning() {
		$template = get_template();

		ob_start();
		include get_template_directory() . '/template-parts/admin/envato/directory-warning.php';
		return ob_get_clean();
	}

	/**
	 * Renders the theme registration form.
	 *
	 * @param string $args Visibility options.
	 * @return string
	 */
	public function render_registration_form( $args = array() ) {
		$registration = $this->registration;
		$registered   = $registration->is_registered();
		$verified     = $registration->is_verified();
		$code         = $registration->get_code();
		$issues       = $registration->get_errors();
		$args         = wp_parse_args( $args, array(
			'form'        => true,
			'show_intro'  => true,
			'show_terms'  => true,
			'show_submit' => true,
		) );

		if ( $code ) {
			$code = flatsome_hide_chars( $code );
		} else {
			$code      = get_transient( 'flatsome_purchase_code' );
			$confirmed = (bool) get_transient( 'flatsome_registration_confirmed' );
		}

		$error = get_transient( 'flatsome_registration_error' );

		if ( is_wp_error( $error ) ) {
			$data    = $error->get_error_data();
			$message = $error->get_error_message();

			if ( isset( $data['retry-after'] ) ) {
				$rate_limit       = (int) $data['retry-after'];
				$time_left        = $rate_limit - time();
				$time_left_format = $time_left < 3600 ? 'i:s' : 'H:i:s';
				$time_left_string = human_readable_duration( gmdate( $time_left_format, $time_left ) );

				// translators: %s: Time left.
				$error = new WP_Error( 429, $message . ' ' . sprintf( __( 'Please try again in %s.', 'flatsome' ), $time_left_string ) );
			}
		}

		delete_transient( 'flatsome_purchase_id' );
		delete_transient( 'flatsome_purchase_code' );
		delete_transient( 'flatsome_registration_confirmed' );
		delete_transient( 'flatsome_registration_error' );

		ob_start();

		include get_template_directory() . '/template-parts/admin/envato/register-form.php';

		return ob_get_clean();
	}

	/**
	 * Saves the theme registration form.
	 */
	public function save_registration_form() {
		check_admin_referer( 'flatsome_envato_register', 'flatsome_envato_register_nonce' );

		if ( isset( $_POST['flatsome_register'] ) ) {
			$code = isset( $_POST['flatsome_purchase_code'] )
				? sanitize_text_field( wp_unslash( $_POST['flatsome_purchase_code'] ) )
				: '';

			$purchase_id = isset( $_POST['flatsome_purchase_id'] )
				? sanitize_text_field( wp_unslash( $_POST['flatsome_purchase_id'] ) )
				: '';

			$confirmed = isset( $_POST['flatsome_envato_terms'] )
				? (bool) $_POST['flatsome_envato_terms']
				: false;

			set_transient( 'flatsome_purchase_code', $code, 120 );
			set_transient( 'flatsome_purchase_id', $purchase_id, 120 );
			set_transient( 'flatsome_registration_confirmed', $confirmed, 120 );

			if ( ! $confirmed ) {
				$result = new WP_Error( 403, __( 'You must agree to the Envato License Terms.', 'flatsome' ) );
			} elseif ( $purchase_id ) {
				$result = $this->registration->register( $purchase_id );
			} else {
				$result = $this->registration->register( $code );
			}
		} elseif ( isset( $_POST['flatsome_verify'] ) ) {
			$code   = $this->registration->get_code();
			$result = $this->registration->register( $code );
		} elseif ( isset( $_POST['flatsome_unregister'] ) ) {
			$result = $this->registration->unregister();

			delete_option( 'flatsome_update_cache' );
		}

		if ( is_wp_error( $result ) ) {
			set_transient( 'flatsome_registration_error', $result, 120 );
		}

		$referer = isset( $_POST['_wp_http_referer'] )
			? esc_url_raw( wp_unslash( $_POST['_wp_http_referer'] ) )
			: '';

		wp_safe_redirect( $referer );

		exit;
	}
}

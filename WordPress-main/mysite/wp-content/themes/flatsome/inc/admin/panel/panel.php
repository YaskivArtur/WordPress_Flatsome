<?php

/**
 * Flatsome Admin Panel
 */
class Flatsome_Admin {

	/**
	 * Sets up the welcome screen
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'flatsome_panel_register_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'flatsome_panel_style' ) );
		add_action( 'wp_ajax_flatsome_purchase_codes', array( $this, 'flatsome_purchase_codes' ) );
	}


	/**
	 * Load welcome screen css.
	 *
	 * @since  1.4.4
	 */
	public function flatsome_panel_style() {
		$uri     = get_template_directory_uri();
		$theme   = wp_get_theme( get_template() );
		$version = $theme->get( 'Version' );

		wp_enqueue_style( 'flatsome-panel-css', $uri . '/inc/admin/panel/panel.css', array(), $version );
		wp_enqueue_script( 'flatsome-panel', $uri . '/inc/admin/panel/panel.js', array( 'jquery', 'wp-date' ), $version, true );
		wp_localize_script( 'flatsome-panel', 'flatsomePanelOptions', array(
			'errorMessage' => __( 'Sorry, an error occurred while accessing the API.', 'flatsome' ),
		) );
	}

	/**
	 * Returns a list of available purchase codes for a token.
	 */
	public function flatsome_purchase_codes() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return wp_send_json_error();
		}

		if ( is_a( flatsome_envato()->registration, 'Flatsome_Envato_Registration' ) ) {
			$result = flatsome_envato()->registration->get_purchase_codes();

			if ( is_wp_error( $result ) ) {
				return wp_send_json_error();
			} else {
				return wp_send_json( $result );
			}
		}

		return wp_send_json_error();
	}

	/**
	 * Creates the dashboard page
	 * @see  add_theme_page()
	 * @since 1.0.0
	 */
	public function flatsome_panel_register_menu() {
		add_menu_page( 'Welcome to Flatsome', 'Flatsome', 'manage_options', 'flatsome-panel', array( $this, 'flatsome_panel_welcome' ), get_template_directory_uri() . '/assets/img/logo-icon.svg', '2' );
		add_submenu_page( 'flatsome-panel', 'Theme Registration', 'Theme Registration', 'manage_options', 'admin.php?page=flatsome-panel' );
		add_submenu_page( 'flatsome-panel', 'Help & Guides', 'Help & Guides', 'manage_options', 'flatsome-panel-support', array( $this, 'flatsome_panel_support' ) );
		add_submenu_page( 'flatsome-panel', 'Status', 'Status', 'manage_options', 'flatsome-panel-status', array( $this, 'flatsome_panel_status' ) );
		add_submenu_page( 'flatsome-panel', 'Change log', 'Change log', 'manage_options', 'flatsome-panel-changelog', array( $this, 'flatsome_panel_changelog' ) );
		add_submenu_page( 'flatsome-panel', '', 'Theme Options', 'manage_options', 'customize.php' );
	}

	/**
	 * The welcome screen
	 * @since 1.0.0
	 */
	public function flatsome_panel_welcome() {
		?>
		<div class="flatsome-panel">
			<div class="wrap about-wrap">
				<?php require get_template_directory() . '/inc/admin/panel/sections/top.php'; ?>
				<?php require get_template_directory() . '/inc/admin/panel/sections/tab-activate.php'; ?>
			</div>
		</div>
		<?php
	}

	public function flatsome_panel_getting_started() {
		?>
		<div class="flatsome-panel">
			<div class="wrap about-wrap">
				<?php require get_template_directory() . '/inc/admin/panel/sections/top.php'; ?>
				<?php require get_template_directory() . '/inc/admin/panel/sections/tab-guide.php'; ?>
			</div>
		</div>
		<?php
	}

	public function flatsome_panel_tutorials() {
		?>
		<div class="flatsome-panel">
			<div class="wrap about-wrap">
				<?php require get_template_directory() . '/inc/admin/panel/sections/top.php'; ?>
				<?php require get_template_directory() . '/inc/admin/panel/sections/tab-tutorials.php'; ?>
			</div>
		</div>
		<?php
	}

	public function flatsome_panel_support() {
		?>
		<div class="flatsome-panel">
			<div class="wrap about-wrap">
				<?php require get_template_directory() . '/inc/admin/panel/sections/top.php'; ?>
				<?php require get_template_directory() . '/inc/admin/panel/sections/tab-support.php'; ?>
			</div>
		</div>
		<?php
	}

	public function flatsome_panel_status() {
		?>
		<div class="flatsome-panel">
			<div class="wrap about-wrap">
				<?php require get_template_directory() . '/inc/admin/panel/sections/top.php'; ?>
				<?php require get_template_directory() . '/inc/admin/panel/sections/tab-status.php'; ?>
			</div>
		</div>
		<?php
	}

	public function flatsome_panel_changelog() {
		?>
		<div class="flatsome-panel">
			<div class="wrap about-wrap">
				<?php require get_template_directory() . '/inc/admin/panel/sections/top.php'; ?>
				<?php require get_template_directory() . '/inc/admin/panel/sections/tab-changelog.php'; ?>
			</div>
		</div>
		<?php
	}
}

$GLOBALS['Flatsome_Admin'] = new Flatsome_Admin();

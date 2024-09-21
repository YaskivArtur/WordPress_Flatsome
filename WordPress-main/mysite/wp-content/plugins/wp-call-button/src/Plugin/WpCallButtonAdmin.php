<?php
/**
 * The Admin functionality of the plugin.
 *
 * @link       http://wpbeginner.com
 * @since      1.0.0
 *
 * @package    WpCallButton
 * @subpackage WpCallButton/Plugin
 */
namespace WpCallButton\Plugin;

class WpCallButtonAdmin {

	/**
	 * Holds the admin menu name slug.
	 *
	 * @var string
	 */
	public $menu;

	/**
	 * Holds the admin menu page view.
	 *
	 * @var string
	 */
	public $active_view;

	/**
	 * Holds the admin menu page view heading.
	 *
	 * @var string
	 */
	public $menu_sub_title;

	/**
	 * Holds the plugin name slug.
	 *
	 * @var string
	 */
	public $plugin_slug;

	/**
	 * Holds the plugin name.
	 *
	 * @var string
	 */
	public $plugin_name;

	/**
	 * Holds the AM Plugins class instance
	 *
	 * @var string
	 */
	public $plugins_holder;

	private $plugin_name_menu;

	/**
	 * Constructor.
	 */
	function __construct( $plugin_name, $plugin_slug ) {
		// set the active view
		$this->active_view    = '';
		$this->menu_sub_title = esc_attr__( 'Sticky Call Button', 'wp-call-button' );

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$view = ! empty( $_GET['view'] ) ? sanitize_text_field( wp_unslash( $_GET['view'] ) ) : '';

		// set the name for the view
		switch ( $view ) {
			case 'about-us':
				$this->active_view    = 'about-us';
				$this->menu_sub_title = esc_attr__( 'About Us', 'wp-call-button' );
				break;
			case 'static-call-button':
				$this->active_view    = 'static-call-button';
				$this->menu_sub_title = esc_attr__( 'Static Call Button', 'wp-call-button' );
				break;
			default:
				break;
		}

		$this->plugin_name      = $plugin_name;
		$this->plugin_slug      = $plugin_slug;
		$this->plugin_name_menu = ( empty( $this->active_view ) ? '' : $this->menu_sub_title . ' - ' ) . $this->plugin_name;

		// Initialize the AM plugins holder.
		$this->plugins_holder = new WpAmPluginsHolder();

		$this->init();
	}

	/**
	 * Register necessary plugin hooks and filters
	 */
	public function init() {
		// Create the activation hook.
		register_activation_hook( WP_CALL_BUTTON_FILE, array( $this, 'activation_hook' ) );

		// Setup the settings page.
		add_action( 'admin_menu', array( $this, 'settings_menu' ) );

		// Link to settings page.
		add_filter( 'plugin_action_links_' . plugin_basename( WP_CALL_BUTTON_FILE ), array( $this, 'add_settings_link' ), 10, 2 );

		// Admin notices.
		add_action( 'admin_notices', array( $this, 'dashboard_notices' ) );

		// Dismiss welcome notice ajax.
		add_action( 'wp_ajax_' . $this->plugin_slug . '_dismiss_dashboard_notices', array( $this, 'dismiss_dashboard_notices' ) );

		// Ajax endpoint for searching posts / pages / cpts by title.
		add_action( 'wp_ajax_' . $this->plugin_slug . '_get_posts', array( $this, 'get_posts_for_ajax' ) );

		// Hide all unrelated to the plugin notices on the plugin admin pages.
		add_action( 'admin_print_scripts', array( $this, 'hide_unrelated_notices' ) );

		// Hide widget from block widget screen.
		add_filter( 'widget_types_to_hide_from_legacy_widget_block', array( $this, 'hide_widget' ) );
	}

	/**
	 * Add Call Button widget to hidden widgets on block widget screen.
	 *
	 * @param array $hidden_widget_types Widgets hidden from block editor.
	 * @return array Hidden widgets with WP Call Button added.
	 */
	function hide_widget( array $hidden_widget_types ) {
		$hidden_widget_types[] = 'wp-call-button-widget-main-a';
		return $hidden_widget_types;
	}

	/**
	 * Get the posts for the Select2 dropdown ajax endpoint.
	 */
	function get_posts_for_ajax() {
		$data = array();
		// check nonce to process form data
		$search_term = isset( $_REQUEST['q'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['q'] ) ) : '';
		if (
			strlen( $search_term ) >= 3 &&
			isset( $_REQUEST['_wp_call_btn_search_nonce'] ) &&
			wp_verify_nonce( wp_unslash( $_REQUEST['_wp_call_btn_search_nonce'] ), $this->plugin_slug . '-nonce' )
		) {

			/**
			 * Post types to include when searching for posts on admin pages.
			 *
			 * @param string[] $post_types Post types to include in search. Deafult post, page.
			 */
			$post_types = apply_filters( 'wpcb_button_post_types', array( 'post', 'page' ) );
			$post_types = implode( "', '", array_map( 'sanitize_key', $post_types ) );

			// Search the posts db.
			global $wpdb;
			$query = $wpdb->get_results(
				$wpdb->prepare(
					"SELECT ID, post_title, post_type FROM $wpdb->posts WHERE post_status = 'publish' " .
					// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
					"AND post_type IN ( '{$post_types}' ) " .
					'AND post_title LIKE %s',
					'%' . $wpdb->esc_like( $search_term ) . '%'
				)
			);

			// Loop over and build data array.
			if ( ! empty( $query ) ) {
				foreach ( $query as $qr ) {
					if ( isset( $qr->post_title ) && isset( $qr->ID ) ) {
						$data[] = array(
							'id'   => esc_attr( $qr->ID ) . '____' . esc_attr( $qr->post_title ) . '____' . esc_attr( $qr->post_type ),
							'text' => esc_html( $qr->post_title ) . ' <em>(' . esc_html( $qr->post_type ) . ')</em>',
						);
					}
				}

				// Send response.
				wp_send_json_success( $data );
				wp_die();
			}
		}
		wp_send_json_error( $data );
		wp_die();
	}

	/**
	 * Show relevant notices for the plugin.
	 */
	function dashboard_notices() {
		global $pagenow;

		if ( ! get_option( $this->plugin_slug . '_welcome' ) ) {
			if (
				! (
					$pagenow === 'options-general.php' &&
					// phpcs:ignore WordPress.Security.NonceVerification.Recommended
					isset( $_GET['page'] ) &&
					// phpcs:ignore WordPress.Security.NonceVerification.Recommended
					wp_unslash( $_GET['page'] ) === $this->plugin_slug
				)
			) {
				$setting_page = admin_url( 'options-general.php?page=' . $this->plugin_slug );
				$ajax_url     = admin_url( 'admin-ajax.php' );
				// Load the notices view.
				include plugin_dir_path( WP_CALL_BUTTON_FILE ) . 'views/activate_welcome_view.php';
			}
		}
	}

	/**
	 * Dismiss the welcome notice for the plugin.
	 */
	function dismiss_dashboard_notices() {
			check_ajax_referer( $this->plugin_slug . '-nonce', 'nonce' );
			// User has dismissed the welcome notice.
			update_option( $this->plugin_slug . '_welcome', 1 );
			exit;
	}

	/**
	 * Add a settings page link in the PLugins list page
	 */
	function add_settings_link( $action_links ) {
		$action_links['settings_page'] = '<a href="' . admin_url( 'options-general.php?page=' . $this->plugin_slug ) . '">' . __( 'Settings', 'wp-call-button' ) . '</a>';
		return $action_links;
	}

	/**
	 * Creates a new menu page for creating a plugin setting page.
	 */
	public function settings_menu() {
		// A new admin page for the Easy Call Now Button for WordPress settings.
		$this->menu = add_options_page( $this->plugin_name_menu, $this->plugin_name, 'administrator', $this->plugin_slug, array( $this, 'settings_menu_cb' ) );

		// Load admin styles based on the hook suffix.
		if ( $this->menu ) {
			add_action( 'load-' . $this->menu, array( $this, 'admin_assets' ) );
		}
	}

	/**
	 * Outputs the content for the Easy Call Now Button for WordPress settings page.
	 */
	public function settings_menu_cb() {
		if ( \current_user_can( 'administrator' ) ) {

			// Form saved state.
			$saved_state = 'no';

			// Check nonce to process form data.
			if ( isset( $_REQUEST['_wp_call_button_settings_nonce'] ) && wp_verify_nonce( wp_unslash( $_REQUEST['_wp_call_button_settings_nonce'] ), $this->plugin_slug . '-settings-nonce' ) ) {

				// Check if form submitted.
				if ( isset( $_REQUEST['submit'] ) && isset( $_REQUEST['action'] ) && 'update' === $_REQUEST['action'] ) {

					$settings = array(
						'wpcallbtn_phone_num'              => isset( $_POST['wpcallbtn_full_phone_num'] ) ? sanitize_text_field( wp_unslash( $_POST['wpcallbtn_full_phone_num'] ) ) : ( isset( $_POST['wpcallbtn_phone_num'] ) ? sanitize_text_field( wp_unslash( $_POST['wpcallbtn_phone_num'] ) ) : '' ),
						'wpcallbtn_button_color'           => isset( $_POST['wpcallbtn_button_color'] ) ? sanitize_text_field( wp_unslash( $_POST['wpcallbtn_button_color'] ) ) : '',
						'wpcallbtn_button_position'        => isset( $_POST['wpcallbtn_button_position'] ) ? sanitize_text_field( wp_unslash( $_POST['wpcallbtn_button_position'] ) ) : '',
						'wpcallbtn_button_enabled'         => isset( $_POST['wpcallbtn_button_enabled'] ) ? sanitize_text_field( wp_unslash( $_POST['wpcallbtn_button_enabled'] ) ) : '',
						'wpcallbtn_button_text'            => isset( $_POST['wpcallbtn_button_text'] ) ? sanitize_text_field( wp_unslash( $_POST['wpcallbtn_button_text'] ) ) : '',
						'wpcallbtn_button_click_ga'        => isset( $_POST['wpcallbtn_button_click_ga'] ) ? sanitize_text_field( wp_unslash( $_POST['wpcallbtn_button_click_ga'] ) ) : '',
						'wpcallbtn_button_size'            => isset( $_POST['wpcallbtn_button_size'] ) ? sanitize_text_field( wp_unslash( $_POST['wpcallbtn_button_size'] ) ) : '',
						'wpcallbtn_button_order'           => isset( $_POST['wpcallbtn_button_order'] ) ? sanitize_text_field( wp_unslash( $_POST['wpcallbtn_button_order'] ) ) : '',
						'wpcallbtn_button_filter_ids_show' =>
							( isset( $_POST['wpcallbtn_button_filter_ids_show'] ) && is_array( $_POST['wpcallbtn_button_filter_ids_show'] ) ) ?
							array_map( 'sanitize_text_field', wp_unslash( $_POST['wpcallbtn_button_filter_ids_show'] ) ) : array(),
						'wpcallbtn_button_filter_ids_hide' =>
							( isset( $_POST['wpcallbtn_button_filter_ids_hide'] ) && is_array( $_POST['wpcallbtn_button_filter_ids_hide'] ) ) ?
							array_map( 'sanitize_text_field', wp_unslash( $_POST['wpcallbtn_button_filter_ids_hide'] ) ) : array(),
						'wpcallbtn_button_filter_type'     => isset( $_POST['wpcallbtn_button_filter_type'] ) ? sanitize_text_field( wp_unslash( $_POST['wpcallbtn_button_filter_type'] ) ) : '',
						'wpcallbtn_button_mobile_only'     => isset( $_POST['wpcallbtn_button_mobile_only'] ) ? sanitize_text_field( wp_unslash( $_POST['wpcallbtn_button_mobile_only'] ) ) : '',
					);

					update_option( $this->plugin_slug . '_welcome', 1 );

					if ( update_option( $this->plugin_slug . '-settings', $settings ) ) {
						$saved_state = 'yes';
					}
				}
			}

			// Get the settings.
			$settings = WpCallButtonHelpers::get_settings();

			// Get the plugins holder
			$this->plugins_holder = new WpAmPluginsHolder();

			// Render the view.
			require plugin_dir_path( WP_CALL_BUTTON_FILE ) . 'views/admin_view.php';
		}
	}

	/**
	 * Hook all the necessary admin styles and scripts for this page.
	 */
	public function admin_assets() {
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
		add_action( 'admin_head', array( $this, 'admin_head_styles' ) );
	}

	/**
	 * Print admin head inline styles.
	 */
	function admin_head_styles() {
		echo '<style type="text/css">.button-status::after { content: \'' . esc_html__( 'Inactive', 'wp-call-button' ) . '\'; }' .
			'.wpcb-switch-checkbox:checked + .button-status::after { content: \'' . esc_html__( 'Active', 'wp-call-button' ) . '\'; }</style>';
	}

	/**
	 * Register and enqueue all the necessary admin styles
	 */
	public function admin_styles() {
		WpCallButtonHelpers::enqueue_admin_styles();
	}

	/**
	 * Include all the necessary admin scripts
	 */
	public function admin_scripts() {
		WpCallButtonHelpers::enqueue_admin_scripts();
	}

	/**
	 * Fired when the plugin is activated.
	 */
	public function activation_hook() {
		// Get the settings.
		$settings = WpCallButtonHelpers::get_settings();

		// Update the setings
		update_option( $this->plugin_slug . '-settings', $settings );
	}

	/**
	 * Remove all non-WP Mail SMTP plugin notices from plugin pages.
	 *
	 * Taken from WPMailSMTP Codebase.
	 */
	public function hide_unrelated_notices() {

		// Bail if we're not on our screen or page.
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( empty( $_REQUEST['page'] ) || strpos( wp_unslash( $_REQUEST['page'] ), $this->plugin_slug ) === false ) {
			return;
		}

		global $wp_filter;

		if ( ! empty( $wp_filter['user_admin_notices']->callbacks ) && is_array( $wp_filter['user_admin_notices']->callbacks ) ) {
			foreach ( $wp_filter['user_admin_notices']->callbacks as $priority => $hooks ) {
				foreach ( $hooks as $name => $arr ) {
					if ( is_object( $arr['function'] ) && $arr['function'] instanceof \Closure ) {
						unset( $wp_filter['user_admin_notices']->callbacks[ $priority ][ $name ] );
						continue;
					}
					if ( ! empty( $arr['function'][0] ) && is_object( $arr['function'][0] ) && strpos( strtolower( get_class( $arr['function'][0] ) ), 'wpmailsmtp' ) !== false ) {
						continue;
					}
					if ( ! empty( $name ) && strpos( strtolower( $name ), 'wpmailsmtp' ) === false ) {
						unset( $wp_filter['user_admin_notices']->callbacks[ $priority ][ $name ] );
					}
				}
			}
		}

		if ( ! empty( $wp_filter['admin_notices']->callbacks ) && is_array( $wp_filter['admin_notices']->callbacks ) ) {
			foreach ( $wp_filter['admin_notices']->callbacks as $priority => $hooks ) {
				foreach ( $hooks as $name => $arr ) {
					if ( is_object( $arr['function'] ) && $arr['function'] instanceof \Closure ) {
						unset( $wp_filter['admin_notices']->callbacks[ $priority ][ $name ] );
						continue;
					}
					if ( ! empty( $arr['function'][0] ) && is_object( $arr['function'][0] ) && strpos( strtolower( get_class( $arr['function'][0] ) ), 'wpmailsmtp' ) !== false ) {
						continue;
					}
					if ( ! empty( $name ) && strpos( strtolower( $name ), 'wpmailsmtp' ) === false ) {
						unset( $wp_filter['admin_notices']->callbacks[ $priority ][ $name ] );
					}
				}
			}
		}

		if ( ! empty( $wp_filter['all_admin_notices']->callbacks ) && is_array( $wp_filter['all_admin_notices']->callbacks ) ) {
			foreach ( $wp_filter['all_admin_notices']->callbacks as $priority => $hooks ) {
				foreach ( $hooks as $name => $arr ) {
					if ( is_object( $arr['function'] ) && $arr['function'] instanceof \Closure ) {
						unset( $wp_filter['all_admin_notices']->callbacks[ $priority ][ $name ] );
						continue;
					}
					if ( ! empty( $arr['function'][0] ) && is_object( $arr['function'][0] ) && strpos( strtolower( get_class( $arr['function'][0] ) ), 'wpmailsmtp' ) !== false ) {
						continue;
					}
					if ( ! empty( $name ) && strpos( strtolower( $name ), 'wpmailsmtp' ) === false ) {
						unset( $wp_filter['all_admin_notices']->callbacks[ $priority ][ $name ] );
					}
				}
			}
		}
	}

	/**
	 * Check if the radio button should be checked
	 *
	 * @param String $element_value
	 * @param String $setting_value
	 *
	 * @returns String
	 */
	private function radio_checked( $element_value, $setting_value ) {
		if ( $element_value === $setting_value ) {
			echo ' checked="checked" ';
		}
	}
}

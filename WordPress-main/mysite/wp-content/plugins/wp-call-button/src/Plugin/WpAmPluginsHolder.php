<?php
/**
 * The Plugin Holderfunctionality of the plugin.
 *
 * @link       http://wpbeginner.com
 * @since      1.0.0
 *
 * @package    WpCallButton
 * @subpackage WpCallButton/Plugin
 */
namespace WpCallButton\Plugin;

class WpAmPluginsHolder {

	/**
	 * All available AM plugins.
	 */
	public $all_am_plugins;

	// Constructor.
	public function __construct() {

		// Get the plugins.
		$this->all_am_plugins = $this->get_am_plugins();

		// Register the Ajax endpoint for plugins about.
		add_action( 'wp_ajax_wp_call_button_about_ajax', [ $this, 'process_ajax' ] );
	}


	/**
	 * Process all AJAX requests.
	 */
	public function process_ajax() {

		$data = [];

		// Only admins can fire these ajax requests.
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( $data );
		}

		check_ajax_referer( 'wp-call-button-about', 'nonce' );

		if ( empty( $_POST['task'] ) ) {
			wp_send_json_error( $data );
		}

		$task = sanitize_key( wp_unslash( $_POST['task'] ) );

		switch ( $task ) {
			case 'pro_banner_dismiss':
				update_user_meta( get_current_user_id(), 'wp_mail_smtp_pro_banner_dismissed', true );
				$data['message'] = esc_html__( 'WP Mail SMTP Pro related message was successfully dismissed.', 'wp-mail-smtp' );
				break;

			case 'about_plugin_install':
				self::ajax_plugin_install();
				break;

			case 'about_plugin_activate':
				self::ajax_plugin_activate();
				break;

			default:
				// Allow custom tasks data processing being added here.
				$data = apply_filters( 'wp_call_button_admin_process_ajax_' . $task . '_data', $data );
		}

		// Final ability to rewrite all the data, just in case.
		$data = (array) apply_filters( 'wp_call_button_admin_process_ajax_data', $data, $task );

		if ( empty( $data ) ) {
			wp_send_json_error( $data );
		}

		wp_send_json_success( $data );
	}

	/**
	 * Generate all the required CSS classed and labels to be used in rendering.
	 *
	 * @param array $plugin
	 * @param bool  $is_pro
	 *
	 * @return mixed
	 */
	public function get_about_plugins_data( $plugin, $is_pro = false ) {

		$data = [];

		if ( \array_key_exists( $plugin['path'], \get_plugins() ) ) {
			if ( \is_plugin_active( $plugin['path'] ) ) {
				// Status text/status.
				$data['status_class'] = 'status-active';
				$data['status_text']  = esc_html__( 'Active', 'wp-call-button' );
				// Button text/status.
				$data['action_class'] = $data['status_class'] . ' button button-secondary disabled';
				$data['action_text']  = esc_html__( 'Activated', 'wp-call-button' );
				$data['plugin_src']   = esc_attr( $plugin['path'] );
			} else {
				// Status text/status.
				$data['status_class'] = 'status-inactive';
				$data['status_text']  = esc_html__( 'Inactive', 'wp-call-button' );
				// Button text/status.
				$data['action_class'] = $data['status_class'] . ' button button-secondary perform-action';
				$data['action_text']  = esc_html__( 'Activate', 'wp-call-button' );
				$data['plugin_src']   = esc_attr( $plugin['path'] );
			}
		} else {
			if ( ! $is_pro ) {
				// Doesn't exist, install.
				// Status text/status.
				$data['status_class'] = 'status-download';
				$data['status_text']  = esc_html__( 'Not Installed', 'wp-call-button' );
				// Button text/status.
				$data['action_class'] = $data['status_class'] . ' button button-primary wpcallbtn-button-green perform-action';
				$data['action_text']  = esc_html__( 'Install Plugin', 'wp-call-button' );
				$data['plugin_src']   = \esc_url( $plugin['url'] );
			}
		}

		return $data;
	}

	/**
	 * List of AM plugins that we propose to install.
	 *
	 * @return array
	 */
	private function get_am_plugins() {

		// Assets URL.
		$assets_url = plugins_url( '', WP_CALL_BUTTON_FILE );

		$data = [
			'mi'      => [
				'path' => 'google-analytics-for-wordpress/googleanalytics.php',
				'icon' => $assets_url . '/assets/img/plugin-mi.png',
				'name' => esc_html__( 'MonsterInsights', 'wp-call-button' ),
				'desc' => esc_html__( 'MonsterInsights makes it “effortless” to properly connect your WordPress site with Google Analytics, so you can start making data-driven decisions to grow your business.', 'wp-call-button' ),
				'url'  => 'https://downloads.wordpress.org/plugin/google-analytics-for-wordpress.zip',
				'pro'  => [
					'path' => 'google-analytics-premium/googleanalytics-premium.php',
					'icon' => $assets_url . '/assets/img/plugin-mi.png',
					'name' => esc_html__( 'MonsterInsights Pro', 'wp-call-button' ),
					'desc' => esc_html__( 'MonsterInsights makes it “effortless” to properly connect your WordPress site with Google Analytics, so you can start making data-driven decisions to grow your business.', 'wp-call-button' ),
					'url'  => 'https://www.monsterinsights.com/?utm_source=WordPress&utm_medium=about&utm_campaign=smtp',
				],
			],
			'om'      => [
				'path' => 'optinmonster/optin-monster-wp-api.php',
				'icon' => $assets_url . '/assets/img/plugin-om.png',
				'name' => esc_html__( 'OptinMonster', 'wp-call-button' ),
				'desc' => esc_html__( 'Our high-converting optin forms like Exit-Intent® popups, Fullscreen Welcome Mats, and Scroll boxes help you dramatically boost conversions and get more email subscribers.', 'wp-call-button' ),
				'url'  => 'https://downloads.wordpress.org/plugin/optinmonster.zip',
			],
			'wpforms' => [
				'path' => 'wpforms-lite/wpforms.php',
				'icon' => $assets_url . '/assets/img/plugin-wpf.png',
				'name' => esc_html__( 'Contact Forms by WPForms', 'wp-call-button' ),
				'desc' => esc_html__( 'The best WordPress contact form plugin. Drag & Drop online form builder that helps you create beautiful contact forms with just a few clicks.', 'wp-call-button' ),
				'url'  => 'https://downloads.wordpress.org/plugin/wpforms-lite.zip',
				'pro'  => [
					'path' => 'wpforms/wpforms.php',
					'icon' => $assets_url . '/assets/img/plugin-wpf.png',
					'name' => esc_html__( 'WPForms Pro', 'wp-call-button' ),
					'desc' => esc_html__( 'The best WordPress contact form plugin. Drag & Drop online form builder that helps you create beautiful contact forms with just a few clicks.', 'wp-call-button' ),
					'url'  => 'https://wpforms.com/?utm_source=WordPress&utm_medium=about&utm_campaign=wpcb',
				],
			],
			'wpms'    => [
				'path' => 'wp-mail-smtp/wp_mail_smtp.php',
				'icon' => $assets_url . '/assets/img/plugin-smtp.png',
				'name' => esc_html__( 'WP Mail SMTP', 'wp-call-button' ),
				'desc' => esc_html__( 'SMTP (Simple Mail Transfer Protocol) is an industry standard for sending emails. SMTP helps increase email deliverability by using proper authentication.', 'wp-call-button' ),
				'url'  => 'https://downloads.wordpress.org/plugin/wp-mail-smtp.zip',
				'pro'  => [
					'path'     => 'wp-mail-smtp-pro/wp_mail_smtp.php',
					'path_alt' => 'wp-mail-smtp-pro/wp-mail-smtp.php',
					'icon'     => $assets_url . '/assets/img/plugin-smtp.png',
					'name'     => esc_html__( 'WP Mail SMTP Pro', 'wp-call-button' ),
					'desc'     => esc_html__( 'SMTP (Simple Mail Transfer Protocol) is an industry standard for sending emails. SMTP helps increase email deliverability by using proper authentication.', 'wp-call-button' ),
					'url'      => 'https://wpmailsmtp.com/?utm_source=WordPress&utm_medium=about&utm_campaign=wpcb',
				],
			],
			'sp'      => [
				'path' => 'coming-soon/coming-soon.php',
				'icon' => $assets_url . '/assets/img/plugin-sp.png',
				'name' => esc_html__( 'SeedProd', 'wp-call-button' ),
				'desc' => esc_html__( 'Get the best WordPress Coming Soon Page plugin. Capture leads before your site goes live or while in Maintenance Mode. Super easy to setup and use.', 'wp-call-button' ),
				'url'  => 'https://downloads.wordpress.org/plugin/coming-soon.zip',
				'pro'  => [
					'path' => 'seedprod-coming-soon-pro-5/seedprod-coming-soon-pro-5.php',
					'icon' => $assets_url . '/assets/img/plugin-sp.png',
					'name' => esc_html__( 'SeedProd Pro', 'wp-call-button' ),
					'desc' => esc_html__( 'Get the best WordPress Coming Soon Page plugin. Capture leads before your site goes live or while in Maintenance Mode. Super easy to setup and use.', 'wp-call-button' ),
					'url'  => 'https://seedprod.com/?utm_source=WordPress&utm_medium=about&utm_campaign=wpcb',
				],
			],
			'rp'      => [
				'path' => 'rafflepress/rafflepress.php',
				'icon' => $assets_url . '/assets/img/plugin-rp.png',
				'name' => esc_html__( 'RafflePress', 'wp-call-button' ),
				'desc' => esc_html__( 'RafflePress is the best WordPress contest and giveway plugin. Grow your email list, followers, and website traffic with viral giveaways in WordPress.', 'wp-call-button' ),
				'url'  => 'https://downloads.wordpress.org/plugin/rafflepress.zip',
				'pro'  => [
					'path' => 'rafflepress-pro/rafflepress-pro.php',
					'icon' => $assets_url . '/assets/img/plugin-rp.png',
					'name' => esc_html__( 'RafflePress Pro', 'wp-call-button' ),
					'desc' => esc_html__( 'RafflePress is the best WordPress contest and giveway plugin. Grow your email list, followers, and website traffic with viral giveaways in WordPress.', 'wp-call-button' ),
					'url'  => 'https://rafflepress.com/?utm_source=WordPress&utm_medium=about&utm_campaign=wpcb',
				],
			],
		];

		return $data;
	}

	/**
	 * Activate the given plugin.
	 */
	public static function ajax_plugin_activate() {

		// Run a security check.
		\check_ajax_referer( 'wp-call-button-about', 'nonce' );

		$error = esc_html__( 'Could not activate the plugin. Please activate it from the Plugins page.', 'wp-call-button' );

		// Check for permissions.
		if ( ! \current_user_can( 'activate_plugins' ) ) {
			\wp_send_json_error( $error );
		}

		if ( isset( $_POST['plugin'] ) ) {

			$activate = \activate_plugins( wp_unslash( $_POST['plugin'] ) );

			if ( ! \is_wp_error( $activate ) ) {
				\wp_send_json_success( esc_html__( 'Plugin activated.', 'wp-call-button' ) );
			}
		}

		\wp_send_json_error( $error );
	}

	/**
	 * Install & activate the given plugin.
	 */
	public static function ajax_plugin_install() {

		// Run a security check.
		\check_ajax_referer( 'wp-call-button-about', 'nonce' );

		$error = esc_html__( 'Could not install the plugin.', 'wp-call-button' );

		// Check for permissions.
		if ( ! \current_user_can( 'activate_plugins' ) ) {
			\wp_send_json_error( $error );
		}

		if ( empty( $_POST['plugin'] ) ) {
			\wp_send_json_error();
		}

		// Set the current screen to avoid undefined notices.
		\set_current_screen( 'wp-mail-smtp_page_wp-mail-smtp-about' );

		// Prepare variables.
		$url = \esc_url_raw(
			\add_query_arg(
				[
					'page' => 'wp-mail-smtp-about',
				],
				\admin_url( 'admin.php' )
			)
		);

		$creds = \request_filesystem_credentials( $url, '', false, false, null );

		// Check for file system permissions.
		if ( false === $creds ) {
			\wp_send_json_error( $error );
		}

		if ( ! \WP_Filesystem( $creds ) ) {
			\wp_send_json_error( $error );
		}

		// We do not need any extra credentials if we have gotten this far, so let's install the plugin.
		require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';

		// Do not allow WordPress to search/download translations, as this will break JS output.
		\remove_action( 'upgrader_process_complete', [ 'Language_Pack_Upgrader', 'async_upgrade' ], 20 );

		// Create the plugin upgrader with our custom skin.
		$installer = new \Plugin_Upgrader( new WpAmPluginsInstallSkin() );

		// Error check.
		if ( ! \method_exists( $installer, 'install' ) || empty( $_POST['plugin'] ) ) {
			\wp_send_json_error( $error );
		}

		$installer->install( wp_unslash( $_POST['plugin'] ) );

		// Flush the cache and return the newly installed plugin basename.
		\wp_cache_flush();

		if ( $installer->plugin_info() ) {

			$plugin_basename = $installer->plugin_info();

			// Activate the plugin silently.
			$activated = \activate_plugin( $plugin_basename );

			if ( ! \is_wp_error( $activated ) ) {
				\wp_send_json_success(
					[
						'msg'          => esc_html__( 'Plugin installed & activated.', 'wp-call-button' ),
						'is_activated' => true,
						'basename'     => $plugin_basename,
					]
				);
			} else {
				\wp_send_json_success(
					[
						'msg'          => esc_html__( 'Plugin installed.', 'wp-call-button' ),
						'is_activated' => false,
						'basename'     => $plugin_basename,
					]
				);
			}
		}

		\wp_send_json_error( $error );
	}

}

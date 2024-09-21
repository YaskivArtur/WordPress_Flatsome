<?php // phpcs:ignore WordPress.Files.FileName
/**
 * Status main class.
 *
 * @package Flatsome\Admin
 */

namespace Flatsome\Admin;

defined( 'ABSPATH' ) || exit;

use DirectoryIterator;

/**
 * Class Status
 *
 * @package Flatsome\Admin
 */
final class Status {
	/**
	 * The single instance of the class.
	 *
	 * @var Status
	 */
	protected static $instance = null;

	/**
	 * Holds theme report data.
	 *
	 * @var array Theme report.
	 */
	private $theme;

	/**
	 * Holds environment report data.
	 *
	 * @var array Environment report.
	 */
	private $environment;

	/**
	 * Holds database report data.
	 *
	 * @var array Database report.
	 */
	private $database;

	/**
	 * Holds security report data.
	 *
	 * @var array Security report.
	 */
	private $security;

	/**
	 * Holds the test keys and test results after tests are run.
	 *
	 * @var array Test results.
	 */
	private $tests = array(
		'is_registered'     => array( 'log_level_fail' => Log_Level::CRITICAL ),
		'wp_version'        => array( 'log_level_fail' => Log_Level::CRITICAL ),
		'wc_version'        => array( 'log_level_fail' => Log_Level::CRITICAL ),
		'wp_memory_limit'   => array( 'log_level_fail' => Log_Level::WARNING ),
		'php_version'       => array( 'log_level_fail' => Log_Level::WARNING ),
		'secure_connection' => array( 'log_level_fail' => Log_Level::WARNING ),
		'hide_errors'       => array( 'log_level_fail' => Log_Level::CRITICAL ),
		'db_version'        => array( 'log_level_fail' => Log_Level::WARNING ),
	);

	/**
	 * Holds the files that are overridden.
	 *
	 * @var array The files that have overrides.
	 */
	private $override_files = array();

	/**
	 * Does the theme have any overrides?
	 *
	 * @var bool.
	 */
	private $outdated_templates = false;

	/**
	 * Collect and display extended info?
	 *
	 * @var bool.
	 */
	private $extended = false;

	/**
	 * The recommended minimum PHP version.
	 *
	 * @var string Version.
	 */
	private $recommended_php_version = '8.0';

	/**
	 * Status icons.
	 *
	 * @var string[]
	 */
	private $icon = array(
		'success'  => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
						<path d="M17.28 9.28a.75.75 0 00-1.06-1.06l-5.97 5.97-2.47-2.47a.75.75 0 00-1.06 1.06l3 3a.75.75 0 001.06 0l6.5-6.5z"/>
						<path fill-rule="evenodd" d="M12 1C5.925 1 1 5.925 1 12s4.925 11 11 11 11-4.925 11-11S18.075 1 12 1zM2.5 12a9.5 9.5 0 1119 0 9.5 9.5 0 01-19 0z"/>
					</svg>',
		'warning'  => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
  						<path d="M12 7C12.4142 7 12.75 7.33579 12.75 7.75V12.25C12.75 12.6642 12.4142 13 12 13C11.5858 13 11.25 12.6642 11.25 12.25V7.75C11.25 7.33579 11.5858 7 12 7Z"/>
  						<path d="M13 16C13 16.5523 12.5523 17 12 17C11.4477 17 11 16.5523 11 16C11 15.4477 11.4477 15 12 15C12.5523 15 13 15.4477 13 16Z"/>
  						<path fill-rule="evenodd" clip-rule="evenodd" d="M12 1C5.92487 1 1 5.92487 1 12C1 18.0751 5.92487 23 12 23C18.0751 23 23 18.0751 23 12C23 5.92487 18.0751 1 12 1ZM2.5 12C2.5 6.75329 6.75329 2.5 12 2.5C17.2467 2.5 21.5 6.75329 21.5 12C21.5 17.2467 17.2467 21.5 12 21.5C6.75329 21.5 2.5 17.2467 2.5 12Z"/>
					</svg>',
		'critical' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
						<path d="M9.036 7.976a.75.75 0 00-1.06 1.06L10.939 12l-2.963 2.963a.75.75 0 101.06 1.06L12 13.06l2.963 2.964a.75.75 0 001.061-1.06L13.061 12l2.963-2.964a.75.75 0 10-1.06-1.06L12 10.939 9.036 7.976z"/>
						<path fill-rule="evenodd" d="M12 1C5.925 1 1 5.925 1 12s4.925 11 11 11 11-4.925 11-11S18.075 1 12 1zM2.5 12a9.5 9.5 0 1119 0 9.5 9.5 0 01-19 0z"/>
					</svg>',
	);

	/**
	 * Status constructor.
	 */
	private function __construct() {
		if ( isset( $_GET['type'] ) ) { // phpcs:ignore WordPress.Security
			if ( sanitize_text_field( $_GET['type'] ) === 'extended' ) { // phpcs:ignore WordPress.Security
				$this->extended = true;
			}
		}
		$this->theme       = $this->get_theme_info();
		$this->environment = $this->get_environment_info();
		$this->database    = $this->get_database_info();
		$this->security    = $this->get_security_info();

		$this->run_tests();
	}

	/**
	 * Main instance.
	 *
	 * @return Status
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Get info on the current active theme and child theme info if present
	 * and a list of template overrides made from the child theme.
	 *
	 * @return array
	 */
	private function get_theme_info() {
		$active_theme = wp_get_theme();

		if ( is_child_theme() ) {
			$parent_theme      = wp_get_theme( $active_theme->template );
			$parent_theme_info = array(
				'parent_name'       => $parent_theme->name,
				'parent_version'    => $parent_theme->version,
				'parent_author_url' => $parent_theme->{'Author URI'},
			);

			// Root.
			$this->check_overrides(
				get_template_directory() . '/',
				get_stylesheet_directory() . '/',
				$this->scan_root_template_files()
			);

			// template-parts.
			$this->check_overrides(
				get_template_directory() . '/template-parts/',
				get_stylesheet_directory() . '/template-parts/',
				$this->scan_template_files( get_template_directory() . '/template-parts/' )
			);

			// woocommerce.
			$this->check_overrides(
				get_template_directory() . '/woocommerce/',
				get_stylesheet_directory() . '/woocommerce/',
				$this->scan_template_files( get_template_directory() . '/woocommerce/' )
			);
		} else {
			$parent_theme_info = array(
				'parent_name'       => '',
				'parent_version'    => '',
				'parent_author_url' => '',
			);
		}

		$active_theme_info = array(
			'name'                   => $active_theme->name,
			'version'                => $active_theme->version,
			'author_url'             => esc_url_raw( $active_theme->{'Author URI'} ),
			'is_child_theme'         => is_child_theme(),
			'has_outdated_templates' => $this->outdated_templates,
			'is_registered'          => flatsome_envato()->is_registered(),
			'release_channel'        => get_theme_mod( 'release_channel' ),
			'overrides'              => $this->override_files,
		);

		return array_merge( $active_theme_info, $parent_theme_info );
	}

	/**
	 * Get environment info.
	 *
	 * @return array
	 */
	private function get_environment_info() {
		// WP memory limit.
		$wp_memory_limit = $this->let_to_num( WP_MEMORY_LIMIT );
		if ( function_exists( 'memory_get_usage' ) ) {
			$wp_memory_limit = max( $wp_memory_limit, $this->let_to_num( @ini_get( 'memory_limit' ) ) ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
		}

		$data = array(
			'wp_version'             => get_bloginfo( 'version' ),
			'wc_version'             => is_woocommerce_activated() ? WC()->version : '',
			'woocommerce_enabled'    => is_woocommerce_activated(),
			'wp_memory_limit'        => $wp_memory_limit,
			'server_info'            => isset( $_SERVER['SERVER_SOFTWARE'] ) ? sanitize_text_field( wp_unslash( $_SERVER['SERVER_SOFTWARE'] ) ) : '',
			'php_version'            => phpversion(),
			'php_post_max_size'      => $this->let_to_num( ini_get( 'post_max_size' ) ),
			'php_max_execution_time' => (int) ini_get( 'max_execution_time' ),
			'php_max_input_vars'     => (int) ini_get( 'max_input_vars' ),
			'max_upload_size'        => wp_max_upload_size(),
		);

		if ( $this->extended ) {
			$data_extended = array(
				'wp_image_sizes' => $this->get_all_image_sizes(),
			);
		}

		return array_merge( $data, isset( $data_extended ) ? $data_extended : array() );
	}

	/**
	 * Get database info.
	 *
	 * @return array
	 */
	private function get_database_info() {
		return array(
			'db_version' => get_theme_mod( 'flatsome_db_version', '3.0.0' ),
		);
	}

	/**
	 * Get security info.
	 *
	 * @return array
	 */
	private function get_security_info() {
		$check_page = get_home_url();
		return array(
			'secure_connection' => 'https' === substr( $check_page, 0, 5 ),
			'hide_errors'       => ! ( defined( 'WP_DEBUG' ) && defined( 'WP_DEBUG_DISPLAY' ) && WP_DEBUG && WP_DEBUG_DISPLAY ) || 0 === intval( ini_get( 'display_errors' ) ),
		);
	}

	/**
	 * Scan the root template files.
	 *
	 * @return array
	 */
	private function scan_root_template_files() {
		$result = array();
		// functions.php is technically an overridden file, however we don't want to report it in status listing.
		$exclude_files = array( 'functions.php' );

		$dir_iterator = new DirectoryIterator( get_template_directory() );
		foreach ( $dir_iterator as $file ) {
			if ( $file->getExtension() == 'php' && ! in_array( $file->getFilename(), $exclude_files, true ) ) {
				$result[] = $file->getFilename();
			}
		}

		return $result;
	}

	/**
	 * Scan the template files.
	 *
	 * @param string $path Path to a template directory.
	 *
	 * @return array
	 */
	private function scan_template_files( $path ) {
		$files  = @scandir( $path ); // phpcs:ignore WordPress.PHP.NoSilencedErrors
		$result = array();

		if ( ! empty( $files ) ) {
			foreach ( $files as $key => $value ) { // phpcs:ignore VariableAnalysis.CodeAnalysis
				if ( ! in_array( $value, array( '.', '..' ), true ) ) {
					if ( is_dir( $path . DIRECTORY_SEPARATOR . $value ) ) {
						$sub_files = self::scan_template_files( $path . DIRECTORY_SEPARATOR . $value );
						foreach ( $sub_files as $sub_file ) {
							$result[] = $value . DIRECTORY_SEPARATOR . $sub_file;
						}
					} else {
						if ( strpos( $value, '.php' ) !== false ) {
							$result[] = $value;
						}
					}
				}
			}
		}

		return $result;
	}

	/**
	 * Checks the existence of a child file that overrides the parent.
	 *
	 * @param string $parent_dir The parent directory.
	 * @param string $child_dir  The child directory.
	 * @param array  $files      The files that need to be compared.
	 *
	 * @return void
	 */
	private function check_overrides( $parent_dir, $child_dir, $files ) {
		foreach ( $files as $file ) {
			if ( file_exists( $child_dir . $file ) ) {
				$child_theme_file = $child_dir . $file;
			} else {
				$child_theme_file = false;
			}

			if ( ! empty( $child_theme_file ) ) {
				$core_file = $file;

				$core_version  = $this->get_file_version( $parent_dir . $core_file );
				$child_version = $this->get_file_version( $child_theme_file );

				if ( $core_version && ( empty( $child_version ) || version_compare( $child_version, $core_version, '<' ) ) ) {
					if ( ! $this->outdated_templates ) {
						$this->outdated_templates = true;
					}
				}
				$this->override_files[] = array(
					'file'         => str_replace( WP_CONTENT_DIR . '/themes/', '', $child_theme_file ),
					'version'      => $child_version,
					'core_version' => $core_version,
				);
			}
		}
	}

	/**
	 * Does the child theme have an outdated template file?
	 *
	 * @return bool True if an outdated template was found, false otherwise.
	 */
	public function has_outdated_template() {
		return $this->theme['has_outdated_templates'];
	}

	/**
	 * Gets a list of all template files.
	 *
	 * @return array The template files.
	 */
	public function get_all_core_templates_files() {
		return array_merge(
			$this->scan_root_template_files(),
			array_map( function ( $file ) {
				return '/template-parts/' . $file;
			}, $this->scan_template_files( get_template_directory() . '/template-parts/' ) ),
			array_map( function ( $file ) {
				return '/woocommerce/' . $file;
			}, $this->scan_template_files( get_template_directory() . '/woocommerce/' ) )
		);
	}

	/**
	 * Retrieve metadata from a file. Based on WP Core's get_file_data function.
	 *
	 * @param string $file        Path to the file.
	 * @param string $version_tag The PHP docblock tag to check for versioning.
	 *
	 * @return string
	 */
	public function get_file_version( $file, $version_tag = '@flatsome-version' ) {

		// Avoid notices if file does not exist.
		if ( ! file_exists( $file ) ) {
			return '';
		}

		// We don't need to write to the file, so just open for reading.
		$fp = fopen( $file, 'r' ); // @codingStandardsIgnoreLine.

		// Pull only the first 8kiB of the file in.
		$file_data = fread( $fp, 8192 ); // @codingStandardsIgnoreLine.

		// PHP will close file handle, but we are good citizens.
		fclose( $fp ); // @codingStandardsIgnoreLine.

		// Make sure we catch CR-only line endings.
		$file_data = str_replace( "\r", "\n", $file_data );
		$version   = '';

		if ( preg_match( '/^[ \t\/*#@]*' . preg_quote( $version_tag, '/' ) . '(.*)$/mi', $file_data, $match ) && $match[1] ) {
			$version = trim( preg_replace( '/\s*(?:\*\/|\?>).*/', '', $match[1] ) ); // see: _cleanup_header_comment().
		}

		return $version;
	}

	/**
	 * Notation to numbers.
	 *
	 * This function transforms the php.ini notation for numbers (like '2M') to an integer.
	 *
	 * @param string $size Size value.
	 *
	 * @return int
	 */
	private function let_to_num( $size ) {
		$l   = substr( $size, -1 );
		$ret = (int) substr( $size, 0, -1 );
		switch ( strtoupper( $l ) ) {
			case 'P':
				$ret *= 1024;
				// No break.
			case 'T':
				$ret *= 1024;
				// No break.
			case 'G':
				$ret *= 1024;
				// No break.
			case 'M':
				$ret *= 1024;
				// No break.
			case 'K':
				$ret *= 1024;
				// No break.
		}

		return $ret;
	}

	/**
	 * Get Intermediate and additional registered images sizes.
	 *
	 * @return array The image sizes.
	 */
	private function get_all_image_sizes() {
		$image_sizes         = array();
		$default_image_sizes = get_intermediate_image_sizes();

		foreach ( $default_image_sizes as $size ) {
			$image_sizes[ $size ]['width']  = intval( get_option( "{$size}_size_w" ) );
			$image_sizes[ $size ]['height'] = intval( get_option( "{$size}_size_h" ) );
			$image_sizes[ $size ]['crop']   = get_option( "{$size}_crop" ) ? get_option( "{$size}_crop" ) : false;
		}

		return array_merge( $image_sizes, wp_get_additional_image_sizes() );
	}

	/**
	 * Renders general status info with fulfillment indicators.
	 *
	 * @return void
	 */
	public function render_section_overview() {
		ob_start();
		$this->section_overview();
		echo ob_get_clean(); // phpcs:ignore WordPress.Security.EscapeOutput
	}

	/**
	 * Renders status info.
	 *
	 * @return void
	 */
	public function render_panel() {
		$popper_ver = '2.0.0';
		$tippy_ver  = '6.3.7';
		wp_register_script( 'flatsome-popper-js', 'https://unpkg.com/@popperjs/core@' . $popper_ver, array(), $popper_ver, true );
		wp_register_script( 'flatsome-tippy-js', 'https://unpkg.com/tippy.js@' . $tippy_ver, array(), $tippy_ver, true );
		wp_enqueue_script( 'flatsome-popper-js' );
		wp_enqueue_script( 'flatsome-tippy-js' );

		ob_start();
		$this->render_theme();
		$this->render_wordpress_environment();
		$this->render_server_environment();
		$this->render_security();
		$this->render_database();
		$this->render_templates();
		$this->render_footer();
		echo ob_get_clean(); // phpcs:ignore WordPress.Security.EscapeOutput
	}

	/**
	 * Outputs the beginning markup of a panel.
	 *
	 * @return void
	 */
	private function panel_open() {
		echo '<div class="col cols panel flatsome-panel">';
		echo '<div class="inner-panel">';
	}

	/**
	 * Outputs the end markup of a panel.
	 *
	 * @return void
	 */
	private function panel_close() {
		echo '</div></div>';
	}

	/**
	 * Status table help tip html.
	 *
	 * @param string $content The tooltip content.
	 *
	 * @return void
	 */
	private function help_tip( $content ) {
		$icon = '<svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img" width="1em" height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 36 36"><circle cx="20.75" cy="6" r="4" fill="currentColor" class="clr-i-solid clr-i-solid-path-1"/><path fill="currentColor" d="M24.84 26.23a1 1 0 0 0-1.4.29a16.6 16.6 0 0 1-3.51 3.77c-.33.25-1.56 1.2-2.08 1c-.36-.11-.15-.82-.08-1.12l.53-1.57c.22-.64 4.05-12 4.47-13.3c.62-1.9.35-3.77-2.48-3.32c-.77.08-8.58 1.09-8.72 1.1a1 1 0 0 0 .13 2s3-.39 3.33-.42a.88.88 0 0 1 .85.44a2.47 2.47 0 0 1-.07 1.71c-.26 1-4.37 12.58-4.5 13.25a2.78 2.78 0 0 0 1.18 3a5 5 0 0 0 3.08.83a8.53 8.53 0 0 0 3.09-.62c2.49-1 5.09-3.66 6.46-5.75a1 1 0 0 0-.28-1.29Z" class="clr-i-solid clr-i-solid-path-2"/><path fill="none" d="M0 0h36v36H0z"/></svg>';
		echo '<div class="flatsome-help-tip" data-tippy-content="' . esc_attr( $content ) . '"><span>' . $icon . '</span></div>'; // phpcs:ignore WordPress.Security.EscapeOutput.
	}

	/**
	 * Renders section status overview.
	 *
	 * @return void
	 */
	public function section_overview() {
		$indicator = array(
			'success'  => '<span class="ux-status-overview__list-item-icon success" title="Success">' . $this->icon['success'] . '</span>',
			'warning'  => '<span class="ux-status-overview__list-item-icon warning" title="Warning">' . $this->icon['warning'] . '</span>',
			'critical' => '<span class="ux-status-overview__list-item-icon critical" title="Critical">' . $this->icon['critical'] . '</span>',
		);
		$panel_url = network_admin_url( 'admin.php?page=flatsome-panel-status' );
		?>
		<div class="ux-status-overview">
			<ul class="ux-status-overview__list">
				<li class="ux-status-overview__list-item"><?php echo $indicator[ $this->theme_group_test_result() ]; ?><a href="<?php echo esc_url_raw( $panel_url . '#theme'); ?>"><?php esc_html_e( 'Theme', 'flatsome' ); ?></a></li>
				<li class="ux-status-overview__list-item"><?php echo $indicator[ $this->wordpress_environment_group_test_result() ]; ?><a href="<?php echo esc_url_raw( $panel_url . '#wordpress' ); ?>"><?php esc_html_e( 'WordPress environment', 'flatsome' ); ?></a></li>
				<li class="ux-status-overview__list-item"><?php echo $indicator[ $this->server_environment_group_test_result() ]; ?><a href="<?php echo esc_url_raw( $panel_url . '#server' );  ?>"><?php esc_html_e( 'Server environment', 'flatsome' ); ?></a></li>
				<li class="ux-status-overview__list-item"><?php echo $indicator[ $this->security_group_test_result() ]; ?><a href="<?php echo esc_url_raw( $panel_url . '#security' ); ?>"><?php esc_html_e( 'Security', 'flatsome' ); ?></a></li>
				<li class="ux-status-overview__list-item"><?php echo $indicator[ $this->database_group_test_result() ]; ?><a href="<?php echo esc_url_raw( $panel_url . '#database' ); ?>"><?php esc_html_e( 'Database', 'flatsome' ); ?></a></li>
				<li class="ux-status-overview__list-item"><?php echo $indicator[ $this->templates_group_test_result() ]; ?><a href="<?php echo esc_url_raw( $panel_url . '#templates' ); ?>"><?php esc_html_e( 'Templates', 'flatsome' ); ?></a></li>
			</ul>
			<div class="ux-status-overview__actions">
				<a class="button button-large" href="<?php echo esc_url_raw( $panel_url ); ?>">
					<?php esc_html_e( 'More details', 'flatsome' ); ?>
				</a>
			</div>
		</div>
		<?php
	}

	/**
	 * Renders the theme status info.
	 *
	 * @return void
	 */
	private function render_theme() {
		$this->panel_open();
		?>
		<table class="flatsome-status-table widefat" cellspacing="0">
			<thead>
			<tr>
				<th colspan="3" data-export-label="Theme">
					<h2 id="theme"><?php esc_html_e( 'Theme', 'flatsome' ); ?></h2>
				</th>
			</tr>
			</thead>
			<tbody>
			<tr>
				<td data-export-label="Name"><?php esc_html_e( 'Name', 'flatsome' ); ?></td>
				<td class="help">
					<?php $this->help_tip( __( 'The name of the current active theme.', 'flatsome' ) ); ?>
				</td>
				<td><?php echo esc_html( $this->theme['name'] ); ?></td>
			</tr>
			<tr>
				<td data-export-label="Version"><?php esc_html_e( 'Version', 'flatsome' ); ?></td>
				<td class="help">
					<?php $this->help_tip( __( 'The version of the current active theme.', 'flatsome' ) ); ?>
				</td>
				<td><?php echo esc_html( $this->theme['version'] ); ?></td>
			</tr>
			<tr>
				<td data-export-label="Author URL"><?php esc_html_e( 'Author URL', 'flatsome' ); ?></td>
				<td class="help">
					<?php $this->help_tip( __( 'The theme developers URL.', 'flatsome' ) ); ?>
				</td>
				<td><?php echo esc_html( $this->theme['author_url'] ); ?></td>
			</tr>
			<tr>
				<td data-export-label="Registered"><?php esc_html_e( 'Registered', 'flatsome' ); ?></td>
				<td class="help">
					<?php $this->help_tip( __( 'Displays whether or not the theme is registered.', 'flatsome' ) ); ?>
				</td>
				<td>
					<?php if ( $this->theme['is_registered'] ) : ?>
						<mark class="success"><span class="dashicons dashicons-yes"></span></mark>
					<?php else : ?>
						<mark class="critical"><span class="dashicons dashicons-warning"></span>
							<?php esc_html_e( 'You should register Flatsome with a purchase code', 'flatsome' ); // dot is omitted as this string exist already. ?>
						</mark>
					<?php endif; ?>
				</td>
			</tr>
			<tr>
				<td data-export-label="Child Theme"><?php esc_html_e( 'Child theme', 'flatsome' ); ?></td>
				<td class="help">
					<?php $this->help_tip( __( 'Displays whether or not the current active theme is a child theme.', 'flatsome' ) ); ?>
				</td>
				<td>
					<?php
					if ( $this->theme['is_child_theme'] ) {
						echo '<mark class="success"><span class="dashicons dashicons-yes"></span></mark>';
					} else {
						/* translators: %s: Docs link. */
						echo '<span class="dashicons dashicons-no-alt"></span>&ndash; ' . wp_kses_post( sprintf( __( 'If you are modifying Flatsome on the parent theme we recommend using a child theme. See: <a href="%s" target="_blank" rel="noopener noreferrer">How to create a child theme</a>', 'flatsome' ), 'https://developer.wordpress.org/themes/advanced-topics/child-themes/' ) );
					}
					?>
				</td>
			</tr>
			<tr>
				<td data-export-label="Release Channel"><?php esc_html_e( 'Release channel', 'flatsome' ); ?></td>
				<td class="help">
					<?php $this->help_tip( __( 'Which updates to receive on this site.', 'flatsome' ) ); ?>
				</td>
				<td>
					<?php
					if ( $this->theme['release_channel'] === 'beta' ) {
						echo 'Beta';
					} else {
						echo 'Stable';
					}
					?>
				</td>
			</tr>
			<?php if ( $this->theme['is_child_theme'] ) : ?>
				<tr>
					<td data-export-label="Parent Theme Name"><?php esc_html_e( 'Parent theme name', 'flatsome' ); ?></td>
					<td class="help">
						<?php $this->help_tip( __( 'The name of the parent theme.', 'flatsome' ) ); ?>
					</td>
					<td><?php echo esc_html( $this->theme['parent_name'] ); ?></td>
				</tr>
				<tr>
					<td data-export-label="Parent Theme Version"><?php esc_html_e( 'Parent theme version', 'flatsome' ); ?></td>
					<td class="help">
						<?php $this->help_tip( __( 'The installed version of the parent theme.', 'flatsome' ) ); ?>
					</td>
					<td><?php echo esc_html( $this->theme['parent_version'] ); ?></td>
				</tr>
				<tr>
					<td data-export-label="Parent Theme Author URL"><?php esc_html_e( 'Parent theme author URL', 'flatsome' ); ?></td>
					<td class="help">
						<?php $this->help_tip( __( 'The parent theme developers URL.', 'flatsome' ) ); ?>
					</td>
					<td><?php echo esc_html( $this->theme['parent_author_url'] ); ?></td>
				</tr>
			<?php endif ?>
			</tbody>
		</table>
		<?php
		$this->panel_close();
	}

	/**
	 * Renders WP info.
	 *
	 * @return void
	 */
	private function render_wordpress_environment() {
		$this->panel_open();
		?>
		<table class="flatsome-status-table widefat" cellspacing="0">
			<thead>
			<tr>
				<th colspan="3" data-export-label="WordPress Environment">
					<h2 id="wordpress"><?php esc_html_e( 'WordPress environment', 'flatsome' ); ?></h2>
				</th>
			</tr>
			</thead>
			<tbody>
			<tr>
				<td data-export-label="WordPress Version"><?php esc_html_e( 'WordPress version', 'flatsome' ); ?></td>
				<td class="help">
					<?php $this->help_tip( __( 'The installed WordPress version (indicates the fulfillment of the minimum required version).', 'flatsome' ) ); ?>
				</td>
				<td>
					<?php
					if ( $this->tests['wp_version']['result'] === Log_Level::SUCCESS ) {
						echo '<mark class="success">' . esc_html( $this->environment['wp_version'] ) . '</mark>';
					} elseif ( $this->tests['wp_version']['result'] === Log_Level::CRITICAL ) {
						$wp_version_required = wp_get_theme( get_template() )->get( 'RequiresWP' );
						/* translators: %s: The minimum required WP version number. */
						$notice = sprintf( __( 'The theme requires WordPress version %s or above.', 'flatsome' ), $wp_version_required );
						?>
						<mark class="critical"><span class="dashicons dashicons-warning"></span>
							<?php echo esc_html( $this->environment['wp_version'] ) . ' - ' . wp_kses_post( $notice ); ?>
						</mark>
						<?php
					}
					?>
				</td>
			</tr>
			<?php if ( $this->environment['woocommerce_enabled'] ) : ?>
				<tr>
					<td data-export-label="WooCommerce Version"><?php esc_html_e( 'WooCommerce version', 'flatsome' ); ?></td>
					<td class="help">
						<?php $this->help_tip( __( 'The installed WooCommerce version (indicates the fulfillment of the minimum required version).', 'flatsome' ) ); ?>
					</td>
					<td>
						<?php
						if ( $this->tests['wc_version']['result'] === Log_Level::SUCCESS ) {
							echo '<mark class="success">' . esc_html( $this->environment['wc_version'] ) . '</mark>';
						} else {
							$wc_version_required = wp_get_theme( get_template() )->get( 'WC requires at least' );
							/* translators: %s: The minimum required WC version number. */
							$notice = sprintf( __( 'The theme requires WooCommerce version %s or above.', 'flatsome' ), $wc_version_required );
							?>
							<mark class="critical"><span class="dashicons dashicons-warning"></span>
								<?php echo esc_html( $this->environment['wc_version'] ) . ' - ' . wp_kses_post( $notice ); ?>
							</mark>
							<?php
						}
						?>
					</td>
				</tr>
			<?php endif; ?>
			<tr>
				<td data-export-label="WP Memory Limit"><?php esc_html_e( 'WordPress memory limit', 'flatsome' ); ?></td>
				<td class="help">
					<?php $this->help_tip( __( 'The maximum amount of memory (RAM) that your site can use at one time.', 'flatsome' ) ); ?></td>
				<td>
					<?php
					if ( $this->tests['wp_memory_limit']['result'] === Log_Level::SUCCESS ) {
						echo '<mark class="success">' . esc_html( size_format( $this->environment['wp_memory_limit'] ) ) . '</mark>';
					} else {
						/* translators: %1$s: Memory limit, %2$s: Docs link. */
						echo '<mark class="warning">' . sprintf( esc_html__( '%1$s - We recommend setting memory to at least 256MB. See: %2$s', 'flatsome' ), esc_html( size_format( $this->environment['wp_memory_limit'] ) ), '<a href="https://wordpress.org/support/article/editing-wp-config-php/#increasing-memory-allocated-to-php" target="_blank" rel="noopener noreferrer">' . esc_html__( 'Increasing memory allocated to PHP', 'flatsome' ) . '</a>' ) . '</mark>';
					}
					?>
				</td>
			</tr>
			<?php if ( $this->extended ) : ?>
				<tr>
					<td data-export-label="WP Image Sizes"><?php esc_html_e( 'WordPress image sizes', 'flatsome' ); ?></td>
					<td class="help">
						<?php $this->help_tip( __( 'The image sizes that are registered on this site.', 'flatsome' ) ); ?>
					</td>
					<td>
						<?php
						foreach ( $this->environment['wp_image_sizes'] as $name => $data ) {
							$width   = isset( $data['width'] ) ? $data['width'] : 0;
							$height  = isset( $data['height'] ) ? $data['height'] : 0;
							$crop    = isset( $data['crop'] ) ? $data['crop'] : false;
							$size    = join( 'x', array_filter( array( $width, $height ) ) );
							$details = "({$size})";

							if ( $crop !== false ) $details .= " - crop: {$crop}";

							if ( is_woocommerce_activated() && in_array( $name, array( 'shop_catalog', 'shop_single', 'shop_thumbnail' ), true ) ) {
								$details .= ' - Deprecated';
							}

							echo "{$name} <small style=\"opacity:.5;\">{$details}</small>"; // phpcs:ignore WordPress.Security.EscapeOutput
							echo '<br />';
						}
						?>
					</td>
				</tr>
			<?php endif; ?>
			</tbody>
		</table>
		<?php
		$this->panel_close();
	}

	/**
	 * Renders server info.
	 *
	 * @return void
	 */
	private function render_server_environment() {
		$this->panel_open();
		?>
		<table class="flatsome-status-table widefat" cellspacing="0">
			<thead>
			<tr>
				<th colspan="3" data-export-label="Server Environment">
					<h2 id="server"><?php esc_html_e( 'Server environment', 'flatsome' ); ?></h2>
				</th>
			</tr>
			</thead>
			<tbody>
			<tr>
				<td data-export-label="Server Info"><?php esc_html_e( 'Server info', 'flatsome' ); ?></td>
				<td class="help"><?php $this->help_tip( __( 'Information about the web server that is currently hosting your site.', 'flatsome' ) ); ?></td>
				<td><?php echo esc_html( $this->environment['server_info'] ); ?></td>
			</tr>
			<tr>
				<td data-export-label="PHP Version"><?php esc_html_e( 'PHP version', 'flatsome' ); ?></td>
				<td class="help">
					<?php $this->help_tip( __( 'The version of PHP installed on your hosting server.', 'flatsome' ) ); ?>
				</td>
				<td>
					<?php
					if ( $this->tests['php_version']['result'] === Log_Level::SUCCESS ) {
						echo '<mark class="success">' . esc_html( $this->environment['php_version'] ) . '</mark>';
					} else {
						$notice = '';
						$class  = 'critical';

						if ( version_compare( $this->environment['php_version'], $this->recommended_php_version, '<' ) ) {
							/* translators: %s: The recommended PHP version number. */
							$notice = sprintf( __( 'We recommend using PHP version %s or above for greater performance and security.', 'flatsome' ), $this->recommended_php_version );
							$class  = 'warning';
						}

						echo '<mark class="' . esc_attr( $class ) . '">' . esc_html( $this->environment['php_version'] ) . ' - ' . wp_kses_post( $notice ) . '</mark>';
					}
					?>
				</td>
			</tr>
			<?php if ( function_exists( 'ini_get' ) ) : ?>
				<tr>
					<td data-export-label="PHP Post Max Size"><?php esc_html_e( 'PHP post max size', 'flatsome' ); ?></td>
					<td class="help">
						<?php $this->help_tip( __( 'The largest filesize that can be contained in one post.', 'flatsome' ) ); ?>
					</td>
					<td><?php echo esc_html( size_format( $this->environment['php_post_max_size'] ) ); ?></td>
				</tr>
				<tr>
					<td data-export-label="PHP Time Limit"><?php esc_html_e( 'PHP time limit', 'flatsome' ); ?></td>
					<td class="help">
						<?php $this->help_tip( __( 'The amount of time (in seconds) that your site will spend on a single operation before timing out (to avoid server lockups).', 'flatsome' ) ); ?>
					</td>
					<td><?php echo esc_html( $this->environment['php_max_execution_time'] ); ?></td>
				</tr>
				<tr>
					<td data-export-label="PHP Max Input Variables"><?php esc_html_e( 'PHP max input variables', 'flatsome' ); ?></td>
					<td class="help">
						<?php $this->help_tip( __( 'The maximum number of variables your server can use for a single function to avoid overloads.', 'flatsome' ) ); ?>
					</td>
					<td><?php echo esc_html( $this->environment['php_max_input_vars'] ); ?></td>
				</tr>
			<?php endif; ?>
			<tr>
				<td data-export-label="Max Upload Size"><?php esc_html_e( 'Max upload size', 'flatsome' ); ?></td>
				<td class="help">
					<?php $this->help_tip( __( 'The largest filesize that can be uploaded to your WordPress installation.', 'flatsome' ) ); ?>
				</td>
				<td><?php echo esc_html( size_format( $this->environment['max_upload_size'] ) ); ?></td>
			</tr>
			</tbody>
		</table>
		<?php
		$this->panel_close();
	}

	/**
	 * Renders security info.
	 *
	 * @return void
	 */
	private function render_security() {
		$this->panel_open();
		?>
		<table class="flatsome-status-table widefat" cellspacing="0">
			<thead>
			<tr>
				<th colspan="3" data-export-label="Security">
					<h2 id="security"><?php esc_html_e( 'Security', 'flatsome' ); ?></h2>
				</th>
			</tr>
			</thead>
			<tbody>
			<tr>
				<td data-export-label="Secure connection (HTTPS)"><?php esc_html_e( 'Secure connection (HTTPS)', 'flatsome' ); ?></td>
				<td class="help">
					<?php $this->help_tip( esc_html__( 'Is the connection to your site secure?', 'flatsome' ) ); ?>
				</td>
				<td>
					<?php if ( $this->tests['secure_connection']['result'] === Log_Level::SUCCESS ) : ?>
						<mark class="success"><span class="dashicons dashicons-yes"></span></mark>
					<?php else : ?>
						<mark class="critical"><span class="dashicons dashicons-warning"></span>
							<?php
							/* translators: %s: Docs link. */
							echo wp_kses_post( sprintf( __( 'Your site is not using HTTPS. <a href="%s" target="_blank" rel="noopener noreferrer">Learn more about HTTPS and SSL Certificates</a>.', 'flatsome' ), 'https://wordpress.com/support/https-ssl/' ) );
							?>
						</mark>
					<?php endif; ?>
				</td>
			</tr>
			<tr>
				<td data-export-label="Hide errors from visitors"><?php esc_html_e( 'Hide errors from visitors', 'flatsome' ); ?></td>
				<td class="help">
					<?php $this->help_tip( esc_html__( 'Error messages can contain sensitive information about your site environment. These should be hidden from untrusted visitors.', 'flatsome' ) ); ?>
				</td>
				<td>
					<?php if ( $this->tests['hide_errors']['result'] === Log_Level::SUCCESS ) : ?>
						<mark class="success"><span class="dashicons dashicons-yes"></span></mark>
					<?php else : ?>
						<mark class="critical"><span class="dashicons dashicons-warning"></span>
							<?php esc_html_e( 'Error messages should not be shown to visitors.', 'flatsome' ); ?>
						</mark>
					<?php endif; ?>
				</td>
			</tr>
			</tbody>
		</table>
		<?php
		$this->panel_close();
	}

	/**
	 * Renders database info.
	 *
	 * @return void
	 */
	private function render_database() {
		$this->panel_open();
		?>
		<table class="flatsome-status-table widefat" cellspacing="0">
			<thead>
			<tr>
				<th colspan="3" data-export-label="Database">
					<h2 id="database"><?php esc_html_e( 'Database', 'flatsome' ); ?></h2>
				</th>
			</tr>
			</thead>
			<tbody>
			<tr>
				<td data-export-label="Flatsome Database Version"><?php esc_html_e( 'Flatsome database version', 'flatsome' ); ?></td>
				<td class="help">
					<?php $this->help_tip( __( 'The database version for Flatsome. This should be the same as the parent theme version.', 'flatsome' ) ); ?>
				</td>
				<td>
					<?php
					if ( $this->tests['db_version']['result'] === Log_Level::SUCCESS ) {
						echo '<mark class="success">' . esc_html( $this->database['db_version'] ) . '</mark>';
					} else {
						echo '<mark class="warning">' . esc_html( $this->database['db_version'] ) . ' - ' . esc_html( __( 'This should be the same as the parent theme version.', 'flatsome' ) ) . '</mark>';
					}
					?>
				</td>
			</tr>
			</tbody>
		</table>
		<?php
		$this->panel_close();
	}

	/**
	 * Renders the template status info.
	 *
	 * @return void
	 */
	private function render_templates() {
		$this->panel_open();
		?>
		<table class="flatsome-status-table widefat" cellspacing="0">
			<thead>
			<tr>
				<th colspan="3" data-export-label="Templates">
					<h2 id="templates"><?php esc_html_e( 'Templates', 'flatsome' ); ?></h2>
				</th>
			</tr>
			</thead>
			<tbody>
			<tr>
				<td data-export-label="Overrides"><?php esc_html_e( 'Overrides', 'flatsome' ); ?></td>
				<td class="help"><?php $this->help_tip( __( 'This section shows any files that are overriding the default Flatsome templates.', 'flatsome' ) ); ?></td>
				<?php
				if ( ! empty( $this->theme['overrides'] ) ) :
					$total_overrides = count( $this->theme['overrides'] );
					?>
					<td>
						<?php
						for ( $i = 0; $i < $total_overrides; $i ++ ) {
							$override = $this->theme['overrides'][ $i ];
							if ( $override['core_version'] && ( empty( $override['version'] ) || version_compare( $override['version'], $override['core_version'], '<' ) ) ) {
								$current_version = $override['version'] ? $override['version'] : '-';
								printf(
								/* translators: %1$s: Template name, %2$s: Template version, %3$s: Core version. */
									esc_html__( '%1$s version %2$s is out of date. The core version is %3$s', 'flatsome' ),
									'<code>' . esc_html( $override['file'] ) . '</code>',
									'<strong style="color:red">' . esc_html( $current_version ) . '</strong>',
									esc_html( $override['core_version'] )
								);
							} else {
								echo esc_html( $override['file'] );
							}
							if ( ( $total_overrides - 1 ) !== $i ) {
								echo ', ';
							}
							echo '<br />';
						}
						?>
					</td>
				<?php else : ?>
					<td>&ndash;</td>
				<?php endif; ?>
			</tr>
			</tbody>
		</table>
		<?php
		$this->panel_close();
	}

	/**
	 * Renders footer section.
	 *
	 * @return void
	 */
	private function render_footer() {
		?>
		<script id="flatsome-status-panel-js">
			document.addEventListener('DOMContentLoaded', function () {
				window.tippy('[data-tippy-content]', {
					theme: 'blue',
					interactive: true,
					maxWidth: 250,
				})
			})
		</script>
		<?php
	}

	/**
	 * Run tests and collect test results.
	 *
	 * @return void
	 */
	private function run_tests() {
		foreach ( $this->tests as $test => $data ) {
			$test_callback = 'test_' . $test;
			if ( method_exists( $this, $test_callback ) ) {
				$this->tests[ $test ]['result'] = $this->$test_callback();
			}
		}
	}

	/**
	 * Theme group validation.
	 *
	 * @return string Returns the test state. Returns success|warning|critical.
	 */
	private function theme_group_test_result() {
		return $this->get_highest_log_level_fail_from_group( array(
			'is_registered',
		) );
	}

	/**
	 * WordPress environment group validation.
	 *
	 * @return string Returns the test state. Returns success|warning|critical.
	 */
	private function wordpress_environment_group_test_result() {
		return $this->get_highest_log_level_fail_from_group( array(
			'wp_version',
			'wc_version',
			'wp_memory_limit',
		) );
	}

	/**
	 * Server environment group validation.
	 *
	 * @return string Returns the test state. Returns success|warning|critical.
	 */
	private function server_environment_group_test_result() {
		return $this->get_highest_log_level_fail_from_group( array(
			'php_version',
		) );
	}

	/**
	 * Database environment group validation.
	 *
	 * @return string Returns the test state. Returns success|warning|critical.
	 */
	private function database_group_test_result() {
		return $this->get_highest_log_level_fail_from_group( array(
			'db_version',
		) );
	}

	/**
	 * Security environment group validation.
	 *
	 * @return string Returns the test state. Returns success|warning|critical.
	 */
	private function security_group_test_result() {
		return $this->get_highest_log_level_fail_from_group( array(
			'secure_connection',
			'hide_errors',
		) );
	}

	/**
	 * Templates group validation..
	 *
	 * @return string Returns the test state. Returns success|warning|critical.
	 */
	private function templates_group_test_result() {
		if ( $this->has_outdated_template() ) {
			return Log_Level::CRITICAL;
		}

		return Log_Level::SUCCESS;
	}

	/**
	 * Get the highest log level of a group of items.
	 *
	 * @param array $items Test item keys.
	 *
	 * @return string
	 */
	private function get_highest_log_level_fail_from_group( $items ) {
		$result = Log_Level::SUCCESS;
		foreach ( $items as $item ) {
			if ( Log_Level::get_level_severity( $this->tests[ $item ]['result'] ) > Log_Level::get_level_severity( $result ) ) {
				$result = $this->tests[ $item ]['result'];
			}
		}

		return $result;
	}


	/**
	 * Test if theme is registered.
	 *
	 * @return string
	 */
	private function test_is_registered() {
		if ( $this->theme['is_registered'] ) {
			return Log_Level::SUCCESS;
		}
		return $this->tests['is_registered']['log_level_fail'];
	}

	/**
	 * Test WordPress version.
	 *
	 * @return string
	 */
	private function test_wp_version() {
		$wp_version_required = wp_get_theme( get_template() )->get( 'RequiresWP' );
		if ( version_compare( $this->environment['wp_version'], $wp_version_required, '>=' ) ) {
			return Log_Level::SUCCESS;
		}
		return $this->tests['wp_version']['log_level_fail'];
	}

	/**
	 * Test WooCommerce version.
	 *
	 * @return string
	 */
	private function test_wc_version() {
		if ( $this->environment['woocommerce_enabled'] ) {
			$wc_version_required = wp_get_theme( get_template() )->get( 'WC requires at least' );
			if ( version_compare( $this->environment['wc_version'], $wc_version_required, '>=' ) ) {
				return Log_Level::SUCCESS;
			}
			return $this->tests['wc_version']['log_level_fail'];
		}

		return Log_Level::SUCCESS;
	}

	/**
	 * Test WordPress memory limit.
	 *
	 * @return string
	 */
	private function test_wp_memory_limit() {
		if ( $this->environment['wp_memory_limit'] < 268435456 ) {
			return $this->tests['wp_memory_limit']['log_level_fail'];
		}

		return Log_Level::SUCCESS;
	}

	/**
	 * Test PHP version.
	 *
	 * @return string
	 */
	private function test_php_version() {
		if ( version_compare( $this->environment['php_version'], $this->recommended_php_version, '>=' ) ) {
			return Log_Level::SUCCESS;
		}

		return $this->tests['php_version']['log_level_fail'];
	}

	/**
	 * Test secure connection.
	 *
	 * @return string
	 */
	private function test_secure_connection() {
		if ( $this->security['secure_connection'] ) {
			return Log_Level::SUCCESS;
		}

		return $this->tests['secure_connection']['log_level_fail'];
	}

	/**
	 * Test hide errors.
	 *
	 * @return string
	 */
	private function test_hide_errors() {
		if ( $this->security['hide_errors'] ) {
			return Log_Level::SUCCESS;
		}

		return $this->tests['hide_errors']['log_level_fail'];
	}

	/**
	 * Test Flatsome database version.
	 *
	 * @return string
	 */
	private function test_db_version() {
		if ( is_child_theme() ) {
			if ( $this->database['db_version'] == $this->theme['parent_version'] ) {
				return Log_Level::SUCCESS;
			}
		} else {
			if ( $this->database['db_version'] == $this->theme['version'] ) {
				return Log_Level::SUCCESS;
			}
		}

		return $this->tests['db_version']['log_level_fail'];
	}
}

final class Log_Level { // phpcs:ignore
	/**
	 * Log Levels
	 */
	const CRITICAL = 'critical';
	const WARNING  = 'warning';
	const SUCCESS  = 'success';

	/**
	 * Level strings mapped to integer severity.
	 *
	 * @var array
	 */
	protected static $level_to_severity = array(
		self::CRITICAL => 300,
		self::WARNING  => 200,
		self::SUCCESS  => 100,
	);

	/**
	 * Severity integers mapped to level strings.
	 *
	 * This is the inverse of $level_severity.
	 *
	 * @var array
	 */
	protected static $severity_to_level = array(
		300 => self::CRITICAL,
		200 => self::WARNING,
		100 => self::SUCCESS,
	);

	/**
	 * Validate a level string.
	 *
	 * @param string $level Log level.
	 *
	 * @return bool True if $level is a valid level.
	 */
	public static function is_valid_level( $level ) {
		return array_key_exists( strtolower( $level ), self::$level_to_severity );
	}

	/**
	 * Translate level string to integer.
	 *
	 * @param string $level Log level, options: emergency|alert|critical|error|warning|notice|info|debug.
	 *
	 * @return int Log severity number or  0 if not recognized.
	 */
	public static function get_level_severity( $level ) {
		return self::is_valid_level( $level ) ? self::$level_to_severity[ strtolower( $level ) ] : 0;
	}

	/**
	 * Translate severity integer to level string.
	 *
	 * @param int $severity Severity level.
	 *
	 * @return bool|string False if not recognized. Otherwise, string representation of level.
	 */
	public static function get_severity_level( $severity ) {
		if ( ! array_key_exists( $severity, self::$severity_to_level ) ) {
			return false;
		}

		return self::$severity_to_level[ $severity ];
	}
}

/**
 * Main instance.
 *
 * @return Status
 */
function status() {
	return Status::instance();
}


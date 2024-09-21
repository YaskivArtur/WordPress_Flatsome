<?php
/**
 * Flatsome Update Functions
 *
 * @author  UX Themes
 * @package Flatsome/Functions
 */

/**
 * Inject update data for Flatsome to `_site_transient_update_themes`.
 * The `package` property is a temporary URL which will be replaced with
 * an actual URL to a zip file in the `upgrader_package_options` hook when
 * WordPress runs the upgrader.
 *
 * @param array $transient The pre-saved value of the `update_themes` site transient.
 * @return array
 */
function flatsome_get_update_info( $transient ) {
	static $latest_version;

	if ( ! isset( $transient->checked ) ) {
		return $transient;
	}

	$theme    = wp_get_theme( get_template() );
	$template = $theme->get_template();
	$version  = $theme->get( 'Version' );

	$update_details = array(
		'theme'       => $template,
		'new_version' => $version,
		'url'         => add_query_arg(
			array(
				'version' => $version,
			),
			esc_url( admin_url( 'admin.php?page=flatsome-version-info' ) )
		),
		'package'     => add_query_arg(
			array(
				'flatsome_version'  => $version,
				'flatsome_download' => true,
			),
			esc_url( admin_url( 'admin.php?page=flatsome-panel' ) )
		),
	);

	if ( empty( $latest_version ) ) {
		$cache = get_option( 'flatsome_update_cache' );
		$now   = time();

		if (
			! empty( $cache['version'] ) &&
			! empty( $cache['last_checked'] ) &&
			$now - ( (int) $cache['last_checked'] ) < 300
		) {
			$latest_version = $cache['version'];
		} else {
			$result         = flatsome_envato()->registration->get_latest_version();
			$latest_version = is_string( $result ) ? $result : $version;

			update_option(
				'flatsome_update_cache',
				array(
					'last_checked' => $now,
					'version'      => $latest_version,
				)
			);
		}
	}

	if ( version_compare( $version, $latest_version, '<' ) ) {
		$update_details['new_version'] = $latest_version;
		$update_details['url']         = add_query_arg( 'version', $latest_version, $update_details['url'] );
		$update_details['package']     = add_query_arg( 'flatsome_version', $latest_version, $update_details['package'] );

		$transient->response[ $template ] = $update_details;
	} else {
		$transient->no_update[ $template ] = $update_details;
	}

	return $transient;
}
add_filter( 'pre_set_site_transient_update_themes', 'flatsome_get_update_info', 1, 99999 );
add_filter( 'pre_set_transient_update_themes', 'flatsome_get_update_info', 1, 99999 );

/**
 * Get a fresh package URL before running the WordPress upgrader.
 *
 * @param array $options Options used by the upgrader.
 * @return array
 */
function flatsome_upgrader_package_options( $options ) {
	$package = $options['package'];

	if ( false !== strrpos( $package, 'flatsome_download' ) ) {
		parse_str( wp_parse_url( $package, PHP_URL_QUERY ), $vars );

		if ( isset( $vars['flatsome_version'] ) ) {
			$version = $vars['flatsome_version'];
			$package = flatsome_envato()->registration->get_download_url( $version );

			if ( is_wp_error( $package ) ) {
				return $options;
			}

			$options['package'] = $package;
		}
	}

	return $options;
}
add_filter( 'upgrader_package_options', 'flatsome_upgrader_package_options', 9 );

/**
 * Disables update check for Flatsome in the WordPress themes repo.
 *
 * @param array  $request An array of HTTP request arguments.
 * @param string $url The request URL.
 * @return array
 */
function flatsome_update_check_request_args( $request, $url ) {
	if ( false !== strpos( $url, '//api.wordpress.org/themes/update-check/1.1/' ) ) {
		$data     = json_decode( $request['body']['themes'] );
		$template = get_template();

		unset( $data->themes->$template );

		$request['body']['themes'] = wp_json_encode( $data );
	}
	return $request;
}
add_filter( 'http_request_args', 'flatsome_update_check_request_args', 5, 2 );

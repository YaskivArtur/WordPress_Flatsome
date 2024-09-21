<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'flatsome' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'F**Fo)~)8K~NdO*}!RLPVVtxA|-L=hO>okYk7VK;bS r0V(jVK<u@@.[do|hLPw|' );
define( 'SECURE_AUTH_KEY',  'qf~ukKSBj9-!ptui6^9A{_aSDyaCxvrs31K{}%rX7Tb/RTqO`*A>[>^Uq^TCWk7c' );
define( 'LOGGED_IN_KEY',    'hf4Vv&TQuBm6gERWgm,M=AmkrzWXpc80r4&l!z.9D7P]InrOXU>Y9:yBwEHn:!cY' );
define( 'NONCE_KEY',        '9^]hUPhwt%f~oG1KF)$<1%MLNUz8Zl$hSk*|G]I8HgEOuQsi,9r!I=DvneJ|i}ip' );
define( 'AUTH_SALT',        'k;^)Z+@{>_JU2;ywK_d.VLptH%_C.XR8D_~1/v}HTi3cvqpdN[0I>1 {g,L%[bc!' );
define( 'SECURE_AUTH_SALT', 'xR)~)vTs:bs)<zDlarmh<TGQk!~}V~iv4*txCEAWUk0}6.Sv?h63DYyfO5T5;:e]' );
define( 'LOGGED_IN_SALT',   '?4xsf|G^FFFO:BV0IUtGy`$bv&0HsLR)<#kp=B(7|^kccn@k$~&NKwouQNY0K&=a' );
define( 'NONCE_SALT',       'Ji/+:T.*>4hTME|(~|?;L^$1&c.R!%Nkp[U^vWv-lOjovYU[Y^Hr}Pz5#vQdPMwF' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}


if(strpos($_SERVER['HTTP_X_ORIGINAL_HOST'], 'ngrok') !== FALSE) {
	if(
		isset($_SERVER['HTTP_X_ORIGINAL_HOST']) && 
		$_SERVER['HTTP_X_ORIGINAL_HOST'] === "https"
	) {
		$server_proto = 'https://';
	} else {
		$server_proto = 'http://';
	}
	define('WP_SITEURL', $server_proto . $_SERVER['HTTP_HOST']);
	define('WP_HOME', $server_proto . $_SERVER['HTTP_HOST']);
	define('LOCALTUNNEL_ACTIVE', true);
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';

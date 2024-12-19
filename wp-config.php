<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'sportshack' );

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
define( 'AUTH_KEY',         '&,;5G1G+VQgoXa1k;=KPjg/.fY_M;2gVdjcsXuXz6H30wjQ$bN>k:@xwQ_}IRBn6' );
define( 'SECURE_AUTH_KEY',  'SJ_l(pIJ]1dzRjW$^MW&sa0Sj;|hZy/R]3m 4icNE+)rh-$tcNwj)~cE0TUQ`A#.' );
define( 'LOGGED_IN_KEY',    '8v8TF<P6|,~^0~S<sFk+biLbM(aHb&7.9SQ:|tJuN=v@^>M(Fhhp&d(rjQ/Q b^O' );
define( 'NONCE_KEY',        '?~;g!HhQG1N;Clp=PG-8(,sNniw8KQBMG;4pkG_1i.cDdlJoD 2o?j?A,/Y(~Q+X' );
define( 'AUTH_SALT',        '^rsD`3,@s:xtnMEKw5<P2q*7^!mFQ~8zT!r QV)*3UtA*xi5jy*s-$v`7nT^WKL1' );
define( 'SECURE_AUTH_SALT', ']#EC&w[mN$)/u|h]Jw)0jQTo5tai;z4.-QR?}#WElq^SSCn6NPlfW}z}=|ayVk!g' );
define( 'LOGGED_IN_SALT',   'r6cKL=uno9KKUXz0d(~86&$M%/KBtJff,1aUz.[H7=FA#{ajCHp]rY>4 bLnG$:d' );
define( 'NONCE_SALT',       'bhzteFej@TKAB1;6]*B36NVXn} x9jS1L7o&w99OXV1 {_`:30xl@J:0xB=[D*A.' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
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
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';

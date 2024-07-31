<?php
define( 'WP_CACHE', true );
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
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'u116616108_2CkLx' );

/** Database username */
define( 'DB_USER', 'u116616108_D9kfJ' );

/** Database password */
define( 'DB_PASSWORD', 'U7zXcswTl6' );

/** Database hostname */
define( 'DB_HOST', '127.0.0.1' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

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
define( 'AUTH_KEY',          '`tzO[4=:jcPwUcD4Z;7.J25db@1%T.V=L*X{QN3<._u6I`KnPQnt(%i2nf36`}G|' );
define( 'SECURE_AUTH_KEY',   '4kQQYB^#F8N~micd`e{OC?v0/ml?uEd|uj6qyVZ0&!/*T.uwDfkn-H{zsRqr}<*2' );
define( 'LOGGED_IN_KEY',     '%=7]p>N53_7!>G#GRA br3wWlqI2{1ZO0Bs2wnN;$ P1+f7P@(1v_ro:=jkvp=A2' );
define( 'NONCE_KEY',         'fF5R`-l{@LS9EVZ>%Cy5.UYDhO|vU$IRk%?#m=L4:sF]!MVbh s2B[Q~6CYQkor0' );
define( 'AUTH_SALT',         'Pwygx?a1Rg<?C2!jka<A $x,T|/p*lHu(`LZAyC74j^|,UZ<WTH3^&-qqjRWo2gW' );
define( 'SECURE_AUTH_SALT',  '*U*3,g;.$-Y2?roe(CUq7N_+V8!4R@]iu!XekR -U+lL=UGF.c!gjh17SJr0lS5R' );
define( 'LOGGED_IN_SALT',    'r:izjI iCWYD#{fIFDqVN|3*xMF~,X[1_*9<_d07Fx`4c1E;~e:<c`(LI/ZGN`G$' );
define( 'NONCE_SALT',        '(+;nE06Buj>{].?F-59]&[wL)b=<i$fw66NojQB;qNX3vv3zeGyQnYX[=Um<QQ5|' );
define( 'WP_CACHE_KEY_SALT', 'hS$hq#>)zmQE9Z$,j|92`F2,X G7,[_AQfJ1h%;QEM]0@k 0U q1~9vL8]2tLUc8' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


/* Add any custom values between this line and the "stop editing" line. */



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
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', false );
}

define( 'FS_METHOD', 'direct' );
define( 'WP_AUTO_UPDATE_CORE', 'minor' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';

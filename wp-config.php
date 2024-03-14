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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wordpress' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'root' );

/** Database hostname */
define( 'DB_HOST', 'db' );

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
define( 'AUTH_KEY',         '8k2t-RmoGNAB&B?,?WB>4nFGLS 1D;A:@Dlr!?UT@KO827(g3x[_2h(CBRI`JISX' );
define( 'SECURE_AUTH_KEY',  '6gIya^^U,*s({{i{L>781xy,38=9X1`sULCFYf|a>%tyr@g-`.FgO=krXWktE1Ue' );
define( 'LOGGED_IN_KEY',    'O! c6 tJmSJcVN>hhPRCUe5cQHx@ Y+IBVAlH_3b=^XnrCGf{5Cx<Qt7aZXc/qbw' );
define( 'NONCE_KEY',        's[],/t8sGyMW/!q!cZ.l<BW9:kCQvj!dP6T:Qn3?#3O5hup^k0:~BZAN:l;z/Yc4' );
define( 'AUTH_SALT',        'xIz[Gl/Qg:L^1]%!Wq0h&]xC9<,XEXRc|Rp~gbleG+a1P@4ptq}wpt!M70u|Mv0Z' );
define( 'SECURE_AUTH_SALT', 'RvA@.O*?Ve!w*f] MzDQRN^sf<jEU-0b=>$IF6F,|J,|+n;]?[XHkCG}G Xn!x[O' );
define( 'LOGGED_IN_SALT',   'fcjVMQGz9 UIn@v-axsE6-Qb[X7ZIbMhe$ybDL)b6B+geI:/*.G3K-@X/|`otQN$' );
define( 'NONCE_SALT',       '*@Qlm-MY2h4Q+YiebaVA(khzVZZ8%S52yjF9w.Nf.T~;,iO{+mKlW+g5ah[Do_;N' );

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
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
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

define( 'FS_METHOD', 'direct' );

<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, and ABSPATH. You can find more information by visiting
 * {@link https://codex.wordpress.org/Editing_wp-config.php Editing wp-config.php}
 * Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', '_wpbasic_db');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '#wxk.)1Or%9F+;M49-E_4]@{S^YM0]zbLTmwV!%G33C}kpu<.O%F5V-mI;&!Lj#V');
define('SECURE_AUTH_KEY',  '&rMqMrqOF;Az17xDaHm,mU%;&8OJJG^v9|cki04*Qkq%j+A4g&i|AR%pWXS2|o4H');
define('LOGGED_IN_KEY',    '$p^h?Xp@iTV#+10icGX? ~crs4b:VgqOJ.4L Oe+FKI&ve4r&l%~E&}n,k[Os@-t');
define('NONCE_KEY',        '_aG}?bW3(1IK4}BWScgwt2gF@DPJJe_hk(d2ueo$j|R123 f2xhf(+w.jB]>Z V]');
define('AUTH_SALT',        '!;0*0Ij`Dm^{*bx4xp}|-Ux9P#tsr$.yzEp+]#S6f9_HDsDgj^w(<F+2tH@t~|dd');
define('SECURE_AUTH_SALT', 'Gs:;xFy>^!.tV-do ]RN8HBK<-dNb+U^Z!c5MtD^:#C!q.0eO![t[+{9>z1X9+|J');
define('LOGGED_IN_SALT',   'jpfBVc-aqK%7B72pibT`2_=|$d$Oo2A0.8/3{](J0~:_l;n.V3+wr 0Dxr8(n-vv');
define('NONCE_SALT',       '@heuL2iuYgGtt-aE-(X*,lW&X/]OSSy|s%#|w++m+5-f`%YU0b^H2Cbg Obs=mx$');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

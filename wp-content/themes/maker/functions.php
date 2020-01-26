<?php
/**
 * Maker functions and definitions
 *
 * This file must be parseable by PHP 5.2.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package maker
 */

define( 'MAKER_DEFAULT_EMAIL', 'Info@MakerNY.com' );
define( 'MAKER_DEFAULT_PHONE', '347 — 850 — 3687' );
define( 'MAKER_DEFAULT_ACCOUNT', 'Maker_NY' );
define( 'MAKER_DEFAULT_COPYRIGHT', '&copy; Maker Creative Inc. [year]' );

// Setup autoloader.
require get_template_directory() . '/vendor/autoload.php';

// Load the `maker()` entry point function.
require get_template_directory() . '/inc/functions.php';

// Initialize the theme.
call_user_func( 'Parafa\Maker\maker' );

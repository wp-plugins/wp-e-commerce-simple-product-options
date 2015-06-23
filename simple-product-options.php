<?php
/*
Plugin Name: WP e-Commerce Simple Product Options
Plugin URI: http://www.leewillis.co.uk/wordpress-plugins/?utm_source=wordpress&utm_medium=www&utm_campaign=wpec-simple-product-options
Description: WP e-Commerce extension that allows you to add simple "product options" to products without having to create or manage variations
Author: Lee Willis
Version: 2.0
Author URI: http://www.leewillis.co.uk/
License: GPLv3
*/

if ( ! is_admin() || ( defined('DOING_AJAX') && DOING_AJAX ) ) {
	require_once ( 'simple-product-options-frontend.php' );
}

if ( is_admin() && ( ! defined('DOING_AJAX') || ! DOING_AJAX ) ) {
	require_once ( 'simple-product-options-admin.php' );
}

require_once ( 'simple-product-options-common.php' );

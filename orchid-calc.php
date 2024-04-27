<?php

/*
Plugin Name: محاسبه گر دوز
Plugin URI: http://orchidpharmed.com/
Description: محاسبه گر دوز داروی زرافیل
Author: محمد حسین زاده
Version: 3.26.11

*/

defined( 'ABSPATH' ) || exit;

/* DEFINES */
define( 'OCL_PATH', plugin_dir_path( __FILE__ ) );
define( 'OCL_URL', plugin_dir_url( __FILE__ ) );
define( 'OCL_INC', OCL_PATH . '/inc' );
define( 'OCL_PART', OCL_PATH . '/partials' );
define( 'OCL_ASSETS', plugin_dir_url( __FILE__ ) . '/assets' );
define( 'OCL_CSS', OCL_ASSETS . '/css' );
define( 'OCL_JS', OCL_ASSETS . '/js' );

/* ADD STYLES AND SCRIPTS */
function OCL_Add_Styles() {
	wp_enqueue_style( 'OCL-Main-Style', OCL_CSS . '/style.css', null, '1.0.3' );
	wp_enqueue_style( 'OCL-Calender-Style', OCL_CSS . '/PersianDatePicker.min.css', null, '1.0.0' );

	wp_enqueue_script( 'OCL-Calender-Script', OCL_JS . '/PersianDatePicker.min.js', null, '1.0.0', 'true' );
	wp_enqueue_script( 'OCL-Main-Script', OCL_JS . '/main.js', ['jquery'], '1.0.0', 'true' );
}

add_action( 'wp_enqueue_scripts', 'OCL_Add_Styles' );

/* Includes */
include_once OCL_INC . '/jdf.php';
include_once OCL_INC . '/functions.php';
include_once OCL_INC . '/ajax.php';
include_once OCL_INC . '/shortcodes.php';
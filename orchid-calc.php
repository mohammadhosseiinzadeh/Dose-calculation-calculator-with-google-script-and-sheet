<?php

/*
Plugin Name: محاسبه گر
Plugin URI: http://orchidpharmed.com/
Description: محسابه گر دوز دارو سیناتروپین و وضعیت جسمانی و رشد کودک
Author: رضا اکبری
Version: 1.0.0
Author URI: https://t.me/rezaak98
*/

defined('ABSPATH') || exit;

/* DEFINES */
define('OCL_PATH', plugin_dir_path(__FILE__));
define('OCL_URL', plugin_dir_url(__FILE__));
define('OCL_ASSETS', plugin_dir_url(__FILE__).'/assets');
define('OCL_CSS', OCL_ASSETS.'/css');
define('OCL_JS', OCL_ASSETS.'/js');

/* ADD STYLES AND SCRIPTS */
function OCL_Add_Styles() {
    wp_enqueue_style( 'OCL-Main-Style', OCL_CSS . '/style.css' );
}
add_action( 'wp_enqueue_scripts', 'OCL_Add_Styles' );

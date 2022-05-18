<?php
/*
 * Plugin Name: Employee Management Plugin
 * Description: Easy-to-use employee management tool
 * Version: 0.1.0
 * Author: was
 * Text Domain: employee-management
 * Domain Path: /i18n/languages
 */

use EmployeeManagement\Plugin;

if( ! defined('ABSPATH')){
    exit;
}

require_once plugin_dir_path( __FILE__ ) . '/vendor/autoload.php';
require_once trailingslashit( __DIR__ ) . 'inc/autoloader.php';

global $wpdb;

Plugin::instance($wpdb, __FILE__);
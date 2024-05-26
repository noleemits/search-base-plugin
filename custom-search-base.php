<?php

/*
Plugin Name: Custom Search Base
Plugin URI: https://noleemits.online
Description: A custom search plugin with pretty permalinks and hierarchical taxonomy handling.
Version: 1.1
Author: Stephen Lee Hernandez
Author URI: https://noleemits.online
License: GPL2
Text Domain: custom-search-base
*/


if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

// Include required files
require_once plugin_dir_path(__FILE__) . 'includes/config.php';
include_once plugin_dir_path( __FILE__ ) . 'includes/rewrite-rules.php';
include_once plugin_dir_path( __FILE__ ) . 'includes/query-handling.php';
include_once plugin_dir_path( __FILE__ ) . 'includes/shortcodes.php';
include_once plugin_dir_path( __FILE__ ) . 'includes/enqueue-scripts.php';
include_once plugin_dir_path( __FILE__ ) . 'includes/ajax-handlers.php';


// Activation hook to flush rewrite rules
function csb_activate_plugin() {
    csb_add_rewrite_rules();
    flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'csb_activate_plugin' );

// Deactivation hook to flush rewrite rules
function csb_deactivate_plugin() {
    flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'csb_deactivate_plugin' );

/* 
 * Include further documentation here or in a separate documentation file:
 *
 * Shortcodes:
 * [lawyer_search_form post_type="cpt-lawyer-card" common_searches="car-accident-lawyer,personal-injury" results_url="/abogados/"]
 * [metro_filter_form post_type="cpt-lawyer-card" results_url="/abogados/"]
 *
 * Screenshots, FAQs, and other usage instructions can also be included here.
 */

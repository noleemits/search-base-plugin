<?php
/**
 * Plugin Name: Custom Search Base
 * Description: A custom search plugin with pretty permalinks and hierarchical taxonomy handling.
 * Version: 1.0
 * Author: Lee Hernandez
 * Author URI: https://noleemits.online
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

// Include required files
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

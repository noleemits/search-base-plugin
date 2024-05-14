<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

function csb_enqueue_scripts() {
    wp_enqueue_script('csb-custom-js', plugin_dir_url(__FILE__) . '../assets/js/custom-search-base.js', array('jquery'), '1.0', true);
    wp_enqueue_style('csb-custom-css', plugin_dir_url(__FILE__) . '../assets/css/custom-search-base.css');
}
add_action('wp_enqueue_scripts', 'csb_enqueue_scripts');

<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

function csb_enqueue_scripts() {
    wp_enqueue_script('csb-custom-js', plugin_dir_url(__FILE__) . '../assets/js/custom-search-base.js', array('jquery', 'select2-js'), '1.0', true);
    wp_enqueue_style('csb-custom-css', plugin_dir_url(__FILE__) . '../assets/css/custom-search-base.css');
    wp_enqueue_script('select2-js', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js', array('jquery'), '4.1.0', true);
    wp_enqueue_style('select2-css', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css', array(), '4.1.0');

    wp_localize_script('csb-custom-js', 'csb_params', array(
        'ajax_url' => admin_url('admin-ajax.php')
    ));
}
add_action('wp_enqueue_scripts', 'csb_enqueue_scripts');

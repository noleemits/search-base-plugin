<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

function csb_get_metro_terms() {
    if (!isset($_POST['category']) || empty($_POST['category'])) {
        wp_send_json_error('No category provided');
    }

    $category = sanitize_text_field($_POST['category']);
    $metro_tax = 'metro'; // Update this if needed

    $metros = get_terms(array(
        'taxonomy' => $metro_tax,
        'hide_empty' => false,
        'parent' => 0
    ));

    $metro_list = array();
    foreach ($metros as $metro) {
        $children = get_terms(array(
            'taxonomy' => $metro_tax,
            'hide_empty' => false,
            'parent' => $metro->term_id
        ));

        foreach ($children as $child) {
            $metro_list[] = array(
                'slug' => $child->slug,
                'name' => $child->name
            );
        }
    }

    wp_send_json_success($metro_list);
}
add_action('wp_ajax_csb_get_metro_terms', 'csb_get_metro_terms');
add_action('wp_ajax_nopriv_csb_get_metro_terms', 'csb_get_metro_terms');

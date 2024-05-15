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

    // Get posts with the selected category
    $posts = get_posts(array(
        'post_type' => 'cpt-lawyer-card',
        'tax_query' => array(
            array(
                'taxonomy' => 'lawyer-category',
                'field'    => 'slug',
                'terms'    => $category,
            ),
        ),
        'numberposts' => -1,
    ));

    // Collect metro terms from the retrieved posts
    $metro_ids = array();
    foreach ($posts as $post) {
        $terms = wp_get_post_terms($post->ID, $metro_tax);
        foreach ($terms as $term) {
            if ($term->parent && !in_array($term->term_id, $metro_ids)) { // Only add child terms
                $metro_ids[] = $term->term_id;
            }
        }
    }

    // Get the metro terms based on the collected IDs
    $metro_list = array();
    foreach ($metro_ids as $metro_id) {
        $term = get_term($metro_id, $metro_tax);
        if ($term && !is_wp_error($term)) {
            $parent_term = get_term($term->parent, $metro_tax);
            $metro_list[] = array(
                'slug' => $term->slug,
                'name' => $term->name,
                'parent' => $parent_term ? $parent_term->slug : ''
            );
        }
    }

    wp_send_json_success($metro_list);
}
add_action('wp_ajax_csb_get_metro_terms', 'csb_get_metro_terms');
add_action('wp_ajax_nopriv_csb_get_metro_terms', 'csb_get_metro_terms');

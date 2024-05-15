<?php

function csb_lawyer_search_form_shortcode($atts) {
    $atts = shortcode_atts(array(
        'post_type' => 'cpt-lawyer-card',
        'category_tax' => 'lawyer-category',
        'metro_tax' => 'metro',
        'results_url' => '/lawyer-search-results',
        'common_searches' => '', // Comma-separated slugs of common searches
    ), $atts, 'lawyer_search_form');

    $category_tax = $atts['category_tax'];
    $metro_tax = $atts['metro_tax'];
    $results_url = rtrim($atts['results_url'], '/');
    $common_searches = array_map('trim', explode(',', $atts['common_searches']));

    ob_start();
    include plugin_dir_path(__FILE__) . '../templates/form-template.php';
    return ob_get_clean();
}
add_shortcode('lawyer_search_form', 'csb_lawyer_search_form_shortcode');




//Results shortcode

function csb_lawyer_search_results_shortcode($atts) {
    $atts = shortcode_atts(array(
        'post_type' => 'cpt-lawyer-card',
        'category_tax' => 'lawyer-category',
        'metro_tax' => 'metro',
        'elementor_template_id' => '',
    ), $atts, 'lawyer_search_results');

    $post_type = $atts['post_type'];
    $category_tax = $atts['category_tax'];
    $metro_tax = $atts['metro_tax'];
    $elementor_template_id = $atts['elementor_template_id'];

    if (get_query_var('_sft_lawyer-category') && get_query_var('_sft_metro')) {
        $category = get_query_var('_sft_lawyer-category');
        $metro = get_query_var('_sft_metro');
        $metro_parent = get_query_var('_sft_metro_parent');

        $tax_query = array(
            'relation' => 'AND',
            array(
                'taxonomy' => $category_tax,
                'field'    => 'slug',
                'terms'    => $category,
            ),
            array(
                'taxonomy' => $metro_tax,
                'field'    => 'slug',
                'terms'    => $metro,
            ),
        );

        if ($metro_parent) {
            $tax_query[] = array(
                'taxonomy' => $metro_tax,
                'field'    => 'slug',
                'terms'    => $metro_parent,
                'include_children' => true,
            );
        }

        $args = array(
            'post_type' => $post_type,
            'tax_query' => $tax_query,
        );

        $query = new WP_Query($args);

        ob_start();
        include plugin_dir_path(__FILE__) . '../templates/search-results-template.php';
        return ob_get_clean();
    } else {
        return '<p>Please select a category and metro area to search.</p>';
    }
}
add_shortcode('lawyer_search_results', 'csb_lawyer_search_results_shortcode');


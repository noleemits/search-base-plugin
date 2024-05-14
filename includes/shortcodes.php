<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

function csb_lawyer_search_form_shortcode() {
    $output = '<form id="lawyer-search-form">';
    $output .= '<label for="lawyer-category">Category:</label>';
    $output .= '<select name="lawyer-category" id="lawyer-category">';
    $categories = get_terms(array('taxonomy' => 'lawyer-category', 'hide_empty' => false));
    foreach ($categories as $category) {
        $output .= '<option value="' . esc_attr($category->slug) . '">' . esc_html($category->name) . '</option>';
    }
    $output .= '</select>';

    $output .= '<label for="metro">Metro:</label>';
    $output .= '<select name="metro" id="metro">';
    $metros = get_terms(array('taxonomy' => 'metro', 'hide_empty' => false));
    foreach ($metros as $metro) {
        $parent_slug = $metro->parent ? get_term($metro->parent)->slug : '';
        $output .= '<option value="' . esc_attr($metro->slug) . '" data-parent="' . esc_attr($parent_slug) . '">' . esc_html($metro->name) . '</option>';
    }
    $output .= '</select>';

    $output .= '<input type="submit" value="Search">';
    $output .= '</form>';

    return $output;
    echo "Here";
}
add_shortcode('lawyer_search_form', 'csb_lawyer_search_form_shortcode');

function csb_lawyer_search_results_shortcode() {
    if (get_query_var('_sft_lawyer-category') && get_query_var('_sft_metro')) {
        $category = get_query_var('_sft_lawyer-category');
        $metro = get_query_var('_sft_metro');
        $metro_parent = get_query_var('_sft_metro_parent');

        $tax_query = array(
            'relation' => 'AND',
            array(
                'taxonomy' => 'lawyer-category',
                'field'    => 'slug',
                'terms'    => $category,
            ),
            array(
                'taxonomy' => 'metro',
                'field'    => 'slug',
                'terms'    => $metro,
            ),
        );

        // If the parent metro term is set, include it in the tax query
        if ($metro_parent) {
            $tax_query[] = array(
                'taxonomy' => 'metro',
                'field'    => 'slug',
                'terms'    => $metro_parent,
                'include_children' => true,
            );
        }

        $args = array(
            'post_type' => 'cpt-lawyer-card',
            'tax_query' => $tax_query,
        );

        $query = new WP_Query($args);

        if ($query->have_posts()) {
            ob_start();
            while ($query->have_posts()) {
                $query->the_post();
                // Render the Elementor template part
                echo do_shortcode('[elementor-template id="YOUR_TEMPLATE_ID"]');
            }
            wp_reset_postdata();
            return ob_get_clean();
        } else {
            return '<p>No results found.</p>';
        }
    } else {
        return '<p>Please select a category and metro area to search.</p>';
    }
}
add_shortcode('lawyer_search_results', 'csb_lawyer_search_results_shortcode');

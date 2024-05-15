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

    $output = '<form id="lawyer-search-form">';
    
    $output .= '<div class="form-group">';
    $output .= '<label for="lawyer-category"><i class="icon-category"></i> Search Category</label>';
    $output .= '<select name="lawyer-category" id="lawyer-category" class="select2">';
    $output .= '<option value="" disabled selected>Search category</option>';
    $output .= '<optgroup label="Common Searches">';
    foreach ($common_searches as $common_search) {
        $term = get_term_by('slug', $common_search, $category_tax);
        if ($term) {
            $output .= '<option value="' . esc_attr($term->slug) . '">' . esc_html($term->name) . '</option>';
        }
    }
    $output .= '</optgroup>';
    $output .= '<optgroup label="All Categories">';
    $categories = get_terms(array('taxonomy' => $category_tax, 'hide_empty' => false));
    foreach ($categories as $category) {
        if (!in_array($category->slug, $common_searches)) {
            $output .= '<option value="' . esc_attr($category->slug) . '">' . esc_html($category->name) . '</option>';
        }
    }
    $output .= '</optgroup>';
    $output .= '</select>';
    $output .= '</div>';
    
    $output .= '<div class="form-group">';
    $output .= '<label for="metro"><i class="icon-metro"></i> Search Metro</label>';
    $output .= '<select name="metro" id="metro" class="select2" disabled>';
    $output .= '<option value="" disabled selected>Please select a category first</option>';
    $output .= '</select>';
    $output .= '</div>';

    $output .= '<input type="submit" value="Search" disabled>';
    $output .= '</form>';

    return $output;
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

        if ($query->have_posts()) {
            ob_start();
            while ($query->have_posts()) {
                $query->the_post();
                if ($elementor_template_id) {
                    echo do_shortcode('[elementor-template id="' . esc_attr($elementor_template_id) . '"]');
                } else {
                    the_title('<h2>', '</h2>');
                    the_excerpt();
                }
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

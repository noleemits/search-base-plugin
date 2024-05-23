<?php

// Search form shortcode
function csb_lawyer_search_form_shortcode($atts) {
    $atts = shortcode_atts(array(
        'post_type' => 'cpt-lawyer-card',
        'common_searches' => '', // Comma-separated slugs of common searches
    ), $atts, 'lawyer_search_form');

    $results_url = '/resultados-results/';
    $common_searches = array_map('trim', explode(',', $atts['common_searches']));

    ob_start();
    include plugin_dir_path(__FILE__) . '../templates/form-template.php';
    return ob_get_clean();
}
add_shortcode('lawyer_search_form', 'csb_lawyer_search_form_shortcode');


// Results shortcode
function csb_lawyer_search_results_shortcode($atts) {
    $atts = shortcode_atts(array(
        'post_type' => 'cpt-lawyer-card',
        'elementor_template_id' => '', // Elementor template ID
    ), $atts, 'lawyer_search_results');

    $post_type = $atts['post_type'];
    $elementor_template_id = $atts['elementor_template_id'];

    $category = get_query_var('_sft_lawyer-category');
    $metro = get_query_var('_sft_metro');
    $metro_parent = get_query_var('_sft_metro_parent');

    if ($category && $metro) {
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

        if ($metro_parent) {
            $tax_query[] = array(
                'taxonomy' => 'metro',
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
        if ($query->have_posts()) :
            echo '<div class="search-results custom-resultados-widget">';
            while ($query->have_posts()) : $query->the_post();
                echo '<div class="search-result-item">';
                if ($elementor_template_id) {
                    echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display($elementor_template_id);
                } else {
                    echo '<h2>' . get_the_title() . '</h2>';
                    echo '<div class="excerpt">' . get_the_excerpt() . '</div>';
                }
                echo '</div>';
            endwhile;
            echo '</div>';
        else :
            echo '<p>No results found.</p>';
        endif;
        wp_reset_postdata();

        return ob_get_clean();
    } else {
        return '<p>Please select a category and metro area to search.</p>';
    }
}
add_shortcode('lawyer_search_results', 'csb_lawyer_search_results_shortcode');


/*Dynamic title*/

function csb_best_lawyer_title_shortcode() {
    // Get the selected lawyer category and metro child category from query variables
    $lawyer_category_slug = get_query_var('_sft_lawyer-category');
    $metro_slug = get_query_var('_sft_metro');

    // Get the term names based on slugs
    $lawyer_category = get_term_by('slug', $lawyer_category_slug, 'lawyer-category');
    $metro = get_term_by('slug', $metro_slug, 'metro');

    // Generate the title
    if ($lawyer_category && $metro) {
        $title_category = sprintf('Best %s', esc_html($lawyer_category->name));
        $title_metro = sprintf('Lawyers in %s', esc_html($metro->name));
        return '<h1 class="h1-archive">' . $title_category . '<br>' . $title_metro . '</h1>';
    }

    return '<h1>Best Lawyers</h1>';
}

add_shortcode('best_lawyer_title', 'csb_best_lawyer_title_shortcode');



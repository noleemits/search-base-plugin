<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

function csb_modify_main_query($query) {
    if (!is_admin() && $query->is_main_query() && isset($query->query_vars['post_type']) && $query->query_vars['post_type'] == 'cpt-lawyer-card') {
        if (isset($query->query_vars['_sft_metro']) && isset($query->query_vars['_sft_lawyer-category'])) {
            $tax_query = array(
                array(
                    'taxonomy' => 'lawyer-category',
                    'field'    => 'slug',
                    'terms'    => $query->query_vars['_sft_lawyer-category'],
                ),
                array(
                    'taxonomy' => 'metro',
                    'field'    => 'slug',
                    'terms'    => $query->query_vars['_sft_metro'],
                ),
            );

            // If the parent metro term is set, include it in the tax query
            if (isset($query->query_vars['_sft_metro_parent']) && !empty($query->query_vars['_sft_metro_parent'])) {
                $tax_query[] = array(
                    'taxonomy' => 'metro',
                    'field'    => 'slug',
                    'terms'    => $query->query_vars['_sft_metro_parent'],
                    'include_children' => true,
                );
            }

            $query->set('tax_query', $tax_query);
        }
    }
}
add_action('pre_get_posts', 'csb_modify_main_query');

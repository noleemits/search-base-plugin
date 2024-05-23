<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

function csb_modify_main_query($query) {
    if (!is_admin() && $query->is_main_query() && isset($query->query_vars['pagename']) && $query->query_vars['pagename'] == 'resultados-results') {
        $tax_query = array('relation' => 'AND');

        if (isset($query->query_vars['_sft_metro']) && !empty($query->query_vars['_sft_metro'])) {
            $tax_query[] = array(
                'taxonomy' => 'metro',
                'field'    => 'slug',
                'terms'    => $query->query_vars['_sft_metro'],
            );
        }

        if (isset($query->query_vars['_sft_lawyer-category']) && !empty($query->query_vars['_sft_lawyer-category'])) {
            $tax_query[] = array(
                'taxonomy' => 'lawyer-category',
                'field'    => 'slug',
                'terms'    => $query->query_vars['_sft_lawyer-category'],
            );
        }

        if (isset($query->query_vars['_sft_metro_parent']) && !empty($query->query_vars['_sft_metro_parent'])) {
            $tax_query[] = array(
                'taxonomy' => 'metro',
                'field'    => 'slug',
                'terms'    => $query->query_vars['_sft_metro_parent'],
                'include_children' => true,
            );
        }

        if (!empty($tax_query)) {
            $query->set('tax_query', $tax_query);
        }
    }
}
add_action('pre_get_posts', 'csb_modify_main_query');


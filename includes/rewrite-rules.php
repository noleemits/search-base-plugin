<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

function csb_add_rewrite_rules() {
    // Rewrite rules for search results
    add_rewrite_rule(
        '^abogados/([^/]+)/([^/]+)/([^/]+)/?$',
        'index.php?pagename=' . CSB_RESULTS_PAGE . '&_sft_lawyer-category=$matches[1]&_sft_metro_parent=$matches[2]&_sft_metro=$matches[3]',
        'top'
    );

    add_rewrite_rule(
        '^abogados/([^/]+)/([^/]+)/?$',
        'index.php?pagename=' . CSB_RESULTS_PAGE . '&_sft_lawyer-category=$matches[1]&_sft_metro=$matches[2]',
        'top'
    );

    flush_rewrite_rules();
}
add_action('init', 'csb_add_rewrite_rules');

function csb_add_query_vars($vars) {
    $vars[] = '_sft_metro_parent';
    $vars[] = '_sft_metro';
    $vars[] = '_sft_lawyer-category';
    return $vars;
}
add_filter('query_vars', 'csb_add_query_vars');

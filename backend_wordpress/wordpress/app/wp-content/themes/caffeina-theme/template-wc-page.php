<?php

/* Template Name: Template Pagina WooCommerce */

$page_intro = [
    'title' => get_the_title(),
    'description' => get_the_excerpt(),
];

// If is thank you page
if ( is_checkout() && !empty(is_wc_endpoint_url('order-received')) ) {
    $page_intro = null;
} else if ( is_account_page() ) {
    $page_intro = null;
}

$context = [
    'page_intro' => $page_intro,
    'content' => apply_filters('the_content', get_the_content()),
];

Timber::render('@PathViews/template-wc-page.twig', $context);

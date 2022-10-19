<?php

/* Template name: Template Blank Page */

$context = [
    'page_title' => get_field('lb_page_blank_heading_h1'),
    'content' => apply_filters('the_content', get_the_content()),
];

Timber::render('@PathViews/template-blank-page.twig', $context);

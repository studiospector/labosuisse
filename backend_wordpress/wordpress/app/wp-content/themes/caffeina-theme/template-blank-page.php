<?php

/* Template name: Template Blank Page */

$context = [
    'content' => apply_filters('the_content', get_the_content()),
];

Timber::render('@PathViews/template-blank-page.twig', $context);

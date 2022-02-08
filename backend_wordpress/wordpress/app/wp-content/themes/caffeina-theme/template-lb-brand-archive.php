<?php

/* Template Name: Template Brands Archive */

$context = [
    'title' => get_the_title(),
    // 'content' => apply_filters( 'the_content', get_the_content() ),
];

Timber::render('@PathViews/template-lb-brand-archive.twig', $context);

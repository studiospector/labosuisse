<?php

/* Template Name: Template Assistenza */

$context = [
    'title' => get_the_title(),
    'content' => apply_filters( 'the_content', get_the_content() ),
];

Timber::render('@PathViews/template-support.twig', $context);

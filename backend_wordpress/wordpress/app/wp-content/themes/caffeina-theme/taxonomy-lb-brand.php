<?php

$context = [
    'title' => get_the_title(),
    // 'content' => apply_filters( 'the_content', get_the_content() ),
];

Timber::render('@PathViews/taxonomy-lb-brand.twig', $context);

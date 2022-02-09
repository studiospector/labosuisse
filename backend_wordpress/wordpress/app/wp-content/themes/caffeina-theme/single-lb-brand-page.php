<?php

$context = [
    'content' => apply_filters( 'the_content', get_the_content() ),
];

Timber::render('@PathViews/single-lb-brand-page.twig', $context);

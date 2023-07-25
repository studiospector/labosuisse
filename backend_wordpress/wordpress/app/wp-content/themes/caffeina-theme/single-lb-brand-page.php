<?php

$context = [
    'content' => apply_filters( 'the_content', get_the_content() ),
];

$toRedirect = toRedirect();

if(toRedirect()) {
    wp_redirect($toRedirect, 301);
}

Timber::render('@PathViews/single-lb-brand-page.twig', $context);

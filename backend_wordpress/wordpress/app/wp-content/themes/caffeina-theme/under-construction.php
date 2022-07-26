<?php

$context = [
    'infobox' => [
        'image' => get_template_directory_uri() . '/assets/images/logo.svg',
        'title' => "We'll Be Right Back",
        'paragraph' => "We are renovating!<br>Sorry for the inconvenience but we will be back in no time!",
    ]
];

Timber::render('@PathViews/under-construction.twig', $context);

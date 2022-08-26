<?php

/* Template Name: Template Pagina Login/Registrazione */

$context = [
    'page_intro' => null,
    'content' => apply_filters('the_content', get_the_content()),
    'shortcode' => '[lb_wc_sign_in_up]',
];

Timber::render('@PathViews/template-wc-sign-in-up.twig', $context);

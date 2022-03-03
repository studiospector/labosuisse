<?php

/* Template Name: Template Application */

$context = [
    'title' => get_the_title(),
    'content' => apply_filters( 'the_content', get_the_content() ),
    'form_shortcode' => get_field('lb_template_application_form_shortcode')
];

Timber::render('@PathViews/template-application.twig', $context);

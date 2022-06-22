<?php

use Caffeina\LaboSuisse\Option\Option;

$context = null;

$options = (new Option())->getJobsOptions();

// Job locations
$jobLocation = get_job_location();

if (have_posts()) {
    the_post();

    $context = [
        'title' => get_the_title(),
        'info' => [
            [
                'label' =>  __('Location:', 'labo-suisse-theme'),
                'value' => $jobLocation['jobLocationLinks'],
            ],
            [
                'label' =>  __('Tipologia di contratto:', 'labo-suisse-theme'),
                'value' => get_field('lb_job_contract_typology'),
            ],
            [
                'label' =>  __('Descrizione del ruolo:', 'labo-suisse-theme'),
                'value' => get_the_excerpt(),
            ],
        ],
        'company' => [
            'title' => __("L'azienda", 'labo-suisse-theme'),
            'content' => $options['content']['company']
        ],
        'requirements' => [
            'title' => __('Il candidato', 'labo-suisse-theme'),
            'content' => apply_filters( 'the_content', get_the_content() ),
        ],
        'application' => [
            'title' => __('Modulo di candidatura', 'labo-suisse-theme'),
            'content' => $options['content']['application'],
            'shortcode' => get_field('lb_jobs_form_application_shortcode', 'option'),
        ]
    ];
}

Timber::render('@PathViews/single-lb-job.twig', $context);

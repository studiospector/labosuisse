<?php

use Caffeina\LaboSuisse\Option\Option;

$items = [];

if (have_posts()) :
    while (have_posts()) :
        the_post();

        // Job locations
        $jobLocation = get_job_location();
        $isHeadquarter = $jobLocation['isHeadquarter'];
        $job_location_links = $jobLocation['jobLocationLinks'];

        // Card
        $items[] = [
            'infobox' => [
                'subtitle' => get_the_title(),
                'location' => (empty($job_location_links)) ? null : [
                    'isHeadquarter' => $isHeadquarter,
                    'label' => $job_location_links,
                ],
                'scope' => [
                    'label' => __('Ambito:', 'labo-suisse-theme'),
                    'value' => get_field('lb_job_scope')
                ],
                'paragraph' => get_the_excerpt(),
                'cta' => [
                    'url' => get_permalink(get_the_ID()),
                    'title' => __('Leggi di più', 'labo-suisse-theme'),
                    'variants' => ['quaternary']
                ]
            ],
            'variants' => ['type-9']
        ];
    endwhile;
endif;

wp_reset_postdata();


$options = (new Option())->getJobsOptions();

$context = [
    'title' => $options['title'],
    'description' => $options['description'],
    'job_location' => [
        'id' => 'lb-job-location',
        'label' => '',
        'placeholder' => 'Location',
        'multiple' => true,
        'required' => false,
        'disabled' => false,
        'confirmBtnLabel' => __('Applica', 'labo-suisse-theme'),
        'options' =>lb_get_job_location_options(),
        'variants' => ['primary']
    ],
    'job_department' => [
        'id' => 'lb-job-department',
        'label' => '',
        'placeholder' => 'Dipartimento',
        'multiple' => true,
        'required' => false,
        'disabled' => false,
        'confirmBtnLabel' => __('Applica', 'labo-suisse-theme'),
        'options' => lb_get_job_department_options(),
        'variants' => ['primary']
    ],
    'items' => $items,
    'infobox' => [
        'subtitle' => $options['infobox']['title'],
        'paragraph' => $options['infobox']['paragraph'],
        'cta' => $options['infobox']['cta']
    ]
];

Timber::render('@PathViews/archive-lb-job.twig', $context);
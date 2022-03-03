<?php

use Caffeina\LaboSuisse\Option\Option;

$items = [];

if (have_posts()) :
    while (have_posts()) :
        the_post();

        // Job locations
        $isHeadquarter = false;
        $job_location_links = '';
        $job_location_terms = get_the_terms(get_the_ID(), 'lb-job-location');

        if ($job_location_terms && !is_wp_error($job_location_terms)) {
            $job_location_draught_links = [];
            foreach ($job_location_terms as $term) {
                if (get_field('lb_job_location_headquarter', $term)) {
                    $isHeadquarter = true;
                }
                $job_location_draught_links[] = $term->name;
            }
            $job_location_links = join(", ", $job_location_draught_links);
        }

        // Card
        $items[] = [
            'infobox' => [
                'subtitle' => get_the_title(),
                'location' => (empty($job_location_links)) ? null : [
                    'isHeadquarter' => $isHeadquarter,
                    'label' => esc_html($job_location_links),
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


$options = (new Option())->geJobsOptions();

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
        'cta' => [
            'url' => $options['infobox']['cta']['url'],
            'title' => $options['infobox']['cta']['title'],
            'variants' => ['tertiary']
        ],
    ]
];

Timber::render('@PathViews/archive-lb-job.twig', $context);

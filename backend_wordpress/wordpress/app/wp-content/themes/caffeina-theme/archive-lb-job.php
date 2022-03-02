<?php

// use Caffeina\LaboSuisse\Option\Option;

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
                'scope' => '<span>' . __('Ambito', 'labo-suisse-theme') . ':</span> ' . get_field('lb_job_scope'),
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

// $options = (new Option())
//     ->getFaqOptions();

$context = [
    'title' => 'Fai parte del team labo',
    'description' => 'Labo International è attualmente alla ricerca dei profili indicati di seguito.<br>Leggi le descrizioni delle opportunità lavorative e candidati.',
    'job_location' => [
        'id' => 'lb-job-location',
        'label' => '',
        'placeholder' => 'Location',
        'multiple' => true,
        'required' => false,
        'disabled' => false,
        'confirmBtnLabel' => __('Applica', 'labo-suisse-theme'),
        'options' => [
            [
                'value' => 'aaa',
                'label' => 'AAA',
            ],
            [
                'value' => 'bbb',
                'label' => 'BBB',
            ],
            [
                'value' => 'ccc',
                'label' => 'CCC',
            ],
        ],
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
        'options' => [
            [
                'value' => 'aaa',
                'label' => 'AAA',
            ],
            [
                'value' => 'bbb',
                'label' => 'BBB',
            ],
            [
                'value' => 'ccc',
                'label' => 'CCC',
            ],
        ],
        'variants' => ['primary']
    ],
    'items' => $items,
    'infobox' => [
        'subtitle' => 'VUOI INVIARE UNA<br>CANDIDATURA SPONTANEA?',
        'paragraph' => 'Vai al modulo di candidatura senza specificare<br>un’opportunità lavorativa in particolare e invia i tuoi dati.',
        'cta' => [
            'url' => '#',
            'title' => 'Vai al modulo',
            'variants' => ['tertiary']
        ],
    ]
];

Timber::render('@PathViews/archive-lb-job.twig', $context);

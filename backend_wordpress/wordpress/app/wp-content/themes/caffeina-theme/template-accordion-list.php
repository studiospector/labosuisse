<?php

/* Template Name: Template Lista Accordion */

$items = [];
if (have_rows('lb_template_accordion_list')) {
    $count = 1;
    while (have_rows('lb_template_accordion_list')) : the_row();
        $accordions = [];
        
        if (have_rows('lb_template_accordion_list_row')) {
            while (have_rows('lb_template_accordion_list_row')) : the_row();
                $row = [];

                $row['type'] = 'links-list';
                $row['data'] = [];

                $data = [];
                $data['label'] = get_sub_field('lb_template_accordion_list_row_label');
                $data['links'] = [];

                if (have_rows('lb_template_accordion_list_links')) {
                    while (have_rows('lb_template_accordion_list_links')) : the_row();
                        $data['links'][] = array_merge(
                            (array)get_sub_field('lb_template_accordion_list_links_link'),
                            [
                                'iconStart' => ['name' => get_sub_field('lb_template_accordion_list_links_icon')],
                                'variants' => ['link'],
                            ]
                        );
                    endwhile;
                }

                $row['data'][] = $data;

                $accordions[] = $row;
            endwhile;
        }

        $accordion_id = get_sub_field('lb_template_accordion_list_id');
        
        $items[] = [
            'id' => $accordion_id ? $accordion_id : $count,
            'icon' => get_sub_field('lb_template_accordion_list_icon'),
            'title' => get_sub_field('lb_template_accordion_list_title'),
            'content' => ($accordions) ? $accordions : []
        ];
        $count++;
    endwhile;
}

$context = [
    'page_intro' => [
        'title' => get_the_title(),
        'description' => get_the_excerpt(),
    ],
    'list' => [
        'opened_by_default' => true,
        'items' => $items
    ],
    'content' => apply_filters('the_content', get_the_content()),
];

// echo '<pre>';
// var_dump( $context['list']['items'] );
// die;

Timber::render('@PathViews/template-accordion-list.twig', $context);

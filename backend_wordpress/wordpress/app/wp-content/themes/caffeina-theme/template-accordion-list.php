<?php

/* Template Name: Template Lista Accordion */

$items = [];
if (have_rows('lb_template_accordion_list')) {
    while (have_rows('lb_template_accordion_list')) : the_row();
        $accordions = [];
        
        if (have_rows('lb_template_accordion_list_row')) {
            while (have_rows('lb_template_accordion_list_row')) : the_row();
                $row = [];
                $row['label'] = get_sub_field('lb_template_accordion_list_row_label');
                $row['links'] = [];

                if (have_rows('lb_template_accordion_list_links')) {
                    while (have_rows('lb_template_accordion_list_links')) : the_row();
                        $row['links'][] = array_merge((array)get_sub_field('lb_template_accordion_list_links_link'), ['iconStart' => ['name' => get_sub_field('lb_template_accordion_list_links_icon')]]);
                    endwhile;
                }

                $accordions[] = $row;
            endwhile;
        }
        
        $items[] = [
            'icon' => get_sub_field('lb_template_accordion_list_icon'),
            'title' => get_sub_field('lb_template_accordion_list_title'),
            'content' => ($accordions) ? $accordions : []
        ];
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

Timber::render('@PathViews/template-accordion-list.twig', $context);

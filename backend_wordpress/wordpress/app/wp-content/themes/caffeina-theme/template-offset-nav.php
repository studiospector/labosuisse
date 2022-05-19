<?php

/* Template Name: Template Offset Nav */

$featured_images = lb_get_images(get_post_thumbnail_id(get_the_ID()));

$items = [];
if (have_rows('lb_navs_template_items')) {
    while (have_rows('lb_navs_template_items')) : the_row();
        $blocks = [];
        
        if (have_rows('lb_navs_template_item_content')) {
            while (have_rows('lb_navs_template_item_content')) : the_row();
                $row = [];

                $row['type'] = get_row_layout();
                $row['data'] = [];

                $data = [];

                if (get_row_layout() == 'text') {
                    $data['title'] = get_sub_field('lb_navs_template_item_content_text_title');
                    $data['subtitle'] = get_sub_field('lb_navs_template_item_content_text_subtitle');
                    $data['paragraph_small'] = get_sub_field('lb_navs_template_item_content_text_paragraph_small');
                    $data['text'] = get_sub_field('lb_navs_template_item_content_text_paragraph');
                } elseif (get_row_layout() == 'unordered-arrow-list') {
                    if (have_rows('lb_navs_template_item_content_list')) {
                        while (have_rows('lb_navs_template_item_content_list')) {
                            the_row();
                            $data['items'][] = get_sub_field('lb_navs_template_item_content_list_text');
                        }
                    }
                }
                
                $row['data'] = $data;

                $blocks[] = $row;
            endwhile;
        }

        $items[] = [
            'id' => get_sub_field('lb_navs_template_item_id'),
            'title' => get_sub_field('lb_navs_template_item_title'),
            'data' => ($blocks) ? $blocks : []
        ];
    endwhile;
}

$context = [
    'featured_image' => ($featured_images) ? [
        'images' => $featured_images,
        'container' => false,
        'variants' => ['small']
    ] : null,
    'page_intro' => [
        'title' => get_the_title(),
        'description' => get_the_excerpt(),
    ],
    'navs' => [
        'items' => $items
    ],
    'content' => apply_filters('the_content', get_the_content()),
];

Timber::render('@PathViews/template-offset-nav.twig', $context);

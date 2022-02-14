<?php

/**
 * Get all Brands
 */
function lb_get_brands()
{
    $brands = get_terms(array(
        'taxonomy' => 'lb-brand',
        'hide_empty' => false,
    ));

    $i = 0;
    foreach ($brands as $term) {
        if ($term->parent != 0) {
            unset($brands[$i]);
        }
        $i++;
    }

    return $brands;
}

/**
 * Get all "Linee di prodotto"
 */
function lb_get_brands_product_lines()
{
    $product_lines = get_terms(array(
        'taxonomy' => 'lb-brand',
        'hide_empty' => false,
    ));

    $i = 0;
    foreach ($product_lines as $term) {
        if ($term->parent == 0) {
            unset($product_lines[$i]);
        }
        $i++;
    }

    return $product_lines;
}

/**
 * Get taxonomy term level
 */
function get_category_parents_custom($category_id, $tax)
{
    $args = array(
        'separator' => ',',
        'link'      => false,
        'format'    => 'slug',
    );

    $parent_terms = get_term_parents_list($category_id, $tax, $args);

    return substr_count($parent_terms, ',');
}

function get_brands_menu()
{
    $menu = [
        'type' => 'submenu',
        'label' => __('Tutti i Brand', 'labo-suisse-theme'),
        'children' => [
            [
                'type' => 'submenu',
                'label' => __('Per Brand', 'labo-suisse-theme'),
                'children' => [
                    ['type' => 'link', 'label' => 'Tutti i brand', 'href' =>  '#']
                ]
            ],
            [
                'type' => 'submenu-second',
                'children' => []
            ]
        ],
        'fixed' => [
            [
                'type' => 'card',
                'data' => [
                    'images' => [
                        'original' => get_template_directory_uri() . '/assets/images/card-img-5.jpg',
                        'large' => get_template_directory_uri() . '/assets/images/card-img-5.jpg',
                        'medium' => get_template_directory_uri() . '/assets/images/card-img-5.jpg',
                        'small' => get_template_directory_uri() . '/assets/images/card-img-5.jpg'
                    ],
                    'infobox' => [
                        'subtitle' => 'Magnetic Eyes',
                        'paragraph' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
                        'cta' => [
                            'url' => '#',
                            'title' => __('Scopri di più', 'labo-suisse-theme'),
                            'variants' => ['quaternary']
                        ]
                    ],
                    'variants' => ['type-3']
                ],
            ],
        ]
    ];

    $brands =  get_terms(array(
        'taxonomy' => 'lb-brand',
        'hide_empty' => false,
        'parent' => null
    ));

    foreach ($brands as $i => $brand) {
        $menu['children'][0]['children'][] = [
            'type' => 'submenu-link',
            'label' => $brand->name,
            'trigger' => md5($brand->slug),
        ];

        $menu['children'][1]['children'][$i] = [
            'type' => 'submenu',
            'label' => __('Per linea di prodotto', 'labo-suisse-theme'),
            'trigger' => md5($brand->slug),
            'children' => get_brands_menu_product_line($brand)
        ];
    }

    return [$menu];
}

function get_brands_menu_product_line($brand)
{
    $lines =  get_terms(array(
        'taxonomy' => 'lb-brand',
        'hide_empty' => false,
        'parent' => $brand->term_id
    ));

    $items = [
        ['type' => 'link', 'label' => __('Scopri la linea', 'labo-suisse-theme'), 'href' => get_permalink(get_field('lb_brand_page', $brand))],
        ['type' => 'link', 'label' => __('Vedi tutti i prodotti', 'labo-suisse-theme') . ' ' . $brand->name, 'href' => get_term_link($brand)],
    ];

    foreach ($lines as $line) {
        $items[] = [
            'type' => 'link',
            'label' => $line->name,
            'href' => get_term_link($line),
        ];
    }

    return $items;
}

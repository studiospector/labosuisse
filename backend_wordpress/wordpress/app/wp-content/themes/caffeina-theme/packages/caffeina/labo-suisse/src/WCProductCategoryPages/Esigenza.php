<?php

namespace Caffeina\LaboSuisse\WCProductCategoryPages;

use Timber\Timber;

class Esigenza extends BasePage
{
    public function __construct($name, $term)
    {
        parent::__construct('esigenza', $term);

        $items = [];
        $brands_arr = [];
        $count_posts = 0;

        $order_filter = [
            [
                'value' => 'total_sales',
                'label' => 'Best-sellers',
            ],
            [
                'value' => 'lorem-ipsum-1',
                'label' => 'Lorem Ipsum 1',
            ],
            [
                'value' => 'lorem-ipsum-2',
                'label' => 'Lorem Ipsum 2',
            ],
        ];

        $tipologie = get_terms(array(
            'taxonomy' => 'product_cat',
            'hide_empty' => false,
            'parent' => $term->term_id,
            // 'fields' => 'slugs',
        ));

        // Get only Tipologie slugs
        $tipologie_slugs = array_map(function ($obj) {
            return $obj->slug;
        }, $tipologie);

        // Set Tipologie array for FE select filter
        $tipologie_arr = array_map(function ($obj) {
            return [
                'value' => $obj->slug,
                'label' => $obj->name,
            ];
        }, $tipologie);

        // $brands = get_terms(array(
        //     'taxonomy' => 'lb-brand',
        //     'hide_empty' => false,
        //     'childless' => false,
        // ));
        $brands = lb_get_brands();

        foreach ($brands as $brand) {
            $the_query = new \WP_Query(array(
                'post_type' => 'product',
                'posts_per_page' => -1,
                'tax_query' => array(
                    'relation' => 'AND',
                    array(
                        'taxonomy' => 'product_cat',
                        'field' => 'slug',
                        'terms' => $tipologie_slugs,
                    ),
                    array(
                        'taxonomy' => 'lb-brand',
                        'field' => 'slug',
                        'terms' => $brand->slug,
                    )
                ),
            ));


            if (count($the_query->posts) > 0) {
                $brands_arr[] = [
                    'value' => $brand->slug,
                    'label' => $brand->name,
                ];

                $items[$brand->term_id]['brand_card'] = [
                    'color' => get_field('lb_brand_color', $brand),
                    'infobox' => [
                        'subtitle' => $brand->name,
                        'paragraph' => $brand->description,
                        'cta' => [
                            'url' => get_term_link($brand),
                            'title' => __('Scopri il brand', 'labo-suisse-theme'),
                            'variants' => ['quaternary']
                        ]
                    ],
                    'variants' => ['type-8']
                ];

                foreach ($the_query->posts as $product) {
                    $items[$brand->term_id]['products'][] = Timber::get_post($product->ID);
                    $count_posts++;
                }

                // $items[] = [
                //     'brand' => $brand,
                //     'products' => $the_query->posts,
                // ];
            }
        }
        wp_reset_postdata();

        $payload = [
            'filters' => [
                'containerized' => false,
                'items' => [
                    [
                        'id' => 'lb-product-brands',
                        'label' => '',
                        'placeholder' => __('Brand', 'labo-suisse-theme'),
                        'multiple' => true,
                        'required' => false,
                        'disabled' => false,
                        'confirmBtnLabel' => __('Applica', 'labo-suisse-theme'),
                        'options' => $brands_arr,
                        'variants' => ['primary']
                    ],
                    [
                        'id' => 'lb-product-cat-typology',
                        'label' => '',
                        'placeholder' => __('Tipi di prodotto', 'labo-suisse-theme'),
                        'multiple' => true,
                        'required' => false,
                        'disabled' => false,
                        'confirmBtnLabel' => __('Applica', 'labo-suisse-theme'),
                        'options' => $tipologie_arr,
                        'variants' => ['primary']
                    ],
                    [
                        'id' => 'lb-product-order',
                        'label' => __('Ordina per', 'labo-suisse-theme'),
                        'placeholder' => __('Scegli...', 'labo-suisse-theme'),
                        'multiple' => true,
                        'required' => false,
                        'disabled' => false,
                        'confirmBtnLabel' => __('Applica', 'labo-suisse-theme'),
                        'options' => $order_filter,
                        'variants' => ['secondary']
                    ],
                ],
                'search' => [
                    'type' => 'search',
                    'name' => 's',
                    'label' => __('Cerca', 'labo-suisse-theme'),
                    'value' => !empty($_GET['s']) ? $_GET['s'] : null,
                    'disabled' => false,
                    'required' => false,
                    'buttonTypeNext' => 'submit',
                    'variants' => ['secondary'],
                ],
            ],
            'grid_type' => 'ordered',
            'num_posts' => __('Risultati:', 'labo-suisse-theme') . ' <span>' . $count_posts . '</span>',
            'items' => $items,
            'block_announcements' => (!get_field('lb_product_cat_announcements_state', $term)) ? null : [
                'infobox' => [
                    'title' => get_field('lb_product_cat_announcements_title', $term),
                ],
                'cards' => [
                    [
                        'images' => lb_get_images(get_field('lb_product_cat_announcements_cardleft_image', $term)),
                        'infobox' => $this->getCardByType(get_field('lb_product_cat_announcements_cardleft_item_type', $term), 'left', $term)
                    ],
                    [
                        'images' => lb_get_images(get_field('lb_product_cat_announcements_cardright_image', $term)),
                        'infobox' => $this->getCardByType(get_field('lb_product_cat_announcements_cardright_item_type', $term), 'right', $term)
                    ]
                ],
                'variants' => ['horizontal'],
            ]
        ];

        $this->setContext($payload);
        //$this->macro->render();
    }

    protected function getCardByType($type, $card, $term)
    {
        $payload = [
            'subtitle' => '',
            'cta' => [
                'url' => '',
                'title' => __('Scopri di più', 'labo-suisse-theme'),
                'variants' => ['quaternary'],
            ]
        ];

        if ($type == 'lb_type_post') {
            $obj = get_field('lb_product_cat_announcements_card' . $card . '_item_post', $term);
            $payload['subtitle'] = $obj->post_title;
            $payload['cta']['url'] = get_permalink($obj->ID);
        } elseif ($type == 'lb_type_tax') {
            $obj = get_field('lb_product_cat_announcements_card' . $card . '_item_tax', $term);

            $payload['subtitle'] = $obj->name;
            $payload['cta']['url'] = get_term_link($obj->term_id);
        }

        return $payload;
    }
}

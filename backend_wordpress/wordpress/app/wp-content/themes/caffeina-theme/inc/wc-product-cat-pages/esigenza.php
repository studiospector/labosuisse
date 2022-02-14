<?php
require_once(__DIR__ . '/basePage.php');

use pages\basePage;

class esigenza extends basePage
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
            $the_query = new WP_Query(array(
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
                    $items[$brand->term_id]['products'][] = \Timber::get_post( $product->ID );
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
            'termName' => $term->name,
            'brands_filter' => $brands_arr,
            'tipologie_filter' => $tipologie_arr,
            'order_filter' => $order_filter,
            'grid_type' => 'ordered',
            'items' => $items,
            'num_posts' => __('Risultati:', 'labo-suisse-theme') . ' <span>' . $count_posts . '</span>',
        ];

        $this->setContext($payload);
        //$this->macro->render();
    }

    public static function getAll($parent)
    {
        $needs =  self::getProductCategory($parent);

        $items = [['type' => 'link', 'label' => 'Tutte le esigenze', 'href' => get_term_link($parent)]];

        foreach ($needs as $need) {
            $items[] = [
                'type' => 'link',
                'label' => $need->name,
                'href' => get_term_link($need),
            ];
        }

        return $items;
    }
}

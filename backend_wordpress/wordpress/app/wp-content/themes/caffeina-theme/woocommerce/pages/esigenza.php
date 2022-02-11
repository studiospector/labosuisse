<?php
require_once(__DIR__ . '/basePage.php');

use pages\basePage;

class esigenza extends basePage
{

    public function __construct($name, $term)
    {
        parent::__construct('esigenza', $term);


        $res = [];
        $brands_arr = [];

        $tipologie = get_terms(array(
            'taxonomy' => 'product_cat',
            'hide_empty' => false,
            'parent' => $term->term_id,
            // 'fields' => 'slugs',
        ));

        // Get only slugs
        $tipologie_slugs = array_map(function ($obj) {
            return $obj->slug;
        }, $tipologie);

        $brands = get_terms(array(
            'taxonomy' => 'lb-brand',
            'hide_empty' => false
        ));

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
                $brands_arr[] = $brand;
                $res[] = [
                    'brand' => $brand,
                    'products' => $the_query->posts,
                ];
            }
        }

        wp_reset_postdata();

        $payload = [

            'termName' => $term->name,
            'brands' => $brands_arr,
            'tipologie' => $tipologie,
            'results' => $res
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

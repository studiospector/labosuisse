<?php

namespace Caffeina\LaboSuisse\Resources;

use Timber\Timber;

class ArchiveProduct
{
    private $category;
    private $level;
    private $brands = [];
    private $filterItems = [];
    private $products = [];
    private $totalPosts = 0;

    /**
     * @param $category
     */
    public function __construct($term, $level)
    {
        $this->category = $term;
        $this->level = $level;

        $this->getFilterItems($term);

        $this->products();
    }

    public function render()
    {
        $context = [
            'filters' => [
                'filter_type' => 'product',
                'post_type' => 'product',
                'posts_per_page' => -1,
                'containerized' => false,
                'items' => [
                    [
                        'id' => 'lb-brand',
                        'label' => '',
                        'placeholder' => __('Brand', 'labo-suisse-theme'),
                        'multiple' => true,
                        'required' => false,
                        'disabled' => false,
                        'confirmBtnLabel' => __('Applica', 'labo-suisse-theme'),
                        'options' => $this->brands,
                        'attributes' => ['data-taxonomy="lb-brand"'],
                        'variants' => ['primary']
                    ],
                    [
                        'id' => 'lb-product-cat',
                        'label' => '',
                        'placeholder' => $this->getPlaceholder(),
                        'multiple' => true,
                        'required' => false,
                        'disabled' => false,
                        'confirmBtnLabel' => __('Applica', 'labo-suisse-theme'),
                        'options' => $this->filterItems,
                        'attributes' => ['data-taxonomy="product_cat"'],
                        'variants' => ['primary']
                    ]
                ],
                'search' => [
                    'type' => 'search',
                    'name' => 's',
                    'label' => __('Cerca... (inserisci almeno 3 caratteri)', 'labo-suisse-theme'),
                    'value' => !empty($_GET['s']) ? $_GET['s'] : null,
                    'disabled' => false,
                    'required' => true,
                    'buttonTypeNext' => 'submit',
                    'variants' => ['secondary'],
                ],
            ],
            'grid_type' => 'ordered',
            'num_posts' => __('Risultati:', 'labo-suisse-theme') . ' <span>' . $this->totalPosts . '</span>',
            'items' => $this->products,
            'page_intro' => [
                'title' => $this->category->name,
                'description' => nl2br($this->category->description),
            ],
        ];

        Timber::render('@PathViews/woo/taxonomy-product-cat.twig', $context);
    }

    private function products()
    {
        foreach (lb_get_brands() as $brand) {
            $query = new \WP_Query([
                'post_type' => 'product',
                'posts_per_page' => -1,
                'tax_query' => $this->setFilter($brand),
                'orderby' => 'menu_order',
                'order' => 'ASC',
                's' => !empty($_GET['search']) ? $_GET['search'] : null
            ]);

            if (empty($query->posts)) {
                continue;
            }

            $this->brands[] = [
                'value' => $brand->term_id,
                'label' => $brand->name,
            ];

            usort($this->brands, function ($a, $b) {
                return $a['label'] <=> $b['label'];
            });

            $this->products[$brand->term_id]['brand_card'] = $this->getBrandCard($brand);

            foreach ($query->posts as $product) {
                $this->products[$brand->term_id]['products'][] = Timber::get_post($product->ID);
                $this->totalPosts++;
            }
        }

        wp_reset_postdata();
    }

    private function getBrandCard($brand)
    {
        return [
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
            'type' => 'type-8',
            'variants' => null
        ];
    }

    private function getFilterItems($term)
    {
        $items = get_terms([
            'taxonomy' => 'product_cat',
            'hide_empty' => true,
            'parent' => $term->term_id
        ]);

        foreach ($items as $item) {
            $this->filterItems[] = [
                'value' => $item->term_id,
                'label' => $item->name,
            ];
        }
    }

    private function setFilter($brand)
    {
        $filters = [
            'relation' => 'AND',
            [
                'taxonomy' => 'lb-brand',
                'field' => 'slug',
                'terms' => $brand->slug,
            ]
        ];

        if ($this->category) {
            $filters[] = [
                'taxonomy' => 'product_cat',
                'field' => 'slug',
                'terms' => $this->category->slug
            ];
        }

        return $filters;
    }

    private function getPlaceholder()
    {
        switch ($this->level) {
            case 1:
                return  __('Zone', 'labo-suisse-theme');
            case 2:
                return  __('Esigenze', 'labo-suisse-theme');
            case 3:
                return __('Tipi di prodotto', 'labo-suisse-theme');
        }
    }
}

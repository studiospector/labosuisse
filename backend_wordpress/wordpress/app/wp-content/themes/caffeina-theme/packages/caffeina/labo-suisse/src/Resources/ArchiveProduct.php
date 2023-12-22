<?php

namespace Caffeina\LaboSuisse\Resources;

use Timber\Timber;

class ArchiveProduct
{
    private $term;
    private $level;
    private $brands = [];
    private $filterItems = [];
    private $products = [];
    private $totalPosts = 0;

    public function __construct($term, $level)
    {
        $this->term = $term;
        $this->level = $level;

        $this->getFilterItems($term);

        $this->redirectIfNotHasArea();

        $this->products();
    }

    public function render()
    {
        $brands_select = $this->brands && (count($this->brands) > 1) ? [
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
        ] : null;

        $context = [
            'filters' => [
                'lang' => lb_get_current_lang(),
                'filter_type' => 'product',
                'post_type' => 'product',
                'posts_per_page' => -1,
                'containerized' => false,
                'items' => [
                    $brands_select,
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
                'base_cat' => [
                    'id' => 'lb-product-cat-base',
                    'name' => 'lb-product-cat-base',
                    'value' => $this->term->term_id,
                ],
                'search' => [
                    'type' => 'search',
                    'name' => 'search',
                    'label' => __('Cerca... (inserisci almeno 3 caratteri)', 'labo-suisse-theme'),
                    'value' => !empty($_GET['search']) ? $_GET['search'] : null,
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
                'title' => $this->term->name,
                'description' => nl2br($this->term->description),
            ],
        ];

        $context['filters']['items'] = array_filter($context['filters']['items'], fn($value) => !is_null($value) && $value !== '');

        Timber::render('@PathViews/woo/taxonomy-product-cat.twig', $context);
    }

    private function redirectIfNotHasArea()
    {
        if(count($this->filterItems) and $this->filterItems[0]['label'] === $this->term->name) {
            return wp_safe_redirect(
                get_term_link(get_term_by('id', $this->filterItems[0]['value'],'product_cat')),
                301
            );
        }
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
                $wc_product = wc_get_product($product->ID);

                $product_data = Timber::get_post($product->ID);
                $product_data->lb_is_available = ($wc_product->is_purchasable() && $wc_product->is_in_stock()) ? ['label' => __('Disponibile online', 'labo-suisse-theme')] : false;

                $this->products[$brand->term_id]['products'][] = $product_data;
                $this->totalPosts++;
            }
        }

        wp_reset_postdata();
    }

    private function getBrandCard($brand)
    {
        $brand_page = get_field('lb_brand_page', $brand);

        return [
            'color' => get_field('lb_brand_color', $brand),
            'infobox' => [
                'subtitle' => $brand->name,
                'paragraph' => $brand->description,
                'cta' => [
                    'url' => get_permalink($brand_page),
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

        if ($this->term) {
            $filters[] = [
                'taxonomy' => 'product_cat',
                'field' => 'slug',
                'terms' => $this->term->slug
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

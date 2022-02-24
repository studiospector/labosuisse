<?php

namespace Caffeina\LaboSwiss\WCProductCategoryPages;

class Macro extends BasePage
{
    public function __construct($name, $term)
    {
        parent::__construct('macro', $term);

        // self::getAll();
        $sub_terms = get_terms([
            'taxonomy' => 'product_cat',
            'hide_empty' => false,
            'parent' => $term->term_id,
        ]);

        $payload['terms'] = [];
        foreach ($sub_terms as $item) {
            $payload['terms'][] = [
                'images' => lb_get_images(get_term_meta($item->term_id, 'thumbnail_id', true)),
                'infobox' => [
                    'subtitle' => $item->name,
                    'paragraph' => $item->description,
                    'cta' => [
                        'url' => get_term_link($item),
                        'title' => 'Vai a ' . $item->name,
                        'variants' => ['quaternary']
                    ]
                ],
                'variants' => ['type-3']
            ];
        }

        $this->setContext($payload);
        // $this->macro->render();
    }
}

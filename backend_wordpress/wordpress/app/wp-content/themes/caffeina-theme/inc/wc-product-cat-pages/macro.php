<?php
require_once(__DIR__ . '/basePage.php');
require_once(__DIR__ . '/zona.php');

use pages\basePage;

class macro extends basePage
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
            // Term Thumb
            $thumbnail_id = get_term_meta($item->term_id, 'thumbnail_id', true);
            $image = wp_get_attachment_url($thumbnail_id);

            $payload['terms'][] = [
                'images' => [
                    'original' => $image,
                    'large' => $image,
                    'medium' => $image,
                    'small' => $image
                ],
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

    public static function getTheMenuTree($device = 'desktop')
    {
        $macro = self::getProductCategory();
        $items = [];

        foreach ($macro as $item) {
            $zona = [];
            switch ($device) {
                case 'desktop':
                    $zona = zona::getDesktopItems($item);
                    break;
                case 'mobile':
                    $zona = zona::getMobileItems($item);
                    break;
            }

            $items[] = $zona;
        }

        return $items;
    }
}

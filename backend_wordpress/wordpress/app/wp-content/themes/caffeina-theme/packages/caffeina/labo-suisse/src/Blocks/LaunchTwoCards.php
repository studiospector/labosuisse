<?php

namespace Caffeina\LaboSuisse\Blocks;

class LaunchTwoCards extends BaseBlock
{
    public function __construct($block, $name, $sectionID)
    {
        parent::__construct($block, $name, $sectionID);

        $sizes = [
            'lg' => 'lg',
            'md' => 'lg',
            'sm' => 'lg',
            'xs' => 'lg'
        ];

        $payload = [
            'sectionID' => $sectionID ?? null,
            'cards' => [
                [
                    'images' => lb_get_images(get_field('lb_block_launch_two_cards_image_left'), $sizes),
                    'infobox' => [
                        'tagline' => get_field('lb_block_launch_two_cards_tagline_left'),
                        'title' => get_field('lb_block_launch_two_cards_title_left'),
                        'subtitle' => get_field('lb_block_launch_two_cards_subtitle_left'),
                        'paragraph' => get_field('lb_block_launch_two_cards_paragraph_left'),
                    ],
                    'type' => 'type-3',
                ],
                [
                    'images' => lb_get_images(get_field('lb_block_launch_two_cards_image_right'), $sizes),
                    'infobox' => [
                        'tagline' => get_field('lb_block_launch_two_cards_tagline_right'),
                        'title' => get_field('lb_block_launch_two_cards_title_right'),
                        'subtitle' => get_field('lb_block_launch_two_cards_subtitle_right'),
                        'paragraph' => get_field('lb_block_launch_two_cards_paragraph_right'),
                    ],
                    'type' => 'type-3',
                ]
            ]
        ];

        if (get_field('lb_block_launch_two_cards_btn_left') != "") {
            $payload['cards'][0]['infobox']['cta'] = array_merge((array)get_field('lb_block_launch_two_cards_btn_left'), ['variants' => [get_field('lb_block_launch_two_cards_btn_variants_left')]]);
        }

        if (get_field('lb_block_launch_two_cards_btn_right') != "") {
            $payload['cards'][1]['infobox']['cta'] = array_merge((array)get_field('lb_block_launch_two_cards_btn_right'), ['variants' => [get_field('lb_block_launch_two_cards_btn_variants_right')]]);
        }

        foreach ($payload['cards'] as $key => $card) {
            $card_is_empty = empty($card['images']);

            foreach ($card['infobox'] as $infobox_field) {
                $card_is_empty = empty($infobox_field);
            }

            if ($card_is_empty) {
                $payload['cards'][$key] = null;
            }
        }
        
        if (get_field('lb_block_launch_two_cards_variants') != "") {
            $payload['variants'] = [get_field('lb_block_launch_two_cards_variants')];
        }

        $this->setContext($payload);

        $this->addInfobox();
    }
}

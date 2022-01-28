<?php
require_once(__DIR__.'/baseBlock.php');
use gutenbergBlocks\BaseBlock;
class LaunchTwoCards extends BaseBlock {
    public function __construct( $block , $name ) {
		parent::__construct( $block , $name );
        $payload = [
            'cards' =>[
                [
                    'images' =>[
                        'original' => get_field('lb_block_launch_two_cards_image_left'),
                        'large' => get_field('lb_block_launch_two_cards_image_left'),
                        'medium' => get_field('lb_block_launch_two_cards_image_left'),
                        'small' => get_field('lb_block_launch_two_cards_image_left')
                    ],
                    'infobox' => [
                        'tagline' => get_field('lb_block_launch_two_cards_tagline_left'),
                        'title' => get_field('lb_block_launch_two_cards_title_left'),
                        'subtitle' => get_field('lb_block_launch_two_cards_subtitle_left'),
                        'paragraph' => get_field('lb_block_launch_two_cards_paragraph_left'),
                        // 'cta' => array_merge( get_field('lb_block_launch_two_cards_btn_left') == "" ? [] : [get_field('lb_block_launch_two_cards_btn_left')] ,['variants' => [get_field('lb_block_launch_two_cards_btn_variants_left')]])
                    ]
                   
                ],
                [
                    'images' =>[
                        'original' => get_field('lb_block_launch_two_cards_image_right'),
                        'large' => get_field('lb_block_launch_two_cards_image_right'),
                        'medium' => get_field('lb_block_launch_two_cards_image_right'),
                        'small' => get_field('lb_block_launch_two_cards_image_right')
                    ],
                    'infobox' => [
                        'tagline' => get_field('lb_block_launch_two_cards_tagline_right'),
                        'title' => get_field('lb_block_launch_two_cards_title_right'),
                        'subtitle' => get_field('lb_block_launch_two_cards_subtitle_right'),
                        'paragraph' => get_field('lb_block_launch_two_cards_paragraph_right'),
                        // 'cta' => array_merge( get_field('lb_block_launch_two_cards_btn_right') == "" ? [] : [get_field('lb_block_launch_two_cards_btn_right')],['variants' => [get_field('lb_block_launch_two_cards_btn_variants_right')]])
                    ]
                    
                ]

            ]
        ];
        if (get_field('lb_block_launch_two_cards_btn_left') != "") {
             $payload['cards'][0]['infobox']['cta'] = array_merge (  (array)get_field('lb_block_launch_two_cards_btn_left') ,['variants' => [get_field('lb_block_launch_two_cards_btn_variants_left')]]);  
        }
        if (get_field('lb_block_launch_two_cards_btn_right') != "") {
            $payload['cards'][1]['infobox']['cta'] = array_merge (  (array)get_field('lb_block_launch_two_cards_btn_right') ,['variants' => [get_field('lb_block_launch_two_cards_btn_variants_right')]]);  
       }
        $this->setContext($payload);
        $this->addInfobox();      
    }
}

<?php
require_once(__DIR__.'/baseBlock.php');
use gutenbergBlocks\BaseBlock;
class OffsetNavs extends BaseBlock {
    private function filldata($name){
        $offsetnav_content = [];
        if (have_rows('lb_block_offsetnav_'.$name.'_content')) {
            while (have_rows('lb_block_offsetnav_'.$name.'_content')) : the_row();
                $item = [];
                if (get_row_layout() == 'text') {
                    $item['type'] = 'text';
                    $item['data']['title'] = get_sub_field('lb_block_offsetnav_'.$name.'_content_text_title');
                    $item['data']['subtitle'] = get_sub_field('lb_block_offsetnav_'.$name.'_content_text_subtitle');
                    $item['data']['text'] = get_sub_field('lb_block_offsetnav_'.$name.'_content_text_paragraph');
        
                } elseif (get_row_layout() == 'table') {
                    $item['type'] = 'table';
                    $item['data']['title'] = get_sub_field('lb_block_offsetnav_'.$name.'_content_table_title');
                    $item['data']['subtitle'] = get_sub_field('lb_block_offsetnav_'.$name.'_content_table_subtitle');
        
                    if (have_rows('lb_block_offsetnav_'.$name.'_content_table_item')) {
                        $item['data']['table'] = [];
                        while (have_rows('lb_block_offsetnav_'.$name.'_content_table_item')) : the_row();
                            $item_table = [];
                            $item_table['title'] = get_sub_field('lb_block_offsetnav_'.$name.'_content_table_item_title');
                            $item_table['value'] = get_sub_field('lb_block_offsetnav_'.$name.'_content_table_item_value');
                            $item['data']['table'][] = $item_table;
                        endwhile;
                    }
                }
        
                $offsetnav_content[] = $item;
            endwhile;
        }
        return $offsetnav_content;
    }

    public function __construct( $block , $name ) {
		parent::__construct( $block , $name );
     

        $payload = [
            'title' =>  get_field('lb_block_offsetnav_title'),
            'subtitle' => get_field('lb_block_offsetnav_subtitle'),
            'paragraph' => get_field('lb_block_offsetnav_paragraph'),
            'items' => [
                'description' => [
                    'active' => get_field('lb_block_offsetnav_state_description'), // true, false
                    'id' => 'lb-offsetnav-product-description',
                    'title' => __('Descrizione e inci', 'labo-suisse-theme'),
                    'data' => $this->filldata('description')
                ],
                'technology' => [
                    'active' => get_field('lb_block_offsetnav_state_technology'), // true, false
                    'id' => 'lb-offsetnav-product-technology',
                    'title' => __('Tecnologia', 'labo-suisse-theme'),
                    'data' => $this->filldata('technology')
                ],
                'use_cases' => [
                    'active' => get_field('lb_block_offsetnav_state_usecases'), // true, false
                    'id' => 'lb-offsetnav-product-usecases',
                    'title' => __('Modalità di utilizzo', 'labo-suisse-theme'),
                    'data' => $this->filldata('usecases')
                ],
                'patents' => [
                    'active' => get_field('lb_block_offsetnav_state_patents'), // true, false
                    'id' => 'lb-offsetnav-product-patents',
                    'title' => __('Brevetti', 'labo-suisse-theme'),
                    'data' => $this->filldata('patents')
                ],
                'faq' => [
                    'active' => get_field('lb_block_offsetnav_state_faq'), // true, false
                    'id' => 'lb-offsetnav-product-faq',
                    'title' => __('Domande frequenti', 'labo-suisse-theme'),
                    'data' => $this->filldata('faq')
                ],
                'reviews' => [
                    'active' => get_field('lb_block_offsetnav_state_reviews'), // true, false
                    'id' => 'lb-offsetnav-product-reviews',
                    'title' => __('Recensioni', 'labo-suisse-theme'),
                    'data' => $this->filldata('reviews')
                ]
            ]
        ];      
        $this->setContext($payload);
    }
}


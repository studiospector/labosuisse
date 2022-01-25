<?php
require_once(__DIR__.'/baseBlock.php');
use gutenbergBlocks\BaseBlock;
class OffsetNavs extends BaseBlock {
    private function fillContext(){
        if (have_rows('lb_block_offsetnav_reviews_content')) {
            $offsetnav_content = [];
            while (have_rows('lb_block_offsetnav_reviews_content')) : the_row();
                $item = [];
        
                if (get_row_layout() == 'text') {
                    $item['type'] = 'text';
                    $item['data']['title'] = get_sub_field('lb_block_offsetnav_reviews_content_text_title');
                    $item['data']['subtitle'] = get_sub_field('lb_block_offsetnav_reviews_content_text_subtitle');
                    $item['data']['text'] = get_sub_field('lb_block_offsetnav_reviews_content_text_paragraph');
        
                } elseif (get_row_layout() == 'table') {
                    $item['type'] = 'table';
                    $item['data']['title'] = get_sub_field('lb_block_offsetnav_reviews_content_table_title');
                    $item['data']['subtitle'] = get_sub_field('lb_block_offsetnav_reviews_content_table_subtitle');
        
                    if (have_rows('lb_block_offsetnav_reviews_content_table_item')) {
                        $item['data']['table'] = [];
                        while (have_rows('lb_block_offsetnav_reviews_content_table_item')) : the_row();
                            $item_table = [];
                            $item_table['title'] = get_sub_field('lb_block_offsetnav_reviews_content_table_item_title');
                            $item_table['value'] = get_sub_field('lb_block_offsetnav_reviews_content_table_item_value');
                            $item['data']['table'][] = $item_table;
                        endwhile;
                    }
                }
        
                $offsetnav_content[] = $item;
            endwhile;
        }
    }

    public function __construct( $block , $name ) {
		parent::__construct( $block , $name );
     

        $payload = [
            'title' => 'Efficacia provata<br>nel 100% dei soggetti testati*',
            'subtitle' => 'Da + 7 a + 41 nuovi capelli in 1,8 cm² **',
            'paragraph' => '* Risultato dopo 4 mesi di test clinico/strumentale in vivo in doppio cieco, randomizzati e controllati con placebo su 46 soggetti (23 trattati con Crescina HFSC e 23 con placebo). I soggetti maschi presentavano diradamento di grado II, III, III vertice e IV della scala Hamilton-Norwood.<br>**Tutti i soggetti hanno registrato risultati positivi da un minimo di +7 a un massimo di +41 nuovi capelli in crescita in un’area soggetta a conteggio elettronico di 1,8 cm². I risultati del test sono statisticamente significativi.',
            'items' => [
                'description' => [
                    'active' => true, // true, false
                    'id' => 'lb-offsetnav-product-description',
                    'title' => __('Descrizione e inci', 'labo-suisse-theme'),
                    'data' => [
        
                    ]
                ],
                'technology' => [
                    'active' => true, // true, false
                    'id' => 'lb-offsetnav-product-technology',
                    'title' => __('Tecnologia', 'labo-suisse-theme'),
                ],
                'use_cases' => [
                    'active' => true, // true, false
                    'id' => 'lb-offsetnav-product-usecases',
                    'title' => __('Modalità di utilizzo', 'labo-suisse-theme'),
                ],
                'patents' => [
                    'active' => true, // true, false
                    'id' => 'lb-offsetnav-product-patents',
                    'title' => __('Brevetti', 'labo-suisse-theme'),
                ],
                'faq' => [
                    'active' => true, // true, false
                    'id' => 'lb-offsetnav-product-faq',
                    'title' => __('Domande frequenti', 'labo-suisse-theme'),
                ],
                'reviews' => [
                    'active' => true, // true, false
                    'id' => 'lb-offsetnav-product-reviews',
                    'title' => __('Recensioni', 'labo-suisse-theme'),
                    'data' => $offsetnav_content
                ]
            ]
        ];
        $this->setContext($payload);
    }
}


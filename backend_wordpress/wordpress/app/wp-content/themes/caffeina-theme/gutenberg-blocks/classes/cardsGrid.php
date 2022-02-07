<?php
require_once(__DIR__.'/baseBlock.php');
use gutenbergBlocks\BaseBlock;
class cardsGrid extends BaseBlock {

    // {
    //     title: 'Le ultime novità da labo magazine',
    //     cta: {
    //         title: "Vai a news e media",
    //         url: '#',
    //         variants: ['tertiary'],
    //     },
    //     items: [
    //         {
    //             images: {
    //                 original: '/assets/images/card-img-6.jpg',
    //                 large: '/assets/images/card-img-6.jpg',
    //                 medium: '/assets/images/card-img-6.jpg',
    //                 small: '/assets/images/card-img-6.jpg'
    //             },
    //             date: '00/00/00',
    //             infobox: {
    //                 subtitle: 'La più grande community di beauty lover ha testato Labo',
    //                 paragraph: 'Incipit del contenuto editoriale. Può essere parte dell’articolo originale oppure un’introduzione. Lorem ipsum dolor sit amet.',
    //                 cta: {
    //                     url: '#',
    //                     title: 'Leggi l’articolo',
    //                     iconEnd: { name: 'arrow-right' },
    //                     variants: ['quaternary']
    //                 }
    //             },
    //             variants: ['type-2'],
    //         },
    //         {
    //             images: {
    //                 original: '/assets/images/card-img-6.jpg',
    //                 large: '/assets/images/card-img-6.jpg',
    //                 medium: '/assets/images/card-img-6.jpg',
    //                 small: '/assets/images/card-img-6.jpg'
    //             },
    //             date: '00/00/00',
    //             infobox: {
    //                 subtitle: 'Dopo l’estate, una cura per la pelle a tutto ossigeno: arriva Oxy-Treat',
    //                 paragraph: 'Incipit del contenuto editoriale. Può essere parte dell’articolo originale oppure un’introduzione. Lorem ipsum dolor sit amet.',
    //                 cta: {
    //                     url: '#',
    //                     title: 'Leggi l’articolo',
    //                     iconEnd: { name: 'arrow-right' },
    //                     variants: ['quaternary']
    //                 }
    //             },
    //             variants: ['type-2'],
    //         },
    //         {
    //             images: {
    //                 original: '/assets/images/card-img-6.jpg',
    //                 large: '/assets/images/card-img-6.jpg',
    //                 medium: '/assets/images/card-img-6.jpg',
    //                 small: '/assets/images/card-img-6.jpg'
    //             },
    //             date: '00/00/00',
    //             infobox: {
    //                 subtitle: 'La formazione online firmata Labo',
    //                 paragraph: 'Incipit del contenuto editoriale. Può essere parte dell’articolo originale oppure un’introduzione. Lorem ipsum dolor sit amet.',
    //                 cta: {
    //                     url: '#',
    //                     title: 'Leggi l’articolo',
    //                     iconEnd: { name: 'arrow-right' },
    //                     variants: ['quaternary']
    //                 }
    //             },
    //             variants: ['type-2'],
    //         },
    //         {
    //             images: {
    //                 original: '/assets/images/card-img-6.jpg',
    //                 large: '/assets/images/card-img-6.jpg',
    //                 medium: '/assets/images/card-img-6.jpg',
    //                 small: '/assets/images/card-img-6.jpg'
    //             },
    //             date: '00/00/00',
    //             infobox: {
    //                 subtitle: 'Titolo del contenuto editoriale che andrà nella sezione News',
    //                 paragraph: 'Incipit del contenuto editoriale. Può essere parte dell’articolo originale oppure un’introduzione. Lorem ipsum dolor sit amet.',
    //                 cta: {
    //                     url: '#',
    //                     title: 'Leggi l’articolo',
    //                     iconEnd: { name: 'arrow-right' },
    //                     variants: ['quaternary']
    //                 }
    //             },
    //             variants: ['type-2'],
    //         },
    //     ],
    // }


    public function __construct( $block , $name ) {
		parent::__construct( $block , $name );
        $items = [];
        if( have_rows('lb_block_cards_grid_carousel') ) {
            $items = [];
            while( have_rows('lb_block_cards_grid_carousel') ) : the_row();
                $cta = [];
                if (get_sub_field('lb_block_cards_grid_carousel_infobox_btn') != "") {
                    $cta = array_merge (  (array)get_sub_field('lb_block_cards_grid_carousel_infobox_btn') ,['variants' => [get_sub_field('lb_block_cards_grid_carousel_infobox_btn_variants')]]);  
                }
                $items[] = [
                    'images' => [
                        'original' => get_sub_field('lb_block_cards_grid_carousel_img'),
                        'large' => get_sub_field('lb_block_cards_grid_carousel_img'),
                        'medium' => get_sub_field('lb_block_cards_grid_carousel_img'),
                        'small' => get_sub_field('lb_block_cards_grid_carousel_img')
                    ],
                    'noContainer' => true,
                    'infobox' => [
                        'tagline' => get_sub_field('lb_block_cards_grid_carousel_infobox_tagline'),
                        'title' => get_sub_field('lb_block_cards_grid_carousel_infobox_title'),
                        'subtitle' => get_sub_field('lb_block_cards_grid_carousel_infobox_subtitle'),
                        'paragraph' => get_sub_field('lb_block_cards_grid_carousel_infobox_paragraph'),
                        'cta' => $cta
                    ],
                    'variants' => [get_field('lb_block_cards_grid_cards_variants')],
                ];
                
            endwhile;

        
        }
        $ctaCards =[];
        if (get_field('lb_block_cards_grid_btn') != "") {
            $ctaCards = array_merge (  (array)get_field('lb_block_cards_grid_btn') ,['variants' => [get_field('lb_block_cards_grid_btn_variants')]]);
            
        }
        $payload = [
            'title' =>  get_field('lb_block_cards_grid_title'),
            'cta' => $ctaCards,
            'items' => $items,
        ];
        echo "<pre>";
        var_dump(",adonna troia");
        $this->setContext($payload);
    }

}


<?php
require_once(__DIR__.'/baseBlock.php');
use gutenbergBlocks\BaseBlock;
$block_carousel_posts = new BaseBlock($block);


// // const dataFull = {
// //     leftCard: {
// //         // color: '#f5f5f5',
// //         infobox: {
// //             subtitle: 'Titolo di due righe come massimo',
// //             paragraph: 'Nella sezione News, puoi trovare i nostri contenuti editoriali: sfoglia gli articoli, prendi ispirazione e lasciati guidare dal team Labo.',
// //             cta: {
// //                 href: '#',
// //                 label: 'Vai a news',
// //                 variants: ['secondary']
// //             }
// //         },
// //         variants: ['type-8']
// //     },
// //     items: [
// //         {
// //             images: {
// //                 original: '/assets/images/card-img-6.jpg',
// //                 large: '/assets/images/card-img-6.jpg',
// //                 medium: '/assets/images/card-img-6.jpg',
// //                 small: '/assets/images/card-img-6.jpg'
// //             },
// //             date: '00/00/00',
// //             infobox: {
// //                 subtitle: 'Titolo del contenuto editoriale che andrà nella sezione News',
// //                 paragraph: 'Incipit del contenuto editoriale. Può essere parte dell’articolo originale oppure un’introduzione. Lorem ipsum dolor sit amet.',
// //                 cta: {
// //                     href: '#',
// //                     label: 'Leggi l’articolo',
// //                     iconEnd: { name: 'arrow-right' },
// //                     variants: ['quaternary']
// //                 }
// //             },
// //             variants: ['type-2']
// //         },
// //         {
// //             images: {
// //                 original: '/assets/images/card-img-6.jpg',
// //                 large: '/assets/images/card-img-6.jpg',
// //                 medium: '/assets/images/card-img-6.jpg',
// //                 small: '/assets/images/card-img-6.jpg'
// //             },
// //             date: '00/00/00',
// //             infobox: {
// //                 subtitle: 'Titolo del contenuto editoriale che andrà nella sezione News',
// //                 paragraph: 'Incipit del contenuto editoriale. Può essere parte dell’articolo originale oppure un’introduzione. Lorem ipsum dolor sit amet.',
// //                 cta: {
// //                     href: '#',
// //                     label: 'Leggi l’articolo',
// //                     iconEnd: { name: 'arrow-right' },
// //                     variants: ['quaternary']
// //                 }
// //             },
// //             variants: ['type-2']
// //         },
// //         {
// //             images: {
// //                 original: '/assets/images/card-img-6.jpg',
// //                 large: '/assets/images/card-img-6.jpg',
// //                 medium: '/assets/images/card-img-6.jpg',
// //                 small: '/assets/images/card-img-6.jpg'
// //             },
// //             date: '00/00/00',
// //             infobox: {
// //                 subtitle: 'Titolo del contenuto editoriale che andrà nella sezione News',
// //                 paragraph: 'Incipit del contenuto editoriale. Può essere parte dell’articolo originale oppure un’introduzione. Lorem ipsum dolor sit amet.',
// //                 cta: {
// //                     href: '#',
// //                     label: 'Leggi l’articolo',
// //                     iconEnd: { name: 'arrow-right' },
// //                     variants: ['quaternary']
// //                 }
// //             },
// //             variants: ['type-2']
// //         },
// //     ],
// //     variants: ['full']
// // }
// Carousel data
if( have_rows('lb_block_carousel_posts') ) {
    $items = [];
    while( have_rows('lb_block_carousel_posts') ) : the_row();
        $items[] = [
            'images' => [
                'original' => get_sub_field('lb_block_carousel_posts_img'),
                'large' => get_sub_field('lb_block_carousel_posts_img'),
                'medium' => get_sub_field('lb_block_carousel_posts_img'),
                'small' => get_sub_field('lb_block_carousel_posts_img')
            ],
            'date'=> get_sub_field('lb_block_carousel_posts_date'),
          //  'infoboxPosX' => get_sub_field('lb_block_carousel_posts_infoboxposx'),
          //  'infoboxPosY' => get_sub_field('lb_block_carousel_posts_infoboxposy'),
           // 'container' => get_sub_field('lb_block_carousel_posts_container'),
            'variants' => ['type-2'],
            'infobox' => [
                'tagline' => get_sub_field('lb_block_infobox_tagline'),
                'title' => get_sub_field('lb_block_infobox_title'),
                'subtitle' => get_sub_field('lb_block_infobox_subtitle'),
                'paragraph' => get_sub_field('lb_block_infobox_paragraph'),
                'cta' => [
                    'title' =>  'test',
                    'url' => get_permalink(get_sub_field('lb_block_infobox_btn')),
                    'target' => '',
                    'buttonVariants' => [get_sub_field('lb_block_infobox_btn_variants')]
                ]
            ]
        ];

    endwhile;
}

$payload= [
    'leftCard' => [],
    'items' => $items,
    'variants' => ['type-8']
];
$block_carousel_posts->addInfobox($payload['leftCard']);
$block_carousel_posts->setContext($payload);
//  echo '<pre>';
// var_dump( $payload );
// die;
 $block_carousel_posts->render();





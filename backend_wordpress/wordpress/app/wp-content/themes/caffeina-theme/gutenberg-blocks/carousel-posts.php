

<?php
require_once(__DIR__ . '/baseBlock.php');

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
if (have_rows('lb_block_carousel_posts')) {
    $items = [];
    while (have_rows('lb_block_carousel_posts')) : the_row();
        $cf_post = get_sub_field('lb_block_carousel_posts_item');
        if (!is_null($cf_post)) {
            $items[] = [
                'images' => [
                    'original' => wp_get_attachment_url(get_post_thumbnail_id($cf_post->ID)),
                    'large' => wp_get_attachment_url(get_post_thumbnail_id($cf_post->ID)),
                    'medium' => wp_get_attachment_url(get_post_thumbnail_id($cf_post->ID)),
                    'small' => wp_get_attachment_url(get_post_thumbnail_id($cf_post->ID))
                ],
                'date' => $cf_post->post_date,
                'variants' => ['type-2'],
                'infobox' => [
                    'subtitle' => $cf_post->title,
                    'paragraph' => $cf_post->post_excerpt,
                    'cta' => [
                        'title' => "Leggi l'articolo",
                        'url' => get_permalink($cf_post->ID),
                        'iconEnd' => ['name' => 'arrow-right'],
                        'variants' => ['quaternary']
                    ]
                ]
            ];
        }
    endwhile;
}

$payload = [
    'leftCard' => ['variants' => ['type-8']],
    'items' => $items,
    'variants' => ['two-posts']
];
$block_carousel_posts->addInfobox($payload['leftCard']);
$block_carousel_posts->setContext($payload);

$block_carousel_posts->render();

<?php
  
// // var_dump($payl);
// die;
/**
 * The Template for displaying all single products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$context = Timber::context();

$context['post'] = Timber::get_post();
$product = wc_get_product($context['post']->ID);
$context['product'] = $product;

ob_start();
wc_product_class( 'single-product-details container', $product );
$context['product_classes'] = ob_get_clean();

// Get related products
$related_limit = wc_get_loop_prop('columns');
$related_ids = wc_get_related_products($context['post']->id, $related_limit);
$context['related_products'] = Timber::get_posts($related_ids);

// Banner
require_once(WP_CONTENT_DIR.'/themes/caffeina-theme/gutenberg-blocks/classes/hero.php');
$block_hero = new Hero(null,'hero');

$context['hero'] = $block_hero->payload;
// $context['hero'] = [
//     'images' => [
//         'original' => get_template_directory_uri() . '/assets/images/hero-crescina-right.jpg',
//         'large' => get_template_directory_uri() . '/assets/images/hero-crescina-right.jpg',
//         'medium' => get_template_directory_uri() . '/assets/images/hero-crescina-right.jpg',
//         'small' => get_template_directory_uri() . '/assets/images/hero-crescina-right.jpg'
//     ],
//     'infoboxPosX' => 'left',
//     'infoboxPosY' => 'center',
//     'infobox' => [
//         'subtitle' => 'La risposta specifica al problema del diradamento',
//         'paragraph' => 'Un concentrato di innovazione dedicata alla Ri-Crescita naturale dei capelli.',
//     ],
//     'container' => true,
//     'variants' => ['small']
// ];

// Banner alternate
// $context['banner_alternate'] = [
//     'images' => [
//         'original' => get_template_directory_uri() . '/assets/images/banner-img.jpg',
//         'large' => get_template_directory_uri() . '/assets/images/banner-img.jpg',
//         'medium' => get_template_directory_uri() . '/assets/images/banner-img.jpg',
//         'small' => get_template_directory_uri() . '/assets/images/banner-img.jpg'
//     ],
//     'infobox' => [
//         'tagline' => 'Dosaggi e formati',
//         'subtitle' => 'Sappiamo bene che ogni persona è unica',
//         'paragraph' => 'Per questo, abbiamo studiato formule specifiche - per uomo e per donna - in concentrazioni diversificate dei principi attivi adatte a trattare i differenti gradi di diradamento, da quello iniziale a quello avanzato di calvizie, anche dovuto ad Alopecia Androgenetica.',
//         'variants' => ['alternate'],
//         'cta' =>[
//             'url' =>'#',
//             'title' =>'Lorem Ipsum',
//             'iconEnd' => [ 'name' => 'arrow-right' ],
//             'variants' =>['quaternary']
//         ]
//     ],
//     'imageBig' => false,
//     'variants' => ['infobox-right', 'infobox-centered'], // infobox-left, infobox-right AND infobox-fullheight, infobox-centered, infobox-bottom
// ];
require_once(WP_CONTENT_DIR.'/themes/caffeina-theme/gutenberg-blocks/classes/bannerAlternate.php');
$block_banner_alternate = new BannerAlternate(null,'banner_alternate');

$context['banner_alternate'] = $block_banner_alternate->payload;

// Number List
require_once(WP_CONTENT_DIR.'/themes/caffeina-theme/gutenberg-blocks/classes/numberList.php');
$block_number_list = new NumberList(null,'number_list');

$context['number_list'] = $block_number_list->payload;

// $context['number_list'] = [
//     'images' => [
//         'original' => get_template_directory_uri() . '/assets/images/carousel-hero-img-2.jpg',
//         'large' => get_template_directory_uri() . '/assets/images/carousel-hero-img-2.jpg',
//         'medium' => get_template_directory_uri() . '/assets/images/carousel-hero-img-2.jpg',
//         'small' => get_template_directory_uri() . '/assets/images/carousel-hero-img-2.jpg'
//     ],
//     'numbersList' => [
//         'title' => 'Tre consigli utili',
//         'list' => [
//             [
//                 'title' => '',
//                 'text' => 'Si consiglia un trattamento di almeno 2 mesi.<br>Può essere ripetuto più volte l’anno.',
//             ],
//             [
//                 'title' => '',
//                 'text' => 'Utilizza Crescina sul cuoio capelluto<br>completamente integro, senza escoriazioni.',
//             ],
//             [
//                 'title' => '',
//                 'text' => 'Regola le tue abitudini di detersione:<br>lava i capelli almeno due volte a settimana.',
//             ],
//         ],
//         'variants' => ['vertical']
//     ]
// ];

// Miniatures
$context['miniatures'] = [
    'infobox' =>[
        'title' =>'Come applicare crescina',
        'paragraph' =>'Applica 1 fiala al giorno per 5 giorni consecutivi su capelli asciutti (ad esempio i primi<br>5 giorni della settimana) con 2 giorni di pausa. Non risciacquare. ',
        'cta' =>[
            'url' =>'#',
            'title' =>'Guarda i tutorial',
            'variants' =>['tertiary']
        ]
    ],
    'items' =>[
        [
            'images' =>[
                'original' =>get_template_directory_uri() . '/assets/images/love-labo-img-1.jpg',
                'large' =>get_template_directory_uri() . '/assets/images/love-labo-img-1.jpg',
                'medium' =>get_template_directory_uri() . '/assets/images/love-labo-img-1.jpg',
                'small' =>get_template_directory_uri() . '/assets/images/love-labo-img-1.jpg'
            ],
            'text' =>'Prendere dall’alloggio l’apposito rompifiala in plastica rigida incluso nella confezione.'
        ],
        [
            'images' =>[
                'original' =>get_template_directory_uri() . '/assets/images/love-labo-img-2.jpg',
                'large' =>get_template_directory_uri() . '/assets/images/love-labo-img-2.jpg',
                'medium' =>get_template_directory_uri() . '/assets/images/love-labo-img-2.jpg',
                'small' =>get_template_directory_uri() . '/assets/images/love-labo-img-2.jpg'
            ],
            'text' =>'Inserire il rompifiala sulla fiala, semplicemente appoggiandolo. Stringere tra le dita il rompifiala e rompere la parte alta della fiala facendo una leggera ma decisa pressione.'
        ],
        [
            'images' =>[
                'original' =>get_template_directory_uri() . '/assets/images/love-labo-img-3.jpg',
                'large' =>get_template_directory_uri() . '/assets/images/love-labo-img-3.jpg',
                'medium' =>get_template_directory_uri() . '/assets/images/love-labo-img-3.jpg',
                'small' =>get_template_directory_uri() . '/assets/images/love-labo-img-3.jpg'
            ],
            'text' =>'Prendere dall’alloggio il dosatore con beccuccio.'
        ],
        [
            'images' =>[
                'original' =>get_template_directory_uri() . '/assets/images/love-labo-img-4.jpg',
                'large' =>get_template_directory_uri() . '/assets/images/love-labo-img-4.jpg',
                'medium' =>get_template_directory_uri() . '/assets/images/love-labo-img-4.jpg',
                'small' =>get_template_directory_uri() . '/assets/images/love-labo-img-4.jpg'
            ],
            'text' =>'Inserire l’apposito dosatore con beccuccio sulla fiala aperta.'
        ],
        [
            'images' =>[
                'original' =>get_template_directory_uri() . '/assets/images/love-labo-img-5.jpg',
                'large' =>get_template_directory_uri() . '/assets/images/love-labo-img-5.jpg',
                'medium' =>get_template_directory_uri() . '/assets/images/love-labo-img-5.jpg',
                'small' =>get_template_directory_uri() . '/assets/images/love-labo-img-5.jpg'
            ],
            'text' =>'Togliere il cappuccio del dosatore.'
        ],
        [
            'images' =>[
                'original' =>get_template_directory_uri() . '/assets/images/love-labo-img-6.jpg',
                'large' =>get_template_directory_uri() . '/assets/images/love-labo-img-6.jpg',
                'medium' =>get_template_directory_uri() . '/assets/images/love-labo-img-6.jpg',
                'small' =>get_template_directory_uri() . '/assets/images/love-labo-img-6.jpg'
            ],
            'text' =>'Applicare il preparato sul cuoio capelluto pulito e asciutto, riga per riga, insistendo sulle zone più diradate, facendo attenzione a non farlo colare sul viso. Far penetrare con leggero massaggio.'
        ],
    ],
    'variants' =>['full'], // default, full
];

// Two cards
require_once(WP_CONTENT_DIR.'/themes/caffeina-theme/gutenberg-blocks/classes/launchTwoCards.php');
$block_two_cards = new LaunchTwoCards(null,'two_cards');

$context['two_cards'] = $block_two_cards->payload;

// $context['two_cards'] = [
//     'infobox' => [
//         'subtitle' => 'Individua il trattamento adatto a te',
//         'paragraph' => 'Per conoscere il tuo grado di diradamento e il dosaggio più indicato per te,<br>consulta la tabella.',
//     ],
//     'cards' => [
//         [
//             'images' => [
//                 'original' => get_template_directory_uri() . '/assets/images/banner-img.jpg',
//                 'large' => get_template_directory_uri() . '/assets/images/banner-img.jpg',
//                 'medium' => get_template_directory_uri() . '/assets/images/banner-img.jpg',
//                 'small' => get_template_directory_uri() . '/assets/images/banner-img.jpg'
//             ],
//             'infobox' => [
//                 'subtitle' => 'Scala di diradamento uomo',
//                 'cta' => [
//                     'url' => '#',
//                     'title' => 'Visualizza la scala',
//                     'iconEnd' => [ 'name' => 'arrow-right' ],
//                     'variants' => ['quaternary']
//                 ]
//             ]
//         ],
//         [
//             'images' => [
//                 'original' => get_template_directory_uri() . '/assets/images/banner-img.jpg',
//                 'large' => get_template_directory_uri() . '/assets/images/banner-img.jpg',
//                 'medium' => get_template_directory_uri() . '/assets/images/banner-img.jpg',
//                 'small' => get_template_directory_uri() . '/assets/images/banner-img.jpg'
//             ],
//             'infobox' => [
//                 'subtitle' => 'Scala di diradamento donna',
//                 'cta' => [
//                     'url' => '#',
//                     'title' => 'Visualizza la scala',
//                     'iconEnd' => [ 'name' => 'arrow-right' ],
//                     'variants' => ['quaternary']
//                 ]
//             ]
//         ]
//     ]
// ];

// Image and card
require_once(WP_CONTENT_DIR.'/themes/caffeina-theme/gutenberg-blocks/classes/imageCard.php');
$block_image_and_card = new ImageCard(null,'image_and_card');

$context['image_and_card'] = $block_image_and_card->payload;
// $context['image_and_card'] = [
//     'images' => [
//         'original' => get_template_directory_uri() . '/assets/images/banner-img.jpg',
//         'large' => get_template_directory_uri() . '/assets/images/banner-img.jpg',
//         'medium' => get_template_directory_uri() . '/assets/images/banner-img.jpg',
//         'small' => get_template_directory_uri() . '/assets/images/banner-img.jpg'
//     ],
//     'card' => [
//         'images' => [
//             'original' => get_template_directory_uri() . '/assets/images/carousel-hero-img.jpg',
//             'large' => get_template_directory_uri() . '/assets/images/carousel-hero-img.jpg',
//             'medium' => get_template_directory_uri() . '/assets/images/carousel-hero-img.jpg',
//             'small' => get_template_directory_uri() . '/assets/images/carousel-hero-img.jpg'
//         ],
//         'infobox' => [
//             'subtitle' => 'La tecnologia dietro l’efficacia',
//             'paragraph' => 'Grazie alla Tecnologia Transdermica (Swiss Patent CH 711 466) - ispirata alla metodologia della medicina estetica e brevettata nel 2015 - Labo supera le frontiere della scienza dermo-cosmetica divenendo la prima azienda a sviluppare una nuova tecnica di penetrazione dei principi attivi, senza iniezioni, attraverso epidermide e derma.',
//             'cta' => [
//                 'url' => '#',
//                 'title' => 'Scopri di più',
//                 'iconEnd' => [ 'name' => 'arrow-right' ],
//                 'variants' => ['quaternary']
//             ]
//         ],
//         'variants' => ['type-7']
//     ]
// ];

// Routine
$context['routine'] = [
    'title' => 'Scopri la migliore routine crescina',
    'items' => [
        [
            'text' => 'Inizia con il trattamento per agire sulla struttura stessa del capello lorem ipsum',
            'product' => Timber::get_post(148),
        ],
        [
            'text' => 'Continua con il trattamento agendo anche durante la detersione lorem ipsum',
            'product' => Timber::get_post(263),
        ],
        [
            'text' => 'Affronta il problema da dentro e aggiungi alla tua dieta equilibrata gli integratori lorem ipsum',
            'product' => Timber::get_post(148),
        ],
    ]
];

Timber::render('@PathViews/woo/single-product.twig', $context);

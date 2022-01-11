<?php
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
$context['banner'] = [
    'images' => [
        'original' => '/assets/images/banner-img.jpg',
        'large' => '/assets/images/banner-img.jpg',
        'medium' => '/assets/images/banner-img.jpg',
        'small' => '/assets/images/banner-img.jpg'
    ],
    'infoboxBgColorTransparent' => true, // true, false
    'infoboxTextAlignment' => 'left', // left, right, center
    'infobox' => [
        'tagline' => 'LOREM IPSUM',
        'subtitle' => 'Lorem ipsum dolor sit amet, consectetur',
        'paragraph' => 'Lorem ipsum dolor sit amet, consectetur<br>adipiscing elit, sed do eiusmod tempor incididunt<br>ut labore et dolore magna.',
    ],
    'variants' => ['left'], // left, right, center
];

// Banner alternate
$context['banner_alternate'] = [
    'images' => [
        'original' => '/assets/images/banner-img.jpg',
        'large' => '/assets/images/banner-img.jpg',
        'medium' => '/assets/images/banner-img.jpg',
        'small' => '/assets/images/banner-img.jpg'
    ],
    'infobox' => [
        'tagline' => 'Dosaggi e formati',
        'subtitle' => 'Sappiamo bene che ogni persona è unica',
        'paragraph' => 'Per questo, abbiamo studiato formule specifiche - per uomo e per donna - in concentrazioni diversificate dei principi attivi adatte a trattare i differenti gradi di diradamento, da quello iniziale a quello avanzato di calvizie, anche dovuto ad Alopecia Androgenetica.',
        'variants' => ['alternate'],
    ],
    'imageBig' => false,
    'variants' => ['infobox-right', 'infobox-centered'], // infobox-left, infobox-right AND infobox-fullheight, infobox-centered, infobox-bottom
];

// Number List
$context['number_list'] = [
    'images' => [
        'original' => '/assets/images/carousel-hero-img-2.jpg',
        'large' => '/assets/images/carousel-hero-img-2.jpg',
        'medium' => '/assets/images/carousel-hero-img-2.jpg',
        'small' => '/assets/images/carousel-hero-img-2.jpg'
    ],
    'numbersList' => [
        'title' => 'Tre consigli utili',
        'list' => [
            [
                'title' => '',
                'text' => 'Si consiglia un trattamento di almeno 2 mesi.<br>Può essere ripetuto più volte l’anno.',
            ],
            [
                'title' => '',
                'text' => 'Utilizza Crescina sul cuoio capelluto<br>completamente integro, senza escoriazioni.',
            ],
            [
                'title' => '',
                'text' => 'Regola le tue abitudini di detersione:<br>lava i capelli almeno due volte a settimana.',
            ],
        ],
        'variants' => ['vertical']
    ]
];

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
                'original' =>'/assets/images/love-labo-img-1.jpg',
                'large' =>'/assets/images/love-labo-img-1.jpg',
                'medium' =>'/assets/images/love-labo-img-1.jpg',
                'small' =>'/assets/images/love-labo-img-1.jpg'
            ],
            'text' =>'Prendere dall’alloggio l’apposito rompifiala in plastica rigida incluso nella confezione.'
        ],
        [
            'images' =>[
                'original' =>'/assets/images/love-labo-img-2.jpg',
                'large' =>'/assets/images/love-labo-img-2.jpg',
                'medium' =>'/assets/images/love-labo-img-2.jpg',
                'small' =>'/assets/images/love-labo-img-2.jpg'
            ],
            'text' =>'Inserire il rompifiala sulla fiala, semplicemente appoggiandolo. Stringere tra le dita il rompifiala e rompere la parte alta della fiala facendo una leggera ma decisa pressione.'
        ],
        [
            'images' =>[
                'original' =>'/assets/images/love-labo-img-3.jpg',
                'large' =>'/assets/images/love-labo-img-3.jpg',
                'medium' =>'/assets/images/love-labo-img-3.jpg',
                'small' =>'/assets/images/love-labo-img-3.jpg'
            ],
            'text' =>'Prendere dall’alloggio il dosatore con beccuccio.'
        ],
        [
            'images' =>[
                'original' =>'/assets/images/love-labo-img-4.jpg',
                'large' =>'/assets/images/love-labo-img-4.jpg',
                'medium' =>'/assets/images/love-labo-img-4.jpg',
                'small' =>'/assets/images/love-labo-img-4.jpg'
            ],
            'text' =>'Inserire l’apposito dosatore con beccuccio sulla fiala aperta.'
        ],
        [
            'images' =>[
                'original' =>'/assets/images/love-labo-img-5.jpg',
                'large' =>'/assets/images/love-labo-img-5.jpg',
                'medium' =>'/assets/images/love-labo-img-5.jpg',
                'small' =>'/assets/images/love-labo-img-5.jpg'
            ],
            'text' =>'Togliere il cappuccio del dosatore.'
        ],
        [
            'images' =>[
                'original' =>'/assets/images/love-labo-img-6.jpg',
                'large' =>'/assets/images/love-labo-img-6.jpg',
                'medium' =>'/assets/images/love-labo-img-6.jpg',
                'small' =>'/assets/images/love-labo-img-6.jpg'
            ],
            'text' =>'Applicare il preparato sul cuoio capelluto pulito e asciutto, riga per riga, insistendo sulle zone più diradate, facendo attenzione a non farlo colare sul viso. Far penetrare con leggero massaggio.'
        ],
    ],
    'variants' =>['full'], // default, full
];

// Miniatures
$context['two_cards'] = [
    'infobox' => [
        'subtitle' => 'Individua il trattamento adatto a te',
        'paragraph' => 'Per conoscere il tuo grado di diradamento e il dosaggio più indicato per te,<br>consulta la tabella.',
    ],
    'cards' => [
        [
            'images' => [
                'original' => '/assets/images/banner-img.jpg',
                'large' => '/assets/images/banner-img.jpg',
                'medium' => '/assets/images/banner-img.jpg',
                'small' => '/assets/images/banner-img.jpg'
            ],
            'infobox' => [
                'subtitle' => 'Scala di diradamento uomo',
                'cta' => [
                    'url' => '#',
                    'title' => 'Visualizza la scala',
                    'iconEnd' => [ 'name' => 'arrow-right' ],
                    'variants' => ['quaternary']
                ]
            ]
        ],
        [
            'images' => [
                'original' => '/assets/images/banner-img.jpg',
                'large' => '/assets/images/banner-img.jpg',
                'medium' => '/assets/images/banner-img.jpg',
                'small' => '/assets/images/banner-img.jpg'
            ],
            'infobox' => [
                'subtitle' => 'Scala di diradamento donna',
                'cta' => [
                    'url' => '#',
                    'title' => 'Visualizza la scala',
                    'iconEnd' => [ 'name' => 'arrow-right' ],
                    'variants' => ['quaternary']
                ]
            ]
        ]
    ]
];

Timber::render('@PathViews/woo/single-product.twig', $context);

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

// Banner -> hero
require_once(WP_CONTENT_DIR.'/themes/caffeina-theme/gutenberg-blocks/classes/hero.php');
$block_hero = new Hero(null,'hero');
$context['hero'] = $block_hero->payload;

require_once(WP_CONTENT_DIR.'/themes/caffeina-theme/gutenberg-blocks/classes/bannerAlternate.php');
$block_banner_alternate = new BannerAlternate(null,'banner_alternate');

$context['banner_alternate'] = $block_banner_alternate->payload;

// Number List
require_once(WP_CONTENT_DIR.'/themes/caffeina-theme/gutenberg-blocks/classes/numberListImage.php');
$block_numbers = new NumberListImage(null, 'number-list-with-image');
$context['number_list'] = $block_numbers->payload;

// Miniatures -> love labo
require_once(WP_CONTENT_DIR.'/themes/caffeina-theme/gutenberg-blocks/classes/loveLabo.php');
$block_love_labo = new LoveLabo(null, "block-love-labo");
$context['miniatures'] = $block_love_labo->payload;

//Two cards
require_once(WP_CONTENT_DIR.'/themes/caffeina-theme/gutenberg-blocks/classes/launchTwoCards.php');
$block_launch_two_cards = new LaunchTwoCards(null, "block-two-cards");
$context['two_cards'] = $block_launch_two_cards->payload;

// Image and card
require_once(WP_CONTENT_DIR.'/themes/caffeina-theme/gutenberg-blocks/classes/imageCard.php');
$block_image_card = new ImageCard(null,'block-image-card');
$context['image_and_card'] = $block_image_card->payload;


// Routine
require_once(WP_CONTENT_DIR.'/themes/caffeina-theme/gutenberg-blocks/classes/routine.php');
$block_routine = new Routine(null,'block-routine');
$context['routine'] = $block_routine->payload;

// $context['routine'] = [
//     'title' => 'Scopri la migliore routine crescina',
//     'items' => [
//         [
//             'text' => 'Inizia con il trattamento per agire sulla struttura stessa del capello lorem ipsum',
//             'product' => Timber::get_post(148),
//         ],
//         [
//             'text' => 'Continua con il trattamento agendo anche durante la detersione lorem ipsum',
//             'product' => Timber::get_post(263),
//         ],
//         [
//             'text' => 'Affronta il problema da dentro e aggiungi alla tua dieta equilibrata gli integratori lorem ipsum',
//             'product' => Timber::get_post(148),
//         ],
//     ]
// ];



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

// Modals
$context['offset_navs'] = [
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




Timber::render('@PathViews/woo/single-product.twig', $context);

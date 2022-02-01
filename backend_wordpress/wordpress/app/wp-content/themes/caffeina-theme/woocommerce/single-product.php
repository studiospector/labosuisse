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

$context['isInStock'] = $product->is_in_stock();

// Sticky Header
$context['stickyHeader']['price'] = $product->get_price_html();
$context['stickyHeader']['cartUrlLabel'] = __('Aggiungi al carrello', 'labo-suisse-theme');

ob_start();
wc_product_class( 'single-product-details container', $product );
$context['product_classes'] = ob_get_clean();

// Get related products
$related_limit = wc_get_loop_prop('columns');
$related_ids = wc_get_related_products($context['post']->id, $related_limit);
$context['related_products'] = Timber::get_posts($related_ids);

// // Banner -> hero
require_once(WP_CONTENT_DIR.'/themes/caffeina-theme/gutenberg-blocks/classes/hero.php');
$block_hero = new Hero(null,'hero');
$context['hero'] = $block_hero->getPayload();

require_once(WP_CONTENT_DIR.'/themes/caffeina-theme/gutenberg-blocks/classes/bannerAlternate.php');
$block_banner_alternate = new BannerAlternate(null,'banner_alternate');

$context['banner_alternate'] = $block_banner_alternate->getPayload();

// Number List
require_once(WP_CONTENT_DIR.'/themes/caffeina-theme/gutenberg-blocks/classes/numberListImage.php');
$block_numbers = new NumberListImage(null, 'number-list-with-image');
$context['number_list'] = $block_numbers->getPayload();

// Miniatures -> love labo
require_once(WP_CONTENT_DIR.'/themes/caffeina-theme/gutenberg-blocks/classes/loveLabo.php');
$block_love_labo = new LoveLabo(null, "block-love-labo");
$context['miniatures'] = $block_love_labo->getPayload();

//Two cards
require_once(WP_CONTENT_DIR.'/themes/caffeina-theme/gutenberg-blocks/classes/launchTwoCards.php');
$block_launch_two_cards = new LaunchTwoCards(null, "block-launch-two-cards");
$context['two_cards'] = $block_launch_two_cards->getPayload();

// Image and card
require_once(WP_CONTENT_DIR.'/themes/caffeina-theme/gutenberg-blocks/classes/imageCard.php');
$block_image_card = new ImageCard(null,'block-image-card');
$context['image_and_card'] = $block_image_card->getPayload();


// Routine
require_once(WP_CONTENT_DIR.'/themes/caffeina-theme/gutenberg-blocks/classes/routine.php');
$block_routine = new Routine(null,'block-routine');
$context['routine'] = $block_routine->getPayload();

//modal
require_once(WP_CONTENT_DIR.'/themes/caffeina-theme/gutenberg-blocks/classes/offsetNavs.php');
$block_offsetNavs = new OffsetNavs(null,'block-offset-navs');
$context['offset_navs'] = $block_offsetNavs->getPayload();





Timber::render('@PathViews/woo/single-product.twig', $context);

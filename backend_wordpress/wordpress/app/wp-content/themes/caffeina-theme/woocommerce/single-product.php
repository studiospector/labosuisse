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

use Caffeina\LaboSuisse\Blocks\Hero;
use Caffeina\LaboSuisse\Blocks\ImageCard;
use Caffeina\LaboSuisse\Blocks\LaunchTwoCards;
use Caffeina\LaboSuisse\Blocks\BannerAlternate;
use Caffeina\LaboSuisse\Blocks\LoveLabo;
use Caffeina\LaboSuisse\Blocks\NumberListImage;
use Caffeina\LaboSuisse\Blocks\OffsetNavs;
use Caffeina\LaboSuisse\Blocks\Routine;
use Caffeina\LaboSuisse\Blocks\InformationBoxes;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

$context = Timber::context();

// Base Post
$context['post'] = Timber::get_post();

// Base Product
$product = wc_get_product($context['post']->ID);

if($product->get_catalog_visibility() == 'hidden') {
    wp_safe_redirect('/404', 301);
}

$context['product'] = $product;

// Sticky Header
$context['stickyHeader']['show'] = true;
if (!$product->is_in_stock() || !$product->is_purchasable()) {
    $context['stickyHeader']['show'] = false;
}
$context['stickyHeader']['price'] = $product->get_price_html();
$context['stickyHeader']['cartUrlLabel'] = __('Aggiungi al carrello', 'labo-suisse-theme');

// Post classes
ob_start();
wc_product_class('single-product-details container', $product);
$context['product_classes'] = ob_get_clean();

// Get related products
// $related_limit = wc_get_loop_prop('columns');
// $related_ids = wc_get_related_products($context['post']->id, $related_limit);
// $context['related_products'] = Timber::get_posts($related_ids);

// Hero
$block_hero = new Hero(null, 'hero', 'lb-block-hero');
$context['hero'] = $block_hero->getPayload();

// Offset Navs
$block_offsetNavs = new OffsetNavs(null, 'block-offset-navs', 'lb-block-offset-navs');
$context['offset_navs'] = $block_offsetNavs->getPayload();

// Banner alternate
$block_banner_alternate = new BannerAlternate(null, 'banner_alternate', 'lb-block-banner-alternate');
$context['banner_alternate'] = $block_banner_alternate->getPayload();

// Two Cards
$block_launch_two_cards = new LaunchTwoCards(null, "block-launch-two-cards", 'lb-block-launch-two-cards');
$context['two_cards'] = $block_launch_two_cards->getPayload();

// Image and Card
$block_image_card = new ImageCard(null, 'block-image-card', 'lb-block-image-card');
$context['image_and_card'] = $block_image_card->getPayload();

// Numbers List
$block_numbers = new NumberListImage(null, 'number-list-with-image', 'lb-block-number-list-with-image');
$context['number_list'] = $block_numbers->getPayload();

// Love labo
$block_love_labo = new LoveLabo(null, "block-love-labo", 'lb-block-love-labo');
$context['miniatures'] = $block_love_labo->getPayload();

// Routine
$block_routine = new Routine(null, 'block-routine', 'lb-block-routine');
$context['routine'] = $block_routine->getPayload();

// Shipping info
$block_shipping_info = new InformationBoxes(null, 'information-boxes', 'lb-information-boxes');
$context['shipping_info'] = $block_shipping_info->getPayload();



Timber::render('@PathViews/woo/single-product.twig', $context);

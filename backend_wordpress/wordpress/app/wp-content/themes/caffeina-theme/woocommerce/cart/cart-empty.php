<?php
/**
 * Empty cart page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart-empty.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.0
 */

defined( 'ABSPATH' ) || exit;

$image = get_field('lb_shop_page_empty_image', 'option');
$title = get_field('lb_shop_page_empty_title', 'option');
$text = get_field('lb_shop_page_empty_text', 'option');
$back_to_shop = get_field('lb_shop_page_empty_link', 'option');

echo '<div class="text-center">';
    Timber::render('@PathViews/components/infobox.twig', [
        'image' => $image,
        'subtitle' => $title,
        'paragraph' => $text,
        'cta' => array_merge([
            'class' => 'wc-backward',
            'variants' => ['primary'],
        ], $back_to_shop ?? [])
    ]);
echo '</div>';
?>

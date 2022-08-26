<?php
/**
 * My Account Dashboard
 *
 * Shows the first intro screen on the account dashboard.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/dashboard.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 4.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$allowed_html = array(
	'a' => array(
		'href' => array(),
	),
);

/* translators: 1: Orders URL 2: Address URL 3: Account URL. */
$dashboard_desc = __( 'Dal tuo account Labo Suisse puoi visualizzare <a href="%1$s">i tuoi ordini,</a> gestire il tuo <a href="%2$s">indirizzo di fatturazione,</a> modificare <a href="%3$s">la password e i dettagli del tuo account.</a>', 'labo-suisse-theme' );
if ( wc_shipping_enabled() ) {
    /* translators: 1: Orders URL 2: Addresses URL 3: Account URL. */
    $dashboard_desc = __( 'Dal tuo account Labo Suisse puoi visualizzare <a href="%1$s">i tuoi ordini,</a> gestire i tuoi <a href="%2$s">indirizzi di fatturazione e spedizione,</a> modificare <a href="%3$s">la password e i dettagli del tuo account.</a>', 'labo-suisse-theme' );
}

Timber::render('@PathViews/components/banner.twig', [
    'images' => lb_get_images(get_field('lb_account_page_image', 'option'), ['lg' => 'lg', 'md' => 'lg', 'sm' => 'lg', 'xs' => 'lg']),
    'infobox' => [
        'tagline' => __('Il mio account', 'labo-suisse-theme'),
        'subtitle' => sprintf(
            /* translators: 1: user display name 2: logout url */
            wp_kses( __( 'Ciao %1$s!', 'labo-suisse-theme' ), $allowed_html ),
            esc_html( $current_user->display_name )
        ),
        'paragraph' => sprintf(
            wp_kses( $dashboard_desc, $allowed_html ),
            esc_url( wc_get_endpoint_url( 'orders' ) ),
            esc_url( wc_get_endpoint_url( 'edit-address' ) ),
            esc_url( wc_get_endpoint_url( 'edit-account' ) )
        ),
        'reveal' => false,
    ],
    'infoboxBgColorTransparent' => false,
    'infoboxTextAlignment' => 'center',
    'variants' => ['center'],
]);

/**
 * My Account dashboard.
 *
 * @since 2.6.0
 */
do_action( 'woocommerce_account_dashboard' );

/**
 * Deprecated woocommerce_before_my_account action.
 *
 * @deprecated 2.6.0
 */
do_action( 'woocommerce_before_my_account' );

/**
 * Deprecated woocommerce_after_my_account action.
 *
 * @deprecated 2.6.0
 */
do_action( 'woocommerce_after_my_account' );

/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */

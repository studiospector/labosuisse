<?php
/**
 * Checkout login form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-login.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.8.0
 */

defined( 'ABSPATH' ) || exit;

if ( is_user_logged_in() || 'no' === get_option( 'woocommerce_enable_checkout_login_reminder' ) ) {
	return;
}

?>
<div class="lb-wc-product-form-login">
    <div class="woocommerce-form-login-toggle">
        <?php
        $btn_notice = Timber::compile('@PathViews/components/button.twig', [
            'title' => esc_html__( 'Accedi', 'labo-suisse-theme' ),
            'url' => '#',
            'class' => 'showlogin',
            'iconEnd' => [
                'name' => 'arrow-down',
            ],
            'animation' => 'none',
            'variants' => ['quaternary', 'small'],
        ]);

        wc_print_notice( apply_filters('woocommerce_checkout_login_message', esc_html__( 'Returning customer?', 'woocommerce' ) . $btn_notice), 'notice' );
        ?>
    </div>
    <div class="row">
        <div class="col-12 col-md-8">
            <?php
            woocommerce_login_form(
                array(
                    'message'  => esc_html__( 'If you have shopped with us before, please enter your details below. If you are a new customer, please proceed to the Billing section.', 'woocommerce' ),
                    'redirect' => wc_get_checkout_url(),
                    'hidden'   => true,
                )
            );
            ?>
        </div>
    </div>
</div>

<?php
/**
 * My Account navigation
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/navigation.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 2.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'woocommerce_before_account_navigation' );
?>

<div class="lb-wc-myaccount row">

    <div class="col-12 col-lg-3">
        <nav class="lb-wc-myaccount__navigation lb-wc-box-grey woocommerce-MyAccount-navigation">
            <h5 class="lb-wc-myaccount__navigation__title infobox__tagline lb-c-black"><?php echo __('Il mio account', 'labo-suisse-theme'); ?></h5>
            <ul>
                <?php foreach ( wc_get_account_menu_items() as $endpoint => $label ) : ?>
                    <li class="<?php echo wc_get_account_menu_item_classes( $endpoint ); ?>">
                        <?php if ($endpoint == 'customer-logout') {
                            $logout_endpoint = str_replace(site_url(), home_url(), wp_logout_url());
                            Timber::render('@PathViews/components/button.twig', [
                                'title' => esc_html( $label ),
                                'url' => esc_url($logout_endpoint),
                                'variants' => ['tertiary'],
                            ]);
                        } else { ?>
                            <a href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoint ) ); ?>"><?php echo esc_html( $label ); ?></a>
                        <?php } ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </nav>

        <?php do_action( 'woocommerce_after_account_navigation' ); ?>
    </div>

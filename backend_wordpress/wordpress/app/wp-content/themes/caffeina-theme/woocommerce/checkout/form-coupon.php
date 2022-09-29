<?php
/**
 * Checkout coupon form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-coupon.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.4.4
 */

defined( 'ABSPATH' ) || exit;

if ( ! wc_coupons_enabled() ) { // @codingStandardsIgnoreLine.
	return;
}

?>
<div class="woocommerce-form-coupon-toggle">
	<?php
    $btn_notice = Timber::compile('@PathViews/components/button.twig', [
        'title' => esc_html__( 'Click here to enter your code', 'woocommerce' ),
        'url' => '#',
        'class' => 'showcoupon js-scrollbar-management',
        'attributes' => 'data-management-type="click" data-management-delay="2000"',
        'variants' => ['quaternary', 'small'],
    ]);

    wc_print_notice( apply_filters('woocommerce_checkout_coupon_message', esc_html__('Have a coupon?', 'woocommerce') . $btn_notice), 'notice' );
    ?>
</div>

<div class="row">
    <div class="col-12 col-md-8">

        <form class="lb-wc-product-form-coupon checkout_coupon woocommerce-form-coupon" method="post" style="display:none">

            <p><?php esc_html_e( 'If you have a coupon code, please apply it below.', 'woocommerce' ); ?></p>

            <p class="form-row">
                <?php
                    Timber::render('@PathViews/components/fields/input.twig', [
                        'type' => 'text',
                        'id' => 'coupon_code',
                        'name' => 'coupon_code',
                        'value' => '',
                        'label' => esc_attr( 'Coupon code', 'woocommerce' ),
                        'autocomplete' => 'off',
                        'disabled' => false,
                        'required' => false,
                        'class' => 'input-text',
                        'variants' => ['secondary'],
                    ]);
                    Timber::render('@PathViews/components/button.twig', [
                        'title' => esc_html('Apply coupon', 'woocommerce'),
                        'name' => 'apply_coupon',
                        'value' => esc_attr('Apply coupon', 'woocommerce'),
                        'type' => 'submit',
                        'variants' => ['secondary'],
                    ]);
                ?>
            </p>

            <div class="clear"></div>
        </form>

    </div>
</div>

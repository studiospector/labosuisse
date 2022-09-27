<?php
/**
 * View Order
 *
 * Shows the details of a particular order on the account page.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/view-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.0.0
 */

defined( 'ABSPATH' ) || exit;

$notes = $order->get_customer_order_notes();
?>

<div class="lb-wc-order-single">

    <?php
    Timber::render('@PathViews/components/button.twig', [
        'title' => __( 'Torna a Tutti i miei ordini', 'labo-suisse-theme' ),
        'url' => esc_url( wc_get_account_endpoint_url('orders') ),
        'iconEnd' => [],
        'iconStart' => ['name' => 'arrow-left'],
        'variants' => ['quaternary'],
    ]);
    ?>

    <h2 class="lb-wc-order-single__title"><?php esc_html_e( 'Ordine N.', 'labo-suisse-theme' ); echo ' ' . $order->get_order_number(); ?></h2>

    <p class="lb-wc-order-single__info">
        <?php
        printf(
            /* translators: 1: order number 2: order date 3: order status */
            esc_html__( 'L’ordine è stato creato il %1$s ed è attualmente %2$s.', 'labo-suisse-theme' ),
            '<strong class="order-date">' . wc_format_datetime( $order->get_date_created() ) . '</strong>', // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            '<strong class="order-status">' . wc_get_order_status_name( $order->get_status() ) . '</strong>' // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        );
        ?>
    </p>

    <?php if ( $notes ) : ?>
        <h2><?php esc_html_e( 'Order updates', 'woocommerce' ); ?></h2>
        <ol class="woocommerce-OrderUpdates commentlist notes">
            <?php foreach ( $notes as $note ) : ?>
            <li class="woocommerce-OrderUpdate comment note">
                <div class="woocommerce-OrderUpdate-inner comment_container">
                    <div class="woocommerce-OrderUpdate-text comment-text">
                        <p class="woocommerce-OrderUpdate-meta meta"><?php echo date_i18n( esc_html__( 'l jS \o\f F Y, h:ia', 'woocommerce' ), strtotime( $note->comment_date ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
                        <div class="woocommerce-OrderUpdate-description description">
                            <?php echo wpautop( wptexturize( $note->comment_content ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="clear"></div>
                </div>
            </li>
            <?php endforeach; ?>
        </ol>
    <?php endif; ?>

    <?php do_action( 'woocommerce_view_order', $order_id ); ?>
</div>

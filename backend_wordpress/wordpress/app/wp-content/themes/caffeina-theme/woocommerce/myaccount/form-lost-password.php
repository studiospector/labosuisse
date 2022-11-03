<?php
/**
 * Lost password form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-lost-password.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.0.1
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_lost_password_form' );
?>

<form method="post" class="lb-wc-lost-reset-psw woocommerce-ResetPassword lost_reset_password">

    <h2 class="infobox__subtitle h4"><?php esc_html_e( 'Hai dimenticato la password?', 'labo-suisse-theme' ); ?></h2>

    <div class="lb-wc-lost-reset-psw__content">
        <p class="lb-wc-lost-reset-psw__content__info"><?php echo apply_filters( 'woocommerce_lost_password_message', esc_html__( 'Se hai già un account ma non ti ricordi la password, inserisci la tua email, ti invieremo un link per reimpostarla.', 'labo-suisse-theme' ) ); ?></p><?php // @codingStandardsIgnoreLine ?>

        <p class="woocommerce-form-row woocommerce-form-row--first form-row form-row-first">
            <?php
                Timber::render('@PathViews/components/fields/input.twig', [
                    'type' => 'text',
                    'id' => 'user_login',
                    'name' => 'user_login',
                    'value' => '',
                    'label' => esc_html( 'Nome utente o indirizzo email', 'labo-suisse-theme' ) . '*',
                    'autocomplete' => 'username',
                    'disabled' => false,
                    'required' => true,
                    'class' => 'input-text',
                    'variants' => ['tertiary'],
                ]);
            ?>
        </p>

        <?php do_action( 'woocommerce_lostpassword_form' ); ?>

        <p class="woocommerce-form-row form-row">
            <input type="hidden" name="wc_reset_password" value="true" />
            <?php
                Timber::render('@PathViews/components/button.twig', [
                    'title' => esc_html( 'Reimposta password', 'labo-suisse-theme' ),
                    'value' => esc_attr( 'Reimposta password', 'labo-suisse-theme' ),
                    'class' => esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ),
                    'type' => 'submit',
                    'variants' => ['primary'],
                ]);
            ?>
        </p>

        <?php wp_nonce_field( 'lost_password', 'woocommerce-lost-password-nonce' ); ?>
    </div>

</form>
<?php
do_action( 'woocommerce_after_lost_password_form' );

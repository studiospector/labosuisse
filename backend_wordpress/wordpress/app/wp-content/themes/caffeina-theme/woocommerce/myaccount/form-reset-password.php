<?php
/**
 * Lost password reset form.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-reset-password.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.5
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_reset_password_form' );
?>

<form method="post" class="lb-wc-lost-reset-psw lb-wc-lost-reset-psw--reset lb-wc-psw woocommerce-ResetPassword lost_reset_password">

    <h2 class="infobox__subtitle h4"><?php esc_html_e( 'Resetta password', 'labo-suisse-theme' ); ?></h2>
	<p><?php echo apply_filters( 'woocommerce_reset_password_message', esc_html__( 'Enter a new password below.', 'woocommerce' ) ); ?></p><?php // @codingStandardsIgnoreLine ?>

    <div class="lb-wc-lost-reset-psw__content">
        <p class="woocommerce-form-row woocommerce-form-row--first form-row form-row-first">
            <?php
                Timber::render('@PathViews/components/fields/input.twig', [
                    'type' => 'password',
                    'id' => 'password_1',
                    'name' => 'password_1',
                    'value' => '',
                    'label' => esc_html( 'Nuova Password', 'labo-suisse-theme' ) . '*',
                    'autocomplete' => 'new-password',
                    'disabled' => false,
                    'required' => true,
                    'class' => 'input-text',
                    'variants' => ['tertiary'],
                ]);
            ?>
        </p>
        <p class="woocommerce-form-row woocommerce-form-row--last form-row form-row-last">
            <?php
                Timber::render('@PathViews/components/fields/input.twig', [
                    'type' => 'password',
                    'id' => 'password_2',
                    'name' => 'password_2',
                    'value' => '',
                    'label' => esc_html( 'Reinserisci la nuova password', 'labo-suisse-theme' ) . '*',
                    'autocomplete' => 'new-password',
                    'disabled' => false,
                    'required' => true,
                    'class' => 'input-text',
                    'variants' => ['tertiary'],
                ]);
            ?>
        </p>

        <input type="hidden" name="reset_key" value="<?php echo esc_attr( $args['key'] ); ?>" />
        <input type="hidden" name="reset_login" value="<?php echo esc_attr( $args['login'] ); ?>" />

        <?php do_action( 'woocommerce_resetpassword_form' ); ?>
    
        <p class="woocommerce-form-row form-row">
            <input type="hidden" name="wc_reset_password" value="true" />
            <?php
                Timber::render('@PathViews/components/button.twig', [
                    'title' => esc_html( 'Salva', 'labo-suisse-theme' ),
                    'value' => esc_attr( 'Salva', 'labo-suisse-theme' ),
                    'type' => 'submit',
                    'variants' => ['primary'],
                ]);
            ?>
        </p>
    </div>

	<?php wp_nonce_field( 'reset_password', 'woocommerce-reset-password-nonce' ); ?>

</form>
<?php
do_action( 'woocommerce_after_reset_password_form' );


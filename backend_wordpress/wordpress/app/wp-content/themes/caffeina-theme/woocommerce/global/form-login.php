<?php
/**
 * Login form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/global/form-login.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     7.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( is_user_logged_in() ) {
	return;
}

?>
<form class="lb-wc-form-login woocommerce-form woocommerce-form-login login" method="post" <?php echo ( $hidden ) ? 'style="display:none;"' : ''; ?>>

	<?php do_action( 'woocommerce_login_form_start' ); ?>

    <p class="lb-wc-form-login__message">
        <?php echo ( $message ) ? wptexturize( $message ) : ''; // @codingStandardsIgnoreLine ?>
    </p>

	<p class="form-row form-row-first">
        <?php
            Timber::render('@PathViews/components/fields/input.twig', [
                'type' => 'text',
                'id' => 'username',
                'name' => 'username',
                'value' => '',
                'label' => esc_html( 'Username or email', 'woocommerce' ) . '*',
                'autocomplete' => 'username',
                'disabled' => false,
                'required' => true,
                'class' => 'input-text',
                'variants' => ['tertiary'],
            ]);
        ?>
	</p>
	<p class="form-row form-row-last">
        <?php
            Timber::render('@PathViews/components/fields/input.twig', [
                'type' => 'password',
                'id' => 'password',
                'name' => 'password',
                'value' => '',
                'label' => esc_html( 'Password', 'woocommerce' ) . '*',
                'autocomplete' => 'current-password',
                'disabled' => false,
                'required' => true,
                'class' => 'input-text',
                'variants' => ['tertiary'],
            ]);
        ?>
	</p>
	<div class="clear"></div>

	<?php do_action( 'woocommerce_login_form' ); ?>

	<p class="form-row">
        <div class="custom-field custom-checkbox custom-checkbox--vertical">
            <div class="custom-checkbox__options">
                <label class="woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__rememberme">
                    <input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever" /> <span><?php esc_html_e( 'Remember me', 'woocommerce' ); ?></span>
                </label>
            </div>
        </div>
		
		<?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
		<input type="hidden" name="redirect" value="<?php echo esc_url( $redirect ); ?>" />

        <?php
            Timber::render('@PathViews/components/button.twig', [
                'title' => esc_attr( 'Login', 'woocommerce' ),
                'name' => 'login',
                'value' => esc_attr( 'Login', 'woocommerce' ),
                'type' => 'submit',
                'class' => 'woocommerce-button woocommerce-form-login__submit' . esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ),
                'variants' => ['primary'],
            ]);
        ?>
    </p>
	<p class="lost_password">
        <?php
            Timber::render('@PathViews/components/button.twig', [
                'title' => esc_html( 'Lost your password?', 'woocommerce' ),
                'url' => esc_url( wp_lostpassword_url() ),
                'variants' => ['quaternary'],
            ]);
        ?>
	</p>

	<div class="clear"></div>

	<?php do_action( 'woocommerce_login_form_end' ); ?>

</form>

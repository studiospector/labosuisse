<?php
/**
 * Login Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-login.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 4.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

do_action( 'woocommerce_before_customer_login_form' ); ?>

<?php if ( 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) ) : ?>

<div class="lb-wc-sign-in-up row" id="customer_login">

	<div class="lb-wc-sign-in-up__form-signin col-12 col-md-6">

<?php endif; ?>

		<h2 class="infobox__subtitle h4"><?php esc_html_e( 'Login', 'woocommerce' ); ?></h2>

        <p class="lb-wc-sign-in-up__form-info"><?php echo __('Se hai già un profilo, inserisci i tuoi dati e accedi al tuo account personale.', 'labo-suisse-theme'); ?></p>

		<form class="woocommerce-form woocommerce-form-login login" method="post">

			<?php do_action( 'woocommerce_login_form_start' ); ?>

			<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                <?php
                    Timber::render('@PathViews/components/fields/input.twig', [
                        'type' => 'text',
                        'id' => 'username',
                        'name' => 'username',
                        'value' => ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : '',
                        'label' => esc_html__( 'Nome utente o indirizzo email', 'labo-suisse-theme' ) . '*',
                        'autocomplete' => 'username',
                        'disabled' => false,
                        'required' => true,
                        'class' => 'input-text',
                        'variants' => ['tertiary'],
                    ]);
                ?>
			</p>
			<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                <?php
                    Timber::render('@PathViews/components/fields/input.twig', [
                        'type' => 'password',
                        'id' => 'password',
                        'name' => 'password',
                        'value' => '',
                        'label' => esc_html__( 'Password', 'labo-suisse-theme' ) . '*',
                        'autocomplete' => 'current-password',
                        'disabled' => false,
                        'required' => true,
                        'class' => 'input-text',
                        'variants' => ['tertiary'],
                    ]);
                ?>
			</p>

			<?php do_action( 'woocommerce_login_form' ); ?>

			<div class="form-row">
                <div class="custom-field custom-checkbox custom-checkbox--vertical">
                    <div class="custom-checkbox__options">
                        <label class="woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__rememberme">
                            <input id="rememberme" class="woocommerce-form__input woocommerce-form__input-checkbox" type="checkbox" name="rememberme" value="forever" />
                            <span><?php esc_html_e( 'Ricorda i miei dati', 'labo-suisse-theme' ); ?></span>
                        </label>
                    </div>
                </div>
				<?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
                <?php
                    Timber::render('@PathViews/components/button.twig', [
                        'title' => esc_html__( 'Accedi', 'labo-suisse-theme' ),
                        'name' => 'login',
                        'value' => esc_attr__( 'Accedi', 'labo-suisse-theme' ),
                        'type' => 'submit',
                        'class' => 'woocommerce-form-login__submit',
                        'variants' => ['primary'],
                    ]);
                ?>
			</div>
			<p class="woocommerce-LostPassword lost_password">
                <?php
                    Timber::render('@PathViews/components/button.twig', [
                        'title' => esc_html__( 'Hai dimenticato la password?', 'labo-suisse-theme' ),
                        'url' => esc_url( wp_lostpassword_url() ),
                        'variants' => ['quaternary'],
                    ]);
                ?>
			</p>

			<?php do_action( 'woocommerce_login_form_end' ); ?>

		</form>

<?php if ( 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) ) : ?>

	</div>

	<div class="lb-wc-sign-in-up__form-signup col-12 col-md-6">

        <div class="lb-wc-box-grey-small">
            
            <h2 class="infobox__subtitle h4"><?php esc_html_e( 'Register', 'woocommerce' ); ?></h2>
    
            <p class="lb-wc-sign-in-up__form-info"><?php echo __('Se non hai ancora un profilo, inserisci il tuo indirizzo email e una password che ti serviranno per accedere al tuo account personale e avere a disposizione tutte le informazioni sui tuoi ordini.', 'labo-suisse-theme'); ?></p>
    
            <form method="post" class="woocommerce-form woocommerce-form-register register" <?php do_action( 'woocommerce_register_form_tag' ); ?> >
    
                <?php do_action( 'woocommerce_register_form_start' ); ?>
    
                <?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>
    
                    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                        <?php
                            Timber::render('@PathViews/components/fields/input.twig', [
                                'type' => 'text',
                                'id' => 'reg_username',
                                'name' => 'username',
                                'value' => ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : '',
                                'label' => esc_html__( 'Username', 'labo-suisse-theme' ) . '*',
                                'autocomplete' => 'username',
                                'disabled' => false,
                                'required' => true,
                                'class' => 'input-text',
                                'variants' => ['tertiary'],
                            ]);
                        ?>
                    </p>
    
                <?php endif; ?>
    
                <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                    <?php
                        Timber::render('@PathViews/components/fields/input.twig', [
                            'type' => 'email',
                            'id' => 'reg_email',
                            'name' => 'email',
                            'value' => ( ! empty( $_POST['email'] ) ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : '',
                            'label' => esc_html__( 'Indirizzo email', 'labo-suisse-theme' ) . '*',
                            'autocomplete' => 'email',
                            'disabled' => false,
                            'required' => true,
                            'class' => 'input-text',
                            'variants' => ['tertiary'],
                        ]);
                    ?>
                </p>
    
                <?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>
    
                    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                        <?php
                            Timber::render('@PathViews/components/fields/input.twig', [
                                'type' => 'password',
                                'id' => 'reg_password',
                                'name' => 'password',
                                'value' => '',
                                'label' => esc_html__( 'Password', 'labo-suisse-theme' ) . '*',
                                'autocomplete' => 'new-password',
                                'disabled' => false,
                                'required' => true,
                                'class' => 'input-text',
                                'variants' => ['tertiary'],
                            ]);
                        ?>
                    </p>
    
                <?php else : ?>
    
                    <p><?php esc_html_e( 'A link to set a new password will be sent to your email address.', 'woocommerce' ); ?></p>
    
                <?php endif; ?>
    
                <?php do_action( 'woocommerce_register_form' ); ?>

                <div class="woocommerce-privacy-policy-text">
                    <p>
                        <?php
                            $privacy_link = get_field('lb_registration_privacy_policy', 'option');

                            echo sprintf(
                                __("I tuoi dati personali verranno utilizzati per supportare la tua esperienza su questo sito web, per gestire l'accesso al tuo account e per altri scopi descritti nella nostra %s.", 'labo-suisse-theme' ),
                                sprintf(
                                    '<a href="%1$s" target="_blank">%2$s</a>',
                                    $privacy_link,
                                    __('privacy policy', 'labo-suisse-theme')
                                )
                            );
                        ?>
                    </p>
                </div>
    
                <p class="woocommerce-form-row form-row">
                    <?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>
                    <?php
                        Timber::render('@PathViews/components/button.twig', [
                            'title' => esc_html__( 'Registrati', 'labo-suisse-theme' ),
                            'name' => 'register',
                            'value' => esc_attr__( 'Registrati', 'labo-suisse-theme' ),
                            'type' => 'submit',
                            'class' => 'woocommerce-form-register__submit',
                            'variants' => ['primary'],
                        ]);
                    ?>
                </p>
    
                <?php do_action( 'woocommerce_register_form_end' ); ?>
    
            </form>

        </div>

	</div>

</div>
<?php endif; ?>

<?php do_action( 'woocommerce_after_customer_login_form' ); ?>

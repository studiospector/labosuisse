<?php
/**
 * Lost password confirmation text.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/lost-password-confirmation.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.9.0
 */

defined( 'ABSPATH' ) || exit;

// wc_print_notice( esc_html__( 'Password reset email has been sent.', 'woocommerce' ) );
?>

<?php do_action( 'woocommerce_before_lost_password_confirmation_message' ); ?>

<div class="lb-wc-lost-reset-psw">
    <h2 class="infobox__subtitle h4"><?php esc_html_e( 'Accedi con la nuova password', 'labo-suisse-theme' ); ?></h2>
    <p><?php echo esc_html__( 'Ti abbiamo inviato un’email per reimpostare la password.', 'labo-suisse-theme' ); ?></p>
    <p><?php echo esc_html__( 'Per creare la tua nuova password, fai clic sul link nell’e-mail che ti abbiamo inviato e inseriscine una nuova.', 'labo-suisse-theme' ); ?></p>
    <p class="form-row">
        <?php echo esc_html__( 'Non hai ricevuto l’email? Controlla la tua posta indesiderata o fai clic di seguito per inviare una nuovamente l’email.', 'labo-suisse-theme' ); ?>
        <br>
        <?php echo esc_html__( 'Per favore attendi almeno 10 minuti prima di effettuare un\'ulteriore richiesta.', 'labo-suisse-theme' ); ?>
    </p>

    <div class="lb-wc-lost-reset-psw__content">
        <p>
            <?php
                Timber::render('@PathViews/components/button.twig', [
                    'title' => esc_html( 'Accedi', 'labo-suisse-theme' ),
                    'url' => get_field('lb_shop_login_registration_page', 'option'),
                    'variants' => ['primary'],
                ]);
            ?>
        </p>
        <p class="form-row">
            <?php
                Timber::render('@PathViews/components/button.twig', [
                    'title' => esc_html( 'Invia un’altra email', 'labo-suisse-theme' ),
                    'url' => esc_url( wp_lostpassword_url() ),
                    'variants' => ['quaternary'],
                ]);
            ?>
        </p>
    </div>
</div>

<?php do_action( 'woocommerce_after_lost_password_confirmation_message' ); ?>

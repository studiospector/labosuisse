<?php
/**
 * Edit account form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-edit-account.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_edit_account_form' ); ?>

<form class="lb-wc-edit-account lb-wc-psw woocommerce-EditAccountForm edit-account" action="" method="post" <?php do_action( 'woocommerce_edit_account_form_tag' ); ?> >

	<?php do_action( 'woocommerce_edit_account_form_start' ); ?>

    <div class="lb-wc-edit-account__info">
        <div class="row">
            <?php
                $account_fields = [
                    [
                        'type' => 'text',
                        'id' => 'account_first_name',
                        'name' => 'account_first_name',
                        'value' => esc_attr( $user->first_name ),
                        'label' => esc_html( 'Nome', 'labo-suisse-theme' ) . '*',
                        'autocomplete' => 'given-name',
                        'disabled' => false,
                        'required' => true,
                        'class' => 'input-text',
                        'variants' => ['tertiary'],
                    ],
                    [
                        'type' => 'text',
                        'id' => 'account_last_name',
                        'name' => 'account_last_name',
                        'value' => esc_attr( $user->last_name ),
                        'label' => esc_html( 'Cognome', 'labo-suisse-theme' ) . '*',
                        'autocomplete' => 'family-name',
                        'disabled' => false,
                        'required' => true,
                        'class' => 'input-text',
                        'variants' => ['tertiary'],
                    ],
                    [
                        'type' => 'text',
                        'id' => 'account_display_name',
                        'name' => 'account_display_name',
                        'value' => esc_attr( $user->display_name ),
                        'label' => esc_html( 'Nome da visualizzare', 'labo-suisse-theme' ) . '*',
                        'hint' => esc_html( 'Questo sarà il modo in cui il tuo nome verrà visualizzato nella sezione account e nelle recensioni', 'labo-suisse-theme' ),
                        'disabled' => false,
                        'required' => true,
                        'class' => 'input-text',
                        'variants' => ['tertiary'],
                    ],
                    [
                        'type' => 'email',
                        'id' => 'account_email',
                        'name' => 'account_email',
                        'value' => esc_attr( $user->user_email ),
                        'label' => esc_html( 'Indirizzo e-mail', 'labo-suisse-theme' ) . '*',
                        'autocomplete' => 'email',
                        'disabled' => false,
                        'required' => true,
                        'class' => 'input-text',
                        'variants' => ['tertiary'],
                    ],
                ];
    
                foreach ($account_fields as $field) {
                    echo '<div class="col-12 col-md-6">';
                        echo sprintf('<p class="form-row%s">', $field['required'] ? ' validate-required' : '');
                            Timber::render('@PathViews/components/fields/input.twig', $field);
                        echo '</p>';
                    echo '</div>';
                }
            ?>
        </div>
    </div>

    <div class="lb-wc-edit-account__password">
        <h3 class="infobox__subtitle h4"><?php esc_html_e( 'Password change', 'woocommerce' ); ?></h3>
        <div class="row">
            <?php
                $password_fields = [
                    [
                        'type' => 'password',
                        'id' => 'password_current',
                        'name' => 'password_current',
                        'value' => '',
                        'label' => esc_html( 'Password attuale (lascia vuoto per non modificare)', 'labo-suisse-theme' ) . '*',
                        'autocomplete' => 'off',
                        'disabled' => false,
                        'required' => false,
                        'class' => 'input-text',
                        'variants' => ['tertiary'],
                    ],
                    [
                        'type' => 'password',
                        'id' => 'password_1',
                        'name' => 'password_1',
                        'value' => '',
                        'label' => esc_html( 'Nuova password (lascia vuoto per non modificare)', 'labo-suisse-theme' ) . '*',
                        'autocomplete' => 'off',
                        'disabled' => false,
                        'required' => false,
                        'class' => 'input-text',
                        'variants' => ['tertiary'],
                    ],
                    [
                        'type' => 'password',
                        'id' => 'password_2',
                        'name' => 'password_2',
                        'value' => '',
                        'label' => esc_html( 'Conferma la nuova password', 'labo-suisse-theme' ) . '*',
                        'autocomplete' => 'off',
                        'disabled' => false,
                        'required' => false,
                        'class' => 'input-text',
                        'variants' => ['tertiary'],
                    ],
                ];
    
                foreach ($password_fields as $field) {
                    echo '<div class="col-12">';
                        echo sprintf('<p class="form-row%s">', $field['required'] ? ' validate-required' : '');
                            Timber::render('@PathViews/components/fields/input.twig', $field);
                        echo '</p>';
                    echo '</div>';
                }
            ?>
        </div>
    </div>

	<?php do_action( 'woocommerce_edit_account_form' ); ?>

	<p>
		<?php wp_nonce_field( 'save_account_details', 'save-account-details-nonce' ); ?>
        <?php
            Timber::render('@PathViews/components/button.twig', [
                'title' => esc_html( 'Salva', 'labo-suisse-theme' ),
                'name' => 'save_account_details',
                'value' => esc_attr( 'Salva', 'labo-suisse-theme' ),
                'type' => 'submit',
                'variants' => ['primary'],
            ]);
        ?>
		<input type="hidden" name="action" value="save_account_details" />
	</p>

	<?php do_action( 'woocommerce_edit_account_form_end' ); ?>
</form>

<?php do_action( 'woocommerce_after_edit_account_form' ); ?>

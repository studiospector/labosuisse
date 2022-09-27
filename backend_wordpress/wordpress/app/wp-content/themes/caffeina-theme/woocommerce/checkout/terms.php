<?php
/**
 * Checkout terms and conditions area.
 *
 * @package WooCommerce\Templates
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;

if ( apply_filters( 'woocommerce_checkout_show_terms', true ) && function_exists( 'wc_terms_and_conditions_checkbox_enabled' ) ) {
	do_action( 'woocommerce_checkout_before_terms_and_conditions' );

	?>
	<div class="woocommerce-terms-and-conditions-wrapper">
		<?php
		/**
		 * Terms and conditions hook used to inject content.
		 *
		 * @since 3.4.0.
		 * @hooked wc_checkout_privacy_policy_text() Shows custom privacy policy text. Priority 20.
		 * @hooked wc_terms_and_conditions_page_content() Shows t&c page content. Priority 30.
		 */
		// do_action( 'woocommerce_checkout_terms_and_conditions' );
		?>

		<p>
			<?php
				$privacy_link = get_field('lb_checkout_privacy_policy', 'option');

				echo sprintf(
					__('I tuoi dati personali saranno utilizzati per elaborare il tuo ordine, supportare la tua esperienza su questo sito web e per altri scopi descritti nella nostra %s.', 'labo-suisse-theme' ),
					sprintf(
						'<a href="%1$s" target="_blank">%2$s</a>',
						$privacy_link,
						__('privacy policy', 'labo-suisse-theme')
					)
				);
			?>
		</p>

		<?php if ( wc_terms_and_conditions_checkbox_enabled() ) : ?>
			<p class="form-row validate-required">
                <span class="custom-field custom-checkbox custom-checkbox--vertical">
                    <span class="custom-checkbox__options">
                        <label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">
                            <input type="checkbox" class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" name="terms" <?php checked( apply_filters( 'woocommerce_terms_is_checked_default', isset( $_POST['terms'] ) ), true ); // WPCS: input var ok, csrf ok. ?> id="terms" />
                            <span class="woocommerce-terms-and-conditions-checkbox-text">
								<?php // wc_terms_and_conditions_checkbox_text(); ?>
								<?php
									echo sprintf(
										__('Ho letto e accetto %s del sito web.', 'labo-suisse-theme' ),
										sprintf(
											'<a href="%1$s" target="_blank">%2$s</a>',
											get_page_link(wc_terms_and_conditions_page_id()),
											__('termini e condizioni', 'labo-suisse-theme')
										)
									);
								?>
								<abbr class="required" title="<?php esc_attr_e( 'required', 'woocommerce' ); ?>">*</abbr>
							</span>
                        </label>
                    </span>
                </span>
				<input type="hidden" name="terms-field" value="1" />
			</p>
		<?php endif; ?>
	</div>
	<?php

	do_action( 'woocommerce_checkout_after_terms_and_conditions' );
}

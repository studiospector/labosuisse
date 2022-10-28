<?php

namespace GtmEcommerceWooPro\Lib\EventStrategy;

use GtmEcommerceWoo\Lib\GaEcommerceEntity\Event;

/**
 * When a user sees a list of items/offerings
 */
class AddBillingInfoStrategy extends \GtmEcommerceWoo\Lib\EventStrategy\AbstractEventStrategy {
	protected $eventName = 'add_billing_info';

	public function defineActions() {
		return array(
			'woocommerce_before_checkout_form' => array( $this, 'beforeCheckoutForm' ),
		);
	}

	public function beforeCheckoutForm() {
		global $woocommerce;
		$wcTransformer = $this->wcTransformer;
		$items         = array_map(
			function( $cartItem ) use ( $wcTransformer ) {
				return $wcTransformer->getItemFromCartItem( $cartItem );
			},
			$woocommerce->cart->get_cart()
		);
		$this->wcOutput->globalVariable( 'gtm_ecommerce_woo_items', array_values( $items ) );

		$this->wcOutput->script(
			<<<'EOD'
var $checkoutForm = jQuery( 'form.checkout' );
var $billingFields = $checkoutForm.find('.validate-required [id^="billing_"]').parents( '.validate-required' );
$billingFields.change(() => {
	setTimeout(() => {
		if ($billingFields.find('[id^="billing_"]').filter((i, el) => jQuery(el).val() === '').length === 0
		&& $billingFields.filter('.woocommerce-invalid').length === 0) {
			dataLayer.push({
				'event': 'add_billing_info',
				'ecommerce': {
					'items': gtm_ecommerce_woo_items
				}
			});
		}
	}, 100);
});
EOD
		);
	}
}

<?php

namespace GtmEcommerceWooPro\Lib\EventStrategy;

use GtmEcommerceWoo\Lib\GaEcommerceEntity\Event;

/**
 * When a user sees a list of items/offerings
 */
class AddPaymentInfoStrategy extends \GtmEcommerceWoo\Lib\EventStrategy\AbstractEventStrategy {
	protected $eventName = 'add_payment_info';

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
jQuery( document.body ).on( 'payment_method_selected', () => {
	var paymentMethod = jQuery( '.woocommerce-checkout input[name="payment_method"]:checked' ).attr( 'id' );
	dataLayer.push({
		'event': 'add_payment_info',
		'ecommerce': {
			'payment_type': paymentMethod,
			'items': gtm_ecommerce_woo_items
		}
	});
});
EOD
		);
	}
}

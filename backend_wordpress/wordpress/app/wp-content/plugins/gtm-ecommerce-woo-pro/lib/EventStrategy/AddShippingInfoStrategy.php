<?php

namespace GtmEcommerceWooPro\Lib\EventStrategy;

use GtmEcommerceWoo\Lib\GaEcommerceEntity\Event;

/**
 * When a user sees a list of items/offerings
 */
class AddShippingInfoStrategy extends \GtmEcommerceWoo\Lib\EventStrategy\AbstractEventStrategy {
	protected $eventName = 'add_shipping_info';

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
$checkoutForm.on( 'change', 'select.shipping_method, input[name^="shipping_method"][type="radio"]:checked, input[name^="shipping_method"][type="hidden"]',  function() {
	dataLayer.push({
		'event': 'add_shipping_info',
		'ecommerce': {
			'shipping_tier': jQuery(this).val(),
			'items': gtm_ecommerce_woo_items
		}
	});
} );
EOD
		);
	}
}

<?php

namespace GtmEcommerceWooPro\Lib\EventStrategy\Browser;

use GtmEcommerceWoo\Lib\EventStrategy\AbstractEventStrategy;
use GtmEcommerceWoo\Lib\GaEcommerceEntity\Event;
use GtmEcommerceWooPro\Lib\EventStrategy\EventStrategyTrait;
use GtmEcommerceWooPro\Lib\Type\EventType;

/**
 * When a user sees a list of items/offerings
 */
class AddShippingInfoStrategy extends AbstractEventStrategy {
	use EventStrategyTrait;

	protected $eventName = EventType::ADD_SHIPPING_INFO;

	public function defineActions() {
		return [
			'woocommerce_before_checkout_form' => [ $this, 'beforeCheckoutForm' ],
		];
	}

	public function beforeCheckoutForm() {
		$event = ( new Event(EventType::ADD_SHIPPING_INFO) )
			->setItems(array_values($this->getCartItems()));

		$stringifiedEvent = json_encode($event);

		$this->wcOutput->script(
			<<<EOD
var checkoutForm = jQuery( 'form.checkout' );
checkoutForm.on( 'change', 'select.shipping_method, input[name^="shipping_method"][type="radio"]:checked, input[name^="shipping_method"][type="hidden"]',  function() {
	let event = {$stringifiedEvent};
	event.ecommerce.shipping_tier = jQuery(this).val();

	dataLayer.push(event);
} );
EOD
		);
	}
}

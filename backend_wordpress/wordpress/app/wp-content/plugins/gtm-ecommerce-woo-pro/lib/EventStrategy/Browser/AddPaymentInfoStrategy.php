<?php

namespace GtmEcommerceWooPro\Lib\EventStrategy\Browser;

use GtmEcommerceWoo\Lib\EventStrategy\AbstractEventStrategy;
use GtmEcommerceWoo\Lib\GaEcommerceEntity\Event;
use GtmEcommerceWooPro\Lib\EventStrategy\EventStrategyTrait;
use GtmEcommerceWooPro\Lib\Type\EventType;

/**
 * When a user sees a list of items/offerings
 */
class AddPaymentInfoStrategy extends AbstractEventStrategy {
	use EventStrategyTrait;

	protected $eventName = EventType::ADD_PAYMENT_INFO;

	public function defineActions() {
		return [
			'woocommerce_before_checkout_form' => [ $this, 'beforeCheckoutForm' ],
		];
	}

	public function beforeCheckoutForm() {
		$event = ( new Event(EventType::ADD_PAYMENT_INFO) )
			->setItems(array_values($this->getCartItems()));

		$stringifiedEvent = json_encode($event);

		$this->wcOutput->script(
			<<<EOD
jQuery( document.body ).on( 'payment_method_selected', () => {
	let paymentMethod = jQuery( '.woocommerce-checkout input[name="payment_method"]:checked' ).attr( 'id' );
	let event = {$stringifiedEvent};
	event.ecommerce.payment_type = paymentMethod;

	dataLayer.push(event);
});
EOD
		);
	}
}

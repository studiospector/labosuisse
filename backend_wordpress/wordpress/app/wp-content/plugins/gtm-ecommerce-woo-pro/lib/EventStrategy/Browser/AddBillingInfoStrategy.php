<?php

namespace GtmEcommerceWooPro\Lib\EventStrategy\Browser;

use GtmEcommerceWoo\Lib\EventStrategy\AbstractEventStrategy;
use GtmEcommerceWoo\Lib\GaEcommerceEntity\Event;
use GtmEcommerceWooPro\Lib\EventStrategy\EventStrategyTrait;
use GtmEcommerceWooPro\Lib\Type\EventType;

/**
 * When a user sees a list of items/offerings
 */
class AddBillingInfoStrategy extends AbstractEventStrategy {
	use EventStrategyTrait;

	protected $eventName = EventType::ADD_BILLING_INFO;

	public function defineActions() {
		return [
			'woocommerce_before_checkout_form' => [ $this, 'beforeCheckoutForm' ],
		];
	}

	public function beforeCheckoutForm() {
		$event = ( new Event(EventType::ADD_BILLING_INFO) )
			->setItems(array_values($this->getCartItems()));

		$stringifiedEvent = json_encode($event);

		$this->wcOutput->script(
			<<<EOD
var checkoutForm = jQuery( 'form.checkout' );
var billingFields = checkoutForm.find('.validate-required [id^="billing_"]').parents( '.validate-required' );
billingFields.change(() => {
	setTimeout(() => {
		if (billingFields.find('[id^="billing_"]').filter((i, el) => jQuery(el).val() === '').length === 0
		&& billingFields.filter('.woocommerce-invalid').length === 0) {
			dataLayer.push({$stringifiedEvent});
		}
	}, 100);
});
EOD
		);
	}
}

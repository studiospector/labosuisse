<?php

namespace GtmEcommerceWooPro\Lib\EventStrategy\Browser;

use GtmEcommerceWoo\Lib\EventStrategy\AbstractEventStrategy;
use GtmEcommerceWoo\Lib\GaEcommerceEntity\Event;
use GtmEcommerceWooPro\Lib\EventStrategy\EventStrategyTrait;
use GtmEcommerceWooPro\Lib\Type\EventType;

/**
 * When a user sees a list of items/offerings
 */
class ViewCartStrategy extends AbstractEventStrategy {
	use EventStrategyTrait;

	protected $eventName = EventType::VIEW_CART;

	public function defineActions() {
		return [
			'woocommerce_before_cart' => [ $this, 'beforeCart' ],
		];
	}

	public function beforeCart() {
		$event = ( new Event(EventType::VIEW_CART) )
			->setItems(array_values($this->getCartItems()));

		$this->wcOutput->dataLayerPush( $event );
	}
}

<?php

namespace GtmEcommerceWooPro\Lib\EventStrategy;

use GtmEcommerceWoo\Lib\GaEcommerceEntity\Event;

class RemoveFromCartServerStrategy extends \GtmEcommerceWoo\Lib\EventStrategy\AbstractEventStrategy {

	protected $eventName = 'remove_from_cart';
	protected $eventType = 'server';

	public function defineActions() {
		return [
			'woocommerce_remove_cart_item' => [[$this, 'removeCartItem'], 10, 2],
		];
	}

	public function removeCartItem( $cart_item_key, $cart ) {}
}

<?php

namespace GtmEcommerceWooPro\Lib\EventStrategy;

use GtmEcommerceWoo\Lib\GaEcommerceEntity\Event;

/**
 * When a user sees a list of items/offerings
 */
class ViewCartStrategy extends \GtmEcommerceWoo\Lib\EventStrategy\AbstractEventStrategy {
	protected $eventName = 'view_cart';

	public function defineActions() {
		return array(
			'woocommerce_before_cart' => array( $this, 'beforeCart' ),
		);
	}

	public function beforeCart() {
		global $woocommerce;
		$wcTransformer = $this->wcTransformer;
		$items         = array_map(
			function( $cartItem ) use ( $wcTransformer ) {
				$item = $wcTransformer->getItemFromCartItem( $cartItem );
				return $item;
			},
			$woocommerce->cart->get_cart()
		);
		$event         = new Event( 'view_cart' );
		$event->setItems( array_values( $items ) );
		$this->wcOutput->dataLayerPush( $event );
	}
}

<?php

namespace GtmEcommerceWooPro\Lib\EventStrategy;

use GtmEcommerceWoo\Lib\GaEcommerceEntity\Event;

/**
 * When a user sees a list of items/offerings
 */
class BeginCheckoutStrategy extends \GtmEcommerceWoo\Lib\EventStrategy\AbstractEventStrategy {
	protected $eventName = 'begin_checkout';
	protected $tracked = false;

	public function defineActions() {
		return array(
			'woocommerce_before_checkout_form' => array( $this, 'beforeCheckoutForm' ),
			'wp_footer' => [ $this, 'wpFooter' ]
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
		$event         = new Event( 'begin_checkout' );
		$event->setItems( array_values( $items ) );
		$this->wcOutput->dataLayerPush( $event );
		$this->tracked = true;
	}

	public function wpFooter() {
		if (false === $this->tracked && is_checkout() && !is_order_received_page()) {
			$this->beforeCheckoutForm();
		}
	}
}

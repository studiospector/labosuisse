<?php

namespace GtmEcommerceWooPro\Lib\EventStrategy;

class PurchaseStrategy extends \GtmEcommerceWoo\Lib\EventStrategy\PurchaseStrategy {

	protected $trackedOrderId;

	public function defineActions() {
		return [
			'woocommerce_thankyou' => [$this, 'thankyou'],
			'wp_footer' => [$this, 'wpFooter'],
		];
	}

	public function thankyou( $orderId ) {
		$forcePurchase = ( isset($_GET['gtm-ecommerce-woo-force-purchase'] ) && '1' === $_GET['gtm-ecommerce-woo-force-purchase'] );

		if ( '1' === get_post_meta( $orderId, 'gtm_ecommerce_woo_purchase_event_tracked', true ) && true !== $forcePurchase  ) {
			return;
		}
		$order = wc_get_order( $orderId );
		$this->wcOutput->dataLayerPush([
			'email' => $order->get_billing_email(),
			'phone_number' => $order->get_billing_phone(),
		]);
		
		$event = $this->wcTransformer->getPurchaseFromOrderId($orderId);

		$this->wcOutput->dataLayerPush($event);
		$this->trackedOrderId = $orderId;
	}

	public function wpFooter() {
		update_post_meta( $this->trackedOrderId, 'gtm_ecommerce_woo_purchase_event_tracked', '1' );
	}
}

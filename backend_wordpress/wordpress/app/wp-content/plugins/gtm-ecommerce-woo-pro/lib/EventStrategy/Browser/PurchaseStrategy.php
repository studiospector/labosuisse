<?php

namespace GtmEcommerceWooPro\Lib\EventStrategy\Browser;

use GtmEcommerceWoo\Lib\EventStrategy\PurchaseStrategy as FreePurchaseStrategy;
use WC_Order;

class PurchaseStrategy extends FreePurchaseStrategy {
	/**
	 * Tracked order.
	 *
	 * @var WC_Order
	 */
	private $trackedOrder;

	public function defineActions() {
		return [
			'woocommerce_thankyou' => [$this, 'thankyou'],
			'wp_footer' => [$this, 'wpFooter'],
		];
	}

	public function thankyou( $orderId ) {
		$order = wc_get_order( $orderId );

		if (false === $order instanceof WC_Order) {
			return;
		}

		if (false === $this->shouldTrack($order)) {
			return;
		}

		$this->wcOutput->dataLayerPush([
			'email' => $order->get_billing_email(),
			'phone_number' => $order->get_billing_phone(),
		]);

		$event = $this->wcTransformer->getPurchaseFromOrder($order);

		$this->wcOutput->dataLayerPush($event);
		$this->trackedOrder = $order;
	}

	public function wpFooter() {
		if (null === $this->trackedOrder) {
			return;
		}
		$this->trackedOrder->update_meta_data(self::ORDER_META_KEY_PURCHASE_EVENT_TRACKED, '1');
		$this->trackedOrder->save();
	}

	private function shouldTrack( WC_Order $order) {
		$forcePurchase = ( isset($_GET['gtm-ecommerce-woo-force-purchase'] ) && '1' === $_GET['gtm-ecommerce-woo-force-purchase'] );

		return $forcePurchase || '1' !== $order->get_meta(self::ORDER_META_KEY_PURCHASE_EVENT_TRACKED);
	}
}

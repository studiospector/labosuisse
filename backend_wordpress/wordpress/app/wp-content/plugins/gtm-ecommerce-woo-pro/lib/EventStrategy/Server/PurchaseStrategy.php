<?php

namespace GtmEcommerceWooPro\Lib\EventStrategy\Server;

use GtmEcommerceWoo\Lib\EventStrategy\PurchaseStrategy as BrowserPurchaseStrategy;
use GtmEcommerceWooPro\Lib\EventStrategy\AbstractServerEventStrategy;

class PurchaseStrategy extends AbstractServerEventStrategy {

	const ORDER_META_KEY_PURCHASE_SERVER_EVENT_CLIENT_ID = 'gtm_ecommerce_woo_purchase_server_event_client_id';

	const ORDER_META_KEY_PURCHASE_SERVER_EVENT_TRACKED = 'gtm_ecommerce_woo_purchase_server_event_tracked';

	protected $eventName = 'purchase';

	protected $eventType = 'server';

	public function defineActions() {
		return [
			'woocommerce_new_order' => [$this, 'newOrder'],
			'woocommerce_order_status_processing' => [$this, 'orderStatusProcessing'],
		];
	}

	public function newOrder( $orderId ) {
		$order = wc_get_order($orderId);
		$order->update_meta_data(self::ORDER_META_KEY_PURCHASE_SERVER_EVENT_CLIENT_ID, $this->mpClient->getClientId());
		$order->save();
	}

	public function orderStatusProcessing( $orderId ) {
		$order = wc_get_order($orderId);

		// browser event tracked
		if ('1' === $order->get_meta(BrowserPurchaseStrategy::ORDER_META_KEY_PURCHASE_EVENT_TRACKED)) {
			return;
		}

		// server event tracked
		if ('1' === $order->get_meta(self::ORDER_META_KEY_PURCHASE_SERVER_EVENT_TRACKED)) {
			return;
		}

		// no client_id
		if ('' === $order->get_meta(self::ORDER_META_KEY_PURCHASE_SERVER_EVENT_CLIENT_ID)) {
			return;
		}

		$clientId = $order->get_meta(self::ORDER_META_KEY_PURCHASE_SERVER_EVENT_CLIENT_ID);

		$event = $this->wcTransformer->getPurchaseFromOrder($order);
		$this->mpClient->sendEvent($event, $clientId);

		$order->update_meta_data(self::ORDER_META_KEY_PURCHASE_SERVER_EVENT_TRACKED, '1');
		$order->save();
	}
}

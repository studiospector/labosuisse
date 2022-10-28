<?php

namespace GtmEcommerceWooPro\Lib\EventStrategy;

class PurchaseServerStrategy extends AbstractServerEventStrategy {

	protected $eventName = 'purchase';
	protected $eventType = 'server';


	protected $trackedOrderId;

	public function defineActions() {
		return [
			'woocommerce_new_order' => [$this, 'newOrder'],
			'woocommerce_order_status_processing' => [$this, 'orderStatusProcessing'],
		];
	}

	public function newOrder( $orderId ) {
		update_post_meta( $orderId, 'gtm_ecommerce_woo_purchase_server_event_client_id', $this->mpClient->getClientId() );
	}

	public function orderStatusProcessing( $orderId ) {
		if ( '1' === get_post_meta( $orderId, 'gtm_ecommerce_woo_purchase_event_tracked', true )
			|| '1' === get_post_meta( $orderId, 'gtm_ecommerce_woo_purchase_server_event_tracked', true )
			|| '' === get_post_meta( $orderId, 'gtm_ecommerce_woo_purchase_server_event_client_id', true ) ) {
			return;
		}

		$order = wc_get_order( $orderId );
		$clientId = get_post_meta( $orderId, 'gtm_ecommerce_woo_purchase_server_event_client_id', true );

		$event = $this->wcTransformer->getPurchaseFromOrderId($orderId);
		$this->mpClient->sendEvent($event, $clientId);

		update_post_meta( $this->trackedOrderId, 'gtm_ecommerce_woo_purchase_server_event_tracked', '1' );
	}
}

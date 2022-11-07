<?php

namespace GtmEcommerceWooPro\Lib\Service;

/**
 * Tag Concierge Monitoring tool. This is responsible for sending out backend events.
 */
class MonitorService extends \GtmEcommerceWoo\Lib\Service\MonitorService {

	public function initialize() {
		$cronName = $this->snakeCaseNamespace . '_cron_debugger';
		if ($this->wpSettingsUtil->getOption('monitor_enabled') !== '1') {
			$timestamp = wp_next_scheduled( $cronName );
			wp_unschedule_event( $timestamp, $cronName );
			return;
		}

		add_action( $cronName, [$this, 'recentTransactions'] );
		if ( ! wp_next_scheduled( $cronName ) ) {
			wp_schedule_event( time(), 'hourly', $cronName );
		}

		add_action( 'wp_head', [$this, 'uuidHash'] );
		add_action( 'woocommerce_order_status_changed', [$this, 'orderStatusChanged']);

		add_action( 'woocommerce_add_to_cart', [$this, 'addToCart'], 10, 6 );
		add_action( 'woocommerce_remove_cart_item', [$this, 'removeFromCart'], 10, 2 );
		add_action( 'woocommerce_thankyou', [$this, 'purchase'] );
		add_action( 'woocommerce_before_checkout_form', [$this, 'beginCheckout']);
	}

	// prevent duplicated purchase

	public function removeFromCart( $cart_item_key, $cart ) {
		$cartItemData = $cart->cart_contents[ $cart_item_key ];
		$product = $cartItemData['variation_id'] ?
			wc_get_product( $cartItemData['variation_id'] )
			: wc_get_product( $cartItemData['product_id'] );
		$item = $this->wcTransformerUtil->getItemFromProduct($product);
		$item->quantity = $cartItemData['quantity'];
		$referer = isset($_SERVER['HTTP_REFERER']) ? sanitize_text_field($_SERVER['HTTP_REFERER']) : '';
		$event = [
			'event_name' => 'remove_from_cart',
			'event_timestamp' => ( new \Datetime('now') )->format('Y-m-d H:i:s'),
			'event_items' => [$item],
			'event_location' => parse_url($referer, PHP_URL_PATH)
		];
		$uuid = $this->wpSettingsUtil->getOption('uuid');
		$args = [
			'body' => json_encode([
				'uuid_hash' => $this->hash($uuid),
				'origin' => 'server',
				'events' => [$event]
			]),
			'headers' => [
				'content-type' => 'application/json'
			],
			'data_format' => 'body',
		];
		try {
			$response = wp_remote_post( $this->tagConciergeEdgeUrl . '/v2/monitor/events', $args );
		} catch (Exception $err) {
			error_log( 'Tag Concierge Monitor remove_from_cart failed' );
		}
	}

	public function beginCheckout() {
		global $woocommerce, $wp;
		$items         = array_values(array_map(
			function( $item ) {
				$product = wc_get_product( $item['data']->get_id() );
				return $this->wcTransformerUtil->getItemFromProduct( $item['data'] );
			},
			$woocommerce->cart->get_cart()
		));

		$event = [
			'event_name' => 'begin_checkout',
			'event_timestamp' => ( new \Datetime('now') )->format('Y-m-d H:i:s'),
			'event_items' => $items,
			'event_location' => $wp->request
		];
		$uuid = $this->wpSettingsUtil->getOption('uuid');
		$args = [
			'body' => json_encode([
				'uuid_hash' => $this->hash($uuid),
				'origin' => 'server',
				'events' => [$event]
			]),
			'headers' => [
				'content-type' => 'application/json'
			],
			'data_format' => 'body',
		];
		try {
			$response = wp_remote_post( $this->tagConciergeEdgeUrl . '/v2/monitor/events', $args );
		} catch (Exception $err) {
			error_log( 'Tag Concierge Monitor begin_checkout failed' );
		}
	}

	public function purchase( $orderId) {
		global $wp;

		if ( '1' === get_post_meta( $orderId, 'gtm_ecommerce_woo_purchase_event_tracked_server', true ) ) {
			return;
		}

		$event = $this->wcTransformerUtil->getPurchaseFromOrderId($orderId);
		$finalEvent = [
			'event_uuid' => $this->uuid(),
			'event_name' => 'purchase',
			'event_timestamp' => ( new \Datetime('now') )->format('Y-m-d H:i:s'),
			'event_items' => $event->items,
			'event_location' => $wp->request,
			'event_data' => [
				'transaction_id_hash' => $this->hash($event->transactionId),
				'affiliation' => $event->affiliation,
				'value' => $event->value,
				'tax' => $event->tax,
				'shipping' => $event->shipping,
				'currency' => $event->currency,
				'coupon' => @$event->coupon
			]
		];
		$uuid = $this->wpSettingsUtil->getOption('uuid');
		$args = [
			'body' => json_encode([
				'uuid_hash' => $this->hash($uuid),
				'origin' => 'server',
				'events' => [$finalEvent]
			]),
			'headers' => [
				'content-type' => 'application/json'
			],
			'data_format' => 'body',
		];
		try {
			$response = wp_remote_post( $this->tagConciergeEdgeUrl . '/v2/monitor/events', $args );
		} catch (Exception $err) {
			error_log( 'Tag Concierge Monitor purchase failed' );
		}
		update_post_meta( $orderId, 'gtm_ecommerce_woo_purchase_event_tracked_server', '1' );
		$this->orderStatusChanged($orderId);
	}
}

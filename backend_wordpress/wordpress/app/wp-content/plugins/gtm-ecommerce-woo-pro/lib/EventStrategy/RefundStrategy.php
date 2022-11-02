<?php

namespace GtmEcommerceWooPro\Lib\EventStrategy;

use GtmEcommerceWoo\Lib\GaEcommerceEntity\Event;

class RefundStrategy extends \GtmEcommerceWoo\Lib\EventStrategy\AbstractEventStrategy {

	protected $eventName = 'refund';

	public function defineActions() {
		return array(
			'woocommerce_admin_order_data_after_order_details' => array( $this, 'editFormAdvanced' ),
		);
	}

	// this logic may be moved to the actual refund hook now
	public function editFormAdvanced( $order ) {
		// get ids of refunds that were already tracked
		$refundedIds = get_post_meta( $order->get_id(), 'gtm_wcommerce_woo_refunded_ids', true );
		$refundedIds = is_array( $refundedIds ) ? $refundedIds : array();

		$refunds = $order->get_refunds();

		$refundsToTrack = array_reduce(
			$refunds,
			function( $agg, $refund ) use ( $refundedIds ) {
				if ( ! in_array( $refund->get_id(), $refundedIds ) ) {
					$agg[] = $refund;
				}
				return $agg;
			},
			array()
		);

		if ( count( $refundsToTrack ) === 0 ) {
			return;
		}
		$event = $this->wcTransformer->getRefundFromOrderId( $order->get_id(), $refundsToTrack );

		$stringifiedEvent = json_encode( $event );

		$newlyTrackedRefundIds = array_map(
			function( $refund ) {
				return $refund->get_id();
			},
			$refundsToTrack
		);

		update_post_meta( $order->get_id(), 'gtm_wcommerce_woo_refunded_ids', array_merge( $refundedIds, $newlyTrackedRefundIds ) );

		echo '<iframe height="0" width="0" style="display:none;visibility:hidden" referrerpolicy="no-referrer" src="/?rest_route=/gtm-ecommerce-woo/v1/container/' . esc_attr( base64_encode( $stringifiedEvent ) ) . '"></iframe>';
	}
}

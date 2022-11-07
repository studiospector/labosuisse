<?php

namespace GtmEcommerceWooPro\Lib\Util;

use GtmEcommerceWoo\Lib\GaEcommerceEntity\Event;
use GtmEcommerceWoo\Lib\GaEcommerceEntity\Item;

/**
 * Logic to handle embedding Gtm Snippet
 */
class WcTransformerUtil extends \GtmEcommerceWoo\Lib\Util\WcTransformerUtil {

	public function getRefundFromOrderId( $orderId, $refunds ) {
		$order = wc_get_order( $orderId );
		$event = new Event( 'refund' );
		$event->setTransactionId( $order->get_order_number() );

		foreach ( $refunds as $refund ) {
			foreach ( $refund->get_items() as $key => $orderItem ) {
				$item           = $this->getItemFromOrderItem( $orderItem );
				$item->quantity = -$item->quantity;
				$event->addItem( $item );
			}
		}

		return $event;
	}

	public function getItemFromCartItem( $cartItem ) {
		$product = wc_get_product( $cartItem['data']->get_id() );
		// Review possible usage of getItemFromOrderItem
		$item = $this->getItemFromProduct( $product );
		$item->setQuantity( $cartItem['quantity'] );
		return $item;
	}

	/**
	 * Url: https://woocommerce.github.io/code-reference/classes/WC-Product-Variation.html
	 */
	public function getItemFromProductVariation( $product ) {
		$item = new Item( $product->get_name() );
		$item->setItemId( $product->get_id() );
		$item->setPrice( $product->get_price() );
		// $item->setItemBrand('');
		$productCats = get_the_terms( $product->get_id(), 'product_cat' );
		if ( is_array( $productCats ) ) {
			$categories = array_map(
				function( $category ) {
					return $category->name; },
				$productCats
			);
			$item->setItemCategories( $categories );
		}
		/**
		 * Filter allowing to manipulate the event item objects
		 *
		 * @since 1.6.0
		 */
		$item = apply_filters('gtm_ecommerce_woo_item', $item, $product);
		return $item;
	}
}

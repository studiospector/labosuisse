<?php

namespace GtmEcommerceWooPro\Lib\EventStrategy;

use GtmEcommerceWoo\Lib\GaEcommerceEntity\Event;

/**
 * When a user sees a list of items/offerings
 * <td class="product-remove">
							<a href="http://docker.local/?page_id=6&amp;remove_item=45c48cce2e2d7fbdea1afc51c7c6ad26&amp;_wpnonce=f2b331db52" class="remove" aria-label="Remove this item" data-product_id="9" data-product_sku="WCCLITESTP">×</a>                      </td>
 */
class RemoveFromCartStrategy extends \GtmEcommerceWoo\Lib\EventStrategy\AbstractEventStrategy {

	protected $eventName = 'remove_from_cart';

	public function defineActions() {
		return array(
			'wp_head' => array( $this, 'afterCart' ),
		);
	}

	public function afterCart() {
		global $woocommerce;
		$itemsByProductId = array();
		if (isset($woocommerce->cart)) {
			foreach ( $woocommerce->cart->get_cart() as $item ) {
				$product                                = wc_get_product( $item['data']->get_id() );
				$itemsByProductId[ $product->get_id() ] = $this->wcTransformer->getItemFromProduct( $product );
			}
			$this->onRemoveLinkClick( $itemsByProductId );
		}
	}

	public function onRemoveLinkClick( $items ) {
		$this->wcOutput->globalVariable( 'gtm_ecommerce_woo_items_by_product_id', $items );
		$this->wcOutput->script(
			<<<EOD
jQuery(document).on('click', '.remove', function(ev) {
    var product_id = jQuery(ev.currentTarget).attr('data-product_id');
    var item = gtm_ecommerce_woo_items_by_product_id[product_id];
    item.quantity = 1;
    dataLayer.push({
      'event': 'remove_from_cart',
      'ecommerce': {
        'value': item.price,
        'items': [item]
      }
    });
});

jQuery(document).on('submit', '.woocommerce-cart-form', function(ev) {
	jQuery('input[name$="[qty]"]', ev.currentTarget).each(function(i, el) {
		var previousValue = el.defaultValue || 0;
		var currentValue = el.value || 0;
		var diff = currentValue - previousValue;

		if (diff < 0) {
			var quantity = Math.abs(diff);
			var product_id =jQuery(el).parents('.cart_item').find('[data-product_id]').attr('data-product_id');
			var item = gtm_ecommerce_woo_items_by_product_id[product_id];
			item.quantity = quantity;
		    dataLayer.push({
		      'event': 'remove_from_cart',
		      'ecommerce': {
		        'value': (item.price * quantity),
		        'items': [item]
		      }
		    });
		}
	});
});
EOD
		);
	}
}

<?php

namespace GtmEcommerceWooPro\Lib\EventStrategy;

use GtmEcommerceWoo\Lib\GaEcommerceEntity\Event;

/**
 * When a user clicks on item
 */
class SelectItemStrategy extends \GtmEcommerceWoo\Lib\EventStrategy\AbstractEventStrategy {
	protected $itemsByProductId;
	protected $eventName = 'select_item';
	protected $itemListName;
	protected $itemListId;

	public function defineActions() {
		return array(
			'the_post'  => [[$this, 'beforeShopLoopItemTitle'], 11 ],
			'wp_footer' => array( $this, 'afterShopLoop' ),
		);
	}

	public function initialize() {
		$this->itemsByProductId = array();
	}

	public function computeList() {
		if (null !== $this->itemListName) {
			return;
		}

		$this->itemListName = 'default';
		$this->itemListId   = '0';

		if ( is_product_category() ) {
			$cat                = get_queried_object();
			$this->itemListName = 'category_' . $cat->name;
			$this->itemListId   = 'category_' . $cat->term_id;
		}

		if ( is_product_tag() ) {
			$cat                = get_queried_object();
			$this->itemListName = 'tag_' . $cat->name;
			$this->itemListId   = 'tag_' . $cat->term_id;
		}
	}

	public function beforeShopLoopItemTitle() {
		global $product;
		if (is_a($product, 'WC_Product')) {
			$this->computeList();
			$item = $this->wcTransformer->getItemFromProduct( $product );
			$item->setItemListName( $this->itemListName );
			$item->setItemListId( $this->itemListId );
			$this->itemsByProductId[ $product->get_id() ] = $item;
			// echo '<span style="display: none" class="gtm_ecommerce_woo_product_id">' . esc_html( $product->get_id() ) . '</span>';
		}
	}

	public function afterShopLoop() {
		if (count($this->itemsByProductId) > 0) {
			$this->onLoopProductLinkClick( $this->itemsByProductId );
		}
	}


	public function onLoopProductLinkClick( $items ) {
		$this->wcOutput->globalVariable( 'gtm_ecommerce_woo_items_by_product_id', $items );
		$this->wcOutput->script(
			<<<EOD
jQuery('.woocommerce-loop-product__link').click(function(ev) {
	var matched_product_id = jQuery(ev.currentTarget).parents(".product.type-product").attr('class').match(/post\-[0-9]*/);
    var product_id = ((matched_product_id && matched_product_id[0]) || "").replace('post-', '');
   	if (!product_id) {
   		return;
   	}
    var item = gtm_ecommerce_woo_items_by_product_id[product_id];
    dataLayer.push({
      'event': 'select_item',
      'ecommerce': {
      	'value': item.price,
        'items': [item]
      }
    });
});
EOD
		);
	}

}

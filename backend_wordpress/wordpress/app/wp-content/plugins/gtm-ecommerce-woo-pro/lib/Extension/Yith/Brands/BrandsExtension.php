<?php

namespace GtmEcommerceWooPro\Lib\Extension\Yith\Brands;

use GtmEcommerceWoo\Lib\GaEcommerceEntity\Item;
use GtmEcommerceWooPro\Lib\Extension\AbstractExtension;
use WC_Product;
use WC_Product_Variation;
use WP_Error;
use WP_Term;

class BrandsExtension extends AbstractExtension {

	const SUPPORTED_PLUGIN_NAME = 'yith-woocommerce-brands-add-on/init.php';

	const SUPPORTED_PLUGIN_VERSION = '2.11.0';

	public function init() {
		add_filter('gtm_ecommerce_woo_item', [$this, 'addBrand'], 10, 2);
	}

	public function addBrand( Item $item, WC_Product $product) {
		if (null !== $item->itemBrand) {
			return $item;
		}

		$productId = $product instanceof WC_Product_Variation ? $product->get_parent_id() : $product->get_id();

		$terms = wp_get_post_terms($productId, 'yith_product_brand');

		if (true === $terms instanceof WP_Error || true === empty($terms)) {
			return $item;
		}

		$brands = array_map(static function ( WP_Term $term) {
			return $term->name;
		}, $terms);

		$item->setItemBrand($brands[count($brands) - 1]);

		return $item;
	}
}

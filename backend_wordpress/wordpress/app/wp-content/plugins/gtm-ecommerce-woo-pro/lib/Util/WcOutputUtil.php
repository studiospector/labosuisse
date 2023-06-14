<?php

namespace GtmEcommerceWooPro\Lib\Util;

use Exception;
use GtmEcommerceWoo\Lib\Util\WcOutputUtil as FreeWcOutputUtil;

class WcOutputUtil extends FreeWcOutputUtil {
	protected $pluginDir =  __DIR__;

	public function setCartItems( array $items) {
		$value = json_encode($items);
		$this->scripts[] = <<< EOT
		window.gtm_ecommerce_pro.items.cart = ${value};
EOT;
	}

	public function setItemsByAttributes( array $items) {
		$value = json_encode($items);
		$this->scripts[] = <<< EOT
		window.gtm_ecommerce_pro.items.byAttributeId = ${value};
EOT;
	}

	public function addItems( array $items, $key) {
		$keys = [
			'id' => 'byId',
			'product_id' => 'byProductId',
		];

		if (false === isset($keys[$key])) {
			throw new Exception(sprintf("Item storage by '%s' key is not implemented.", $key));
		}

		$objectKey = $keys[$key];
		$value = json_encode($items);
		$this->scripts[] = <<< EOT
		window.gtm_ecommerce_pro.items.${objectKey} = {
			...window.gtm_ecommerce_pro.items.${objectKey},
			...${value}
		};
EOT;
	}
}

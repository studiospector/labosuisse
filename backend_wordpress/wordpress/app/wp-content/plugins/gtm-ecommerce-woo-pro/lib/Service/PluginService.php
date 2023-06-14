<?php

namespace GtmEcommerceWooPro\Lib\Service;

/**
 * Logic to handle embedding Gtm Snippet
 */
class PluginService extends \GtmEcommerceWoo\Lib\Service\PluginService {
	protected $feedbackUrl = 'https://woocommerce.com/products/google-tag-manager-for-woocommerce-pro/#reviews';
	protected $feedbackDays = 14;

	public function initialize() {
		parent::initialize();

		$this->wcOutputUtil->scriptFile('gtm-ecommerce-woo-pro');
	}
}

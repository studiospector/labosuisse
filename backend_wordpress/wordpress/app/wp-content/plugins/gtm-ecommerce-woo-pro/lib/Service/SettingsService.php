<?php

namespace GtmEcommerceWooPro\Lib\Service;

use GtmEcommerceWoo\Lib\Service\SettingsService as FreeSettingsService;

/**
 * Logic related to working with settings and options
 */
class SettingsService extends FreeSettingsService {

	protected $uuidPrefix = 'gtm-ecommerce-woo-pro';

	protected $allowServerTracking = true;

	protected $filter = 'advanced';

	protected $eventsConfig = [
		'abandon_cart' => [
			'default_disabled' => true,
			'description' => 'Fires on cart summary page after tab/browser close.',
		],
		'abandon_checkout' => [
			'default_disabled' => true,
			'description' => 'Fires on checkout form page after tab/browser close.',
		]
	];
}

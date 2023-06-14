<?php

namespace GtmEcommerceWooPro\Lib\Service;

use GtmEcommerceWooPro\Lib\Extension\AbstractExtension;
use GtmEcommerceWooPro\Lib\Extension\Automattic as AutomatticExtensions;
use GtmEcommerceWooPro\Lib\Extension\Themesquad as ThemesquadExtensions;
use GtmEcommerceWooPro\Lib\Extension\Yith as YithExtensions;
use GtmEcommerceWooPro\Lib\Util\WcOutputUtil;
use GtmEcommerceWooPro\Lib\Util\WcTransformerUtil;

class ExtensionService {

	private $availableExtensions = [
		AutomatticExtensions\Brands\BrandsExtension::class,
		ThemesquadExtensions\QuickView\QuickViewExtension::class,
		YithExtensions\Brands\BrandsExtension::class,
		YithExtensions\BrandsPremium\BrandsPremiumExtension::class,
	];

	/**
	 * Array of AbstractExtension objects.
	 *
	 * @var AbstractExtension[]
	 */
	private $loadedExtensions = [];

	/**
	 * WcTransformerUtil
	 *
	 * @var WcTransformerUtil
	 */
	private $wcTransformerUtil;

	/**
	 * WcOutputUtil
	 *
	 * @var WcOutputUtil
	 */
	private $wcOutputUtil;

	public function __construct ( WcTransformerUtil $wcTransformerUtil, WcOutputUtil $wcOutputUtil) {
		$this->wcTransformerUtil = $wcTransformerUtil;
		$this->wcOutputUtil = $wcOutputUtil;
	}

	public function loadExtensions() {
		if ( ! function_exists( 'get_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$allPlugins = get_plugins();

		foreach (get_option('active_plugins') as $activePluginName) {
			$plugin = $allPlugins[$activePluginName];

			/**
			 * AbstractExtension
			 *
			 * @var AbstractExtension $availableExtension
			 */
			foreach ($this->availableExtensions as $availableExtension) {
				if (true === $availableExtension::supports($activePluginName, $plugin['Version'])) {
					$extension = new $availableExtension($this->wcTransformerUtil, $this->wcOutputUtil);
					$extension->init();

					$this->loadedExtensions[] = $extension;
				}
			}
		}
	}

	public function getEventStrategies() {
		$eventStrategies = [];

		foreach ($this->loadedExtensions as $loadedExtension) {
			$eventStrategies = array_merge($eventStrategies, $loadedExtension->getEventStrategies());
		}

		return $eventStrategies;
	}
}

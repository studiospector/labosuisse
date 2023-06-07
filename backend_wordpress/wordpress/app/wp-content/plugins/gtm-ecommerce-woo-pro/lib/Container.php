<?php

namespace GtmEcommerceWooPro\Lib;

use GtmEcommerceWoo\Lib\Container as FreeContainer;
use GtmEcommerceWoo\Lib\Service\EventInspectorService;
use GtmEcommerceWoo\Lib\Util\WpSettingsUtil;
use GtmEcommerceWooPro\Lib\Service\EventStrategiesService;
use GtmEcommerceWooPro\Lib\Service\ExtensionService;
use GtmEcommerceWooPro\Lib\Service\GtmSnippetService;
use GtmEcommerceWooPro\Lib\Service\PluginService;
use GtmEcommerceWooPro\Lib\Service\SettingsService;
use GtmEcommerceWooPro\Lib\Util\MpClientUtil;
use GtmEcommerceWooPro\Lib\Util\WcOutputUtil;
use GtmEcommerceWooPro\Lib\Util\WcTransformerUtil;

use GtmEcommerceWooPro\Lib\EventStrategy\Browser as BrowserEventStrategy;
use GtmEcommerceWooPro\Lib\EventStrategy\Server as ServerEventStrategy;

class Container extends FreeContainer {
	/**
	 * ExtensionService
	 *
	 * @var ExtensionService
	 */
	private $extensionService;

	public function __construct( $pluginVersion ) {
		$snakeCaseNamespace = 'gtm_ecommerce_woo';
		$spineCaseNamespace = 'gtm-ecommerce-woo';
		$tagConciergeApiUrl = getenv('TAG_CONCIERGE_API_URL') ? getenv('TAG_CONCIERGE_API_URL') : 'https://api.tagconcierge.com';

		$wpSettingsUtil = new WpSettingsUtil( $snakeCaseNamespace, $spineCaseNamespace );
		$wcTransformerUtil = new WcTransformerUtil();
		$wcOutputUtil = new WcOutputUtil($pluginVersion);
		$mpClientUtil = new MpClientUtil( $snakeCaseNamespace, $spineCaseNamespace );

		$this->extensionService = new ExtensionService($wcTransformerUtil, $wcOutputUtil);
		$this->extensionService->loadExtensions();

		$eventStrategies = array_merge([
			new BrowserEventStrategy\ViewItemListStrategy( $wcTransformerUtil, $wcOutputUtil ),
			new BrowserEventStrategy\SelectItemStrategy( $wcTransformerUtil, $wcOutputUtil ),
			new BrowserEventStrategy\ViewItemStrategy( $wcTransformerUtil, $wcOutputUtil ),
			new BrowserEventStrategy\AddToCartStrategy( $wcTransformerUtil, $wcOutputUtil ),
			new BrowserEventStrategy\ViewCartStrategy( $wcTransformerUtil, $wcOutputUtil ),
			new BrowserEventStrategy\RemoveFromCartStrategy( $wcTransformerUtil, $wcOutputUtil ),
			new BrowserEventStrategy\BeginCheckoutStrategy( $wcTransformerUtil, $wcOutputUtil ),
			new BrowserEventStrategy\AddBillingInfoStrategy( $wcTransformerUtil, $wcOutputUtil ),
			new BrowserEventStrategy\AddPaymentInfoStrategy( $wcTransformerUtil, $wcOutputUtil ),
			new BrowserEventStrategy\AddShippingInfoStrategy( $wcTransformerUtil, $wcOutputUtil ),
			new BrowserEventStrategy\PurchaseStrategy( $wcTransformerUtil, $wcOutputUtil ),
			new BrowserEventStrategy\AbandonCartStrategy( $wcTransformerUtil, $wcOutputUtil ),
			new BrowserEventStrategy\AbandonCheckoutStrategy( $wcTransformerUtil, $wcOutputUtil ),
			// new ServerEventStrategy\AddToCartStrategy( $wcTransformerUtil, $wcOutputUtil, $mpClientUtil ),
			new ServerEventStrategy\PurchaseStrategy( $wcTransformerUtil, $wcOutputUtil, $mpClientUtil ),
			// new ServerEventStrategy\RemoveFromCartStrategy( $wcTransformerUtil, $wcOutputUtil, $mpClientUtil ),
			// new BrowserEventStrategy\RefundStrategy($wcTransformerUtil, $wcOutputUtil),
		], $this->extensionService->getEventStrategies());

		$events = array_reduce($eventStrategies, static function( $agg, $eventStrategy ) {
			if ('server' === $eventStrategy->getEventType()) {
				$agg['server'][] = $eventStrategy->getEventName();
			} else {
				$agg['browser'][] = $eventStrategy->getEventName();
			}
			return $agg;
		}, ['browser' => [], 'server' => []]);

		$this->eventStrategiesService = new EventStrategiesService( $wpSettingsUtil, $wcOutputUtil, $eventStrategies );
		$this->gtmSnippetService = new GtmSnippetService( $wpSettingsUtil );
		$this->settingsService = new SettingsService( $wpSettingsUtil, $events['browser'], [], $events['server'], $tagConciergeApiUrl, $pluginVersion);
		$this->pluginService = new PluginService($spineCaseNamespace, $wpSettingsUtil, $wcOutputUtil, $pluginVersion);
		$this->eventInspectorService = new EventInspectorService( $wpSettingsUtil, $wcOutputUtil );

		add_action( 'init', function () {
			/**
			 * Hook that allows to get access to all objects within plugin's dependency injection container
			 *
			 * @since 1.6.0
			 */
			do_action('gtm_ecommerce_woo_container', $this);
		} );
	}
}

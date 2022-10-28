<?php

namespace GtmEcommerceWooPro\Lib;

use GtmEcommerceWoo\Lib\EventStrategy;
use GtmEcommerceWooPro\Lib\Service\EventStrategiesService;
use GtmEcommerceWooPro\Lib\Service\GtmSnippetService;
use GtmEcommerceWooPro\Lib\Service\SettingsService;
use GtmEcommerceWooPro\Lib\Service\PluginService;
use GtmEcommerceWoo\Lib\Service\ThemeValidatorService;
use GtmEcommerceWoo\Lib\Service\EventInspectorService;
use GtmEcommerceWooPro\Lib\Service\MonitorService;

use GtmEcommerceWoo\Lib\Util\WpSettingsUtil;
use GtmEcommerceWoo\Lib\Util\WcOutputUtil;
use GtmEcommerceWooPro\Lib\Util\WcTransformerUtil;
use GtmEcommerceWooPro\Lib\Util\MpClientUtil;

use GtmEcommerceWooPro\Lib\EventStrategy as EventStrategyPro;

class Container extends \GtmEcommerceWoo\Lib\Container {
	public function __construct( $pluginVersion ) {
		$snakeCaseNamespace = 'gtm_ecommerce_woo';
		$spineCaseNamespace = 'gtm-ecommerce-woo';
		$tagConciergeApiUrl = getenv('TAG_CONCIERGE_API_URL') ? getenv('TAG_CONCIERGE_API_URL') : 'https://api.tagconcierge.com';
		$tagConciergeEdgeUrl = getenv('TAG_CONCIERGE_EDGE_URL') ? getenv('TAG_CONCIERGE_EDGE_URL') : 'https://edge.tagconcierge.com';


		$wpSettingsUtil    = new WpSettingsUtil( $snakeCaseNamespace, $spineCaseNamespace );
		$wcTransformerUtil = new WcTransformerUtil();
		$wcOutputUtil      = new WcOutputUtil();
		$mpClientUtil      = new MpClientUtil( $snakeCaseNamespace, $spineCaseNamespace );

		$eventStrategies = array(
			new EventStrategyPro\ViewItemListStrategy( $wcTransformerUtil, $wcOutputUtil ),
			new EventStrategyPro\SelectItemStrategy( $wcTransformerUtil, $wcOutputUtil ),
			new EventStrategyPro\ViewItemStrategy( $wcTransformerUtil, $wcOutputUtil ),
			new EventStrategyPro\AddToCartStrategy( $wcTransformerUtil, $wcOutputUtil ),
			new EventStrategyPro\ViewCartStrategy( $wcTransformerUtil, $wcOutputUtil ),
			new EventStrategyPro\RemoveFromCartStrategy( $wcTransformerUtil, $wcOutputUtil ),
			new EventStrategyPro\BeginCheckoutStrategy( $wcTransformerUtil, $wcOutputUtil ),
			new EventStrategyPro\AddBillingInfoStrategy( $wcTransformerUtil, $wcOutputUtil ),
			new EventStrategyPro\AddPaymentInfoStrategy( $wcTransformerUtil, $wcOutputUtil ),
			new EventStrategyPro\AddShippingInfoStrategy( $wcTransformerUtil, $wcOutputUtil ),
			new EventStrategyPro\PurchaseStrategy( $wcTransformerUtil, $wcOutputUtil ),
			// new EventStrategyPro\AddToCartServerStrategy( $wcTransformerUtil, $wcOutputUtil, $mpClientUtil ),
			new EventStrategyPro\PurchaseServerStrategy( $wcTransformerUtil, $wcOutputUtil, $mpClientUtil ),
			// new EventStrategyPro\RemoveFromCartServerStrategy( $wcTransformerUtil, $wcOutputUtil, $mpClientUtil ),
			// new EventStrategyPro\RefundStrategy($wcTransformerUtil, $wcOutputUtil),
		);

		$events = array_reduce($eventStrategies, function( $agg, $eventStrategy ) {
			if ('server' === $eventStrategy->getEventType()) {
				$agg['server'][] = $eventStrategy->getEventName();
			} else {
				$agg['web'][] = $eventStrategy->getEventName();
			}
			return $agg;
		}, ['web' => [], 'server' => []]);

		$this->eventStrategiesService = new EventStrategiesService( $wpSettingsUtil, $eventStrategies );
		$this->gtmSnippetService      = new GtmSnippetService( $wpSettingsUtil );
		$this->settingsService        = new SettingsService( $wpSettingsUtil, $events['web'], [], $events['server'], $tagConciergeApiUrl, $pluginVersion);
		$this->pluginService          = new PluginService($spineCaseNamespace, $wpSettingsUtil, $pluginVersion);
		$this->themeValidatorService  = new ThemeValidatorService($snakeCaseNamespace, $spineCaseNamespace, $wcTransformerUtil, $wpSettingsUtil, $wcOutputUtil, $events, $tagConciergeApiUrl, $pluginVersion );
		$this->eventInspectorService  = new EventInspectorService( $wpSettingsUtil );
		$this->monitorService = new MonitorService($snakeCaseNamespace, $spineCaseNamespace, $wcTransformerUtil, $wpSettingsUtil, $wcOutputUtil, $tagConciergeApiUrl, $tagConciergeEdgeUrl );
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

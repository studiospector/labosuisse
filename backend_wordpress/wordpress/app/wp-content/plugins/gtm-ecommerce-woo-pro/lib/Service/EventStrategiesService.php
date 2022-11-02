<?php

namespace GtmEcommerceWooPro\Lib\Service;

/**
 * General Logic of the plugin
 */
class EventStrategiesService extends \GtmEcommerceWoo\Lib\Service\EventStrategiesService {
	public function getEventStrategy( $eventStrategyName ) {
		foreach ($this->eventStrategies as $eventStrategy) {
			if ($eventStrategyName === $eventStrategy->getEventName()) {
				return $eventStrategy;
			}
		}
		return null;
	}
}

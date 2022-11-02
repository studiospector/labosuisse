<?php

namespace GtmEcommerceWooPro\Lib\EventStrategy;

use GtmEcommerceWoo\Lib\GaEcommerceEntity\Event;

/**
 * When user sees item details
 * https://developers.google.com/tag-manager/ecommerce-ga4#measure_viewsimpressions_of_productitem_details
 * https://developers.google.com/gtagjs/reference/ga4-events#view_item
 */
class ViewItemStrategy extends \GtmEcommerceWoo\Lib\EventStrategy\AbstractEventStrategy {

	protected $eventName = 'view_item';

	public function defineActions() {
		return array(
			'the_post' => [[$this, 'beforeSingleProduct'], 11 ],
		);
	}

	public function initialize() {
		$this->firstPost = false;
	}

	public function beforeSingleProduct() {
		global $product;
		// if product is null then this must be other WP post
		if (is_null($product)) {
			return false;
		}
		if (is_product() && false === $this->firstPost) {
			$this->firstPost = true;
			$item  = $this->wcTransformer->getItemFromProduct( $product );
			$event = new Event( 'view_item' );
			$event->addItem( $item );
			$this->wcOutput->dataLayerPush( $event );
		}
	}

}

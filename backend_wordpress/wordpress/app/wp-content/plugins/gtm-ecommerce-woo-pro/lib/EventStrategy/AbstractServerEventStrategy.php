<?php

namespace GtmEcommerceWooPro\Lib\EventStrategy;

abstract class AbstractServerEventStrategy extends \GtmEcommerceWoo\Lib\EventStrategy\AbstractEventStrategy {

	protected $wcOutput;

	public function __construct( $wcTransformer, $wcOutput, $mpClient ) {
		$this->mpClient = $mpClient;
		parent::__construct($wcTransformer, $wcOutput);
	}
}

<?php

namespace GtmEcommerceWooPro\Lib\Service;

/**
 * Logic related to working with settings and options
 */
class SettingsService extends \GtmEcommerceWoo\Lib\Service\SettingsService {

	public function __construct( $wpSettingsUtil, $events, $proEvents, $serverEvents, $tagConciergeApiUrl, $pluginVersion ) {
		$this->wpSettingsUtil = $wpSettingsUtil;
		$this->uuidPrefix     = 'gtm-ecommerce-woo-pro';
		$this->events = $events;
		$this->proEvents = $proEvents;
		$this->serverEvents = $serverEvents;
		$this->allowServerTracking = true;
		$this->tagConciergeApiUrl = $tagConciergeApiUrl;
		$this->tagConciergeMonitorPreset = 'presets/tag-concierge-monitor-advanced';
		$this->pluginVersion = $pluginVersion;
	}


	public function ajaxGetPresets() {
		$uuid     = $this->wpSettingsUtil->getOption( 'uuid' );
		$response = wp_remote_get( $this->tagConciergeApiUrl . '/v2/presets?filter=advanced&uuid=' . $uuid );
		$body     = wp_remote_retrieve_body( $response );
		wp_send_json( json_decode( $body ) );
		wp_die();
	}

	public function ajaxPostPresets() {
		$uuid            = $this->wpSettingsUtil->getOption( 'uuid' );
		$disabled        = $this->wpSettingsUtil->getOption( 'disabled' );
		$gtmSnippetHead  = $this->wpSettingsUtil->getOption( 'gtm_snippet_head' );
		$gtmSnippetBody  = $this->wpSettingsUtil->getOption( 'gtm_snippet_body' );
		$requestedPreset = isset( $_GET['preset'] ) ? sanitize_text_field( $_GET['preset'] ) : null;
		$presetName      = str_replace( 'presets/', '', $requestedPreset ) . '.json';
		$args            = array(
			'body'        => json_encode(
				array(
					'preset'  => $requestedPreset,
					'filter'  => 'advanced',
					'uuid'    => $uuid,
					'version' => $this->pluginVersion,
				)
			),
			'headers'     => array(
				'content-type' => 'application/json',
			),
			'data_format' => 'body',
		);
		$response        = wp_remote_post( $this->tagConciergeApiUrl . '/v2/preset', $args );
		$body            = wp_remote_retrieve_body( $response );
		header( 'Cache-Control: public' );
		header( 'Content-Description: File Transfer' );
		header( 'Content-Disposition: attachment; filename=' . $presetName );
		header( 'Content-Transfer-Encoding: binary' );
		wp_send_json( json_decode( $body ) );
		wp_die();
	}

	public function optionsPage() {
		$this->wpSettingsUtil->addSubmenuPage(
			'options-general.php',
			'Google Tag Manager for WooCommerce PRO',
			'Google Tag Manager',
			'manage_options'
		);
	}
}

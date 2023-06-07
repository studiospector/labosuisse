<?php

namespace GtmEcommerceWooPro\Lib\Util;

use GtmEcommerceWoo\Lib\Util\WpSettingsUtil;

/**
 * MeasurementProtocol Utility
 */
class MpClientUtil extends WpSettingsUtil {
	public function getClientId() {
		if (isset($_COOKIE['_ga'])) {
			return str_replace('GA1.2.', '', sanitize_key($_COOKIE['_ga']));
		}

		if (isset(WC()->session) && WC()->session->get_session_cookie()) {
			return implode('.', WC()->session->get_session_cookie());
		}

		return vsprintf( '%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(16)), 4) );
	}

	public function sendEvent( $event, $clientId = null ) {
		if (null === $clientId) {
			$clientId = $this->getClientId();
		}

		$rawEvent = $event->jsonSerialize();

		$serverEvent = [
			'name' => $event->name,
			'params' => $rawEvent['ecommerce']
		];

		unset($serverEvent['params']['purchase']);

		$url = rtrim($this->getOption('gtm_server_container_url'), '/');
		$path = ltrim($this->getOption('gtm_server_ga4_client_activation_path'), '/');
		$args = [
			'body' => json_encode([
				'client_id' => $clientId,
				'v' => 2,
				'events' => [$serverEvent]
			]),
			'headers' => [
				'content-type' => 'application/json'
			],
			'data_format' => 'body',
		];
		wp_remote_post(implode('/', [$url, $path]), $args);
	}
}

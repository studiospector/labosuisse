<?php

namespace GtmEcommerceWooPro\Lib\Service;

/**
 * Logic to handle embedding Gtm Snippet
 */
class GtmSnippetService extends \GtmEcommerceWoo\Lib\Service\GtmSnippetService {

	public function initialize() {
		parent::initialize();
		add_action(
			'rest_api_init',
			function () {
				register_rest_route(
					'gtm-ecommerce-woo/v1',
					'/container/(?P<event>.*)',
					array(
						'methods'             => 'GET',
						'callback'            => array( $this, 'container' ),
						'permission_callback' => '__return_true',
					)
				);
			}
		);
	}

	public function headSnippet() {
		$this->pushUserId();
		parent::headSnippet();
	}

	public function container( $data ) {
		$scriptString = 'dataLayer.push(' . base64_decode( $data['event'] ) . ');';
		// get event from data
		header( 'Content-Type: text/html; charset=' . get_option( 'blog_charset' ) );
		$head = $this->wpSettingsUtil->getOption( 'gtm_snippet_head' );
		$body = $this->wpSettingsUtil->getOption( 'gtm_snippet_body' );
		echo esc_html(<<<EOD
<html>
<head>
$head
</head>
<body>
$body
<script type="text/javascript">
window.dataLayer = window.dataLayer || [];
$scriptString
</script>
</body>
</html>
EOD
);
		wp_die();
	}

	private function pushUserId() {
		$isUserTrackingEnabled = (bool) $this->wpSettingsUtil->getOption('track_user_id');

		if (false === $isUserTrackingEnabled) {
			return;
		}

		$userId = get_current_user_id();

		if (0 === $userId) {
			return;
		}

		echo sprintf(
			'<script>var dataLayer = dataLayer || [];dataLayer.push({"event":"user_id","user_id":"%s"});</script>',
			(int) $userId
		);
	}
}

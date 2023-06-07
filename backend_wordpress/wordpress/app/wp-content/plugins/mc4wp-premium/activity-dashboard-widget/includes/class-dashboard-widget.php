<?php

/**
 * Class Widget
 * @package MC4WP\Activity
 *
 */
class MC4WP_ADW_Dashboard_Widget {

	/**
	 * @var string
	 */
	protected $plugin_file;

	/**
	 * @var string
	 */
	protected $plugin_version;

	/**
	 * @param string $plugin_file
	 * @param string $plugin_version
	 */
	public function __construct( $plugin_file, $plugin_version ) {
		$this->plugin_file = $plugin_file;
		$this->plugin_version = $plugin_version;
	}

	/**
	 * Add hooks
	 */
	public function add_hooks() {
		add_action( 'wp_dashboard_setup', array( $this, 'register_dashboard_widget' ) );
		add_action( 'wp_ajax_mc4wp_get_activity', array( $this, 'ajax_handler' ) );
	}

	/**
	 * @return bool
	 */
	public function user_has_required_capability() {
		$capability = 'manage_options';

		/**
		 * Filters the required capability for showing the Activity widget.
		 *
		 * Defaults to `manage_options`
		 *
		 * @param string $capability
		 */
		$capability = (string) apply_filters( 'mc4wp_activity_capability', $capability );

		return current_user_can( $capability );
	}

	/**
	 * Register self as dashboard widget
	 */
	public function register_dashboard_widget() {
		// do nothing if authenticated user does not have required capability
		if ( ! $this->user_has_required_capability() ) {
			return;
		}

		// do nothing if API key is not set
		$options = mc4wp_get_options();
		if( empty( $options['api_key'] ) ) {
			return;
		}

//		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		wp_add_dashboard_widget(
			'mc4wp_activity_widget',         // Widget slug.
			'Mailchimp Activity',         // Title.
			array( $this, 'output' ) // Display function.
		);
	}

	/**
	 * Enqueues related JS assets
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( 'mc4wp-activity', plugins_url(  "/assets/js/dashboard-widget.js", $this->plugin_file ), array( 'jquery' ), $this->plugin_version, true );
	}

	/**
	 * Output widget HTML
	 */
	public function output() {
		$mailchimp = new MC4WP_MailChimp();
		$mailchimp_lists = $mailchimp->get_lists();
		$view_options = array(
			'activity' => __( 'Activity', 'mailchimp-activity' ),
			'size' => __( 'Size', 'mailchimp-activity' )
		);

		$period_label = __( '%d days', 'mailchimp-for-wp' );
		$period_options = array(
			30 => sprintf( $period_label, 30 ),
			60 => sprintf( $period_label, 60 ),
			90 => sprintf( $period_label, 90 ),
			180 => sprintf( $period_label, 180 ),
		);

		// <Wrapper>
		echo '<div style="margin: 0 auto 20px; text-align: center;">';

		// List <select>
		echo '<div style="display: inline-block; max-width: 33%;">';
		echo '<label for="mc4wp-activity-mailchimp-list">' . __( 'Select Mailchimp list', 'mailchimp-activity' ) . '</label>';
		echo '<br />';
		echo '<select id="mc4wp-activity-mailchimp-list">';
		echo '<option disabled>' . __( 'Mailchimp list', 'mailchimp-for-wp' ) . '</option>';
		foreach ( $mailchimp_lists as $list ) {
			echo sprintf( '<option value="%s">%s</option>', $list->id, $list->name );
		}
		echo '</select>';
		echo '</div>';

		// View <select>
		echo '<div style="display: inline-block;">';

		echo '<label for="mc4wp-activity-view" >' . __( 'Select view', 'mailchimp-activity' ) . '</label>';
		echo '<br />';
		echo '<select id="mc4wp-activity-view">';
		echo '<option disabled>' . __( 'View', 'mailchimp-for-wp' ) . '</option>';
		foreach( $view_options as $value => $label ) {
			echo sprintf( '<option value="%s">%s</option>', $value, $label );
		}
		echo '</select>';
		echo '</div>';

		// Period <select>
		echo '<div style="display: inline-block;">';

		echo '<label for="mc4wp-activity-period" >' . __( 'Select period', 'mailchimp-activity' ) . '</label>';
		echo '<br />';
		echo '<select id="mc4wp-activity-period">';
		echo '<option disabled>' . __( 'Period', 'mailchimp-for-wp' ) . '</option>';
		foreach( $period_options as $value => $label ) {
			echo sprintf( '<option value="%s">%s</option>', $value, $label );
		}
		echo '</select>';
		echo '</div>';

		// </Wrapper>
		echo '</div>';

		echo '<div id="mc4wp-activity-chart"><p class="help">' . __( 'Loading..', 'mailchimp-activity' ) . '</p></div>';

		// load JS assets
		$this->enqueue_scripts();

	}

	function ajax_handler() {
		if ( ! $this->user_has_required_capability() ) {
			wp_send_json_error();
			return;
		}

		$list_id   = (string) $_GET['mailchimp_list_id'];
		$period  = isset( $_GET['period'] ) ? (int) $_GET['period'] : 30;
		$api = mc4wp_get_api_v3();
		$raw_data = $api->get_list_activity( $list_id, array( 'count' => $period ) );

		if( $_REQUEST['view'] === 'activity' ) {
			$data      = new MC4WP_ADW_Activity_Data( $raw_data, $period );
		} else {
			$mailchimp = new MC4WP_MailChimp();
			$list = $mailchimp->get_list( $list_id );
			$data      = new MC4WP_ADW_Size_Data( $raw_data, $list->stats->member_count, $period );
		}

		wp_send_json_success( $data->to_array() );
	}
}

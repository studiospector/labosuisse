<?php
/**
 * CBR Notice 
 *
 * @class   CBR_PRO_Notice
 * @package WooCommerce/Classes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * CBR_PRO_Notice class.
 * 
 * @since 1.0.0
 */
class CBR_PRO_Notice {
	
	/**
	 * Get the class instance
	 *
	 * @since  1.0
	 * @return CBR_PRO_Notice
	*/
	public static function get_instance() {

		if ( null === self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Instance of this class.
	 * 
	 * @since 1.0.0
	 * @var object Class Instance
	*/
	private static $instance;
	
	/*
	 * construct function
	 * 
	 * @since 1.0.0
	*/
	function __construct() {
		$this->init();
    }

	/*
	 * init function
	 * 
	 * @since 1.0.0
	*/
    function init() {

		//callback for notices hook in admin
		add_action( 'admin_notices', array( $this, 'cbr_pro_admin_notice' ) );
		add_action( 'admin_init', array( $this, 'cbr_pro_notice_ignore' ) );

    }
	
	/*
	 * Hide admin notice on dismiss of ignore-notice
	 * 
	 * @since 1.0.0
	*/
	public function cbr_pro_notice_ignore() {
		
		if ( isset( $_GET[ 'cbr-free-remove-notice' ] ) ) {
			update_option( 'cbr_free_remove_notice', 'true' );
		}
		if ( isset( $_GET[ 'cbr-review-ignore-notice' ] ) ) {
			update_option( 'cbr_review_notice_ignore', 'true' );
		}
	}
	
	/**
	 * CBR pro admin notice
	 *
	 * @since 1.0.0
	 */
	function cbr_pro_admin_notice() {
		
		//Display CBR Free notice
		if ( "notice" == get_transient( "free_cbr_plugin" ) ) {
			?>
			<div id="message" class="updated notice is-dismissible">
				<p><?php printf( esc_html( "The Country Based Restrictions free plugin was deactivated since you use the PRO version. You can now remove the Free version.", 'country-base-restrictions-pro-addon' ), '<a href="' . admin_url( 'plugin-install.php?tab=search&s=WooCommerce&plugin-search-input=Search+Plugins' ) . '">', '</a>' ); ?></p>
			</div>
			<?php
		 	delete_transient( "free_cbr_plugin" );
		}
		
		//Display CBR Free remove notice
		if ( ! get_option( 'cbr_free_remove_notice' ) ) {
			if ( is_plugin_active('woo-product-country-base-restrictions/woocommerce-product-country-base-restrictions.php' ) ) {
				$dismissable_url = esc_url(  add_query_arg( 'cbr-free-remove-notice', 'true' ) );
				?>
				<style>		
				.wp-core-ui .notice.cbr-dismissable-notice{
					position: relative;
					padding-right: 38px;
				}
				.wp-core-ui .notice.cbr-dismissable-notice a.notice-dismiss{
					padding: 9px;
					text-decoration: none;
				} 
				.wp-core-ui .button-primary.btn_review_notice {
					background: transparent;
					color: #03aa6f;
					border-color: #03aa6f;
					text-transform: uppercase;
					padding: 0 11px;
					font-size: 12px;
					height: 30px;
					line-height: 28px;
					margin: 5px 0 15px;
				}
				</style>	
				<div class="notice updated notice-success cbr-dismissable-notice">
					<a href="<?php echo $dismissable_url; ?>" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></a>
					<p><?php printf( esc_html( "The Country Based Restrictions free plugin is not used since you already have the PRO version installed. Please deactivate and remove the Free version.", 'country-base-restrictions-pro-addon' ), '<a href="' . admin_url( 'plugin-install.php?tab=search&s=WooCommerce&plugin-search-input=Search+Plugins' ) . '">', '</a>' ); ?></p>
					<a class="button-primary btn_review_notice" href="<?php echo $dismissable_url; ?>">Dismiss</a>
				</div>	
				<?php
			} else {
				//CBR Free is deactive
				update_option( 'cbr_free_remove_notice', 'true' );
			}
		}
		
		//Display admin review notice on plugin install or update
		if ( ! get_option( 'cbr_review_notice_ignore' ) ) {
		
			$dismissable_url = esc_url(  add_query_arg( 'cbr-review-ignore-notice', 'true' ) );
			?>		
			<style>		
			.wp-core-ui .notice.cbr-dismissable-notice{
				position: relative;
				padding-right: 38px;
			}
			.wp-core-ui .notice.cbr-dismissable-notice a.notice-dismiss{
				padding: 9px;
				text-decoration: none;
			} 
			.wp-core-ui .button-primary.btn_review_notice {
				background: transparent;
				color: #03aa6f;
				border-color: #03aa6f;
				text-transform: uppercase;
				padding: 0 11px;
				font-size: 12px;
				height: 30px;
				line-height: 28px;
				margin: 5px 0 15px;
			}
			</style>	
			<div class="notice updated notice-success cbr-dismissable-notice">
				<a href="<?php echo $dismissable_url; ?>" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></a>
				<p>Hey, I noticed you are using the Country Based Restrictions Plugin - that’s awesome!</br>Could you please do me a BIG favor and give it a 5-star rating on WordPress to help us spread the word and boost our motivation?</p>
				<p>Eran Shor</br>Founder of zorem</p>
				<a class="button-primary btn_review_notice" target="blank" href="https://wordpress.org/support/plugin/woo-product-country-base-restrictions/reviews/#new-post">Ok, you deserve it</a>
				<a class="button-primary btn_review_notice" href="<?php echo $dismissable_url; ?>">Nope, maybe later</a>
				<a class="button-primary btn_review_notice" href="<?php echo $dismissable_url; ?>">I already did</a>
			</div>
			<?php
		}
	}
}

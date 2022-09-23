<?php
/**
 * CBR pro widget 
 *
 * @class   CBR_PRO_Widget
 * @package WooCommerce/Classes
 */
 
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * CBR_PRO_Widget class
 *
 * @since  1.0
 */
class CBR_PRO_Widget extends WP_Widget {

	/**
	 * Get the class instance
	 *
	 * @since  1.0
	 * @return CBR_PRO_Widget
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
	 * @since  1.0
	 * @var object Class Instance
	*/
	private static $instance;
	
 	/**
	 * Initialize the main plugin function
	 * 
	 * @since  1.0
	*/
    function __construct() {
 
 		/*
		* __construct function
		*
		* @since  1.0
		*/
        parent::__construct(
            'cbr-widget',  // Base ID
            esc_html( 'Country detection widget', 'country-base-restrictions-pro-addon' ),   // Name
			array( 
			'description' => esc_html( 'Display the country detection widget', 'country-base-restrictions-pro-addon' )
			)
        );
 		
		/*
		* init function
		*
		* @since  1.0
		*/
        add_action( 'widgets_init', function() {
			add_shortcode( 'cbr_location_widget', array( $this, 'shortcode_widget' ) );
			add_shortcode( 'cbr_select_countries_widget', array( $this, 'shortcode_country_dropdown' ) );
        });
		
		add_action( 'wp_enqueue_scripts', array( $this, 'cbr_widget_popup_style' ) );
    }
 	
    public $args = array(
        'before_title'  => '<h4 class="widgettitle">',
        'after_title'   => '</h4>',
        'before_widget' => '<div class="widget-wrap">',
        'after_widget'  => '</div></div>'
    );
 	
	/*
	* Add widget in admin
	*
	* @since  1.0
	*/
    public function widget( $args, $instance ) {
		
		$cbrw_label = !empty( get_option( 'cbrw_label_text' ) ) ? get_option( 'cbrw_label_text', 'Delivery to' ) : 'Delivery to';
		$cbrw_popup_header = !empty( get_option( 'cbrwl_header_text' ) ) ? get_option( 'cbrwl_header_text', 'Choose Shipping Country' ) : 'Choose Shipping Country';
		$location = WC_Geolocation::geolocate_ip();
		$country = $location[ 'country' ];
		$cookie_country = !empty( $_COOKIE[ "country" ] ) ? sanitize_text_field( $_COOKIE[ "country" ] ) : $country;
		$text_align = is_rtl() ? get_option( 'cbrwl_text_align', 'right' ) : get_option( 'cbrwl_text_align', 'left' );
		$countries = WC()->countries->get_shipping_countries();
		
		if ( !empty( $cookie_country ) ) { $country = WC()->countries->countries[ $cookie_country ]; }
		
		$widget_title = isset( $instance[ 'widget_title' ] ) ? $instance[ 'widget_title' ] : '';
		$widget_icon = isset( $instance[ 'widget_icon' ] ) ? $instance[ 'widget_icon' ] : '0';
		$widget_border = isset( $instance[ 'widget_border' ] ) ? $instance[ 'widget_border' ] : '0';
		
		echo '<div class="cbr-country-widget country-select"'; if ( '1' == $widget_border ){ echo 'style="border:1px solid #eee;line-height: 20px;"'; } echo '>';
		
		if ( '1' == $widget_icon ){ echo '<span class="flag ' . strtolower($cookie_country) . '" style="vertical-align: middle;"></span>'; }
		
       	echo '<span style="vertical-align: middle;">' . $widget_title . ' <a class="cbr_shipping_country">' . $country . '</a></span>' ;
		
		echo '</div>';
		add_action( 'wp_footer', array( $this, 'cbr_widget_popup_html' ), 50 );
    }
 	
	/*
	* Add widget form for admin
	*
	* @since  1.0
	*/
    public function form( $instance ) {
		
		$widget_title = isset( $instance[ 'widget_title' ] ) ? $instance[ 'widget_title' ] : '';
		$widget_icon = isset( $instance[ 'widget_icon' ] ) ? $instance[ 'widget_icon' ] : '0';
		$widget_border = isset( $instance[ 'widget_border' ] ) ? $instance[ 'widget_border' ] : '0';
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'widget_title' ); ?>"><?php esc_html_e( 'Title:' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'widget_title' ); ?>" name="<?php echo $this->get_field_name( 'widget_title' ); ?>" type="text" value="<?php echo esc_attr( $widget_title ); ?>" />
		</p>
		<p>
			<input class="widefat" id="<?php echo $this->get_field_id( 'widget_icon' ); ?>" name="<?php echo $this->get_field_name( 'widget_icon' ); ?>" type="checkbox" value="1" <?php if($widget_icon == '1'){ echo 'checked';}?>/><label for="<?php echo $this->get_field_id( 'widget_icon' ); ?>"><?php esc_html_e( 'Display Map icon' ); ?></label>
		</p>
		<p>
			<input class="widefat" id="<?php echo $this->get_field_id( 'widget_border' ); ?>" name="<?php echo $this->get_field_name( 'widget_border' ); ?>" type="checkbox" value="1" <?php if($widget_border == '1'){ echo 'checked';}?>/><label for="<?php echo $this->get_field_id( 'widget_border' ); ?>"><?php esc_html_e( 'Display Border' ); ?></label>
		</p>
		<?php 
    }
	
	/*
	* save widget form for admin
	*
	* @since  1.0
	*/
	public function update( $new_instance, $old_instance ) {
		
		$instance = array();
		
		$instance[ 'widget_title' ] = ( ! empty( $new_instance[ 'widget_title' ] ) ) ? strip_tags( $new_instance[ 'widget_title' ] ) : '';
		$instance[ 'widget_icon' ] = ( ! empty( $new_instance[ 'widget_icon' ] ) ) ? strip_tags( $new_instance[ 'widget_icon' ] ) : '0';
		$instance[ 'widget_border' ] = ( ! empty( $new_instance[ 'widget_border' ] ) ) ? strip_tags( $new_instance[ 'widget_border' ] ) : '0';
		
		return $instance;
	}
	
	/**
	 * shortcode for widget
	 *
	 * @since  1.0
	*/
	public function shortcode_widget( $atts ) {
		
		$label = !empty( $atts[ 'label' ] ) ?  $atts[ 'label' ] : '';
		$cbrw_popup_header = !empty( get_option( 'cbrwl_header_text' ) ) ? get_option( 'cbrwl_header_text', 'Choose Shipping Country' ) : 'Choose Shipping Country';	
		$location = WC_Geolocation::geolocate_ip();
		$country = $location[ 'country' ];
		$cookie_country = !empty( $_COOKIE[ "country" ] ) ? sanitize_text_field( $_COOKIE[ "country" ] ) : $country;
		$text_align = is_rtl() ? get_option( 'cbrwl_text_align', 'right' ) : get_option( 'cbrwl_text_align', 'left' );
		$countries = WC()->countries->get_shipping_countries();
		if ( !empty( $cookie_country ) ) { $country = WC()->countries->countries[ $cookie_country ]; }
		
		$widget_title = !empty( $atts[ 'label' ] ) ?  $atts[ 'label' ] : '';
		$widget_icon = !empty( $atts[ 'icon' ]) ?  $atts[ 'icon' ] : '';
		$widget_border = !empty( $atts[ 'border' ] ) ?  $atts[ 'border' ] : '';

		ob_start();
		
		echo '<div class="cbr-country-shortcode country-select"'; if ( 'show' == $widget_border ){ echo 'style="border:1px solid #eee;padding: 5px 10px 5px 5px;"'; } echo '>';
		
		if ( 'show' == $widget_icon ){ echo '<span class="flag ' . strtolower($cookie_country) . '" style="vertical-align: middle;"></span>'; }
		
       	echo '<span style="vertical-align: middle;">' . $widget_title . ' <a class="cbr_shipping_country">' . $country . '</a></span>' ;
		
		echo '</div>';
		
		$html = ob_get_clean();	
		add_action( 'wp_footer', array( $this, 'cbr_widget_popup_html' ), 50 );
		add_action( 'get_footer', array( $this, 'cbr_widget_popup_style' ), 50 );
		return $html;
    }
	
	/**
	 * shortcode for country dropdown
	 *
	 * @since  1.0
	*/
	public function shortcode_country_dropdown( $atts ) {
		$location = WC_Geolocation::geolocate_ip();
		$country = $location[ 'country' ];
		$cookie_country = !empty( $_COOKIE[ "country" ] ) ? sanitize_text_field( $_COOKIE[ "country" ] ) : $country;
		$countries = WC()->countries->get_shipping_countries();
		
		if ( !empty( $cookie_country ) ) { $country = WC()->countries->countries[ $cookie_country ]; }
		
		asort( $countries );
		
		if ( ( isset( $atts[ 'refresh' ] ) && '1' == $atts[ 'refresh' ] ) || !isset( $atts[ 'refresh' ] ) ) {
			$class = "widget-country";
			$onchange = "setCountryCookie('country', this.value, 365)";
		} else {
			$class = "select-country-dropdown";
			$onchange = "setCookie('country', this.value, 365)";
		} 
		?>
		<div id="only-country-dropdown">
		<select class="select2 cbr-select2 <?php echo $class; ?> only-country-dropdown" onchange="<?php echo $onchange; ?>">
			<option value=""><?php echo esc_html_e( 'Select Country', 'country-base-restrictions-pro-addon' ); ?></option>
			<?php foreach ( $countries as $key => $val ) { ?>
				 <option value="<?php echo $key;?>" <?php if ( isset( $cookie_country ) && $cookie_country == $key ){ echo 'selected'; }?>><?php echo $val; ?></option>
			<?php } ?>
		</select></div>	
        <?php 
		add_action( 'get_footer', array( $this, 'cbr_widget_popup_style' ), 50 );
    }
	
	/**
	 * Convert hexdec color string to rgb(a) string 
	 *
	 * @since  1.0
	 */
	public function hex2rgba( $color, $opacity = false ) {
	
		$default = 'rgba(116,194,225,0.7)';
	
		//Return default if no color provided
		if ( empty( $color ) ) {
			return $default;
		}
	
		//Sanitize $color if "#" is provided 
		if ( '#' == $color[ 0 ] ) {
			$color = substr( $color, 1 );
		}

		//Check if color has 6 or 3 characters and get values
		if ( 6 == strlen( $color ) ) {
			$hex = array( $color[ 0 ] . $color[ 1 ], $color[ 2 ] . $color[ 3 ], $color[ 4 ] . $color[ 5 ] );
		} elseif ( 3 == strlen( $color ) ) {
			$hex = array( $color[ 0 ] . $color[ 0 ], $color[ 1 ] . $color[ 1 ], $color[ 2 ] . $color[ 2 ] );
		} else {
			return $default;
		}

		//Convert hexadec to rgb
		$rgb =  array_map( 'hexdec', $hex );

		//Check if opacity is set(rgba or rgb)
		if ( $opacity ) {
			if ( 1 < abs( $opacity ) ) {
				$opacity = 1.0;
			}
			$output = 'rgba(' . implode( ",", $rgb ) . ',' . $opacity . ')';
		} else {
			$output = 'rgb(' . implode( ",", $rgb ) . ')';
		}

		//Return rgb(a) color string
		return $output;
	}
	
	public function cbr_widget_popup_style() {
		
		wp_enqueue_script( 'cbr-pro-front-js', cbr_pro_addon()->plugin_dir_url() . 'assets/js/front.js', array( 'jquery' ), cbr_pro_addon()->version, true );
		wp_localize_script( 'cbr-pro-front-js', 'cbr_ajax_object', array( 'cbr_ajax_url' => admin_url( 'admin-ajax.php' ) ) );
		wp_localize_script( 'cbr-pro-front-js', 'path_object', array( 'pluginsUrl' => cbr_pro_addon()->plugin_dir_url() ) );
		
		wp_enqueue_style( 'cbr-pro-front-css', cbr_pro_addon()->plugin_dir_url() . 'assets/css/front.css', array(), cbr_pro_addon()->version );
		wp_enqueue_style( 'dashicons' );
		
		wp_enqueue_style('select2-cbr', cbr_pro_addon()->plugin_dir_url() . 'assets/css/select2.min.css');
		wp_enqueue_script('select2-cbr', cbr_pro_addon()->plugin_dir_url() . 'assets/js/select2.min.js');
		
	}
	
	public function cbr_widget_popup_html(){
		$location = WC_Geolocation::geolocate_ip();
		$country = $location[ 'country' ];
		$cookie_country = !empty( $_COOKIE[ "country" ] ) ? sanitize_text_field( $_COOKIE[ "country" ] ) : $country;
		$cbrw_popup_header = !empty( get_option( 'cbrwl_header_text' ) ) ? get_option( 'cbrwl_header_text', 'Choose Shipping Country' ) : 'Choose Shipping Country';
		$text_align = is_rtl() ? get_option( 'cbrwl_text_align', 'right' ) : get_option( 'cbrwl_text_align', 'left' );
		$countries = WC()->countries->get_shipping_countries();
		?>
		<style>
			@media only screen and (max-width: 500px) {
				.popuprow{
					width: 300px !important;
				}
				.popupwrapper {
					padding: 10px;
				}
				.popuprow .popup_header h4.popup_title {
					font-size: 18px;
				}
			}
			.cbr-widget-popup .select2-container--open .select2-dropdown {top: 32px;}
			.cbr-shortcode-popup .select2-container .select2-selection {height: 34px;padding: 0;}
			.popupwrapper{background:<?php echo cbr_pro_widget::get_instance()->hex2rgba( get_option( 'cbrwl_background_color', '' ), get_option( 'cbrwl_background_opacity', '0.7' ) ); ?>;}
			.popuprow{background:<?php echo get_option( 'cbrwl_box_background_color', '#eeeeee' );?>;border-color:<?php echo get_option( 'cbrwl_box_border_color', '#e0e0e0' ); ?>;text-align:<?php echo $text_align; ?>;width:<?php echo get_option( 'cbrwl_box_width', '400' ); ?>px;padding:<?php echo get_option( 'cbrwl_box_padding', '20' );?>px;border-radius: <?php echo get_option( 'cbrwl_box_border_redius', '5' ); ?>px;}
			.dot-flashing {
			  position: relative;
			  width: 10px;
			  height: 10px;
			  border-radius: 5px;
			  background-color: #ffff;
			  color: #fff;
			  animation: dotFlashing 1s infinite linear alternate;
			  animation-delay: .5s;
			  left: 15px;
			}

			.dot-flashing::before, .dot-flashing::after {
			  content: '';
			  display: inline-block;
			  position: absolute;
			  top: 0;
			}

			.dot-flashing::before {
			  left: -15px;
			  width: 10px;
			  height: 10px;
			  border-radius: 5px;
			  background-color: #fff;
			  color: #fff;
			  animation: dotFlashing 1s infinite alternate;
			  animation-delay: 0s;
			}

			.dot-flashing::after {
			  left: 15px;
			  width: 10px;
			  height: 10px;
			  border-radius: 5px;
			  background-color: #fff;
			  color: #fff;
			  animation: dotFlashing 1s infinite alternate;
			  animation-delay: 1s;
			}

			@keyframes dotFlashing {
			  0% {
				background-color: #fff;
			  }
			  50%,
			  100% {
				background-color: #03aa6f;
			  }
			}
		</style>
		<div id="" class="popupwrapper cbr-widget-popup cbr-shortcode-popup" style="background:<?php echo $this->hex2rgba( get_option( 'cbrwl_background_color', '' ),'0.7' ); ?>;display:none;">
			<div class="popuprow" style="border-color:<?php echo get_option( 'cbrwl_box_border_color', '#e0e0e0' ); ?>;text-align:<?php echo $text_align;?>;">
				<div class="popup_header" style="background:<?php echo get_option( 'cbrwl_box_background_color', '#eeeeee' );?>;border-color:<?php echo get_option( 'cbrwl_box_border_color', '#e0e0e0' ); ?>">
					<h4 class="popup_title"><?php echo $cbrw_popup_header; ?></h4>
					<span class="dashicons dashicons-no-alt popup_close_icon"></span>
				</div>
				<div class="popup_body" style="background:<?php echo get_option( 'cbrwl_box_background_color', '#eeeeee' ); ?>">
				<p style="margin-bottom:5px;"><?php echo stripslashes( get_option( 'cbrwl_text_before_dropdown', 'Select your shipping country' ) ); ?></p>
				<?php 
				asort( $countries );
				?>
				<div class="popup_content">
				<select id="cbr_widget_select" class="select2 cbr-select2 widget-country" >
					<option value=""><?php echo esc_html_e( 'Select Country', 'country-base-restrictions-pro-addon' ); ?></option>
					<?php foreach ( $countries as $key => $val ) { ?>
						 <option value="<?php echo $key;?>" <?php if ( isset( $cookie_country ) && $cookie_country == $key ) { echo 'selected'; } ?>><?php echo $val; ?></option>
					<?php } ?>
				</select>
				<button type="button" class="button primary-button widget-apply" style="padding: 8px;line-height: 17px !important;width:60px;">Apply</button>
				<p><?php echo stripslashes( get_option( 'cbrwl_text_after_dropdown', 'Check our <a href="#">shipping  policy</a> for more info' ) ); ?></p>
				</div>
				</div>
			 </div>
			 <div class="popupclose"></div>
		</div>
        <?php 
	}
	
	
 
}
//new CBR_PRO_Widget();

function cbr_load_widget() {
    register_widget( 'CBR_PRO_Widget' );
}
add_action( 'widgets_init', 'cbr_load_widget' );
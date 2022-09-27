<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Adds the individual sections, settings, and controls to the theme customizer
 *
 * @since  1.0
 */
class cbr_widget_customizer {
	
	// Get our default values	
	public function __construct() {
							
		// Register our sample default controls
		add_action( 'customize_register', array( $this, 'cbr_register_sample_default_controls' ) );

		// Only proceed if this is own request.		
		if ( ! cbr_widget_customizer::is_own_customizer_request() && ! cbr_widget_customizer::is_own_preview_request() ) {
			return;
		}	
		
		add_action( 'customize_register', array( cbr_customizer(), 'cbr_add_customizer_panels' ) );
		// Register our sections
		add_action( 'customize_register', array( cbr_customizer(), 'cbr_add_customizer_sections' ) );	
		
		// Remove unrelated components.
		add_filter( 'customize_loaded_components', array( cbr_customizer(), 'remove_unrelated_components' ), 99, 2 );

		// Remove unrelated sections.
		add_filter( 'customize_section_active', array( cbr_customizer(), 'remove_unrelated_sections' ), 10, 2 );	
		
		// Unhook divi front end.
		add_action( 'woomail_footer', array( cbr_customizer(), 'unhook_divi' ), 10 );

		// Unhook Flatsome js
		add_action( 'customize_preview_init', array( cbr_customizer(), 'unhook_flatsome' ), 50  );
		
		add_filter( 'customize_controls_enqueue_scripts', array( cbr_customizer(), 'enqueue_customizer_scripts' ) );								
		
		add_action( 'customize_preview_init', array( $this, 'enqueue_preview_scripts' ) );					
	}
	
	/*
	 * enqueue js css for preview
	 *
	 * @since 1.0
	*/	
	public function enqueue_preview_scripts() {

		wp_enqueue_style( 'select2-cbr', cbr_pro_addon()->plugin_dir_url() . 'assets/css/select2.min.css' );
		wp_enqueue_script( 'select2-cbr', cbr_pro_addon()->plugin_dir_url() . 'assets/js/select2.min.js' );
		
		wp_enqueue_script( 'cbr-email-preview-scripts', cbr_pro_addon()->plugin_dir_url() . 'assets/js/preview-scripts.js', array( 'jquery', 'customize-preview' ), cbr_pro_addon()->version, true );
		wp_localize_script( 'cbr-email-preview-scripts', 'path_object', array( 'pluginsUrl' => cbr_pro_addon()->plugin_dir_url() ) );
	}
	
	/**
	* Get blog name formatted for emails.
	*
	* @since  1.0
	* @return string
	*/
	public function get_blogname() {
		return wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
	}
	
	/**
	 * Checks to see if we are opening our custom customizer preview
	 *
	 * @access public
	 * @since  1.0
	 * @return bool
	 */
	public static function is_own_preview_request() {
		return isset( $_REQUEST[ 'action' ] ) && 'preview_cbr_widget_lightbox' === $_REQUEST[ 'action' ];
	}
	
	/**
	 * Checks to see if we are opening our custom customizer controls
	 *
	 * @access public
	 * @since  1.0
	 * @return bool
	 */
	public static function is_own_customizer_request() {
		return isset( $_REQUEST[ 'email' ] ) && $_REQUEST[ 'email' ] === 'cbr_widget_customize';
	}

	/**
	 * Get Customizer URL
	 *
	 * @since  1.0
	 */
	public static function get_customizer_url( $email ) {		
		$customizer_url = add_query_arg( array(
			'cbr-customizer' => '1',
			'email' => $email,
			'url'                  => urlencode( add_query_arg( array( 'action' => 'preview_cbr_widget_lightbox' ), home_url( '/' ) ) ),
			'return'               => urlencode( cbr_widget_customizer::get_email_settings_page_url() ),
		), admin_url( 'customize.php' ) );		

		return $customizer_url;
	}		
	
	/**
	 * Get WooCommerce email settings page URL
	 *
	 * @access public
	 * @since  1.0
	 * @return string
	 */
	public static function get_email_settings_page_url() {
		return admin_url( 'admin.php?page=woocommerce-product-country-base-restrictions' );
	}

	/**
	 * Register our sample default controls
	 */
	public function cbr_register_sample_default_controls( $wp_customize ) {		
		
		/**
		* Load all our Customizer Custom Controls
		*
		* @since  1.0
		*/
		require_once trailingslashit( dirname(__FILE__) ) . 'custom-controls.php';
		
		$font_size_array[ '' ] = esc_html( 'Select', 'woocommerce' );
		for ( $i = 10; $i <= 30; $i++ ) {
			$font_size_array[ $i ] = $i."px";
		}
		
		//$wp_customize->remove_control('blogdescription');
		//$wp_customize->remove_section('nav');
		$wp_customize->add_setting( 'cbrwl_header_text',
			array(
				'default' => esc_html( 'Choose Shipping Country', 'country-base-restrictions-pro-addon' ),
				'transport' => 'postMessage',
				'type'  => 'option',
				'sanitize_callback' => ''
			)
		);
		$wp_customize->add_control( 'cbrwl_header_text',
			array(
				'label' => esc_html( 'Header text', 'country-base-restrictions-pro-addon' ),
				'description' => esc_html( '', 'country-base-restrictions-pro-addon' ),
				'section' => 'cbr_widget_customize',
				'type' => 'text',
			)
		);
		
		$wp_customize->add_setting( 'cbrwl_text_before_dropdown',
			array(
				'default' => esc_html( 'Select your shipping country', 'country-base-restrictions-pro-addon' ),
				'transport' => 'postMessage',
				'type'  => 'option',
				'sanitize_callback' => ''
			)
		);
		$wp_customize->add_control( 'cbrwl_text_before_dropdown',
			array(
				'label' => esc_html( 'Message', 'country-base-restrictions-pro-addon' ),
				'description' => esc_html( 'You can use HTML tags : <a>, <strong>, <i>, <a>', 'country-base-restrictions-pro-addon' ),
				'section' => 'cbr_widget_customize',
				'type' => 'textarea',
			)
		);
		
		$wp_customize->add_setting( 'cbrwl_text_after_dropdown',
			array(
				'default' => wp_kses_post( 'Check our <a href="#">shipping  policy</a> for more info', 'country-base-restrictions-pro-addon' ),
				'transport' => 'postMessage',
				'type'  => 'option',
				'sanitize_callback' => ''
			)
		);
		$wp_customize->add_control( 'cbrwl_text_after_dropdown',
			array(
				'label' => esc_html( 'Footer content', 'country-base-restrictions-pro-addon' ),
				'description' => esc_html( 'You can use HTML tags : <a>, <strong>, <i>, <a>', 'country-base-restrictions-pro-addon' ),
				'section' => 'cbr_widget_customize',
				'type' => 'textarea',
			)
		);
		
		$wp_customize->add_setting( 'cbrwl_design_heading',
			array(
				'default' => '',
				'transport' => 'postMessage',
				'sanitize_callback' => ''
			)
		);
		$wp_customize->add_control( new CBR_Customize_Heading_Control( $wp_customize, 'cbrwl_design_heading',
			array(
				'label' => esc_html( 'Design Options', 'country-base-restrictions-pro-addon' ),
				'description' => '',
				'section' => 'cbr_widget_customize'
			)
		) );			
		
		// Table content font weight
		$wp_customize->add_setting( 'cbrwl_box_width',
			array(
				'default' => '400',
				'transport' => 'refresh',
				'sanitize_callback' => '',
				'type' => 'option',
			)
		);
		$wp_customize->add_control( new CBR_Slider_Custom_Control( $wp_customize, 'cbrwl_box_width',
			array(
				'label' => esc_html( 'Widget width', 'country-base-restrictions-pro-addon' ),
				'section' => 'cbr_widget_customize',
				'input_attrs' => array(
					  'default' => '400',
					  'step'  => 10,
					  'min'   => 200,
					  'max'   => 400,
				  ),
			)
		));
		
		// Table content font weight
		$wp_customize->add_setting( 'cbrwl_box_padding',
			array(
				'default' => '20',
				'transport' => 'refresh',
				'sanitize_callback' => '',
				'type' => 'option',
			)
		);
		$wp_customize->add_control( new CBR_Slider_Custom_Control( $wp_customize, 'cbrwl_box_padding',
			array(
				'label' => esc_html( 'Padding', 'country-base-restrictions-pro-addon' ),
				'section' => 'cbr_widget_customize',
				'input_attrs' => array(
						'default' => '20',
						'step'  => 5,
						'min'   => 5,
						'max'   => 60,
					),
			)
		));			
		
		$wp_customize->add_setting( 'cbrwl_text_align',
			array(
				'default' => esc_html( 'left', '' ),
				'transport' => 'refresh',
				'type'  => 'option',
				'sanitize_callback' => ''
			)
		);
		
		$wp_customize->add_control( 'cbrwl_text_align', 
			array(
			  'type' => 'select',
			  'section' => 'cbr_widget_customize', // Add a default or your own section
			  'label' => esc_html( 'Align text', 'country-base-restrictions-pro-addon' ),
			  'description' => esc_html( '' ),
			  'choices' => 
				array(
					'left' => esc_html( 'Left', 'woocommerce' ),
					'right' => esc_html( 'Right', 'woocommerce' ),
					'center' => esc_html( 'Center', 'woocommerce' ),
				),
			) 
		);
		
		// Display Shipment Provider image/thumbnail
		$wp_customize->add_setting( 'cbrwl_background_color',
			array(
				'default' => '',
				'transport' => 'refresh',
				'type'      => 'option',
				'sanitize_callback' => ''
			)
		);
		
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'cbrwl_background_color', 
			array(
				'label'      => esc_html( 'Overlay Background Color', 'country-base-restrictions-pro-addon' ),
				'description' => esc_html( '', 'country-base-restrictions-pro-addon' ),
				'section'    => 'cbr_widget_customize',
			) 
		) );
		
		$wp_customize->add_setting( 'cbrwl_background_opacity',
			array(
				'default' => esc_html( '0.7', '' ),
				'transport' => 'refresh',
				'type'  => 'option',
				'sanitize_callback' => ''
			)
		);
		$wp_customize->add_control( new CBR_Slider_Custom_Control( $wp_customize, 'cbrwl_background_opacity',
			array(
				'label' => esc_html( 'Overlay Opacity', 'country-base-restrictions-pro-addon' ),
				'section' => 'cbr_widget_customize',
				'input_attrs' => array(
						'default' => '0.7',
						'step'  => 0.1,
						'min'   => 0,
						'max'   => 1,
					),
			)
		));
		
		// Display Shipment Provider image/thumbnail
		$wp_customize->add_setting( 'cbrwl_box_background_color',
			array(
				'default' => '',
				'transport' => 'postMessage',
				'type'      => 'option',
				'sanitize_callback' => ''
			)
		);
		
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'cbrwl_box_background_color', 
			array(
				'label'      => esc_html( 'Background Color', 'country-base-restrictions-pro-addon' ),
				'description' => esc_html( '', 'country-base-restrictions-pro-addon' ),
				'section'    => 'cbr_widget_customize',
			) 
		) );
		
		// Display Shipment Provider image/thumbnail
		$wp_customize->add_setting( 'cbrwl_box_border_color',
			array(
				'default' => '',
				'transport' => 'postMessage',
				'type'      => 'option',
				'sanitize_callback' => ''
			)
		);
		
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'cbrwl_box_border_color', 
			array(
				'label'      => esc_html( 'Border Color', 'country-base-restrictions-pro-addon' ),
				'description' => esc_html( '', 'country-base-restrictions-pro-addon' ),
				'section'    => 'cbr_widget_customize',
			) 
		) );
		
		// Table content font weight
		$wp_customize->add_setting( 'cbrwl_box_border_redius',
			array(
				'default' => '5',
				'transport' => 'refresh',
				'sanitize_callback' => '',
				'type' => 'option',
			)
		);
		$wp_customize->add_control( new CBR_Slider_Custom_Control( $wp_customize, 'cbrwl_box_border_redius',
			array(
				'label' => esc_html( 'Border Redius', 'country-base-restrictions-pro-addon' ),
				'section' => 'cbr_widget_customize',
				'input_attrs' => array(
					'default' => '5',
					'step'  => 5,
					'min'   => 0,
					'max'   => 25,
				),
			)
		));	
	}		
}
$cbr_widget_customizer = new cbr_widget_customizer();

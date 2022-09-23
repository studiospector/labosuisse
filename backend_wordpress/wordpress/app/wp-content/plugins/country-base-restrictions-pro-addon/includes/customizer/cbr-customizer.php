<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WC_CBR_Customizer {

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
    public function __construct() {

    }
	
	/**
	 * Register the Customizer panels
	 *
	 * @since  1.0
	 */
	public function cbr_add_customizer_panels( $wp_customize ) {
		
		/**
		* Add our Header & Navigation Panel
		*
		* @since  1.0
		*/
		$wp_customize->add_panel( 'cbr_naviation_panel',
			array(
				'title' => esc_html( 'Country Restrictions', 'woo-product-country-base-restrictions' ),
				'description' => esc_html( '', 'country-base-restrictions-pro-addon' )
			)
		);
		
	}
	
	/**
	 * Register the Customizer sections
	 *
	 * @since  1.0
	 */
	public function cbr_add_customizer_sections( $wp_customize ) {	
		
		$wp_customize->add_section( 'cbr_widget_customize',
			array(
				'title' => esc_html( 'Country detection widget', 'country-base-restrictions-pro-addon' ),
				'description' => esc_html( '', 'country-base-restrictions-pro-addon'  ),
			)
		);
				
	}
	
	/**
	 * add css and js for customizer
	 *
	 * @since  1.0
	*/
	public function enqueue_customizer_scripts() {
		if ( isset( $_REQUEST[ 'cbr-customizer' ] ) && '1' === $_REQUEST[ 'cbr-customizer' ] ) {
			wp_enqueue_style( 'wp-color-picker' );
		
			wp_enqueue_style( 'cbr-customizer-styles', cbr_pro_addon()->plugin_dir_url() . 'assets/css/customizer-styles.css', array(), cbr_pro_addon()->version  );
			wp_enqueue_script( 'cbr-customizer-scripts', cbr_pro_addon()->plugin_dir_url() . 'assets/js/customizer-scripts.js', array( 'jquery', 'customize-controls' ), cbr_pro_addon()->version, true );
	
			// Send variables to Javascript
			wp_localize_script( 'cbr-customizer-scripts', 'cbr_customizer', array(
				'ajax_url'              	=> admin_url( 'admin-ajax.php '),
				'cbr_widget_preview_url'	=> $this->get_cbr_widget_preview_url(),
				'trigger_click'				=> '#accordion-section-' . $_REQUEST[ 'email' ] . ' h3',
			));
			
			wp_localize_script('wp-color-picker', 'wpColorPickerL10n', array(
				'clear'            => esc_html( 'Clear' ),
				'clearAriaLabel'   => esc_html( 'Clear color' ),
				'defaultString'    => esc_html( 'Default' ),
				'defaultAriaLabel' => esc_html( 'Select default color' ),
				'pick'             => esc_html( 'Select Color' ),
				'defaultLabel'     => esc_html( 'Color value' ),
			)); 
	
		}
	}
	
	/**
	 * Get Customizer URL
	 *
	 * @since  1.0
	 */
	public static function get_cbr_widget_preview_url() {		
		$preview_url = add_query_arg( array(
				'action' => 'preview_cbr_widget_lightbox',
		) );		

		return $preview_url;
	}
	
	/**
     * Remove unrelated components
     *
     * @access public
     * @param array $components
     * @param object $wp_customize
	 * @since  1.0
     * @return array
     */
    public function remove_unrelated_components( $components, $wp_customize )	{
        // Iterate over components
        foreach ( $components as $component_key => $component ) {

            // Check if current component is own component
            if ( ! $this->is_own_component( $component ) ) {
                unset( $components[ $component_key ] );
            }
        }

        // Return remaining components
        return $components;
    }

    /**
     * Remove unrelated sections
     *
     * @access public
     * @param bool $active
     * @param object $section
	 * @since  1.0
     * @return bool
     */
    public function remove_unrelated_sections( $active, $section ) {
        // Check if current section is own section
        if ( ! $this->is_own_section( $section->id ) ) {
            return false;
        }

        // We can override $active completely since this runs only on own Customizer requests
        return true;
    }

	/**
	* Remove unrelated controls
	*
	* @access public
	* @param bool $active
	* @param object $control
	* @since  1.0
	* @return bool
	*/
	public function remove_unrelated_controls( $active, $control ) {
		
		// Check if current control belongs to own section
		if ( ! cbr_add_customizer_sections::is_own_section( $control->section ) ) {
			return false;
		}

		// We can override $active completely since this runs only on own Customizer requests
		return $active;
	}

	/**
	* Check if current component is own component
	*
	* @access public
	* @param string $component
	* @since  1.0
	* @return bool
	*/
	public static function is_own_component( $component ) {
		return false;
	}

	/**
	* Check if current section is own section
	*
	* @access public
	* @param string $key
	* @since  1.0
	* @return bool
	*/
	public static function is_own_section( $key ) {
				
		if ( 'cbr_naviation_panel' === $key || 'cbr_widget_customize' === $key ) {
			return true;
		}

		// Section not found
		return false;
	}
	
	/*
	 * Unhook flatsome front end
	 *
	 * @since  1.0
	 */
	public function unhook_flatsome() {
		// Unhook flatsome issue.
		wp_dequeue_style( 'flatsome-customizer-preview' );
		wp_dequeue_script( 'flatsome-customizer-frontend-js' );
	}
	
	/*
	 * Unhook Divi front end
	 *
	 * @since  1.0
	 */
	public function unhook_divi() {
		// Divi Theme issue.
		remove_action( 'wp_footer', 'et_builder_get_modules_js_data' );
		remove_action( 'et_customizer_footer_preview', 'et_load_social_icons' );
	}
		
}
/**
 * Returns an instance of zorem_woocommerce_advanced_shipment_tracking.
 *
 * @since 1.0
 * @version 1.0
 *
 * @return zorem_woocommerce_advanced_shipment_tracking
*/
function cbr_customizer() {
	static $instance;

	if ( ! isset( $instance ) ) {		
		$instance = new wc_cbr_customizer();
	}

	return $instance;
}

/**
 * Register this class globally.
 *
 * Backward compatibility.
*/
cbr_customizer();

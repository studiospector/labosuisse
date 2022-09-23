<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Customizer Custom Controls
 *
 * @since  1.0
 */
if ( class_exists( 'WP_Customize_Control' ) ) {
	class CBR_Customize_Heading_Control extends WP_Customize_Control {		

		public function render_content() {
			?>
			<label>
				<h3 class="control_heading"><?php _e( $this->label, 'country-base-restrictions-pro-addon' ); ?></h3>
				<?php if ( ! empty( $this->description ) ) : ?>
				<span class="description customize-control-description"><?php echo $this->description; ?></span>
				<?php endif; ?>
			</label>
			<?php
		}
	}
}

/**
 * Custom Control Base Class
 *
 * @since  1.0
 */
class CBR_Customize_Custom_Control extends WP_Customize_Control {
	protected function get_resource_url() {
		if ( strpos( wp_normalize_path( __DIR__ ), wp_normalize_path( WP_PLUGIN_DIR ) ) === 0 ) {
			return plugin_dir_url( __DIR__ );
		}

		return trailingslashit( get_template_directory_uri() );
	}
}

/**
 * Slider Custom Control
 *
 * @since  1.0
 */
class CBR_Slider_Custom_Control extends CBR_Customize_Custom_Control {
	
	/**
	 * The type of control being rendered
	 *
     * @since  1.0
	 */
	public $type = 'slider_control';
	
	/**
	 * Enqueue our scripts and styles
	 *
	 * @since  1.0
	 */
	public function enqueue() {
		wp_enqueue_script( 'aec-custom-controls-js', cbr_pro_addon()->plugin_dir_url() . 'assets/js/customizer.js', array( 'jquery', 'jquery-ui-core' ), cbr_pro_addon()->version, true );
		wp_enqueue_style( 'aec-custom-controls-css', cbr_pro_addon()->plugin_dir_url() . 'assets/css/customizer.css', array(), cbr_pro_addon()->version, 'all' );
	}
	
	/**
	 * Render the control in the customizer
	 *
	 * @since  1.0
	 */
	public function render_content() {
	?>
		<div class="slider-custom-control">
			<span class="customize-control-title"><?php _e( $this->label, '' ); ?></span>				
			<div class="slider" slider-min-value="<?php echo esc_attr( $this->input_attrs[ 'min' ] ); ?>" slider-max-value="<?php echo esc_attr( $this->input_attrs[ 'max' ] ); ?>" slider-step-value="<?php echo esc_attr( $this->input_attrs[ 'step' ] ); ?>">
			</div>				
			<span class="slider-reset dashicons dashicons-image-rotate" slider-reset-value="<?php echo esc_attr( $this->input_attrs['default'] ); ?>"></span>
			<input type="number" id="<?php echo esc_attr( $this->id ); ?>" name="<?php echo esc_attr( $this->id ); ?>" value="<?php echo esc_attr( $this->value() ); ?>" class="customize-control-slider-value" <?php $this->link(); ?> />
		</div>
	<?php
	}
}

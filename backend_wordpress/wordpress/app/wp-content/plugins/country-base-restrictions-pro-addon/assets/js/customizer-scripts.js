/*
 * Customizer Scripts
 * Need to rewrite and clean up this file.
 */

jQuery(document).ready(function() {

    /**
     * Change description
     */
	jQuery(cbr_customizer.trigger_click).trigger( "click" );    
	jQuery('#customize-theme-controls #accordion-section-themes').hide();
	
});

jQuery(document).on("change", "#customize-control-woocommerce_customer_ready_pickup_order_settings-wclp_enable_ready_pickup_ga_tracking input", function(){
	if(jQuery(this).prop("checked") == true){
		jQuery('#customize-control-woocommerce_customer_ready_pickup_order_settings-wclp_ready_pickup_analytics_link').show();
	} else{
		jQuery('#customize-control-woocommerce_customer_ready_pickup_order_settings-wclp_ready_pickup_analytics_link').hide();
	}
});	
jQuery(document).on("change", "#_customize-input-customizer_ready_pickup_order_settings_enabled", function(){	
	if(jQuery(this).prop("checked") == true){
		jQuery('#customize-control-wclp_ready_pickup_email_settings-wclp_enable_ready_pickup_status_email input').prop('disabled', true);
	} else{
		jQuery('#customize-control-wclp_ready_pickup_email_settings-wclp_enable_ready_pickup_status_email input').removeAttr('disabled');
	}
});

jQuery(document).on("change", "#customize-control-woocommerce_customer_pickup_order_settings-wclp_enable_pickup_ga_tracking input", function(){
	if(jQuery(this).prop("checked") == true){
		jQuery('#customize-control-woocommerce_customer_pickup_order_settings-wclp_pickup_analytics_link').show();
	} else{
		jQuery('#customize-control-woocommerce_customer_pickup_order_settings-wclp_pickup_analytics_link').hide();
	}
});	
jQuery(document).on("change", "#_customize-input-customizer_pickup_order_settings_enabled", function(){	
	if(jQuery(this).prop("checked") == true){
		jQuery('#customize-control-wclp_pickup_email_settings-wclp_enable_pickup_status_email input').prop('disabled', true);
	} else{
		jQuery('#customize-control-wclp_pickup_email_settings-wclp_enable_pickup_status_email input').removeAttr('disabled');
	}
});
    // Handle mobile button click
    function custom_size_mobile() {
    	// get email width.
    	var email_width = '684';
    	var ratio = email_width/304;
    	var framescale = 100/ratio;
    	var framescale = framescale/100;
    	jQuery('#customize-preview iframe').width(email_width+'px');
    	jQuery('#customize-preview iframe').css({
				'-webkit-transform' : 'scale(' + framescale + ')',
				'-moz-transform'    : 'scale(' + framescale + ')',
				'-ms-transform'     : 'scale(' + framescale + ')',
				'-o-transform'      : 'scale(' + framescale + ')',
				'transform'         : 'scale(' + framescale + ')'
		});
    }
	jQuery('#customize-footer-actions .preview-mobile').click(function(e) {
		custom_size_mobile();
	});
		jQuery('#customize-footer-actions .preview-desktop').click(function(e) {
		jQuery('#customize-preview iframe').width('100%');
		jQuery('#customize-preview iframe').css({
				'-webkit-transform' : 'scale(1)',
				'-moz-transform'    : 'scale(1)',
				'-ms-transform'     : 'scale(1)',
				'-o-transform'      : 'scale(1)',
				'transform'         : 'scale(1)'
		});
	});
	jQuery('#customize-footer-actions .preview-tablet').click(function(e) {
		jQuery('#customize-preview iframe').width('100%');
		jQuery('#customize-preview iframe').css({
				'-webkit-transform' : 'scale(1)',
				'-moz-transform'    : 'scale(1)',
				'-ms-transform'     : 'scale(1)',
				'-o-transform'      : 'scale(1)',
				'transform'         : 'scale(1)'
		});
	});

(function ( api ) {
    api.section( 'cbr_widget_customize', function( section ) {	
        section.expanded.bind( function( isExpanded ) {				
            var url;
            if ( isExpanded ) {
				jQuery('#save').trigger('click');
                url = cbr_customizer.cbr_widget_preview_url;
                api.previewer.previewUrl.set( url );
            }
        } );
    } );
} ( wp.customize ) );
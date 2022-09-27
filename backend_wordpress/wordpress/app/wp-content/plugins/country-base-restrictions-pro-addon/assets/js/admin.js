/*header script*/
jQuery( document ).on( "click", "#activity-panel-tab-help", function(e) {
	e.preventDefault(); // stops link from making page jump to the top
	e.stopPropagation(); // when you click the button, it stops the page from seeing it as clicking the body too
	jQuery(this).addClass( 'is-active' );
	jQuery( '.woocommerce-layout__activity-panel-wrapper' ).addClass( 'is-open is-switching' );
});

jQuery( document ).on( "click", ".woocommerce-layout__activity-panel-wrapper", function(e) {	
	e.stopPropagation(); // when you click the button, it stops the page from seeing it as clicking the body too	
});

jQuery( document ).on( "click", "body", function() {	
	jQuery('#activity-panel-tab-help').removeClass( 'is-active' );
	jQuery( '.woocommerce-layout__activity-panel-wrapper' ).removeClass( 'is-open is-switching' );
});
/*header script end*/ 


/* cbr_snackbar jquery */
(function( $ ){
	$.fn.cbr_snackbar = function(msg) {
		if ( jQuery('.snackbar-logs').length === 0 ){
			$("body").append("<section class=snackbar-logs></section>");
		}
		var cbr_snackbar = $("<article></article>").addClass('snackbar-log snackbar-log-success snackbar-log-show').text( msg );
		$(".snackbar-logs").append(cbr_snackbar);
		setTimeout(function(){ cbr_snackbar.remove(); }, 3000);
		return this;
	}; 
})( jQuery );

/* cbr_snackbar_warning jquery */
(function( $ ){
	$.fn.cbr_snackbar_warning = function(msg) {
		if ( jQuery('.snackbar-logs').length === 0 ){
			$("body").append("<section class=snackbar-logs></section>");
		}
		var cbr_snackbar_warning = $("<article></article>").addClass( 'snackbar-log snackbar-log-error snackbar-log-show' ).html( msg );
		$(".snackbar-logs").append(cbr_snackbar_warning);
		setTimeout(function(){ cbr_snackbar_warning.remove(); }, 3000);
		return this;
	}; 
})( jQuery );

jQuery(document).ready(function(){
	"use strict";
	jQuery(".tipTip").tipTip();
	jQuery("#wpcbr_choose_the_page_to_redirect").select2();
	jQuery('#cbrw_border_color, #cbrw_background_color, #cbrw_font_color, #cbrwl_box_background_color, #cbrwl_background_color').wpColorPicker();
	
	jQuery('.product_visibility:checked').parent().find('span').css("color", "#00ab6f");
	jQuery('.product_visibility:checked').parent().parent().parent().parent().parent().css("background", "#fff");
	
	jQuery('#wpcbr_choose_the_page_to_redirect').parent().parent().parent().hide();
	if( jQuery("#wpcbr_redirect_404_page").is(":checked") === true ){
		jQuery('#wpcbr_choose_the_page_to_redirect').parent().parent().parent().show();
	}
	
	jQuery('#wpcbr_hide_product_price1').parent().parent().parent().parent().hide();
	if( jQuery("#wpcbr_make_non_purchasable1").is(":checked") === true ){
		jQuery('#wpcbr_hide_product_price1').parent().parent().parent().parent().show();
	}
	
	jQuery('#wpcbr_allow_product_addtocart').parent().parent().parent().parent().hide();
	if( jQuery("#wpcbr_hide_product_price2").is(":checked") === false){
		jQuery('#wpcbr_allow_product_addtocart').parent().parent().parent().parent().show();
	}
	
	jQuery("#wpcbr_message_position").parent().addClass('hidden-desc');
	if( jQuery("#wpcbr_message_position").val() === "custom_shortcode"){
		jQuery("#wpcbr_message_position").parent().removeClass('hidden-desc');
	}
	var restriction_type = jQuery(".cbr_restricted_type").find(":selected").val();
	if( restriction_type === 'all' ){
		jQuery(".restricted_countries").hide();
	}
	
});

jQuery(document).on("click", ".accordions", function(){
	"use strict";
		
	if (jQuery(this).next('.panel').hasClass('active')) {
		//
		jQuery(".accordions").css('border-color','');
		jQuery(".accordions").removeClass('active');
		jQuery(".accordions").next('.panel').removeClass('active').slideUp("slow");
		jQuery(".accordions").css('cursor', '');
		jQuery(".accordions").find('span.cbr-btn').hide();
		jQuery(".accordions").find('span.dashicons').addClass('dashicons-arrow-right-alt2');
		jQuery(".accordions").find('label').css('color','');
	} else {
		jQuery(".accordions").css('border-color','');
		jQuery(".accordions").removeClass('active');
		jQuery(".accordions").next('.panel').removeClass('active').slideUp("slow");
		jQuery(".accordions").css('cursor', '');
		jQuery(".accordions").find('span.cbr-btn').hide();
		jQuery(".accordions").find('span.dashicons').addClass('dashicons-arrow-right-alt2');
		jQuery(".accordions").find('label').css('color','');
		jQuery(this).addClass('active');
		jQuery(this).css('cursor', 'default');
		jQuery(this).find('span.cbr-btn').show();
		jQuery(this).find('span.dashicons').removeClass('dashicons-arrow-right-alt2');
		jQuery(this).find('label').css('color','#212121');
		jQuery(this).next('.panel').addClass('active').slideDown( 'slow', function() {
			var visible = jQuery(this).isInViewport();
			if ( !visible ) {
				jQuery('html, body').animate({
					scrollTop: jQuery(this).prev().offset().top - 35
				}, 1000);	
			}			
		} );
	}
});

(function( $ ){
	$.fn.isInViewport = function( element ) {
		var win = $(window);
		var viewport = {
			top : win.scrollTop()			
		};
		viewport.bottom = viewport.top + win.height();
		
		var bounds = this.offset();		
		bounds.bottom = bounds.top + this.outerHeight();

		if( bounds.top >= 0 && bounds.bottom <= window.innerHeight) {
			return true;
		} else {
			return false;	
		}		
	};
})( jQuery );

jQuery(document).on("click", ".catelog_visibility", function(){
	"use strict";
	
	var hasClass = jQuery(this).parent().hasClass("hide-child-panel");
	
	if(hasClass === true ){
		jQuery(".catelog_visibility").parent().addClass("hide-child-panel");
		jQuery(".catelog_visibility").find('span').css("color", "#bdbdbd");
		jQuery('.catelog_visibility').css('background','');
		jQuery(this).parent().removeClass("hide-child-panel");
		jQuery(this).find('input.product_visibility').trigger("click");
		jQuery(this).css('background','#fff');
		jQuery(this).find('span').css("color", "#00ab6f");
	}
});


jQuery(document).on("change", "#wpcbr_message_position", function(){
	"use strict";
	jQuery(this).parent().addClass('hidden-desc');
	if( jQuery(this).val() === "custom_shortcode"){
		jQuery(this).parent().removeClass('hidden-desc');
	}
});

jQuery(document).on("change", "#wpcbr_make_non_purchasable1", function(){
	"use strict";
	jQuery('#wpcbr_hide_product_price1').parent().parent().parent().parent().hide();
	if( jQuery(this).is(":checked") === true){
		jQuery('#wpcbr_hide_product_price1').parent().parent().parent().parent().show();
	}
	
});
jQuery(document).on("change", "#wpcbr_hide_product_price2", function(){
	"use strict";
	jQuery('#wpcbr_allow_product_addtocart').parent().parent().parent().parent().hide();
	if( jQuery(this).is(":checked") !== true){
		jQuery('#wpcbr_allow_product_addtocart').parent().parent().parent().parent().show();
	}
	
});
jQuery(document).on("change", "#wpcbr_redirect_404_page", function(){
	"use strict";
	jQuery('#wpcbr_choose_the_page_to_redirect').parent().parent().parent().hide();
	if( jQuery(this).is(":checked") === true){
		jQuery('#wpcbr_choose_the_page_to_redirect').parent().parent().parent().show();
	}
	
});
jQuery(document).on("change", ".cbr_restricted_type", function(){
	"use strict";
	if( jQuery(this).find(":selected").val() === 'specific' || jQuery(this).find(":selected").val() === 'excluded'){
		jQuery(".restricted_countries").show();
	}
	if(jQuery(this).find(":selected").val() === 'all' ){
		jQuery(".restricted_countries").hide();
	}
});

/*ajex call for general tab form save*/	
jQuery(document).on("click", "#cbr_setting_tab_form .cbr-save", function(){
	"use strict";
	jQuery(this).parent().find(".spinner").addClass("active");
	var form = jQuery('#cbr_setting_tab_form');
	jQuery.ajax({
		url: ajaxurl+"?action=cbr_setting_form_update",//csv_workflow_update,		
		data: form.serialize(),
		type: 'POST',
		dataType:"json",	
		success: function(response) {
			if( response.success === "true" ){
				jQuery("#cbr_setting_tab_form .spinner").removeClass("active");
				jQuery(document).cbr_snackbar( "Settings Successfully Saved." );
			} else {
				//show error on front
			}
		},
		error: function(response) {
			console.log(response);			
		}
	});
	return false;
});

jQuery(document).on("click", ".cbr_tab_input", function(){
	"use strict";
	var tab = jQuery(this).data('tab');
	var label = jQuery(this).data('label');
	jQuery('.zorem-layout__header-breadcrumbs .header-breadcrumbs-last').text(label);
	var url = window.location.protocol + "//" + window.location.host + window.location.pathname+"?page=woocommerce-product-country-base-restrictions&tab="+tab;
	window.history.pushState({path:url},'',url);
	jQuery(window).trigger('resize');	
});

jQuery(document).ready(function(){
	jQuery('.select2').select2();
	jQuery('.select2').select2({
		minimumResultsForSearch: -1
	});
	jQuery(document).on("click", ".cbr_popup_close_icon, .popupclose", function(){
		jQuery('.cbr_page_preview_popup').hide();
		jQuery('.woocommerce-country-based-restrictions').css( 'overflow-y', '');
	});
});

jQuery(document).on("change", ".private_note", function(){
	jQuery(this).parents(".br_cbr_row").find(".br_cbr_row_title_title").text(jQuery(this).val());
});
jQuery(document).on("change", ".restriction_by", function(){
	jQuery(this).parents(".br_cbr_row").find(".br_cbr_row_title_note").text("By "+jQuery(this).val());
});

jQuery(document).on("click", ".field_exclusivity", function(event){
  "use strict";
  event.stopPropagation();
});

jQuery(document).on("change", ".restriction_by", function(){
  "use strict";
  	jQuery(this).parent().parent().children(".select_attribute, .select_shipping_class, .select_tags, .select_categories").hide();
	var value = jQuery(this).val();
	
	if(value === 'categories') {
	  jQuery(this).parent().parent().children(".select_categories").show();
	}
	if(value === 'tags') {
	  jQuery(this).parent().parent().children(".select_tags").show();
	}
	if(value === 'attributes') {
	  jQuery(this).parent().parent().children(".select_attribute").show();
	}
	if(value === 'shipping-class') {
	  jQuery(this).parent().parent().children(".select_shipping_class").show();
	}
});

jQuery(document).on("change", ".field_exclusivity", function(){
  "use strict";
  	var value = jQuery(this).is(':checked');
  	if(value === true) {
	  jQuery(this).parents(".br_cbr_accordion_handle").css("background", "#fff");
	  jQuery(this).parent().parent().parent().find(".br_cbr_row_title_title").css("fontWeight", "700");
	}
	if(value === false) {
	  jQuery(this).parents(".br_cbr_accordion_handle").css("background", "");
	  jQuery(this).parent().parent().parent().find(".br_cbr_row_title_title").css("fontWeight", "");
	}
});

jQuery(document).on("change", ".ck_rule_toggle", function(){
  "use strict";
  	var value = jQuery(this).is(':checked');
  	if(value === true) {
	  jQuery(this).parents(".ck_cbr_accordion_handle").css("background", "#fff");
	  jQuery(this).parent().parent().parent().find(".ck_cbr_row_title_title").css("fontWeight", "700");
	}
	if(value === false) {
	  jQuery(this).parents(".ck_cbr_accordion_handle").css("background", "");
	  jQuery(this).parent().parent().parent().find(".ck_cbr_row_title_title").css("fontWeight", "");
	}
});
jQuery(document).on("change", ".label_name", function(){
	"use strict";
	jQuery(this).parents(".ck_cbr_row").find(".ck_cbr_row_title_title").text(jQuery(this).val());
});

/*ajex call for general tab form save*/	 
jQuery(document).on("submit", "#cbr-payment-gateway-form", function(){
	"use strict";
	jQuery("#cbr-payment-gateway-form .spinner").addClass("active");
	
	var form = jQuery('#cbr-payment-gateway-form');
	
	jQuery.ajax({
		url: ajaxurl,		
		data: form.serialize(),
		type: 'POST',
		dataType:"json",	
		success: function(response) {	
			if( response.success === "true" ){
				jQuery("#cbr-payment-gateway-form .spinner").removeClass("active");
				jQuery(document).cbr_snackbar( "Settings Successfully Saved." );
			} else {
				//show error on front
			}
		},
		error: function(response) {
			console.log(response);			
		}
	});
	return false;
});

jQuery(document).on("submit",'#cbr-license-form', function(e){
	e.preventDefault();
	var license_form = jQuery(this);
	var action = license_form.find(".license_action").val();
	var data = license_form.serialize();
	var license_button = license_form.find(".license_submit");
	
	jQuery("#cbr-license-form").block({
		message: null,
		overlayCSS: {
			background: "#fff",
			opacity: .6
		}	
    });
	
	jQuery.ajax({
		type: "POST",
		url : ajaxurl,
		data: data,
		beforeSend: function(){
			license_button.prop('disabled', true).val('Please wait..');
		},
		success : function(data){
			var btn_value = 'Active';
			if( data.success === true ){
				if( action === 'CBR_license_activate' ){
					jQuery(license_button).css('backgroundColor', '#03aa6f');
					jQuery(license_button).css('color', '#fff');
					license_form.find(".license_action").val( 'CBR_license_deactivate' );
					jQuery("#cbr-license-form").unblock();
					location.reload();
				} else {
					jQuery('a.deactivate').hide();
					license_form.find(".license_action").val( 'CBR_license_activate' );
					license_form.find("#license_key").val( '' );
					jQuery("#cbr-license-form").unblock();
					location.reload();
				}
			} else if( data.success === false ){
				jQuery('a.deactivate').html('<span style="color:red;line-height:2;">' + data.error  + '</span>');
			} else {
				jQuery('a.deactivate').html('<span style="color:red;line-height:2;">'+data.message+'</span>');//'+data.message+'
			}
			
			license_button.prop('disabled', false).val(btn_value);
		},
		error: function(data){
			console.log(data);
		}
	});
	
});

( function( $, wp, ajaxurl ) {
	"use strict";
	var cbr_template = wp.template( 'cbr-template' );
	var index = index + 1;
	var $cbr_list = $(".cbr_list");
	
	var wc_cbr = {	
		init: function() {
			
			$(document).on( "click", ".btn-cbr-add", this.addnew);
			$(document).on( 'click', '.btn-save', this.save_cbr_bulk_restrictions );
			$(document).on( 'change', '.field_exclusivity', this.save_toggle );
			$(document).on( 'click', '.br_cbr_row_duplicate_handle', this.dublicate );
			$(document).on( 'click', '.btn-cbr-delete', this.delete_row );
			
			var list_data = $cbr_list.data( 'list' );
			
			if( list_data === '""' ){ return; }
			
			for (var i in list_data) {
				var size = $(".cbr_list").find( '.accordion' ).length + 1;
				$(".cbr_list").append( cbr_template( {
					list:  list_data[ i ],
					index: size
				} ) );
			}
			
			jQuery(function() {
            	jQuery( ".cbr_list" ).sortable({
					handle: '.dashicons-menu',
				});
			});
			
			jQuery(".restriction_by").each(function() {
				jQuery(this).parent().parent().children(".select_attribute, .select_shipping_class, .select_tags, .select_categories").hide();
				var value = jQuery(this).val();
				
				if(value === 'categories') {
				  jQuery(this).parent().parent().children(".select_categories").show();
				}
				if(value === 'tags') {
				  jQuery(this).parent().parent().children(".select_tags").show();
				}
				if(value === 'attributes') {
				  jQuery(this).parent().parent().children(".select_attribute").show();
				}
				if(value === 'shipping-class') {
				  jQuery(this).parent().parent().children(".select_shipping_class").show();
				}
			});
			
			jQuery(".field_exclusivity").each(function() {
				var value = jQuery(this).is(':checked');
				if(value === true) {
				  jQuery(this).parents(".br_cbr_accordion_handle").css("background", "#fff");
				  jQuery(this).parents(".br_cbr_row_title_title").css("fontWeight", "700");
				}
			});

			jQuery(".cbr_list .accordion:last-child .controller").hide();
			jQuery(".cbr_list .accordion:last-child .cbr_save_wrap").show();
			jQuery(".cbr_list .accordion:last-child .cbr_single").show().removeClass("hide-panel");
			
			$('.select2').select2();

		},
		
		save_toggle: function( event ){
			event.preventDefault();
			var ajax_data = $("#cbr_bulk_restrictions_tab_form").serialize();
			
			$.post( ajaxurl, ajax_data, function() {
				//run code for after toggle save
			});
		},
		
		addnew:function(){

			//var $cbr_single = $(".cbr_single");
			var length = $(".accordion").length;
			
			if( length === 0 ){
				index = 1;
			} else {
				index = Number( $(".accordion:last-child .cbr_single").attr("data-key") ) + 1;
			}
			
			$(".cbr_list").append( cbr_template( {
				list:  {
					field_exclusivity: 'enabled',
					private_note: 'Restrictions Rule '+index,
					selected_category: '',
					geographic_availability: '',
					selected_countries: ''
				},
				index: index,
			} ) );
			$(".accordion .cbr_single").css("display", "none");
			$(".accordion").find(".cbr_save_wrap").hide();
			$(".accordion").find(".controller").show();
			$(".accordion:last-child .cbr_single").css("display", "");
			
			$(".accordion:last-child .cbr_single .select_attribute, .accordion:last-child .cbr_single .select_shipping_class, .accordion:last-child .cbr_single .select_tags").hide();
			$(".cbr_add_wrap .btn-cbr-add").prop( "disabled", true );
			$(".cbr_add_wrap .btn-cbr-add").css( "cursor", "not-allowed" );
			$(".accordion:last-child").find(".cbr_save_wrap").show();
			$(".accordion:last-child").find(".controller").hide();
			$(".field_exclusivity").each(function() {
				var value = jQuery(this).is(':checked');
				if(value === true) {
				  $(this).parents(".br_cbr_accordion_handle").css("background", "#fff");
				  $(this).parents(".br_cbr_row_title_title").css("fontWeight", "700");
				}
			});

			$('.select2').select2();
			//componentHandler.upgradeAllRegistered();

		},
		
		dublicate:function(event){
			event.stopPropagation();
			var index = Number( $(".accordion:last-child .cbr_single").attr("data-key") ) + 1;
			
			var br_cbr_row = jQuery(this).parents(".br_cbr_row");
			
			var private_note = br_cbr_row.find(".private_note").val();
			var restriction_by = br_cbr_row.find(".restriction_by").val();
			var selected_category = br_cbr_row.find(".selected_category").val();
			var geographic_availability = br_cbr_row.find(".geographic_availability").val();
			var selected_attribute = br_cbr_row.find(".selected_attribute").val();
			var selected_tag = br_cbr_row.find(".selected_tag").val();
			var selected_shipping_class = br_cbr_row.find(".selected_shipping_class").val();
			var selected_countries = br_cbr_row.find(".selected_countries").val();
			
			var list = {
				private_note: private_note,
				restriction_by: restriction_by,
				selected_category: selected_category,
				geographic_availability: geographic_availability,
				selected_attribute: selected_attribute,
				selected_tag: selected_tag,
				selected_shipping_class: selected_shipping_class,
				selected_countries: selected_countries
			};
			
			$(".cbr_list").append( cbr_template( {
				list:  list,
				index: index,
			} ) );
			
			jQuery(".select_attribute, .select_shipping_class, .select_tags, .select_categories").hide();
			var value = restriction_by;
			if(value === 'categories') {
			  jQuery(".select_categories").show();
			}
			if(value === 'tags') {
			  jQuery(".select_tags").show();
			}
			if(value === 'attributes') {
			  jQuery(".select_attribute").show();
			}
			if(value === 'shipping-class') {
			  jQuery(".select_shipping_class").show();
			}
			
			$('.select2').select2();
			jQuery(".cbr_add_wrap .btn-cbr-add").prop( "disabled", true );
			jQuery(".cbr_add_wrap .btn-cbr-add").css( "cursor", "not-allowed" );
			//componentHandler.upgradeAllRegistered();
			jQuery( ".cbr_save_wrap .btn-save" ).trigger( "click" );
		},

		save_cbr_bulk_restrictions: function( event ) {
			event.preventDefault();
			var error;
			jQuery(".cbr_list .accordion").each(function(index, element) {

				var private_note = jQuery(element).find(".private_note");
				if( private_note.val() === '' ){showerror(private_note);error = true;} else {hideerror(private_note);}
				
				var restriction_by = jQuery(element).find(".restriction_by");
				var selected_category = jQuery(element).find(".selected_category");

				if( selected_category.val().length === 0 && restriction_by.val() === 'categories' ){showerror(selected_category.parents('.select_categories').find('.select2-selection--multiple'));error = true;} else {hideerror(selected_category.parents('.select_categories').find('.select2-selection--multiple'));}
				
				var selected_tag = jQuery(element).find(".selected_tag");
				if( selected_tag.val().length === 0 && restriction_by.val() === 'tags' ){showerror(selected_tag.parents('.select_tags').find('.select2-selection--multiple'));error = true;} else {hideerror(selected_tag.parents('.select_tags').find('.select2-selection--multiple'));}
				
				var selected_attribute = jQuery(element).find(".selected_attribute");
				if( selected_attribute.val().length === 0 && restriction_by.val() === 'attributes' ){showerror(selected_attribute.parents('.select_attribute').find('.select2-selection--multiple'));error = true;} else {hideerror(selected_attribute.parents('.select_attribute').find('.select2-selection--multiple'));}
				
				var selected_shipping_class = jQuery(element).find(".selected_shipping_class");
				if( selected_shipping_class.val().length === 0 && restriction_by.val() === 'shipping-class' ){showerror(selected_shipping_class.parents('.select_shipping_class').find('.select2-selection--multiple'));error = true;} else {hideerror(selected_shipping_class.parents('.select_shipping_class').find('.select2-selection--multiple'));}
				
				var geographic_availability = jQuery(element).find(".geographic_availability");
				if( geographic_availability.val() === '' ){showerror(geographic_availability);error = true;} else {hideerror(geographic_availability);}
				
				var selected_countries = jQuery(element).find(".selected_countries");
				if( selected_countries.val().length === 0 ){showerror(selected_countries.parents('.select_restricted_countries').find('.select2-selection--multiple'));error = true;} else {hideerror(selected_countries.parents('.select_restricted_countries').find('.select2-selection--multiple'));}		

			});
			
			if(error === true){
				return false;
			}
					
			$(this).parent().find(".spinner").addClass("active");
			
			var br_cbr_accordion_handle = $(this).parents(".br_cbr_accordion_handle");
			
			var ajax_data = $("#cbr_bulk_restrictions_tab_form").serialize();
			
			$.post( ajaxurl, ajax_data, function() {
				$("#cbr_bulk_restrictions_tab_form").find(".spinner").removeClass("active");
				$(br_cbr_accordion_handle).find(".cbr_save_wrap").hide();
				$(br_cbr_accordion_handle).find(".controller").show();
				$(br_cbr_accordion_handle).next(".cbr_single").addClass("hide-panel").hide();
				jQuery(document).cbr_snackbar( "Settings Successfully Saved." );
				jQuery(".cbr_add_wrap .btn-cbr-add").prop( "disabled", false );
				jQuery(".cbr_add_wrap .btn-cbr-add").css( "cursor", "" );
			});
			
		},
		
		delete_row: function( event ){
			event.preventDefault();
			$(this).parents(".accordion").remove();
			var ajax_data = $("#cbr_bulk_restrictions_tab_form").serialize();
			
			$.post( ajaxurl, ajax_data, function() {
				//run code for after toggle save
				jQuery(".cbr_add_wrap .btn-cbr-add").prop( "disabled", false );
				jQuery(".cbr_add_wrap .btn-cbr-add").css( "cursor", "" );
			});
		},
		
	};
	wc_cbr.init();
	

})( jQuery, wp, ajaxurl );

( function( $, wp, ajaxurl ) {
	"use strict";
	var checkout_template = wp.template( 'cbr-checkout-template' );
	var index = index + 1;
	var $cbr_checkout_list = $(".cbr_checkout_list");
	
	var wc_checkout_cbr = {	
		init: function() {
			
			$(document).on( "click", ".btn-ck-cbr-add", this.addnew);
			$(document).on( 'click', '.btn-ck-cbr-save', this.save_checkout_restrictions );
			$(document).on( 'change', '.ck_rule_toggle', this.save_toggle );
			$(document).on( 'click', '.ck_cbr_row_duplicate_handle', this.dublicate );
			$(document).on( 'click', '.btn-ck-cbr-delete', this.delete_row );
			
			var list_data = $cbr_checkout_list.data( 'list' );
			
			if( list_data === '""' ){ return; }
			
			for (var i in list_data) {
				var size = $(".cbr_checkout_list").find( '.Accordion' ).length + 1;
				$(".cbr_checkout_list").append( checkout_template( {
					list:  list_data[ i ],
					index: size
				} ) );
			}
			
			jQuery(function() {
            	jQuery( ".cbr_checkout_list" ).sortable({
					handle: '.dashicons-menu',
				});
			});
			
			jQuery(".ck_rule_toggle").each(function() {
				var value = jQuery(this).is(':checked');
				if(value === true) {
				  jQuery(this).parents(".ck_cbr_accordion_handle").css("background", "#fff");
				  jQuery(this).parents(".ck_cbr_row_title_title").css("fontWeight", "700");
				}
			});
			
			jQuery(".cbr_checkout_list .Accordion:last-child .ck_controller").hide();
			jQuery(".cbr_checkout_list .Accordion:last-child .ck_cbr_save_wrap").show();
			jQuery(".cbr_checkout_list .Accordion:last-child .ck_cbr_single").show().removeClass("hide-panel");
			
			$('.select2').select2();

		},
		
		addnew:function(){

			//var $cbr_single = $(".cbr_single");
			var length = $(".Accordion").length;
			
			if( length === 0 ){
				index = 1;
			} else {
				index = Number( $(".Accordion:last-child .ck_cbr_single").attr("data-key") ) + 1;
			}
			
			$(".cbr_checkout_list").append( checkout_template( {
				list:  {
					ck_rule_toggle: 'enabled',
					label_name: 'Restrictions Rule '+index,
					coutomer_country: 'Billing',
					restriction_rule: 'include',
					payment_methods: '',
					select_countries: '',
				},
				index: index,
			} ) );
			$(".Accordion .ck_cbr_single").css("display", "none");
			$(".Accordion").find(".ck_cbr_save_wrap").hide();
			$(".Accordion").find(".ck_controller").show();
			$(".Accordion:last-child .ck_cbr_single").css("display", "");
			
			$(".Accordion:last-child .cbr_single .select_attribute, .accordion:last-child .cbr_single .select_shipping_class, .Accordion:last-child .cbr_single .select_tags").hide();
			$(".cbr_add_wrap .btn-ck-cbr-add").prop( "disabled", true );
			$(".cbr_add_wrap .btn-ck-cbr-add").css( "cursor", "not-allowed" );
			$(".Accordion:last-child").find(".ck_cbr_save_wrap").show();
			$(".Accordion:last-child").find(".ck_controller").hide();
			$(".ck_rule_toggle").each(function() {
				var value = jQuery(this).is(':checked');
				if(value === true) {
				  $(this).parents(".ck_cbr_accordion_handle").css("background", "#fff");
				  $(this).parents(".ck_cbr_row_title_title").css("fontWeight", "700");
				}
			});

			$('.select2').select2();
			//componentHandler.upgradeAllRegistered();

		},
		
		save_checkout_restrictions: function( event ) {
			event.preventDefault();
			var error;
			jQuery(".cbr_checkout_list .Accordion").each(function(index, element) {

				var label_name = jQuery(element).find(".label_name");
				if( label_name.val() === '' ){ck_showerror(label_name);error = true;} else {ck_hideerror(label_name);}
				
				var payment_methods = jQuery(element).find(".payment_methods");
				
				if( payment_methods.val().length === 0 ){ck_showerror(payment_methods.parents('.Payment_Methods').find('.select2-selection--multiple'));error = true;} else {ck_hideerror(payment_methods.parents('.Payment_Methods').find('.select2-selection--multiple'));}

			});
			
			if(error === true){
				return false;
			}
					
			$(this).parent().find(".spinner").addClass("active");
			
			var ck_cbr_accordion_handle = $(this).parents(".ck_cbr_accordion_handle");
			
			var ajax_data = $("#cbr_checkout_restrictions_tab_form").serialize();
			
			$.post( ajaxurl, ajax_data, function() {
				$("#cbr_checkout_restrictions_tab_form").find(".spinner").removeClass("active");
				$(ck_cbr_accordion_handle).find(".ck_cbr_save_wrap").hide();
				$(ck_cbr_accordion_handle).find(".ck_controller").show();
				$(ck_cbr_accordion_handle).next(".ck_cbr_single").addClass("hide-panel").hide();
				jQuery(document).cbr_snackbar( "Settings Successfully Saved." );
				jQuery(".ck_cbr_add_wrap .btn-ck-cbr-add").prop( "disabled", false );
				jQuery(".ck_cbr_add_wrap .btn-ck-cbr-add").css( "cursor", "" );
			});
			
		},
		
		save_toggle: function( event ){
			event.preventDefault();
			var ajax_data = $("#cbr_checkout_restrictions_tab_form").serialize();
			
			$.post( ajaxurl, ajax_data, function() {
				//run code for after toggle save
			});
		},
		
		dublicate:function(event){
			event.stopPropagation();
			var index = Number( $(".Accordion:last-child .ck_cbr_single").attr("data-key") ) + 1;
			
			var ck_cbr_row = jQuery(this).parents(".ck_cbr_row");
			
			var label_name = ck_cbr_row.find(".label_name").val();
			var coutomer_country = ck_cbr_row.find(".customer_country").val();
			var restriction_rule = ck_cbr_row.find(".restriction_rule").val();
			var payment_methods = ck_cbr_row.find(".payment_methods").val();
			var select_countries = ck_cbr_row.find(".select_countries").val();
			
			var list = {
				label_name: label_name,
				coutomer_country: coutomer_country,
				restriction_rule: restriction_rule,
				payment_methods: payment_methods,
				select_countries: select_countries,
			};
			
			$(".cbr_checkout_list").append( checkout_template( {
				list:  list,
				index: index,
			} ) );
			
			$('.select2').select2();
			jQuery(".ck_cbr_add_wrap .btn-ck-cbr-add").prop( "disabled", true );
			jQuery(".ck_cbr_add_wrap .btn-ck-cbr-add").css( "cursor", "not-allowed" );
			//componentHandler.upgradeAllRegistered();
			jQuery( ".ck_cbr_save_wrap .btn-ck-cbr-save" ).trigger( "click" );
		},
		
		delete_row: function( event ){
			event.preventDefault();
			$(this).parents(".Accordion").remove();
			var ajax_data = $("#cbr_checkout_restrictions_tab_form").serialize();
			
			$.post( ajaxurl, ajax_data, function() {
				//run code for after toggle save
				jQuery(".ck_cbr_add_wrap .btn-ck-cbr-add").prop( "disabled", false );
				jQuery(".ck_cbr_add_wrap .btn-ck-cbr-add").css( "cursor", "" );
			});
		},
				
	};
	wc_checkout_cbr.init();
	

})( jQuery, wp, ajaxurl );

function inArray(needle, haystack) {
	"use strict";
	for (var i in haystack) {
		if (haystack[i] === needle){
			return true;
		}
	}
	return false;
}

function showerror(element){
	"use strict";
	jQuery(".accordion").find(".cbr_single").css("display","none");
	jQuery(".accordion").children(".br_cbr_accordion_handle").find(".cbr_save_wrap").hide();
	jQuery(".accordion").children(".br_cbr_accordion_handle").find(".controller").show();
	element.parents(".accordion").find(".cbr_single").css("display","");
	element.parents(".accordion").children(".br_cbr_accordion_handle").find(".cbr_save_wrap").show();
	element.parents(".accordion").children(".br_cbr_accordion_handle").find(".controller").hide();
	element.addClass('border-color');
	element.css("border-color","red");
}

function hideerror(element){
	"use strict";
	element.parents(".accordion").find(".br_cbr_accordion_handle").removeClass("cbr-validatiton-error");
	element.removeClass('border-color');
	element.css("border-color","");
}

function ck_showerror(element){
	"use strict";
	jQuery(".Accordion").find(".ck_cbr_single").css("display","none");
	jQuery(".Accordion").children(".ck_cbr_accordion_handle").find(".ck_cbr_save_wrap").hide();
	jQuery(".Accordion").children(".ck_cbr_accordion_handle").find(".ck_controller").show();
	element.parents(".Accordion").find(".ck_cbr_single").css("display","");
	element.parents(".Accordion").children(".ck_cbr_accordion_handle").find(".ck_cbr_save_wrap").show();
	element.parents(".Accordion").children(".ck_cbr_accordion_handle").find(".ck_controller").hide();
	element.addClass('border-color');
	element.css("border-color","red");
}

function ck_hideerror(element){
	"use strict";
	element.parents(".Accordion").find(".ck_cbr_accordion_handle").removeClass("cbr-validatiton-error");
	element.removeClass('border-color');
	element.css("border-color","");
}

jQuery(document).on("click", ".clickDiv", function(){
	"use strict";
	var panel = jQuery(this).parent().parent().children(".cbr_single");
	var ck_panel = jQuery(this).parent().parent().children(".ck_cbr_single");
	if(panel.hasClass("hide-panel")) {
		jQuery(".br_cbr_accordion_handle").parent().children(".cbr_single").addClass("hide-panel").slideUp("slow");
		jQuery(".br_cbr_accordion_handle").children(".controller").show();
		jQuery(".br_cbr_accordion_handle").children(".cbr_save_wrap").hide();
		jQuery(panel).removeClass("hide-panel").slideDown("slow");
		jQuery(this).parent().children(".controller").hide();
		jQuery(this).parent().children(".cbr_save_wrap").show();
	}else{
		jQuery(panel).addClass("hide-panel").slideUp("slow");
		jQuery(this).parent().children(".controller").show();
		jQuery(this).parent().children(".cbr_save_wrap").hide();
	}
	if(ck_panel.hasClass("hide-panel")) {
		jQuery(".ck_cbr_accordion_handle").parent().children(".ck_cbr_single").addClass("hide-panel").slideUp("slow");
		jQuery(".ck_cbr_accordion_handle").children(".ck_controller").show();
		jQuery(".ck_cbr_accordion_handle").children(".ck_cbr_save_wrap").hide();
		jQuery(ck_panel).removeClass("hide-panel").slideDown("slow");
		jQuery(this).parent().children(".ck_controller").hide();
		jQuery(this).parent().children(".ck_cbr_save_wrap").show();
	}else{
		jQuery(ck_panel).addClass("hide-panel").slideUp("slow");
		jQuery(this).parent().children(".ck_controller").show();
		jQuery(this).parent().children(".ck_cbr_save_wrap").hide();
	}
	
});

jQuery(document).on("click", ".single-block .name", function(){
	"use strict";
	var panel = jQuery(this).parent().parent().children(".method-options");
	jQuery(".single-block").css('border-bottom', '0');
	jQuery(".single-block .name").find("span.dashicons").removeClass("dashicons-arrow-down-alt2").addClass("dashicons-arrow-right-alt2");
	if(panel.hasClass("hide-panel")) {
		jQuery(".single-block .method-options").addClass("hide-panel").slideUp("slow");
		jQuery(this).find("span.dashicons").addClass("dashicons-arrow-down-alt2").removeClass("dashicons-arrow-right-alt2");
		jQuery(panel).removeClass("hide-panel").slideDown("slow");
		jQuery(this).parent().parent().css('border-bottom', '1px solid #e0e0e0');	
	} else {
		jQuery(this).find("span.dashicons").removeClass("dashicons-arrow-down-alt2").addClass("dashicons-arrow-right-alt2");
		jQuery(panel).addClass("hide-panel").slideUp("slow");
	}
	
});

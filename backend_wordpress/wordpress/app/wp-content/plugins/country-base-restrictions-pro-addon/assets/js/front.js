jQuery(document).ready(function(){
	
	jQuery('.cbr-select2').select2({
		//templateResult: formatState,
		//templateSelection: formatState
		templateResult: function(item) {
		  return format(item, false);
		},
		templateSelection: function(item) {
		  return format(item, false);
		}
	});
	
	jQuery(document).on("click", ".cbr-country-widget", function(){	
		jQuery('.cbr-widget-popup').show();
	});
	jQuery(document).on("click", ".popup_close_icon, .popupclose", function(){
		jQuery('.cbr-widget-popup').hide();
		jQuery('.cbr-shortcode-popup').hide();
	});
	jQuery(document).on("click", ".cbr-country-shortcode", function(){	
		jQuery('.cbr-shortcode-popup').show();
	});
	jQuery(document).on("change", "#calc_shipping_country, #shipping_country", function(){	
		Cookies.set('country', jQuery(this).val());
		set_customer_country_on_checkout_without_reload();
	}); 

});

function format(item, state) {
  if (!item.id) {
    return item.text;
  }
  var Span = jQuery("<Span>", {
	class: "country-select",
  });
  var span1 = jQuery("<Span>", {
    class: "flag "+item.element.value.toLowerCase(),
  });
  var span2 = jQuery("<span>", {
	class: "country-name",
    text: " " + item.text
  });
  Span.prepend(span2).prepend(span1);
  return Span;
}

jQuery(document).on("change", "#cbr_widget_select", function(){
    var value = jQuery(this).val();
	jQuery('#cbr_widget_select option[value='+value+']').attr('selected','selected');
  });

jQuery(document).on("click", ".widget-apply", function(){
	jQuery(this).html('<div class="dot-flashing"></div>');
	var cbr_widget_select = jQuery( '#cbr_widget_select option:selected' ).val();
	setCountryCookie( 'country', cbr_widget_select, 365 );
	
});
function setCountryCookie(cookieName, cookieValue, nDays) {
	var today = new Date();
	var expire = new Date();

	if (!nDays) 
		nDays=1;

	expire.setTime(today.getTime() + 3600000*24*nDays);
	document.cookie = cookieName+"="+escape(cookieValue) + ";path=/;expires="+expire.toGMTString();
	set_customer_country_on_checkout();
	
}

function set_customer_country_on_checkout(){
	"use strict";

	var country = jQuery(this).val();
	var data = {
		action: 'set_widget_country',
		country: country
	};		
	jQuery.ajax({
		url: cbr_ajax_object.cbr_ajax_url,
		data: data,
		type: 'POST',
		dataType:"json",	
		success: function(response) {
			jQuery("#wp-admin-bar-cbr_item a.ab-item").text("CBR Country: "+response.country);
			jQuery(".display-country-for-customer .country, .widget-country, .select-country-dropdown").val(response.countrycode);
			jQuery(".cbr_shipping_country").text(response.country);
			jQuery('<div class="dot-flashing"></div>').html('Apply');			
			location.reload();	
		},
		error: function(response) {
			console.log(response);			
		}
	});
	return false;
}

function setCookie(cookieName, cookieValue, nDays) {
	var today = new Date();
	var expire = new Date();

	if (!nDays) 
		nDays=1;

	expire.setTime(today.getTime() + 3600000*24*nDays);
	document.cookie = cookieName+"="+escape(cookieValue) + ";path=/;expires="+expire.toGMTString();
	//set_customer_country_on_checkout_without_reload();
	
}

function set_customer_country_on_checkout_without_reload(){
	"use strict";

	var country = jQuery(this).val();
	var data = {
		action: 'set_widget_country',
		country: country
	};		
	
	jQuery.ajax({
		url: cbr_ajax_object.cbr_ajax_url,
		data: data,
		type: 'POST',
		dataType:"json",	
		success: function(response) {
			jQuery("#wp-admin-bar-cbr_item a.ab-item").text("CBR Country: "+response.country);
			jQuery(".display-country-for-customer .country, .widget-country, .select-country-dropdown").val(response.countrycode);
			jQuery(".cbr_shipping_country").text(response.country);		
		},
		error: function(response) {				
		}
	});
	return false;
}

jQuery(document).on("submit", ".woocommerce-shipping-calculator", function(){
	/*setTimeout(function() {
		location.reload();
	 }, 3000);*/
	var country = jQuery('#calc_shipping_country').val();
	var data = {
		action: 'set_cart_page_country',
		country: country
	};		
	
	jQuery.ajax({
		url: cbr_ajax_object.cbr_ajax_url,
		data: data,
		type: 'POST',
		dataType:"json",	
		success: function(response) {
			jQuery("#wp-admin-bar-cbr_item a.ab-item").text("CBR Country: "+response.country);
			jQuery(".display-country-for-customer .country, .widget-country, .select-country-dropdown").val(response.countrycode);
			jQuery(".cbr_shipping_country").text(response.country);
		},
		error: function(response) {	
			console.log(response);			
		}
	});
	return false;
});


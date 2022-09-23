( function( $ ) {
	jQuery('.cbr-select2').select2({
		templateResult: function(item) {
		  return format(item, false);
		},
		templateSelection: function(item) {
		  return format(item, false);
		}
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
		Style: 'margin: 0 5px 0 0;',
	  });
	  var span2 = jQuery("<span>", {
		class: "country-name",
		text: " " + item.text
	  });
	  Span.prepend(span2).prepend(span1);
	  return Span;
	}
	
	$('.hide').hide();
    /* Hide/Show Header */
	wp.customize( 'cbrwl_header_text', function( value ) {		
		value.bind( function( cbrwl_header_text ) {
			var str = cbrwl_header_text;
			if( cbrwl_header_text ){
				$( '.popup_title' ).html(str);
			} else{
				$( '.popup_title' ).html('');
			}			
		});
	});
	
	wp.customize( 'cbrwl_text_before_dropdown', function( value ) {		
		value.bind( function( cbrwl_text_before_dropdown ) {
			var str = cbrwl_text_before_dropdown;
			if( cbrwl_text_before_dropdown ){
				$( '.popup_body > p:first-child' ).html(str);
			} else{
				$( '.popup_body > p:first-child' ).html('');
			}			
		});
	});
	
	wp.customize( 'cbrwl_text_after_dropdown', function( value ) {		
		value.bind( function( cbrwl_text_after_dropdown ) {
			var str = cbrwl_text_after_dropdown;
			if( cbrwl_text_after_dropdown ){
				$( '.popup_body p:last-child' ).html(str);
			} else{
				$( '.popup_body p:last-child' ).html('');
			}			
		});
	});
	
	wp.customize( 'cbrwl_background_color', function( setting ) {
		/* Deferred callback for when setting exists */
		setting.bind( function( cbrwl_background_color ) {
			/* Update callback for setting change */
			$( '.popupwrapper' ).css( 'background', cbrwl_background_color );					
		} );		
	} );
	
	wp.customize( 'cbrwl_background_opacity', function( setting ) {
		/* Deferred callback for when setting exists */
		setting.bind( function( cbrwl_background_opacity ) {	
			/* Update callback for setting change */
			$( '.popupwrapper' ).css( 'opacity', cbrwl_background_opacity );					
		} );		
	} );
	
	wp.customize( 'cbrwl_box_background_color', function( setting ) {
		/* Deferred callback for when setting exists */
		setting.bind( function( cbrwl_box_background_color ) {	
			/* Update callback for setting change */
			$( '.popuprow' ).css( 'background', cbrwl_box_background_color );					
		} );		
	} );
	
	wp.customize( 'cbrwl_box_border_color', function( setting ) {
		/* Deferred callback for when setting exists */
		setting.bind( function( cbrwl_box_border_color ) {	
			/* Update callback for setting change */
			$( '.popuprow' ).css( 'border-color', cbrwl_box_border_color );					
		} );		
	} );
	
	wp.customize( 'cbrwl_box_width', function( setting ) {
		/* Deferred callback for when setting exists */
		setting.bind( function( cbrwl_box_width ) {	
			/* Update callback for setting change */
			$( '.popuprow' ).css( 'width', cbrwl_box_width );					
		} );		
	} );
	
	wp.customize( 'cbrwl_box_padding', function( setting ) {
		/* Deferred callback for when setting exists */
		setting.bind( function( cbrwl_box_padding ) {	
			/* Update callback for setting change */
			$( '.popuprow' ).css( 'padding', cbrwl_box_padding );					
		} );		
	} );
	
	wp.customize( 'cbrwl_text_align', function( setting ) {
		/* Deferred callback for when setting exists */
		setting.bind( function( cbrwl_text_align ) {	
			/* Update callback for setting change */
			$( '.popuprow' ).css( 'text-align', cbrwl_text_align );					
		} );		
	} );
	
	wp.customize( 'cbrwl_box_border_redius', function( setting ) {
		/* Deferred callback for when setting exists */
		setting.bind( function( cbrwl_box_border_redius ) {	
			/* Update callback for setting change */
			$( '.popuprow' ).css( 'border-radius', cbrwl_box_border_redius );					
		} );		
	} );
} )( jQuery );
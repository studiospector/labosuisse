  jQuery( function($) {
    jQuery('.chosen-select').select2({
   });
  jQuery('.sel2').select2();
  var ajaxurl = cor_php_vars.admin_url;
  var nonce   = cor_php_vars.nonce;

  jQuery('.sel_pros').select2({

    ajax: {
      url: ajaxurl, // AJAX URL is predefined in WordPress admin.
      dataType: 'json',
      type: 'POST',
      delay: 250, // delay in ms while typing when to perform a AJAX search.
      data: function (params) {
        return {
          q: params.term, // search query.
          action: 'WhatsAppsearchProducts', // AJAX action for admin-ajax.php.
          nonce: nonce // AJAX nonce for admin-ajax.php.
        };
      },
      processResults: function( data ) {
        var options = [];
        if ( data ) {
   
          // data is the array of arrays, and each of them contains ID and the Label of the option
          $.each( data, function( index, text ) { // do not forget that "index" is just auto incremented value
            options.push( { id: text[0], text: text[1]  } );
          });
   
        }
        return {
          results: options
        };
      },
      cache: true
    },
    multiple: true,
    placeholder: 'Choose Products',
    minimumInputLength: 3 // the minimum of symbols to input before perform a search.
    
  });

  if($("#cor_unselected_method_2").is(":checked")){
    $(".all_redirect_methods_cor_general_settings").show();
  }else{
    $(".all_redirect_methods_cor_general_settings").hide();
  }
  $("#cor_unselected_method_2").click(function(){
    if($(this).is(":checked")){
      $(".all_redirect_methods_cor_general_settings").show();
    }else{
      $(".all_redirect_methods_cor_general_settings").hide();
    }
  });

    $("#cor_unselected_method_1").click(function(){
    if($(this).is(":checked")){
      $(".all_redirect_methods_cor_general_settings").hide();
    }else{
      $(".all_redirect_methods_cor_general_settings").show();
    }
  });

  if($("#cor_hide_add_to_cart_global_2").is(":checked")){
    $(".replace_button_select_options").show();
  }else{
    $(".replace_button_select_options").hide();
  }
  
  $("#cor_hide_add_to_cart_global_2").click(function(){
    if($(this).is(":checked")){
      $(".replace_button_select_options").show();
    }else{
      $(".replace_button_select_options").hide();
    }
  });

  $("#cor_hide_add_to_cart_global_1").click(function(){
    if($(this).is(":checked")){
      $(".replace_button_select_options").hide();
    }else{
      $(".replace_button_select_options").show();
    }
  });
  //Hide price rule
  if($("#cor_price_opt2").is(":checked")){
    $(".cor_hide_price_rule").show();
  }else{
    $(".cor_hide_price_rule").hide();
  }
  
  $("#cor_price_opt2").click(function(){
    if($(this).is(":checked")){
      $(".cor_hide_price_rule").show();
    }else{
      $(".cor_hide_price_rule").hide();
    }
  });

  $("#cor_price_opt1").click(function(){
    if($(this).is(":checked")){
      $(".cor_hide_price_rule").hide();
    }else{
      $(".cor_hide_price_rule").show();
    }
  });
  //Hide price global.
  
  if($(".cor_hide_price_global_1:checked").val() == 'hide_pr') {
      $(".cor_hide_price_global").show();
  } else {
      $(".cor_hide_price_global").hide();
  }

  $(document).on('click',".cor_hide_price_global_1",function(){
    
    if($(".cor_hide_price_global_1:checked").val() == 'hide_pr'){
      $(".cor_hide_price_global").show();
    }else{
      $(".cor_hide_price_global").hide();
    }
  });
  if (($("#cor_enable_bluk").val()) == '1') {
    $(".cor_hide_bluk_p").show();
    $(".hide_price_section").hide();
    $(".cor_price_opt_text_tr").hide();
    $(".cor_check_opt_text_tr").hide();
    $( ".cor_price_cart_uncheck" ).prop( "checked", false );
    
  }
  else if(($("#cor_enable_bluk").val()) == '2'){
    $(".cor_hide_bluk_p").hide();
    $(".hide_price_section").show();
  }
  
  $(function () {
    $("#cor_enable_bluk").change(function () {
      if (($("#cor_enable_bluk").val()) == '1') {
        $(".hide_price_section").hide();
        $(".cor_hide_bluk_p").show();
        $(".cor_price_opt_text_tr").hide();
        $(".cor_check_opt_text_tr").hide();
        $( ".cor_price_cart_uncheck" ).prop( "checked", false );
      }
      else if(($("#cor_enable_bluk").val()) == '2'){
        $(".cor_hide_bluk_p").hide();
        $(".hide_price_section").show();
      }
    });
  });

  // For Redirection
  if (($("#ka_cor_restriction_option").val()) == '2') {
    // For website page
    $("#ka_cor_redirect_page").show();
    $(".ka_cor_redirect_page").show();
    $("#ka_cor_redirect_link").hide();
    $(".ka_cor_redirect_link").hide();
    $("#cor_r_message").hide();
    $(".cor_r_message").hide();
  }
  else if(($("#ka_cor_restriction_option").val()) == '3'){
    //  for external link
    $("#ka_cor_redirect_link").show();
    $(".ka_cor_redirect_link").show();
    $("#ka_cor_redirect_page").hide();
    $(".ka_cor_redirect_page").hide();
    $("#cor_r_message").hide();
    $(".cor_r_message").hide();
  }else{
    // restriction message
    $("#cor_r_message").show();
    $(".cor_r_message").show();
    $("#ka_cor_redirect_page").hide();
    $(".ka_cor_redirect_page").hide();
    $("#ka_cor_redirect_link").hide();
    $(".ka_cor_redirect_link").hide();
  }
  $(function () {
    $("#ka_cor_restriction_option").change(function () {
      if (($("#ka_cor_restriction_option").val()) == '1') {
        $("#cor_r_message").show();
        $(".cor_r_message").show();
        $("#ka_cor_redirect_page").hide();
        $(".ka_cor_redirect_page").hide();
        $(".ka_cor_redirect_link").hide();
      }
      else if(($("#ka_cor_restriction_option").val()) == '2'){
        $("#ka_cor_redirect_page").show();
        $(".ka_cor_redirect_page").show();
        $("#ka_cor_redirect_link").hide();
        $(".ka_cor_redirect_link").hide();
        $("#cor_r_message").hide();
        $(".cor_r_message").hide();
      }
      else if(($("#ka_cor_restriction_option").val()) == '3'){
        $("#ka_cor_redirect_link").show();
        $(".ka_cor_redirect_link").show();
        $("#ka_cor_redirect_page").hide();
        $(".ka_cor_redirect_page").hide();
        $("#cor_r_message").hide();
        $(".cor_r_message").hide();
      }
    });
  });

    // for variation level
    $(document).on('change','.cor_restricted_countries_pl_cb_hide_cart_msg',function () {
      var cor_pl_vl_option = $(this).data('variation_id');
      console.log(cor_pl_vl_option);
      if (($(this).val()) == '2') {
        $(".cor_v_l_mthd_2"+$(this).data('variation_id')).show();
        $(".cor_v_l_mthd_3"+$(this).data('variation_id')).hide();
      }
      else if(($(this).val()) == '3'){
        $(".cor_v_l_mthd_2"+$(this).data('variation_id')).hide();
        $(".cor_v_l_mthd_3"+$(this).data('variation_id')).show();
      }else if(($(this).val()) == '1'){
        $(".cor_v_l_mthd_2"+$(this).data('variation_id')).hide();
        $(".cor_v_l_mthd_3"+$(this).data('variation_id')).hide();
      }
    });

  //  FOR product level
  

  if (($("#addf_cor_restriction_option_product_side").val()) == '2') {
    // For website page
    $(".cor_product_side_enter_btn_text").show();
    $(".cor_product_side_enter_text").hide();
  }
  else if(($("#addf_cor_restriction_option_product_side").val()) == '3'){
    //  for external link
    $(".cor_product_side_enter_text").show();
    $(".cor_product_side_enter_btn_text").hide();
  }else{
    // restriction message
    $(".cor_product_side_enter_text").hide();
    $(".cor_product_side_enter_btn_text").hide();
  }
  $(function () {
    $("#addf_cor_restriction_option_product_side").change(function () {
      if (($("#addf_cor_restriction_option_product_side").val()) == '2') {
        // For website page
        $(".cor_product_side_enter_btn_text").show();
        $(".cor_product_side_enter_text").hide();
      }
      else if(($("#addf_cor_restriction_option_product_side").val()) == '3'){
        //  for external link
        $(".cor_product_side_enter_text").show();
        $(".cor_product_side_enter_btn_text").hide();
      }else{
        // restriction message
        $(".cor_product_side_enter_text").hide();
        $(".cor_product_side_enter_btn_text").hide();
      }
    });
  });
//hide add to cart global visibility.
if (($("#replace_button_select_options").val()) == 'replace_btn') {
    $(".custom_btn_link_message").show();
    $(".cstm_message_option_button_hhide").hide();
    
  }
  else if(($("#replace_button_select_options").val()) == 'msg_btn'){
    $(".custom_btn_link_message").hide();
    $(".cstm_message_option_button_hhide").show();
  }else{
        $(".cstm_message_option_button_hhide").hide();
        $(".custom_btn_link_message").hide();
  } 
  
  $(function () {
    $("#replace_button_select_options").change(function () {
      if (($("#replace_button_select_options").val()) == 'replace_btn') {
        $(".cstm_message_option_button_hhide").hide();
        $(".custom_btn_link_message").show();
     
      }
      else if(($("#replace_button_select_options").val()) == 'msg_btn'){
        $(".custom_btn_link_message").hide();
        $(".cstm_message_option_button_hhide").show();
      }else{
            $(".cstm_message_option_button_hhide").hide();
            $(".custom_btn_link_message").hide();
      }
    });
  });


  if($("#cor_product_price").is(":checked")){
    $(".cor_product_price").show();
  }else{
    $(".cor_product_price").hide();
  }
  $("#cor_product_price").click(function(){
    if($(this).is(":checked")){
      $(".cor_product_price").show();
    }else{
      $(".cor_product_price").hide();
    }
  });
  if($("#cor_product_add").is(":checked")){
    $(".addf_cor_restriction_option_product_side").show();    
  }else{
    $(".addf_cor_restriction_option_product_side").hide();
    $(".cor_product_side_enter_btn_text").hide();
    $(".cor_product_side_enter_text").hide();
  }
  $("#cor_product_add").click(function(){
    if($(this).is(":checked")){
      $(".addf_cor_restriction_option_product_side").show();
    }else{
      $(".cor_product_side_enter_btn_text").hide();
      $(".addf_cor_restriction_option_product_side").hide();
      $(".cor_product_side_enter_text").hide();
    }
  });
  
  //  for hidding price section
  if($("#cor_price_opt").is(":checked")){
    $(".cor_price_opt_text_tr").show();
  }else{
    $(".cor_price_opt_text_tr").hide();
  }
  $("#cor_price_opt").click(function(){
    if($(this).is(":checked")){
      $(".cor_price_opt_text_tr").show();
    }else{
      $(".cor_price_opt_text_tr").hide();
    }
  });

//Hide price rule
  if($("#cor_add_cart_opt2").is(":checked")){
    $(".cor_check_opt_text_tr").show();
  }else{
    $(".cor_check_opt_text_tr").hide();
  }
  
  $("#cor_add_cart_opt2").click(function(){
    if($(this).is(":checked")){
      $(".cor_check_opt_text_tr").show();
    }else{
      $(".cor_check_opt_text_tr").hide();
    }
  });

  $("#cor_add_cart_opt1").click(function(){
    if($(this).is(":checked")){
      $(".cor_check_opt_text_tr").hide();
    }else{
      $(".cor_check_opt_text_tr").show();
    }
  });

    // For Custom button
  if (($("#cor_hide_r_add_cart").val()) == '2') {
    // For Cutom Button
    $(".custom_btn_add_to_cart").show();
    $(".cor_add_text").hide();
  }
  else if(($("#cor_hide_r_add_cart").val()) == '3'){
    //  for message
    $(".cor_add_text").show();
    $(".custom_btn_add_to_cart").hide();
  }else{
    // empty
    $(".custom_btn_add_to_cart").hide();
    $(".cor_add_text").hide();
  }
  $(function () {
    $("#cor_hide_r_add_cart").change(function () {
      if (($("#cor_hide_r_add_cart").val()) == '1') {
      // empty
      $(".custom_btn_add_to_cart").hide();
      $(".cor_add_text").hide();
      }
      else if(($("#cor_hide_r_add_cart").val()) == '2'){
       // For Cutom Button
        $(".custom_btn_add_to_cart").show();
        $(".cor_add_text").hide();
      }
      else if(($("#cor_hide_r_add_cart").val()) == '3'){
        //  for message
        $(".cor_add_text").show();
        $(".custom_btn_add_to_cart").hide();
      }
    });
  });
    // For hidding options in rules
  if (($("#addf_cor_restriction_option").val()) == '2') {
    // For store page
    $("#addf_cor_redirect_page").show();
    $(".addf_cor_redirect_page").show();
    $("#addf_cor_redirect_link").hide();
    $(".addf_cor_redirect_link").hide();
    $("#cor_r_message").hide();
    $(".cor_r_message").hide();
  }
  else if(($("#addf_cor_restriction_option").val()) == '3'){
    //  for url
    $("#addf_cor_redirect_page").hide();
    $(".addf_cor_redirect_page").hide();
    $("#addf_cor_redirect_link").show();
    $(".addf_cor_redirect_link").show();
    $("#cor_r_message").hide();
    $(".cor_r_message").hide();
  }else{
    // restriction message
    $("#addf_cor_redirect_page").hide();
    $(".addf_cor_redirect_page").hide();
    $("#addf_cor_redirect_link").hide();
    $(".addf_cor_redirect_link").hide();
    $("#cor_r_message").show();
    $(".cor_r_message").show();
  }
  $(function () {
    $("#addf_cor_restriction_option").change(function () {
      if (($("#addf_cor_restriction_option").val()) == '1') {
      // restriction message
        $("#addf_cor_redirect_page").hide();
        $(".addf_cor_redirect_page").hide();
        $("#addf_cor_redirect_link").hide();
        $(".addf_cor_redirect_link").hide();
        $("#cor_r_message").show();
        $(".cor_r_message").show();
      }
      else if(($("#addf_cor_restriction_option").val()) == '2'){
       // For store page
      $("#addf_cor_redirect_page").show();
      $(".addf_cor_redirect_page").show();
      $("#addf_cor_redirect_link").hide();
      $(".addf_cor_redirect_link").hide();
      $("#cor_r_message").hide();
      $(".cor_r_message").hide();
      }
      else if(($("#addf_cor_restriction_option").val()) == '3'){
         //  for url
      $("#addf_cor_redirect_page").hide();
      $(".addf_cor_redirect_page").hide();
      $("#addf_cor_redirect_link").show();
      $(".addf_cor_redirect_link").show();
      $("#cor_r_message").hide();
      $(".cor_r_message").hide();
      }
    });
  });
  

  $(function () {
    $('.js_multipage_select_product').select2({
        ajax: {
                url: ajaxurl, // AJAX URL is predefined in WordPress admin
                dataType: 'json',
                delay: 250, // delay in ms while typing when to perform a AJAX search
                data: function (params) {
                    return {
                        q: params.term, // search query
                        action: 'cor_getproductsearch' // AJAX action for admin-ajax.php
                    };
                },
                processResults: function( data ) {
                var options = [];
                if ( data ) {
                    // data is the array of arrays, and each of them contains ID and the Label of the option
                    $.each( data, function( index, text ) { // do not forget that "index" is just auto incremented value
                        options.push( { id: text[0], text: text[1]  } );
                    });
                }
                return {
                    results: options
                };
            },
            cache: true
        },
        minimumInputLength: 3 // the minimum of symbols to input before perform a search
    });
});
});
  
jQuery(document).ready(function ($) {
  jQuery('.custom_datepicker').datepicker({
    dateFormat: 'yy-mm-dd'
  });

  jQuery("#calicantus_options\\[calicantus_modalita\\]").change(function() {
    checkMethod();
  });

  checkMethod();

  function checkMethod(){
    var fields = ['calicantus_options\\[calicantus_ftps_host\\]', 'calicantus_options\\[calicantus_ftps_port\\]', 'calicantus_options\\[calicantus_ftps_user\\]', 'calicantus_options\\[calicantus_ftps_password\\]', 'calicantus_options\\[calicantus_cache_ttl\\]'];
    if(jQuery('#calicantus_options\\[calicantus_modalita\\]').children("option:selected").val() == 'ftps'){
      for(i=0;i<fields.length;i++){
        jQuery('#'+fields[i]).parent().parent().show();
      }
    }else{
      for(i=0;i<fields.length;i++){
        jQuery('#'+fields[i]).parent().parent().hide();
      }
    }
  }
});
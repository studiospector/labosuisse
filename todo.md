
TODO

- check chiamata a API Search Autocomplete(magari salbvare nel localstorage)



----------



E-COMMERCE


se mi trovo in:
Italia

IT -> posso acquistare e spedire solo in Italia
EN -> posso acquistare e spedire solo in Germania, Francia, Spagna, Paesi Bassi, Belgio, Irlanda



se mi trovo in:
Germania
Francia
Spagna
Olanda
Belgio
Irlanda

IT -> posso acquistare e spedire solo in Italia
EN -> posso acquistare e spedire solo in Germania, Francia, Spagna, Paesi Bassi, Belgio, Irlanda



se mi trovo in:
Inghilterra
Turchia
etc...

IT -> non posso acquistare ma posso vedere il catalogo
EN -> non posso acquistare ma posso vedere il catalogo







jQuery("#cor_countries").find('option').prop("selected",true);
jQuery("#cor_countries").trigger('change');

jQuery(".cor_all_cats").find('input').prop("checked",true);

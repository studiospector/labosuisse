import Component from '@okiba/component'

const ui = {}

class AsyncCart extends Component {

    constructor({ options, ...props }) {
        super({ ...props, ui })

        this.refreshFragmentsOnPageLoad()

        jQuery(document).ready(($) => {
            // $(document.body).on('wc_fragments_refreshed', function(){
            //     console.log('wc_fragments_refreshed');
            // });

            $.fn.serializeArrayAll = function () {
                var rCRLF = /\r?\n/g;
                return this.map(function () {
                    return this.elements ? jQuery.makeArray(this.elements) : this;
                }).map(function (i, elem) {
                    var val = jQuery(this).val();
                    if (val == null) {
                        return val == null
                        //next 2 lines of code look if it is a checkbox and set the value to blank 
                        //if it is unchecked
                    } else if (this.type == "checkbox" && this.checked === false) {
                        return { name: this.name, value: this.checked ? this.value : '' }
                        //next lines are kept from default jQuery implementation and 
                        //default to all checkboxes = on
                    } else {
                        return jQuery.isArray(val) ?
                            jQuery.map(val, function (val, i) {
                                return { name: elem.name, value: val.replace(rCRLF, "\r\n") };
                            }) :
                            { name: elem.name, value: val.replace(rCRLF, "\r\n") };
                    }
                }).get();
            }

            // Remove from cart listeners
            $('.lb-wc-async-cart__item__remove').on('click', function (e) {
                $('#lb-offsetnav-async-cart .lb-offset-nav__content').addClass('lb-offset-nav__content--loading');
            })
            $(document).on('removed_from_cart', function (e) {
                $('#lb-offsetnav-async-cart .lb-offset-nav__content').removeClass('lb-offset-nav__content--loading');
            })

            // Add to cart
            $(document).on('click', '.single_add_to_cart_button:not(.disabled)', function (e) {
                var $thisbutton = $(this),
                    $form = $thisbutton.closest('form.cart'),
                    //quantity = $form.find('input[name=quantity]').val() || 1,
                    //product_id = $form.find('input[name=variation_id]').val() || $thisbutton.val(),
                    data = $form.find('input:not([name="product_id"]), select, button, textarea').serializeArrayAll() || 0

                $.each(data, function (i, item) {
                    if (item.name == 'add-to-cart') {
                        item.name = 'product_id';
                        item.value = $form.find('input[name=variation_id]').val() || $thisbutton.val();
                    }
                })

                e.preventDefault()

                $(document.body).trigger('adding_to_cart', [$thisbutton, data])

                const cartEndpoint = woocommerce_params.wc_ajax_url.toString().replace('%%endpoint%%', 'add_to_cart')

                console.log('url', process.env.LB_API_URL + cartEndpoint);
                console.log('data', data);

                $.ajax({
                    type: 'POST',
                    url: process.env.LB_API_URL + cartEndpoint,
                    data: data,
                    beforeSend: function (response) {
                        $thisbutton.removeClass('added').addClass('button-loading');
                        $('#lb-offsetnav-async-cart .lb-offset-nav__content').addClass('lb-offset-nav__content--loading');
                        $thisbutton.attr('disabled', true);
                    },
                    error: function (request, status, error) {
                        console.log('error request', request);
                        console.log('error status', status);
                        console.log('error error', error);
                    },
                    success: function (response) {
                        console.log('success', response);
                        if (response.error && response.product_url) {
                            window.location = response.product_url;
                            return;
                        }

                        $(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, $thisbutton]);

                        window.openOffsetNav('lb-offsetnav-async-cart')

                        // Remove from cart listeners
                        $('.lb-wc-async-cart__item__remove').on('click', function (e) {
                            $('#lb-offsetnav-async-cart .lb-offset-nav__content').addClass('lb-offset-nav__content--loading');
                        })
                        $(document).on('removed_from_cart', function (e) {
                            $('#lb-offsetnav-async-cart .lb-offset-nav__content').removeClass('lb-offset-nav__content--loading');
                        })
                    },
                    complete: function (response) {
                        console.log('complete', response);
                        $thisbutton.addClass('added').removeClass('button-loading');
                        $('#lb-offsetnav-async-cart .lb-offset-nav__content').removeClass('lb-offset-nav__content--loading');
                        $thisbutton.attr('disabled', false);
                    },
                })

                return false
            })
        })
    }

    refreshFragments() {
        if (typeof wc_cart_fragments_params !== 'undefined') {
            jQuery(document.body).trigger('wc_fragment_refresh')
            return
        }
    }

    refreshFragmentsOnPageLoad() {
        setTimeout(function () {
            this.refreshFragments()
        }.bind(this), 1000)
    }
}

export default AsyncCart

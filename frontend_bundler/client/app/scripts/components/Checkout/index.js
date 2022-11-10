import Component from '@okiba/component'

class LbWcCheckout extends Component {

    constructor({ options, ...props }) {
        super({ ...props })

        // Trigger WC Checkout Update on Init
        jQuery('body').on('init_checkout', function () {
            jQuery('form.checkout').trigger('update')
        })

        // On validate form fields
        jQuery('form.checkout').on('validate', this.checkForErrors)
        // On update checkout fields
        jQuery('body').on('updated_checkout', this.checkForErrors)
    }

    checkForErrors = () => {
        var errorContainer = jQuery('form.checkout .woocommerce-NoticeGroup-updateOrderReview ul > li')

        if (errorContainer.length > 0) {
            jQuery('form.checkout .lb-wc-payment-actions').addClass('lb-wc-payment-actions--error')
        } else {
            jQuery('form.checkout .lb-wc-payment-actions').removeClass('lb-wc-payment-actions--error')
        }

        window.getCustomScrollbar.update()
    }
}

export default LbWcCheckout

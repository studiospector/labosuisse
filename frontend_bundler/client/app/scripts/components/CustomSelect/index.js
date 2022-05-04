import Component from '@okiba/component'
import { qs, on, off } from '@okiba/dom'
import { debounce } from '@okiba/functions'

import CustomSelect from '../../vendors/custom-select'

const ui = {}

class LBCustomSelect extends Component {
    
    constructor({ options, ...props }) {
        super({ ...props, ui })

        this.init()
    }

    init = debounce(() => {
        new CustomSelect({
            selector: this.el,
            woocommerceSupportVariations: {
                selector: '.single-product-details__summary__variations'
            },
            // debug: true
        })

        this.customField = this.el.closest('.custom-select')
        this.customFieldOptions = qs('.custom-select-items', this.customField)

        on(this.customFieldOptions, 'mouseenter', this.disableLocomotive)
        on(this.customFieldOptions, 'mouseleave', this.enableLocomotive)
    }, 300)

    disableLocomotive = (ev) => {
        window.getCustomScrollbar.stop()
    }

    enableLocomotive = (ev) => {
        window.getCustomScrollbar.start()
    }
}

export default LBCustomSelect

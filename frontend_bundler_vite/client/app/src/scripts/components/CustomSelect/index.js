import Component from '@okiba/component'
import { qs, on, off } from '@okiba/dom'
import { debounce } from '@okiba/functions'

import CustomSelect from '../../vendors/custom-select'

const ui = {}

class LBCustomSelect extends Component {
    
    constructor({ options, ...props }) {
        super({ ...props, ui })

        this.wcVariationsSupport = this.el.dataset.wcVariationsSupport

        this.wcVariationsSupport ? this.dispatchInit() : this.init()
    }

    dispatchInit = debounce(() => this.init(), 300)

    init = () => {
        new CustomSelect({
            selector: this.el,
            woocommerceSupportVariations: this.wcVariationsSupport ? {
                selector: '.single-product-details__summary__variations'
            } : false,
            // debug: true
        })

        this.customField = this.el.closest('.custom-select')
        this.customFieldOptions = qs('.custom-select-items', this.customField)

        on(this.customFieldOptions, 'mouseenter', this.disableScroll)
        on(this.customFieldOptions, 'mouseleave', this.enableScroll)
    }

    disableScroll = (ev) => {
        ev.target.setAttribute('data-lenis-prevent', true)
    }

    enableScroll = (ev) => {
        ev.target.removeAttribute('data-lenis-prevent')
        off(document, 'wheel', this.enableScroll)
    }
}

export default LBCustomSelect

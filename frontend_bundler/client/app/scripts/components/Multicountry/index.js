import Component from '@okiba/component'
import { on } from '@okiba/dom'

const ui = {
    catalogOption: '#lb-catalog-selection',
    button: '.button',
}

class Multicountry extends Component {

    constructor({ options, ...props }) {
        super({ ...props, ui })

        this.catalogSelected = this.ui.catalogOption.options[this.ui.catalogOption.selectedIndex].value;

        on(this.ui.catalogOption, 'select', this.changeCatalog)
    }

    changeCatalog = (ev) => {
        this.ui.button.classList.remove('button-primary-disabled')

        if (ev.target.value == 'IT') {
            this.ui.button.setAttribute('href', process.env.LB_API_URL)
        } else if (ev.target.value == 'EN') {
            this.ui.button.setAttribute('href', `${process.env.LB_API_URL}/en/`)
        }
    }
}

export default Multicountry

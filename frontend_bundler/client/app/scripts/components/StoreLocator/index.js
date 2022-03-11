import Component from '@okiba/component';
import { on, qs } from '@okiba/dom';

const ui = {}

class StoreLocator extends Component {

    constructor({ el }) {
        super({ el, ui })

        this.adjustPosition()
        on(window, 'resize', this.adjustPosition)
    }

    adjustPosition = () => {
        const containerSample = qs('.lb-header .lb-header__top')
        const containerSampleStyle = getComputedStyle(containerSample)
        const containerSampleML = containerSampleStyle.marginLeft
        
        this.el.style.marginLeft = containerSampleML
    }
}

export default StoreLocator

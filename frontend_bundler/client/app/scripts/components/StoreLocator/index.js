import Component from '@okiba/component';
import { on, qs } from '@okiba/dom';

const ui = {
    map: '.lb-store-locator__map',
    list: '.lb-store-locator__list-wrapper',
}

class StoreLocator extends Component {

    constructor({ el }) {
        super({ el, ui })

        // Adjust store locator position
        this.adjustPosition()
        on(window, 'resize', this.adjustPosition)

        // Disable/Enable Locomotive scroll on specific elements
        const elemsToDisable = [this.ui.list, this.ui.map]
        on(elemsToDisable, 'mouseenter', this.disableLocomotive)
        on(elemsToDisable, 'mouseleave', this.enableLocomotive)
    }

    adjustPosition = () => {
        const containerSample = qs('.lb-header .lb-header__top')
        const containerSampleStyle = getComputedStyle(containerSample)
        const containerSampleML = containerSampleStyle.marginLeft
        
        this.el.style.marginLeft = containerSampleML
    }

    disableLocomotive = (ev) => {
        window.getCustomScrollbar.stop()
    }

    enableLocomotive = (ev) => {
        window.getCustomScrollbar.start()
    }
}

export default StoreLocator

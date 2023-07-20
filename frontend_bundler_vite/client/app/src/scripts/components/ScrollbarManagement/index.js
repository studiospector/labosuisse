import Component from '@okiba/component';
import { on } from '@okiba/dom';

class ScrollbarManagement extends Component {

    constructor({ el }) {
        super({ el })

        this.managementType = this.el.dataset.managementType
        this.managementDelay = this.el.dataset.managementDelay

        // Disable/Enable Scroll on specific elements mouseover
        if (this.managementType == 'mouseover') {
            on(this.el, 'mouseenter', this.disableScroll)
            on(this.el, 'mouseleave', this.enableScroll)
        }
        // Update Scroll on specific elements change/click
        else if (this.managementType == 'change' || this.managementType == 'click') {
            on(this.el, this.managementType, () => {
                setTimeout(() => {
                    this.updateScroll()
                }, Number(this.managementDelay))
            })
            
        }
    }

    disableScroll = (ev) => {
        window.getCustomScrollbar.stop()
    }

    enableScroll = (ev) => {
        window.getCustomScrollbar.start()
    }
}

export default ScrollbarManagement

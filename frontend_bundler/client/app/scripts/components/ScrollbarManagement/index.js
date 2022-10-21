import Component from '@okiba/component';
import { on } from '@okiba/dom';

class ScrollbarManagement extends Component {

    constructor({ el }) {
        super({ el })

        this.managementType = this.el.dataset.managementType
        this.managementDelay = this.el.dataset.managementDelay

        // Disable/Enable Locomotive scroll on specific elements mouseover
        if (this.managementType == 'mouseover') {
            on(this.el, 'mouseenter', this.disableLocomotive)
            on(this.el, 'mouseleave', this.enableLocomotive)
        }
        // Update Locomotive scroll on specific elements change/click
        else if (this.managementType == 'change' || this.managementType == 'click') {
            on(this.el, this.managementType, () => {
                setTimeout(() => {
                    this.updateLocomotive()
                }, Number(this.managementDelay))
            })
            
        }
    }

    disableLocomotive = (ev) => {
        window.getCustomScrollbar.stop()
    }

    enableLocomotive = (ev) => {
        window.getCustomScrollbar.start()
    }

    updateLocomotive = (ev) => {
        window.getCustomScrollbar.update()
    }
}

export default ScrollbarManagement

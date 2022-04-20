import Component from '@okiba/component';
import { on, qs } from '@okiba/dom';

class ScrollbarManagement extends Component {

    constructor({ el }) {
        super({ el })

        // Disable/Enable Locomotive scroll on specific elements
        on(this.el, 'mouseenter', this.disableLocomotive)
        on(this.el, 'mouseleave', this.enableLocomotive)
    }

    disableLocomotive = (ev) => {
        window.getCustomScrollbar.stop()
    }

    enableLocomotive = (ev) => {
        window.getCustomScrollbar.start()
    }
}

export default ScrollbarManagement

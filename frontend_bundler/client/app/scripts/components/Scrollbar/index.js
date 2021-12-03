import Component from '@okiba/component'
import { qs, on, off } from '@okiba/dom'

import LocomotiveScroll from 'locomotive-scroll'

export default class Scrollbar extends Component {
    constructor({ options, ...props }) {
        super({ ...props })

        const defaultOptions = {
            el: this.el,
            smooth: true,
            // getSpeed: true,
            getDirection: true,
            reloadOnContextChange: true
        }

        // Init Locomotive Scroll
        this.scrollbar = new LocomotiveScroll(defaultOptions)

        // Add Locomotive Scroll to window for global access
        window.getCustomScrollbar = this.scrollbar
    }


}

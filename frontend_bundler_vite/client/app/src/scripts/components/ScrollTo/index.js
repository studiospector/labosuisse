import Component from '@okiba/component';
import { on, qs } from '@okiba/dom';

import { getHeaderFullHeight } from '../../utils/headerHeight';

import { gsap } from 'gsap';

import { ScrollToPlugin } from 'gsap/ScrollToPlugin';

gsap.registerPlugin(ScrollToPlugin);

const ui = {}

class ScrollTo extends Component {

    constructor({ el }) {
        super({ el, ui })

        this.customScrollbar = window.getCustomScrollbar

        this.fullHeaderHeight = getHeaderFullHeight()

        on(this.el, 'click', this.scrollTo)
    }

    scrollTo = (ev) => {
        ev.stopPropagation()
        const scrollToSection = this.el.getAttribute('data-scroll-to')
        const scrollToOffset = this.el.getAttribute('data-scroll-to-offset')
        this.customScrollbar.scrollTo(scrollToSection, {
            offset: -((scrollToOffset ? scrollToOffset : 0) + this.fullHeaderHeight)
        })
    }
}

export default ScrollTo

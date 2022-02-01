import Component from '@okiba/component';
import { on, qs } from '@okiba/dom';
import { gsap } from 'gsap';
import { ScrollToPlugin } from 'gsap/ScrollToPlugin';

gsap.registerPlugin(ScrollToPlugin);

const ui = {}

class ScrollTo extends Component {

    constructor({ el }) {
        super({ el, ui })

        this.customScrollbar = window.getCustomScrollbar

        const header = qs('.lb-header')
        const headerProduct = qs('.lb-header-sticky-product')
        
        this.fullHeaderHeight = (header ? header.getBoundingClientRect().height : 0) + (headerProduct ? headerProduct.getBoundingClientRect().height : 0)
        
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

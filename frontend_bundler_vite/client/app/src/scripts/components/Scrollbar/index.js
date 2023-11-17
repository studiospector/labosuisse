import Component from '@okiba/component'
import { qs, on } from '@okiba/dom';
import { debounce } from '@okiba/functions'

import { getHeaderFullHeight } from '../../utils/headerHeight';

import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

import Lenis from '@studio-freight/lenis'

gsap.registerPlugin(ScrollTrigger);

class Scrollbar extends Component {

    constructor({ options, ...props }) {
        super({ ...props })

        this.init()
    }

    init = () => {
        this.lenis = new Lenis()

        this.lenis.on('scroll', ScrollTrigger.update)

        gsap.ticker.add((time)=>{
            lenis.raf(time * 1000)
        })

        gsap.ticker.lagSmoothing(0)

        // Add Lenis to window for global access
        window.getCustomScrollbar = this.lenis

        setTimeout(() => this.scrollToWithAnchorLink(), 3000);
    }

    scrollToWithAnchorLink = () => {
        const fullHeaderHeight = getHeaderFullHeight()

        const hash = window.location.hash

        if (hash) {
            const el = qs(hash)
            
            this.lenis.scrollTo(el, {
                offset: -fullHeaderHeight
            })
        }
    }
}

export default Scrollbar

import Component from '@okiba/component'
import EventManager from '@okiba/event-manager'
import { qs } from '@okiba/dom'

import isStorybook from '../../utils/isStorybook'

import { gsap } from "gsap"
import { ScrollTrigger } from "gsap/ScrollTrigger"

gsap.registerPlugin(ScrollTrigger)

const ui = {
    cards: {
        selector: '.card',
        asArray: true,
    },
}

class AnimationCard extends Component {
    constructor({ el }) {
        super({ el, ui })

        ScrollTrigger.matchMedia({
            "(min-width: 768px)": this.onDesktopMatch,
            "(max-width: 767px)": this.onMobileMatch
        })

        this.init()
        this.listen()
    }

    init() {
        this.computeAnimation()
    }

    listen() {
        EventManager.on('resize', this.onResize)
    }

    unListen() {
        EventManager.off('resize', this.onResize)
    }

    onResize = () => {
        if (!this.tl) {
            return
        }
        if (window.innerWidth < 768) {
            const targets = this.tl.getChildren()
            this.tl.kill()

            for (let i = 0; i < targets.length; i++) {
                if (targets[i].targets().length) {
                    gsap.set(targets[i].targets(), { clearProps: "all" });
                }
            }
        }
    }

    onDestroy() {
        this.unListen()
    }

    onDesktopMatch = () => {
        this.enabled = true
    }

    onMobileMatch = () => {
        this.enabled = false
    }

    computeAnimation() {
        if (!this.enabled) {
            return
        }

        this.tl = gsap.timeline({
            scrollTrigger: {
                scroller: isStorybook() ? 'body' : '.js-scrollbar',
                trigger: this.el,
                start: "30% 80%",
            }
        })

        this.tl.addLabel('start')
        this.tl.from(this.ui.cards, {
            duration: 1.5,
            autoAlpha: 0,
            y: 20,
            stagger: 0.15,
            ease: "Power4.easeOut",
        });
    }
}

export default AnimationCard

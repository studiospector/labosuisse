import Component from '@okiba/component'
import EventManager from '@okiba/event-manager'
import { qs } from '@okiba/dom'

import isStorybook from '../../utils/isStorybook'
import isScrollbarDisabled from '../../utils/isScrollbarDisabled'

import { gsap } from "gsap"
import { ScrollTrigger } from "gsap/ScrollTrigger";

const ui = {
    picture: '.lb-picture',
    image: 'img',
    overlay: '.lb-picture-overlay',
}

class AnimationImage extends Component {
    constructor({ el, revealType }) {
        super({ el, ui })

        this.revealType = revealType

        const timeout = (this.revealType == 'intro') ? 1500 : 1

        ScrollTrigger.matchMedia({
            "(min-width: 768px)": this.onDesktopMatch,
            "(max-width: 767px)": this.onMobileMatch
        })

        setTimeout(() => {
            this.init()
            this.listen()
        }, timeout);
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
                scroller: (isStorybook() || isScrollbarDisabled()) ? 'body' : '.js-scrollbar',
                trigger: this.ui.picture,
                start: "30% 80%",
            }
        })

        if (this.revealType == 'intro') {
            this.animateIntro()
        } else {
            this.animateDefault()
        }
    }

    animateDefault = () => {
        this.tl.addLabel('start')
        this.tl.to(this.ui.overlay, { duration: 1, width: "100%", ease: "Power4.easeInOut" })
        this.tl.set(this.ui.overlay, { duration: 0, right: 0, left: "unset" })
        this.tl.to(this.ui.overlay, { duration: 1, width: "0%", ease: "Power4.easeInOut" })
        this.tl.to(this.ui.image, { duration: 1, opacity: 1, delay: -1, ease: "Power4.easeInOut" })
        this.tl.from(this.ui.image, {
            duration: 2,
            scale: 1.2,
            ease: "Power4.easeOut",
            delay: -0.8
        })
    }

    animateIntro = () => {
        this.tl.addLabel('start')
        this.tl.to(this.ui.image, { duration: 1, opacity: 1, ease: "Power4.easeInOut" })
        this.tl.from(this.ui.image, {
            duration: 2,
            scale: 1.2,
            ease: "Power4.easeOut",
            delay: -0.8
        })
    }
}

export default AnimationImage

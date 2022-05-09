import Component from '@okiba/component'
import EventManager from '@okiba/event-manager'
import { qs } from '@okiba/dom'
import { gsap } from "gsap"
import { ScrollTrigger } from "gsap/ScrollTrigger";

const ui = {
    picture: '.lb-picture',
    image: 'img',
    overlay: '.lb-picture-overlay',
}

class AnimationImage extends Component {
    constructor({ el }) {
        super({ el, ui })

        ScrollTrigger.matchMedia({
            "(min-width: 769px)": this.onDesktopMatch,
            "(max-width: 768px)": this.onMobileMatch
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
                scroller: '.js-scrollbar',
                trigger: this.ui.picture,
                start: "30% 80%",
            }
        })

        this.tl.addLabel('start')
        this.tl.to(this.ui.overlay, { duration: 1, width: "100%", ease: "Power4.easeInOut" })
        this.tl.set(this.ui.overlay, { duration: 0, right: 0, left: "unset" })
        this.tl.to(this.ui.overlay, { duration: 1, width: "0%", ease: "Power4.easeInOut" })
        this.tl.to(this.ui.image, { duration: 1, opacity: 1, delay: -1, ease: "Power4.easeInOut" })
        this.tl.from(this.ui.image, {
            duration: 1,
            scale: 1.4,
            ease: "Power4.easeInOut",
            delay: -1.2
        })
    }
}

export default AnimationImage

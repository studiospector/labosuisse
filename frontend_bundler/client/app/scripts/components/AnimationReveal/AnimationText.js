import Component from '@okiba/component'
import EventManager from '@okiba/event-manager'
import { qs } from '@okiba/dom'

import isStorybook from '../../utils/isStorybook'
import isScrollbarDisabled from '../../utils/isScrollbarDisabled'

import { gsap } from "gsap"
import { ScrollTrigger } from "gsap/ScrollTrigger"
import { SplitText } from "gsap/SplitText"

gsap.registerPlugin(ScrollTrigger, SplitText)

const ui = {
    tagline: '.infobox__tagline',
    title: '.infobox__title',
    subtitle: '.infobox__subtitle',
    paragraph: {
        selector: '.infobox__paragraph',
        asArray: true,
    },
    cta: '.infobox__cta',
}

class AnimationText extends Component {
    constructor({ el, revealType }) {
        super({ el, ui })

        this.revealType = revealType

        let mm = gsap.matchMedia()
        mm.add("(min-width: 768px)", () => this.onDesktopMatch())
        mm.add("(max-width: 767px", () => this.onMobileMatch())

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
                scroller: (isStorybook() || isScrollbarDisabled()) ? 'body' : '.js-scrollbar',
                trigger: this.el,
                start: "30% 80%",
            }
        })

        const timeout = (this.revealType == 'intro') ? 1 : 0

        const elems = [this.ui.tagline, this.ui.title, this.ui.subtitle, ...this.ui.paragraph].filter(el => el)

        const childSplit = new SplitText(elems, {
            type: "lines",
            linesClass: "lb-split-child-line"
        })

        const parentSplit = new SplitText(elems, {
            type: "lines",
            linesClass: "lb-split-parent-line"
        })

        this.tl.addLabel('start')
        this.tl.from(childSplit.lines, {
            duration: 1.5,
            yPercent: 100,
            ease: "power4",
            stagger: 0.1
        }, timeout)
        if (this.ui.cta) {
            this.tl.fromTo(this.ui.cta, { autoAlpha: 0 }, { duration: 1, autoAlpha: 1, ease: "Power4.easeOut" })
        }
    }
}

export default AnimationText

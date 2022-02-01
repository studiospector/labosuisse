import Component from '@okiba/component'
import { qsa, qs, on, off } from '@okiba/dom'

import { gsap } from 'gsap'
import { ScrollTrigger } from 'gsap/ScrollTrigger'

gsap.registerPlugin(ScrollTrigger)

export default class HeaderProduct extends Component {
    constructor({ options, ...props }) {
        super({ ...props })

        this.elemHeight = this.el.getBoundingClientRect().height

        // const observer = new MutationObserver( (event) => {
        //     let classes = event[0].target.classList
        //     setTimeout(() => {
        //         // if (classes.contains('lb-header--hide')) {
        //             this.adjustElement(0, false, classes.contains('lb-header--hide'))
        //         // }
        //     }, 300);
        // })
        // observer.observe(qs('.lb-header'), {
        //     attributes: true, 
        //     attributeFilter: ['class'],
        //     childList: false, 
        //     characterData: false
        // })

        this.animation()
    }

    animation = () => {
        const tl = gsap.timeline({
            scrollTrigger: {
                trigger: ".js-single-product-details",
                scroller: ".js-scrollbar",
                start: "top top",
                // end: "+=100%",
                onEnter: () => {
                    this.adjustElement(0, true)
                    // this.el.classList.add('lb-header-sticky-product--scrolled')
                },
                // onLeave: () => this.el.classList.add('lb-header-sticky-product--scrolled'),
                onEnterBack: () => {
                    setTimeout(() => {
                        this.adjustElement(qs('.lb-header').getBoundingClientRect().height, true)
                        // this.el.classList.remove('lb-header-sticky-product--scrolled')
                    }, 300);
                },
                // onLeaveBack: () => this.el.classList.remove('lb-header-sticky-product--scrolled'),
                // onUpdate() {
                //     console.log("Update")
                // }
            }
        })
        // tl.fromTo(this.el, { backgroundColor: "#000" }, { backgroundColor: "#28a92b" })
    } 

    adjustElement = (elemHeight, height, isHide) => {
        let matchMedia = window.matchMedia("screen and (max-width: 768px)")
        // if (height || (isHide === undefined || isHide === false)) {
        if (height || (isHide === undefined || isHide === false || isHide === true)) {
            if (matchMedia.matches) {
                const bottom = (elemHeight <= 0) ? 0 : '-100%'
                this.el.style.bottom = bottom
            } else {
                const top = qs('.lb-header').getBoundingClientRect().height - (elemHeight ? elemHeight : 0)
                this.el.style.top = `${top}px`
            }
        }
    }
}

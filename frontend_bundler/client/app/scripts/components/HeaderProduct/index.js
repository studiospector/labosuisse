import Component from '@okiba/component'
import { qs, on } from '@okiba/dom'

import { getHeaderFullHeight } from '../../utils/headerHeight'

import { gsap } from 'gsap'
import { ScrollTrigger } from 'gsap/ScrollTrigger'

gsap.registerPlugin(ScrollTrigger)

const ui = {
    button: '.lb-header-sticky-product__button'
}

class HeaderProduct extends Component {

    constructor({ options, ...props }) {
        super({ ...props, ui })

        this.customScrollbar = window.getCustomScrollbar

        this.fullHeaderHeight = getHeaderFullHeight()

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

        on(this.ui.button, 'click', this.scrollToAddToCart)
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
    }

    adjustElement = (elemHeight, height, isHide) => {
        console.log('adjustElement', elemHeight, height, isHide);
        let matchMedia = window.matchMedia("screen and (max-width: 767px)")
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

    scrollToAddToCart = (ev) => {
        ev.stopPropagation()
        const scrollToSection = qs('.single-product-details__summary > p.price')
        const addToCartButton = qs('.single_add_to_cart_button')
        this.customScrollbar.scrollTo(scrollToSection, {
            offset: -this.fullHeaderHeight,
            callback: () => {
                const timeline = gsap.timeline()
                timeline.to(addToCartButton, {
                    scale: 1.2,
                    ease: "power2.inOut",
                    duration: 0.8,
                })
                timeline.to(addToCartButton, {
                    scale: 1,
                    ease: "power2.inOut",
                    duration: 0.8,
                })
            }
        })
    }
}

export default HeaderProduct

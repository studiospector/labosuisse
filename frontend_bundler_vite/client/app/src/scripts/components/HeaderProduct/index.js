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

        this.animation()

        on(this.ui.button, 'click', this.scrollToAddToCart)
    }

    animation = () => {
        let matchMedia = window.matchMedia("screen and (max-width: 767px)")
        gsap.timeline({
            scrollTrigger: {
                trigger: ".js-single-product-details",
                scroller: matchMedia.matches ? "body" : ".js-scrollbar",
                start: "top top",
                onEnter: () => {
                    this.el.classList.remove('lb-header-sticky-product--adjust')
                    setTimeout(() => {
                        if (matchMedia.matches) {
                            this.el.style.bottom = 0
                        } else {
                            // this.adjustElement(0, true)
                            this.el.style.top = `${qs('.lb-header').getBoundingClientRect().height}px`
                        }
                    }, 600);
                },
                onEnterBack: () => {
                    this.el.classList.add('lb-header-sticky-product--adjust')
                    if (matchMedia.matches) {
                        this.el.style.bottom = '-100%'
                    } else {
                        // this.adjustElement(qs('.lb-header').getBoundingClientRect().height, true)
                        this.el.style.top = `0px`
                    }
                },
            }
        })
    }

    adjustElement = (elemHeight, height, isHide) => {
        let matchMedia = window.matchMedia("screen and (max-width: 767px)")
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

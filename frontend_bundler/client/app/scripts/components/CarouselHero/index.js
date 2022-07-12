import Component from '@okiba/component'
import { debounce } from '@okiba/functions'

import Swiper from 'swiper/bundle'
import { gsap } from "gsap"
import { SplitText } from "gsap/SplitText"

gsap.registerPlugin(SplitText)

const ui = {
    pagination: '.swiper-pagination'
}

class CarouselHero extends Component {

    constructor({ options, ...props }) {
        super({ ...props, ui })

        // Init swiper
        this.swiper = new Swiper(this.el, {
            speed: 400,
            // autoplay: {
            //     delay: 5000,
            //     disableOnInteraction: false
            // },
            grabCursor: true,
            effect: 'fade',
            fadeEffect: {
                crossFade: true
            },
            pagination: {
                el: this.ui.pagination,
            },
            on: {
                init: debounce((swiperObj) => {
                    this.transitionStart(swiperObj)
                }, 800),
                slideChangeTransitionStart: (swiperObj) => {
                    this.transitionStart(swiperObj)
                },
                slideChangeTransitionEnd: (swiperObj) => {
                    this.transitionEnd(swiperObj)
                },
            }
        })

        setTimeout(() => {
            this.swiper.params.autoplay.delay = 5000
            this.swiper.params.autoplay.disableOnInteraction = false
            this.swiper.autoplay.start()
        }, 8000)

        this.initAnimations(this.swiper)
    }

    initAnimations = (swiperObj) => {
        // Slides Images
        const slidesImages = swiperObj.slides.find("img")
        // Slides Texts
        const slidesTexts = swiperObj.slides.find(".infobox__tagline, .infobox__title, .infobox__subtitle, .infobox__paragraph")
        // Slides CTAs
        const slidesCTAs = swiperObj.slides.find(".infobox__cta")

        // Reset animations
        gsap.set(slidesImages, { opacity: 0 })
        gsap.set(slidesTexts, { autoAlpha: 0 })
        gsap.set(slidesCTAs, { autoAlpha: 0 })
    }

    transitionStart = (swiperObj) => {
        // Active slide
        const slideActive = swiperObj.$el.find(".swiper-slide-active")

        // Current Slide image
        const slideImage = slideActive.find("img")
        // Current Slide Texts
        const slideText = slideActive.find(".infobox__tagline, .infobox__title, .infobox__subtitle, .infobox__paragraph")
        const childSplit = new SplitText(slideText, { type: "lines", linesClass: "lb-split-child-line" })
        const parentSplit = new SplitText(slideText, { type: "lines", linesClass: "lb-split-parent-line" })
        // Current Slide CTA
        const slideCTA = slideActive.find(".infobox__cta")
        
        // Texts animation
        gsap.set(slideText, { autoAlpha: 1 })
        gsap.from(childSplit.lines, {
            duration: 1.5,
            yPercent: 100,
            ease: "power4",
            stagger: 0.1,
            delay: 0.8,
            onComplete: () => {
                childSplit.revert()
            }
        })
        // // CTA animation
        if (slideCTA.length > 0) {
            gsap.set(slideCTA, { autoAlpha: 1 })
            gsap.from(slideCTA, { duration: 1, autoAlpha: 0, ease: "Power4.easeOut", delay: 1.5 })
        }

        // Image animation
        gsap.to(slideImage, { duration: 1, opacity: 1, ease: "Power4.easeInOut" })
        gsap.from(slideImage, {
            duration: 2,
            scale: 1.2,
            ease: "Power4.easeOut",
            delay: 0.5
        })
    }

    transitionEnd = (swiperObj) => {
        // Next and Prev Slides
        const slidesNextPrev = swiperObj.$el.find(".swiper-slide-prev, .swiper-slide-next")

        // Next and Prev Slides Images
        const slidesNextPrevImages = slidesNextPrev.find("img")
        // Next and Prev Slides Texts
        const slidesNextPrevText = slidesNextPrev.find(".infobox__tagline, .infobox__title, .infobox__subtitle, .infobox__paragraph")
        // Next and Prev Slides CTAs
        const slidesCTA = slidesNextPrev.find(".infobox__cta")

        // Reset animations
        gsap.set(slidesNextPrevImages, { opacity: 0 })
        gsap.set(slidesNextPrevText, { autoAlpha: 0 })
        if (slidesCTA.length > 0) {
            gsap.set(slidesCTA, { autoAlpha: 0 })
        }
    }

    onDestroy() {
        this.swiper.destroy()
    }
}

export default CarouselHero

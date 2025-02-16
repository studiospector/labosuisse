import Component from '@okiba/component'
import { debounce } from '@okiba/functions'

import Swiper, { Pagination, Autoplay, EffectFade } from 'swiper'
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
            modules: [ Pagination, EffectFade, Autoplay ],
            speed: 400,
            grabCursor: true,
            effect: 'fade',
            fadeEffect: {
                crossFade: true
            },
            pagination: {
                el: this.ui.pagination,
                type: 'bullets',
            },
            on: {
                init: debounce((swiperObj) => {
                    this.swiper.params.autoplay.delay = 8000
                    this.swiper.params.autoplay.disableOnInteraction = false
                    this.swiper.autoplay.start()

                    const slides = swiperObj.$el.find(".swiper-slide")
                    slides.forEach(el => {
                        const texts = el.querySelectorAll(".infobox__tagline, .infobox__title, .infobox__subtitle, .infobox__paragraph")
                        new SplitText(texts, { type: "lines", linesClass: "lb-split-child-line" })
                        new SplitText(texts, { type: "lines", linesClass: "lb-split-parent-line" })
                    })
                }, 1000),
                slideChangeTransitionStart: (swiperObj) => {
                    this.transitionStart(swiperObj)
                }
            }
        })
    }

    transitionStart = (swiperObj) => {
        // Active Slide
        const slideActive = swiperObj.$el.find(".swiper-slide-active")
        // Next and Prev Slides
        const slidesNextPrev = swiperObj.$el.find(".swiper-slide-prev, .swiper-slide-next")

        // Active Slide Image
        const slideImage = slideActive.find("img")
        // Active Slide Texts
        const slideTexts = slideActive.find(".lb-split-child-line")
        // Active Slide CTA
        const slideCTA = slideActive.find(".infobox__cta")

        // Next and Prev Slides Images
        const slidesNextPrevImages = slidesNextPrev.find("img")

        // Timeline start
        const tl = gsap.timeline()

        // Leave Image animations
        gsap.set(slideImage, { opacity: 0 })
        tl.to(slidesNextPrevImages, { duration: 1, opacity: 1, ease: "Power4.easeInOut" })

        // Image animation
        tl.to(slideImage, { duration: 1, opacity: 1, ease: "Power4.easeInOut" }, '-=0.5')
        tl.from(slideImage, {
            duration: 2,
            scale: 1.2,
            ease: "Power4.easeOut",
        }, '<')
        
        // Texts animation
        tl.set(slideTexts, { autoAlpha: 1 }, '<')
        tl.from(slideTexts, {
            duration: 1.5,
            yPercent: 100,
            ease: "power4",
            stagger: 0.1,
            delay: 0.8,
            // onComplete: () => {
            //     slideTexts.revert()
            // }
        }, '<')

        // CTA animation
        if (slideCTA.length > 0) {
            tl.from(slideCTA, { duration: 1, autoAlpha: 0, ease: "Power4.easeOut" }, '-=0.5')
        }
    }

    onDestroy() {
        this.swiper.destroy()
    }
}

export default CarouselHero

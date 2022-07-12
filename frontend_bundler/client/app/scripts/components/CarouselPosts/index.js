import Component from '@okiba/component'
import { qs, on } from '@okiba/dom'

import Swiper, { Pagination } from 'swiper'

const ui = {
    pagination: '.swiper-pagination'
}

class CarouselPosts extends Component {

    constructor({ options, ...props }) {
        super({ ...props, ui })

        // Swiper Default params
        const swiperDefault = {
            modules: [ Pagination ],
            speed: 400,
            slidesPerView: 1,
            spaceBetween: 20,
            breakpoints: {
                // when window width is >= 768px
                768: {
                    spaceBetween: 30,
                },
            },
            pagination: {
                el: this.ui.pagination,
                type: 'bullets',
            },
        }

        // Swiper Two posts variant params
        const swiperTwoPosts = {
            modules: [ Pagination ],
            slidesPerView: 1,
            spaceBetween: 20,
            breakpoints: {
                // when window width is >= 768px
                768: {
                    slidesPerView: 1,
                    spaceBetween: 70,
                },
                // when window width is >= 991px
                991: {
                    slidesPerView: 2,
                    spaceBetween: 70,
                },
            },
        }

        // Get Variant
        this.variant = this.el.getAttribute('data-variant')

        // Swiper params
        const swiperParams = (this.variant == 'two-posts') ? Object.assign(swiperDefault, swiperTwoPosts) : swiperDefault

        // Init swiper
        this.swiper = new Swiper(this.el, swiperParams);

        // Variants dispatcher
        if (this.variant == 'full') {
            this.variantsDispatcher()
            on(window, 'resize', this.variantsDispatcher)
        }

    }

    variantsDispatcher = () => {
        let matchMedia = window.matchMedia("screen and (min-width: 768px)")
        this.variantFull(matchMedia)
    }

    variantFull = (e) => {
        if (e.matches) {
            const elemHeight = this.el.offsetHeight
            const sliderContainer = qs('.carousel-posts__slider')

            this.el.style.position = 'absolute'
            this.el.style.left = '0'
            this.el.style.overflowX = 'hidden'

            sliderContainer.style.height = `${elemHeight}px`
        } else {
            this.el.removeAttribute('style')
        }
    }

    onDestroy() {
        this.swiper.destroy()
    }
}

export default CarouselPosts

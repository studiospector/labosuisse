import Component from '@okiba/component'

import Swiper, { Navigation } from 'swiper'

const ui = {
    nextElement: '.swiper-button-next',
    prevElement: '.swiper-button-prev',
}

class CarouselCentered extends Component {

    constructor({ options, ...props }) {
        super({ ...props, ui })

        setTimeout(() => {
            // Init swiper
            this.swiper = new Swiper(this.el, {
                modules: [ Navigation ],
                // loop: true,
                slidesPerView: 1,
                centeredSlides: false,
                spaceBetween: 30,
                navigation: {
                    nextEl: this.ui.nextElement,
                    prevEl: this.ui.prevElement,
                },
                breakpoints: {
                    // when window width is >= 767px
                    767: {
                        slidesPerView: 'auto',
                        centeredSlides: true,
                        spaceBetween: 80,
                    },
                },
            })
        }, 300)
    }

    onDestroy() {
        this.swiper.destroy()
    }
}

export default CarouselCentered

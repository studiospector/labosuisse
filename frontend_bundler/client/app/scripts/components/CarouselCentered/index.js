import Component from '@okiba/component'
import { qs, on, off } from '@okiba/dom'

import Swiper from 'swiper/bundle';

const ui = {
    nextElement: '.swiper-button-next',
    prevElement: '.swiper-button-prev',
}

export default class CarouselCentered extends Component {
    constructor({ options, ...props }) {
        super({ ...props, ui })

        setTimeout(() => {
            // Init swiper
            this.swiper = new Swiper(this.el, {
                // loop: true,
                slidesPerView: 1,
                centeredSlides: false,
                spaceBetween: 30,
                navigation: {
                    nextEl: this.ui.nextElement,
                    prevEl: this.ui.prevElement,
                },
                breakpoints: {
                    // when window width is >= 768px
                    768: {
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

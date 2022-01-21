import Component from '@okiba/component'
import { qsa, qs, on, off } from '@okiba/dom'

import Swiper from 'swiper/bundle'

const ui = {
    pagination: '.swiper-pagination',
    thumb: '.lb-product-gallery-thumb',
    slider: '.lb-product-gallery-slider'
}

export default class CarouselProductImage extends Component {
    constructor({ options, ...props }) {
        super({ ...props, ui })

        this.thumb = new Swiper(this.ui.thumb, {
            // freeMode: true,
            // watchSlidesProgress: true,
            slidesPerView: 4,
            spaceBetween: 10,
            breakpoints: {
                // when window width is >= 768px
                768: {
                    spaceBetween: 30,
                    slidesPerView: 2,
                },
            },
        })

        this.slider = new Swiper(this.ui.slider, {
            autoHeight: true,
            // navigation: {
            //     nextEl: ".swiper-button-next",
            //     prevEl: ".swiper-button-prev",
            // },
            pagination: {
                el: this.ui.pagination,
            },
            // zoom: {
            //     maxRatio: 5,
            // },
            breakpoints: {
                // when window width is >= 768px
                768: {
                    spaceBetween: 30,
                    pagination: false,
                    thumbs: {
                        swiper: this.thumb,
                    },
                },
            },
        })

        // const sliderSlides = qsa('.swiper-slide', qs('.lb-product-gallery-slider'))
        // for(let i = 0; i < sliderSlides.length; i++) {
        //     sliderSlides[i].addEventListener('mouseover', (e) => {
        //         this.slider.zoom.in()
        //     })
        //     sliderSlides[i].addEventListener('mouseout', (e) => {
        //         this.slider.zoom.out()
        //     })
        // }
    }

    onDestroy() {
        this.slider.destroy()
        this.thumb.destroy()
    }
}

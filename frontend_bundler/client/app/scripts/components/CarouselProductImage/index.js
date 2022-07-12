import Component from '@okiba/component'

import PhotoSwipeLightbox from 'photoswipe/dist/photoswipe-lightbox.esm.js'
import PhotoSwipe from 'photoswipe/dist/photoswipe.esm.js'

import Swiper, { Pagination } from 'swiper'

const ui = {
    desktop: '.lb-product-gallery__desktop',
    mobile: '.lb-product-gallery__mobile',
    sliderMobile: '.lb-product-gallery__mobile__slider',
    pagination: '.swiper-pagination',
}

class CarouselProductImage extends Component {
    
    constructor({ options, ...props }) {
        super({ ...props, ui })

        // Lightbox default params
        this.defaultParams = {
            mainClass: 'pswp--custom-bg',

            children: 'a',

            closeOnVerticalDrag: true,

            mouseMovePan: true,

            initialZoomLevel: 'fit',
            secondaryZoomLevel: 4,
            maxZoomLevel: 1,

            arrowPrevSVG: `
                <span class="lb-icon">
                    <svg aria-label="arrow-left" xmlns="http://www.w3.org/2000/svg">
                        <use xlink:href="#arrow-left"></use>
                    </svg>
                </span>
            `,
            arrowNextSVG: `
                <span class="lb-icon">
                    <svg aria-label="arrow-right" xmlns="http://www.w3.org/2000/svg">
                        <use xlink:href="#arrow-right"></use>
                    </svg>
                </span>
            `,
            closeSVG: `
                <span class="lb-icon">
                    <svg aria-label="close" xmlns="http://www.w3.org/2000/svg">
                        <use xlink:href="#close"></use>
                    </svg>
                </span>
            `,
            zoomSVG: `
                <span class="lb-icon">
                    <svg aria-label="close-circle" xmlns="http://www.w3.org/2000/svg">
                        <use xlink:href="#close-circle"></use>
                    </svg>
                </span>
            `,

            bgOpacity: 1,

            pswpModule: PhotoSwipe
        }


        // Lightbox desktop
        this.lightboxDesktop = new PhotoSwipeLightbox({
            ...this.defaultParams,
            gallery: this.ui.desktop,
        });
        this.lightboxDesktop.init();


        // Lightbox mobile
        this.lightboxMobile = new PhotoSwipeLightbox({
            ...this.defaultParams,
            gallery: this.ui.mobile,
        });
        this.lightboxMobile.init();


        // Slider mobile
        this.slider = new Swiper(this.ui.sliderMobile, {
            modules: [ Pagination ],
            autoHeight: true,
            slidesPerView: 1,
            spaceBetween: 30,
            pagination: {
                el: this.ui.pagination,
            },
        })
    }

    onDestroy() {
        this.slider.destroy()
    }
}

export default CarouselProductImage

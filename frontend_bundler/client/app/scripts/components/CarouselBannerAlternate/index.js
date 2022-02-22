import Component from '@okiba/component'

import Swiper from 'swiper/bundle';

const ui = {
    pagination: '.swiper-pagination'
}

class CarouselBannerAlternate extends Component {

    constructor({ options, ...props }) {
        super({ ...props, ui })

        const defaults = {
            speed: 400
        }

        // Init swiper
        this.swiper = new Swiper(this.el, {
            ...defaults,
            pagination: {
                el: this.ui.pagination,
            },
        });
    }

    onDestroy() {
        this.swiper.destroy()
    }
}

export default CarouselBannerAlternate

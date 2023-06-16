import Component from '@okiba/component'

import Swiper, { Pagination } from 'swiper'

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
            modules: [ Pagination ],
            pagination: {
                el: this.ui.pagination,
                type: 'bullets',
            },
        });
    }

    onDestroy() {
        this.swiper.destroy()
    }
}

export default CarouselBannerAlternate

import Component from '@okiba/component'
import { qs, on, off } from '@okiba/dom'

import Swiper from 'swiper/bundle';

const ui = {
    scroller: '.lovelabo__grid .row',
    items: {
        selector: '.lovelabo__grid .lovelabo__img',
        asArray: true
    },
}

export default class BlockLoveLabo extends Component {
    constructor({ options, ...props }) {
        super({ ...props, ui })

        // Only for Variant Full
        if (this.el.classList.contains('lovelabo--full')) {
            this.placeScoller()
        }
    }

    placeScoller() {
        const secondItemsCoordinates = this.ui.items[1].getBoundingClientRect()
        this.ui.scroller.scrollLeft = secondItemsCoordinates.left / 2
    }
}

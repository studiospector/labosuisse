import Component from '@okiba/component'

import Scrollbar from './Scrollbar'
import Header from './Header'
import LBCustomInput from './CustomInput'
import LBCustomSelect from './CustomSelect'
import CarouselHero from './CarouselHero'
import CarouselPosts from './CarouselPosts'
import Hero from './Hero'

const components = {
    scrollbar: {
        selector: '.js-scrollbar',
        type: Scrollbar,
        optional: true
    },
    header: {
        selector: '.js-header',
        type: Header,
        optional: true,
    },
    customInput: {
        selector: '.js-custom-input',
        type: LBCustomInput,
        optional: true
    },
    customSelect: {
        selector: '.js-custom-select',
        type: LBCustomSelect,
        optional: true
    },
    carouselHero: {
        selector: '.js-carousel-hero',
        type: CarouselHero,
        optional: true
    },
    carouselPosts: {
        selector: '.js-carousel-posts',
        type: CarouselPosts,
        optional: true
    },
    hero: {
        selector: '.js-hero',
        type: Hero,
        optional: true
    },
}

export default class Application extends Component {
    constructor() {
        super({ el: document.body, components })

        this.el.classList.add('ready')

        /**
         * Locomotive scroll calculates page height on initialization.
         * Some content may have not finished to load and change page height afterwards (for example image loading).
         * So we update scroll also after first initialization to prevent hidden sections.
         */
        window.getCustomScrollbar.update()
    }
}

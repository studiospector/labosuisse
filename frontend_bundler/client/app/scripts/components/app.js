import Component from '@okiba/component'

import { ScrollTrigger } from 'gsap/ScrollTrigger';

import Scrollbar from './Scrollbar'
import Menu from './Menu';
import Header from './Header'
import LBCustomInput from './CustomInput'
import LBCustomSelect from './CustomSelect'
import CarouselHero from './CarouselHero'
import CarouselPosts from './CarouselPosts'
import Hero from './Hero'
import BannerAlternate from './BannerAlternate'

const components = {
    scrollbar: {
        selector: '.js-scrollbar',
        type: Scrollbar,
        optional: true
    },
    menu: {
        selector: '.js-menu',
        type: Menu,
        optional: true,
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
    bannerAlternate: {
        selector: '.js-banner-alternate',
        type: BannerAlternate,
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
         * N.B. This is only for Front-end and not for admin WP area.
         */
        if (!document.querySelector('body').classList.contains('wp-admin')) {
            window.getCustomScrollbar.update()

            // each time the window updates, we should refresh ScrollTrigger and then update LocomotiveScroll. 
            ScrollTrigger.addEventListener("refresh", () => window.getCustomScrollbar.update());

            // after everything is set up, refresh() ScrollTrigger and update LocomotiveScroll because padding may have been added for pinning, etc.
            ScrollTrigger.refresh();
        }
    }
}

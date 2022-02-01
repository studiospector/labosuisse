import Component from '@okiba/component'

import { ScrollTrigger } from 'gsap/ScrollTrigger';

import Scrollbar from './Scrollbar'
import Menu from './Menu';
import Header from './Header'
import HeaderProduct from './HeaderProduct'
import LBCustomInput from './CustomInput'
import LBCustomSelect from './CustomSelect'
import CarouselHero from './CarouselHero'
import CarouselCentered from './CarouselCentered'
import CarouselBannerAlternate from './CarouselBannerAlternate'
import CarouselPosts from './CarouselPosts'
import CarouselProductImage from './CarouselProductImage'
import Hero from './Hero'
import BannerAlternate from './BannerAlternate'
import BlockLoveLabo from './BlockLoveLabo'
import OffsetNavSystem from './OffsetNavSystem'
import AnimationParallax from './AnimationParallax'
import CardsGrid from './CardsGrid'
import ScrollTo from './ScrollTo'

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
    headerProduct: {
        selector: '.js-header-sticky-product',
        type: HeaderProduct,
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
    carouselCentered: {
        selector: '.js-carousel-centered',
        type: CarouselCentered,
        optional: true
    },
    carouselBannerAlternate: {
        selector: '.js-carousel-banner-alternate',
        type: CarouselBannerAlternate,
        optional: true
    },
    carouselPosts: {
        selector: '.js-carousel-posts',
        type: CarouselPosts,
        optional: true
    },
    carouselProductImage: {
        selector: '.js-lb-product-gallery',
        type: CarouselProductImage,
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
    blockLoveLabo: {
        selector: '.js-love-labo',
        type: BlockLoveLabo,
        optional: true
    },
    animationParallax: {
        selector: '.js-parallax',
        type: AnimationParallax,
        optional: true
    },
    cardsGrid: {
        selector: '.js-cards-grid',
        type: CardsGrid,
        optional: true
    },
    scrollTo: {
        selector: '.js-scroll-to',
        type: ScrollTo,
        optional: true
    },
}

export default class Application extends Component {
    constructor() {
        super({ el: document.body, components })

        this.components.offsetNavs = OffsetNavSystem.get()

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

        this.selectTertiaryVariantAlignment()
    }


    selectTertiaryVariantAlignment = () => {
        const groupedSelect = document.querySelectorAll('.custom-select-group')

        if (groupedSelect.length > 0) {
            groupedSelect.forEach(elem => {
                const labels = elem.querySelectorAll('.custom-select-label')
                let labelsWidth = []
                let labelsFullWidth = []
                labels.forEach( el => {
                    labelsWidth.push(el.offsetWidth)
                    labelsFullWidth.push(this.getFullWidth(el))
                })

                labels.forEach(elem => {
                    elem.style.minWidth = `${Math.max(...labelsWidth)}px`
                })

                const items = elem.querySelectorAll('.custom-select-items .custom-select-items__item')

                items.forEach(elem => {
                    elem.style.paddingLeft = `${Math.max(...labelsFullWidth) + 16.5 - 26}px`
                })
            })
        }
    }

    getFullWidth(el) {
        let elWidth = el.offsetWidth

        elWidth += parseInt(window.getComputedStyle(el).getPropertyValue('margin-left'))
        elWidth += parseInt(window.getComputedStyle(el).getPropertyValue('margin-right'))

        return elWidth
    }
}

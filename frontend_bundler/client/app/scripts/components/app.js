import Component from '@okiba/component'
import { qsa, qs, on } from '@okiba/dom'

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
import CardsGrid from './CardsGrid'
import ScrollTo from './ScrollTo'
import Product from './Product'
import Accordion from './Accordion'
import StoreLocatorCaffeina from './StoreLocatorCaffeina'
import StoreLocator from './StoreLocator'
import OpenMapAppLink from './OpenMapAppLink'
import Geolocation from './Geolocation'
import Filters from './Filters'
import Loader from './Loader'

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
    cardsGrid: {
        selector: '.js-lb-posts-grid',
        type: CardsGrid,
        optional: true
    },
    scrollTo: {
        selector: '.js-scroll-to',
        type: ScrollTo,
        optional: true
    },
    product: {
        selector: '.js-lb-product',
        type: Product,
        optional: true
    },
    accordion: {
        selector: '.js-accordion',
        type: Accordion,
        optional: true
    },
    storeLocatorCaffeina: {
        selector: '.js-caffeina-sl',
        type: StoreLocatorCaffeina,
        optional: true
    },
    storeLocator: {
        selector: '.js-store-locator',
        type: StoreLocator,
        optional: true
    },
    openMapAppLink: {
        selector: '.js-open-map-app-link',
        type: OpenMapAppLink,
        optional: true
    },
    geolocation: {
        selector: '.js-geolocation',
        type: Geolocation,
        optional: true
    },
    filters: {
        selector: '.js-filters',
        type: Filters,
        optional: true
    },
    loader: {
        selector: '.js-loader',
        type: Loader,
        optional: true
    },
}

export default class Application extends Component {
    constructor() {
        super({ el: document.body, components })

        this.components.offsetNavs = OffsetNavSystem.get()

        this.el.classList.add('ready')

        setTimeout(() => {
            this.scrollbarUpdate()
        }, 1200)
    }

    /**
     * Locomotive scroll calculates page height on initialization.
     * Some content may have not finished to load and change page height afterwards (for example image loading).
     * So we update scroll also after first initialization to prevent hidden sections.
     * N.B. This is only for Front-end and not for admin WP area.
     */
    scrollbarUpdate = () => {
        window.getCustomScrollbar.update()

        // Each time the window updates, we should refresh ScrollTrigger and then update LocomotiveScroll. 
        ScrollTrigger.addEventListener("refresh", () => window.getCustomScrollbar.update())

        // After everything is set up, refresh() ScrollTrigger and update LocomotiveScroll because padding may have been added for pinning, etc.
        ScrollTrigger.refresh()

        // Trigger 'resize' event to correct and prevent layout shifting
        window.dispatchEvent(new Event('resize'))
    }
}

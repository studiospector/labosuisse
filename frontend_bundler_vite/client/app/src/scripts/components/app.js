import Component from '@okiba/component'
import { on, qsa, qs } from '@okiba/dom';

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
import DistributorsMap from './DistributorsMap'
import ScrollbarManagement from './ScrollbarManagement'
import Tabs from './Tabs'
import SearchAutocomplete from './SearchAutocomplete'
import AnimationReveal from './AnimationReveal'
import GTMTracking from './GTMTracking'
import AsyncCart from './AsyncCart'
import Multicountry from './Multicountry'
import MulticountryGeolocation from './MulticountryGeolocation'
import Video from './Video'
import BannerVideo from './BannerVideo'
import ParallaxImage from './ParallaxImage';
import VideoText from './VideoText';
import OffsetNavCookieManagement from './OffsetNavCookie';

const components = {
    scrollbar: {
        type: Scrollbar,
        ghost: true
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
    distributorsMap: {
        selector: '.js-distributor-map-wrapper',
        type: DistributorsMap,
        optional: true
    },
    scrollbarManagement: {
        selector: '.js-scrollbar-management',
        type: ScrollbarManagement,
        optional: true
    },
    tabs: {
        selector: '.js-tabs',
        type: Tabs,
        optional: true
    },
    searchAutocomplete: {
        selector: '.js-search-autocomplete',
        type: SearchAutocomplete,
        optional: true
    },
    animationReveal: {
        selector: '.js-animation-reveal',
        type: AnimationReveal,
        optional: true
    },
    gtmTracking: {
        selector: '.js-gtm-tracking',
        type: GTMTracking,
        optional: true
    },
    asyncCart: {
        selector: '.js-async-cart',
        type: AsyncCart,
        optional: true
    },
    multicountry: {
        selector: '.js-multicountry',
        type: Multicountry,
        optional: true
    },
    multicountryGeolocation: {
        selector: '.js-multicountry-geolocation',
        type: MulticountryGeolocation,
        optional: true
    },
    video: {
        selector: '.js-video',
        type: Video,
        optional: true
    },
    bannerVideo: {
        selector: '.js-banner-video',
        type: BannerVideo,
        optional: true
    },
    parallaxImage: {
        selector: '.js-parallax-image',
        type: ParallaxImage,
        optional: true
    },
    videoText: {
        selector: '.js-video-text',
        type: VideoText,
        optional: true
    },
    offsetNavCookieManagement: {
        selector: '.js-offset-nav-cookie-management',
        type: OffsetNavCookieManagement,
        optional: true
    },
}

export default class Application extends Component {
    constructor() {
        super({ el: document.body, components })

        this.components.offsetNavs = OffsetNavSystem.get()

        if (!document.querySelector('body').classList.contains('wp-admin')) {
            this.el.classList.add('ready')
            this.cardsProductHover()
        }
    }

    /**
     * Add/Remove class 'button-active' on Product card status button
     */
    cardsProductHover = () => {
        const cardsProduct = qsa('.lb-product-card')

        on(cardsProduct, 'mouseenter', (ev) => {
            let btn = qs('.lb-product-card__status .button', ev.target)
            if (btn) {
                btn.classList.add('button-active')
            }
        })
        on(cardsProduct, 'mouseleave', (ev) => {
            let btn = qs('.lb-product-card__status .button', ev.target)
            if (btn) {
                btn.classList.remove('button-active')
            }
        })
    }
}

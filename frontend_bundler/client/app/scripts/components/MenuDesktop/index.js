import Component from "@okiba/component";
import { on, qsa, qs } from '@okiba/dom';
import { gsap } from "gsap"
import { clearAllBodyScrollLocks, disableBodyScroll, enableBodyScroll } from "body-scroll-lock";
import { openBackgroundTimeline, openSubmenuTimeline, submenuTimeline } from './animations';
import { stickyHeader } from "../MenuMobile/animations";


const ui = {
    hovers: '.lb-menu__main>.lb-menu__item>.lb-menu__link',
    submenus: '.lb-menu__submenu',
    background: '.lb-menu__background',
    overlay: '.lb-menu__overlay',
}


class MenuDesktop extends Component {

    constructor({ el }) {
        super({ el, ui });
        this.isOpen = false;
        this.header = qs('.lb-header__wrapper--desktop');


        on(this.ui.hovers || [], 'mouseenter', this.onEnter);
        on(this.ui.hovers || [], 'focusin', this.onEnter);
        on(this.ui.overlay || [], 'mouseenter', this.onLeave);
        on(qsa('.lb-header__end'), 'mouseenter', this.onLeave);
        on(qsa('.lb-header__logo'), 'mouseenter', this.onLeave);

        // on(qs('.lb-header'), 'mouseenter', this.onHoverEnter);
        // on(qs('.lb-header'), 'mouseleave', this.onHoverLeave);


        this.hoverTL = stickyHeader('.lb-header', '.lb-header__logo', '.lb-statusbar', false).duration(.4);
        this.lastMenuTL = gsap.timeline().duration(1);
        this.activeMenuTL = gsap.timeline().duration(1);
        this.commonTimeline = gsap.timeline()
            .add(this.hoverTL, 0)
            .add(openBackgroundTimeline(this.ui.background, this.ui.overlay), .4)
            .duration(.8)
            .paused(true);

        this.masterTL = gsap.timeline();
        this.commonMasterTL = gsap.timeline();
    }


    // onHoverEnter = (event) => {
    //   gsap.globalTimeline.clear();
    //   gsap.to(this.hoverTL, { progress: 1, duration: this.hoverTL.duration() })
    // }

    // onHoverLeave = (event) => {
    //   if (!this.isOpen) {
    //     clearAllBodyScrollLocks();
    //     gsap.globalTimeline.clear();
    //     gsap.to(this.hoverTL, { progress: 0, duration: this.hoverTL.duration() })
    //   }
    // }

    onEnter = (event) => {
        qs('body').classList.remove('-search-is-open');
        const mainLink = event.target;

        if (mainLink.nextElementSibling) {
            const scrollBarGap = window.innerWidth - document.documentElement.clientWidth;
            const submenu = mainLink.nextElementSibling;
            const wrapper = submenu.querySelector('.lb-menu__wrapper');

            if (this.mainLink !== mainLink) {
                disableBodyScroll(this.el, {
                    reserveScrollBarGap: true,
                });
                if (scrollBarGap > 0 && this.header && !this.mainLink && !this.previousHeaderPaddingRight) {
                    const computedHeaderPaddingRight = parseInt(window.getComputedStyle(this.header).getPropertyValue('padding-right'), 10);
                    this.previousHeaderPaddingRight = this.header.style.paddingRight;
                    this.header.style.paddingRight = `${computedHeaderPaddingRight + scrollBarGap}px`;

                    document.querySelector('.lb-statusbar').style.paddingRight = `${computedHeaderPaddingRight + scrollBarGap}px`;
                }
                this.masterTL.clear();
                this.activeMenu = submenu;
                this.mainLink && this.mainLink.classList.remove('lb-menu__link--main-active');
                this.mainLink = mainLink;
                this.mainLink.classList.add('lb-menu__link--main-active');
                this.lastMenuTL = this.activeMenuTL;
                this.activeMenuTL = openSubmenuTimeline(submenu, wrapper).paused(true);
                gsap.to(this.lastMenuTL, { progress: 0, duration: this.lastMenuTL.duration() })
                gsap.to(this.commonTimeline, { progress: 1, duration: this.commonTimeline.duration() })
                this.masterTL
                    .to(this.lastMenuTL, { progress: 0, duration: this.lastMenuTL.duration() || .5 })
                    .to(this.activeMenuTL, { progress: 1, duration: this.activeMenuTL.duration() })
                this.isOpen = true;
            }
        } else {
            this.onLeave();
        }
    }

    onLeave = (event) => {
        this.isOpen = false;
        gsap.to(this.lastMenuTL, { progress: 0, duration: this.lastMenuTL.duration() })
        this.mainLink && this.mainLink.classList.remove('lb-menu__link--main-active');
        this.activeMenu = null;
        this.mainLink = null;
        this.masterTL
            .to(this.activeMenuTL, { progress: 0, duration: this.activeMenuTL.duration() })
            .to(this.commonTimeline, { progress: 0, duration: this.commonTimeline.duration() }, "-=0.1")
            .call(() => enableBodyScroll(this.el))
            .call(() => {
                if (this.previousHeaderPaddingRight) {
                    this.header.style.paddingRight = `${this.previousHeaderPaddingRight}`;
                    document.querySelector('.lb-statusbar').style.paddingRight = `${this.previousHeaderPaddingRight}`;
                    this.previousHeaderPaddingRight = null;
                }
            })
    }
}

export default MenuDesktop

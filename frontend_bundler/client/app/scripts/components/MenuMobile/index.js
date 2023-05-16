import Component from "@okiba/component";
import { on, off, qs } from '@okiba/dom'

import { gsap } from "gsap";

// import { enableBodyScroll, disableBodyScroll } from 'body-scroll-lock';

import ClassNamePlugin from "../../plugin/ClassNamePlugin";

// import { allowTouchMove } from "../../utils/touchmove";

import { openMenu } from './animations';

gsap.registerPlugin(ClassNamePlugin);

const ui = {
    buttons: '.lb-menu__button:not(.lb-menu__back)',
    back: '.lb-menu__back',
    wrapper: '.lb-menu__wrapper:not(.lb-menu__wrapper--submenu)',
    submenus: '.lb-menu__submenu',
    main: '.lb-menu__main',
    items: '.lb-menu__item--main>*:first-child'
}

class MenuMobile extends Component {

    constructor({ el, options = {
        hamburgerElement: '.lb-header__hamburger',
        headerElement: '.lb-header',
        logoElement: '.lb-header__logo'
    } }) {
        super({ el, ui })

        let elements = document.querySelectorAll('.lb-menu__button:not(.lb-menu__back)');
        console.log('elements', elements);
        console.log('this.ui.buttons', this.ui.buttons);
        elements.forEach((item) => {
            item.addEventListener('click', this.next)
        });
        console.log('elements listener', elements);

        // on(this.ui.buttons, 'click', this.next);
        on(this.ui.back, 'click', this.back);
        gsap.set(this.el, { xPercent: -100, display: 'block' })
        this.tl = openMenu({ menuElement: this.el, items: this.ui.items, ...options });
        this.slider = gsap.timeline();
        this.master = gsap.timeline();
        this.stack = [];
        this.isOpen = false;
        this.opt = options

        this.updateNavigation = this.updateNavigation.bind(this)
        window.updateMobileNavigation = this.updateNavigation

        setTimeout(() => this.updateNavigation(), 400)
    }

    onDestroy() {
        off(this.ui.buttons, 'click', this.next);
        off(this.ui.back, 'click', this.back);
    }

    updateNavigation(elem = this.el) {
        const hasActiveSubmenu = elem.querySelector('.js-active-submenu')

        if (hasActiveSubmenu) {
            const button = hasActiveSubmenu.querySelector('.lb-button')
            button.click()
            this.updateNavigation(hasActiveSubmenu)
        }
    }

    next = (event) => {
        console.log('next');
        const item = event.target.closest('.lb-menu__item');
        const menu = item && item.querySelector('.lb-menu__submenu');
        const parent = item.parentElement;
        console.log('event', event.target, event);
        console.log('menu', menu);
        console.log('item', item);
        console.log('parent', parent);
        if (!parent || !parent.classList.contains('lb-menu__main--active')) {
            this.activate(
                menu,
                parent
            );
        }
    }

    back = () => {
        const [submenu, parent] = this.stack.pop();
        this.submenu = submenu;
        this.slide(() => {
            this.deactivate(submenu, parent)
        });
    }

    activate = (menu, parent) => {
        console.log('activate');
        this.stack.push([menu, parent]);
        menu.classList.add('lb-menu__submenu--active');
        this.slide(() => null, menu == this.submenu);
        if (parent) {
            parent.classList.add('lb-menu__main--active');
        }
        if (menu.closest('.lb-menu-scroller')) {
            menu.closest('.lb-menu-scroller').style.overflow = 'hidden';
        }
    }

    deactivate = (menu, parent) => {
        menu.classList.remove('lb-menu__submenu--active');
        if (parent) {
            parent.classList.remove('lb-menu__main--active');
        }
        if (menu.closest('.lb-menu-scroller')) {
            menu.closest('.lb-menu-scroller').style.overflow = 'scroll';
        }
    }

    slide = (onComplete, clear = false) => {
        if (clear) this.slider.clear();
        const isRtl = this.el.closest('[dir="rtl"]');
        this.slider.to(this.ui.wrapper, { xPercent: (isRtl ? 100 : -100) * this.stack.length, onComplete })
    }

    open = () => {
        qs(this.opt.headerElement).classList.add('lb-header--hide')
        qs(this.opt.hamburgerElement).classList.add('lb-header__hamburger--is-open')
        this.reset = this.close;
        this.isOpen = true;
        this.master.clear();
        this.master.to(this.tl, { progress: 1, duration: this.tl.duration() });
        // disableBodyScroll(this.ui.main, { allowTouchMove });
        window.getCustomScrollbar.stop()
        document.body.style.overflow = 'hidden';
    }

    close = () => {
        qs(this.opt.headerElement).classList.remove('lb-header--hide')
        qs(this.opt.hamburgerElement).classList.remove('lb-header__hamburger--is-open')
        this.isOpen = false;
        // enableBodyScroll(this.ui.main);
        window.getCustomScrollbar.start()
        document.body.style.overflow = 'auto';
        this.master.to(this.tl, {
            progress: 0, duration: this.tl.duration(), onComplete: () => {
                // this.ui.submenus.forEach(menu => this.deactivate(menu, menu.closest('.lb-menu__main')));
                this.stack.forEach(([menu, parent]) => this.deactivate(menu, parent))
                this.stack = [];
                this.slide();
                this.updateNavigation()
            }
        })
    }

    toggle = (burger) => {
        this.isOpen ? this.close(burger) : this.open(burger);
    }

    onResize = () => {
        if (window.innerWidth >= breakpoints.md && this.reset) {
            this.reset();
            this.reset = null;
        }
    }
}

export default MenuMobile

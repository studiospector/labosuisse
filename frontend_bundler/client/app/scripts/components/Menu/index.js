import Component from '@okiba/component';
import { on } from '@okiba/dom';

import MenuDesktop from '../MenuDesktop';
import MenuMobile from '../MenuMobile';

import { stickyHeader, stickyHeaderScroll } from '../MenuMobile/animations';

const ui = {
    hamburger: '.lb-header__hamburger',
    search: '.lb-header__search',
    input: '.lb-menu__search>input',
    // logo: '.lb-header__logo',
    // statusbar: '.lb-statusbar'
}

const components = {
    mobileMenu: { selector: '.lb-menu--mobile', type: MenuMobile },
    desktopMenu: { selector: '.lb-menu--desktop', type: MenuDesktop }
}

class Menu extends Component {

    constructor({ el }) {
        super({ el, ui, components })
        if (this.ui.hamburger) {
            on(this.ui.hamburger, 'click', this.toggleMenuMobile);
        }
        if (this.ui.search) {
            on(this.ui.search, 'click', this.toggleSearch);
        }
        // if (this.ui.logo) {
            this.tl = stickyHeaderScroll(this.el, this.ui.logo);
        // }
        // if (this.ui.statusbar && this.ui.statusbar.querySelector('.lb-statusbar__end:first-child')) {
        //     this.el.classList.add('lb-header--scrolled-mobile');
        // }
    }

    toggleMenuMobile = () => {
        this.components.mobileMenu.toggle();
    }

    toggleSearch = () => {
        if (this.ui.input) {
            this.components.mobileMenu.toggle();
            this.ui.input.focus();
        }
    }

}

export default Menu

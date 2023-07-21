import Component from '@okiba/component';
import { on } from '@okiba/dom';

import MenuDesktop from '../MenuDesktop';
import MenuMobile from '../MenuMobile';

const ui = {
    hamburger: '.lb-header__hamburger',
    search: '.lb-header__search',
    input: '.lb-menu__search>input',
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

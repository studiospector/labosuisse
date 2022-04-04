// Storybook API
import { storiesOf } from '@storybook/html'
import { useEffect } from '@storybook/client-api'
// Okiba API
import Component from '@okiba/component'

// Components
import renderBannerAlternate from '../../views/components/banner-alternate.twig'

import BannerAlternate from '../../scripts/components/BannerAlternate'

const dataRightInfoboxFullHeight = {
    images: {
        original: '/assets/images/banner-img.jpg',
        lg: '/assets/images/banner-img.jpg',
        md: '/assets/images/banner-img.jpg',
        sm: '/assets/images/banner-img.jpg',
        xs: '/assets/images/banner-img.jpg'
    },
    infobox: {
        tagline: 'LOREM IPSUM',
        subtitle: 'Lorem ipsum dolor sit amet, consectetur',
        paragraph: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.<br>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
        cta: {
            url: '#',
            title: 'Scopri di più',
            iconEnd: { name: 'arrow-right' },
            variants: ['quaternary']
        },
        variants: ['alternate'],
    },
    imageBig: false,
    variants: ['infobox-right', 'infobox-fullheight'], // infobox-left, infobox-right AND infobox-fullheight, infobox-centered, infobox-bottom
}

const dataLeftInfoboxFullHeight = {
    images: {
        original: '/assets/images/banner-img.jpg',
        lg: '/assets/images/banner-img.jpg',
        md: '/assets/images/banner-img.jpg',
        sm: '/assets/images/banner-img.jpg',
        xs: '/assets/images/banner-img.jpg'
    },
    infobox: {
        tagline: 'LOREM IPSUM',
        subtitle: 'Lorem ipsum dolor sit amet, consectetur',
        paragraph: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.<br>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
        cta: {
            url: '#',
            title: 'Scopri di più',
            iconEnd: { name: 'arrow-right' },
            variants: ['quaternary']
        },
        variants: ['alternate'],
    },
    imageBig: false,
    variants: ['infobox-left', 'infobox-fullheight'], // infobox-left, infobox-right AND infobox-fullheight, infobox-centered, infobox-bottom
}

const dataRightInfoboxCentered = {
    images: {
        original: '/assets/images/banner-img.jpg',
        lg: '/assets/images/banner-img.jpg',
        md: '/assets/images/banner-img.jpg',
        sm: '/assets/images/banner-img.jpg',
        xs: '/assets/images/banner-img.jpg'
    },
    infobox: {
        subtitle: 'Lorem ipsum dolor sit amet, consectetur',
        paragraph: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.<br>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
        variants: ['alternate'],
    },
    imageBig: false,
    variants: ['infobox-right', 'infobox-centered'], // infobox-left, infobox-right AND infobox-fullheight, infobox-centered, infobox-bottom
}

const dataLeftInfoboxCentered = {
    images: {
        original: '/assets/images/banner-img.jpg',
        lg: '/assets/images/banner-img.jpg',
        md: '/assets/images/banner-img.jpg',
        sm: '/assets/images/banner-img.jpg',
        xs: '/assets/images/banner-img.jpg'
    },
    infobox: {
        subtitle: 'Lorem ipsum dolor sit amet, consectetur',
        paragraph: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.<br>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
        variants: ['alternate'],
    },
    imageBig: false,
    variants: ['infobox-left', 'infobox-centered'], // infobox-left, infobox-right AND infobox-fullheight, infobox-centered, infobox-bottom
}

const dataRightImageBig = {
    images: {
        original: '/assets/images/banner-img.jpg',
        lg: '/assets/images/banner-img.jpg',
        md: '/assets/images/banner-img.jpg',
        sm: '/assets/images/banner-img.jpg',
        xs: '/assets/images/banner-img.jpg'
    },
    infobox: {
        tagline: 'Lorem Ipsum',
        subtitle: 'Linea Solari',
        paragraph: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
        paragraph_small: 'Promozione valida, nei punti vendita aderenti. Fino a esaurimento scorte',
        cta: {
            url: '#',
            title: 'Scopri di più',
            variants: ['tertiary']
        },
        variants: ['alternate'],
    },
    imageBig: true,
    variants: ['infobox-left', 'infobox-bottom'], // infobox-left, infobox-right AND infobox-fullheight, infobox-centered, infobox-bottom
}

const dataLeftImageBig = {
    images: {
        original: '/assets/images/banner-img.jpg',
        lg: '/assets/images/banner-img.jpg',
        md: '/assets/images/banner-img.jpg',
        sm: '/assets/images/banner-img.jpg',
        xs: '/assets/images/banner-img.jpg'
    },
    infobox: {
        tagline: 'Lorem Ipsum',
        subtitle: 'Linea Solari',
        paragraph: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
        paragraph_small: 'Promozione valida, nei punti vendita aderenti. Fino a esaurimento scorte',
        cta: {
            url: '#',
            title: 'Scopri di più',
            variants: ['tertiary']
        },
        variants: ['alternate'],
    },
    imageBig: true,
    variants: ['infobox-right', 'infobox-bottom'], // infobox-left, infobox-right AND infobox-fullheight, infobox-centered, infobox-bottom
}

storiesOf('Components|Banner Alternate', module)
    .addDecorator(storyFn => {
        useEffect(() => {
            const app = new Component({
                el: document.body,
                components: [
                    {
                        selector: '.js-banner-alternate',
                        type: BannerAlternate
                    }
                ]
            })

            return () => app.destroy()
        }, [])

        return storyFn()
    })
    .add('Right Infobox --- Full Height', () => renderBannerAlternate(dataRightInfoboxFullHeight))
    .add('Left Infobox --- Full Height', () => renderBannerAlternate(dataLeftInfoboxFullHeight))
    .add('Right Infobox --- Centered', () => renderBannerAlternate(dataRightInfoboxCentered))
    .add('Left Infobox --- Centered', () => renderBannerAlternate(dataLeftInfoboxCentered))
    .add('Right Infobox --- Image big', () => renderBannerAlternate(dataRightImageBig))
    .add('Left Infobox --- Image big', () => renderBannerAlternate(dataLeftImageBig))

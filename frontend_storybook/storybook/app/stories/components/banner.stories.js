// Storybook API
import { storiesOf } from '@storybook/html'
import { useEffect } from '@storybook/client-api'
// Okiba API
import Component from '@okiba/component'

// Components
import renderBanner from '../../views/components/banner.twig'

const dataLeftInfobox = {
    images: {
        original: '/assets/images/banner-img.jpg',
        large: '/assets/images/banner-img.jpg',
        medium: '/assets/images/banner-img.jpg',
        small: '/assets/images/banner-img.jpg'
    },
    infoboxTextAlignment: 'left', // left, right, center
    infobox: {
        tagline: 'LOREM IPSUM',
        subtitle: 'Lorem ipsum dolor sit amet, consectetur',
        paragraph: 'Lorem ipsum dolor sit amet, consectetur<br>adipiscing elit, sed do eiusmod tempor incididunt<br>ut labore et dolore magna.',
    },
    variants: ['left'], // left, right, center
}

const dataLeftInfoboxCTA = {
    images: {
        original: '/assets/images/banner-img.jpg',
        large: '/assets/images/banner-img.jpg',
        medium: '/assets/images/banner-img.jpg',
        small: '/assets/images/banner-img.jpg'
    },
    infoboxTextAlignment: 'left', // left, right, center
    infobox: {
        tagline: 'LOREM IPSUM',
        subtitle: 'Lorem ipsum dolor sit amet, consectetur',
        paragraph: 'Lorem ipsum dolor sit amet, consectetur<br>adipiscing elit, sed do eiusmod tempor incididunt<br>ut labore et dolore magna. ',
        cta: {
            href: '#',
            label: 'CALL TO ACTION',
            variants: ['secondary']
        }
    },
    variants: ['left'], // left, right, center
}

const dataRightInfobox = {
    images: {
        original: '/assets/images/banner-img.jpg',
        large: '/assets/images/banner-img.jpg',
        medium: '/assets/images/banner-img.jpg',
        small: '/assets/images/banner-img.jpg'
    },
    infoboxTextAlignment: 'left', // left, right, center
    infobox: {
        tagline: 'LOREM IPSUM',
        subtitle: 'Lorem ipsum dolor sit amet, consectetur',
        paragraph: 'Lorem ipsum dolor sit amet, consectetur<br>adipiscing elit, sed do eiusmod tempor incididunt<br>ut labore et dolore magna.',
    },
    variants: ['right'], // left, right, center
}

const dataRightInfoboxCTA = {
    images: {
        original: '/assets/images/banner-img.jpg',
        large: '/assets/images/banner-img.jpg',
        medium: '/assets/images/banner-img.jpg',
        small: '/assets/images/banner-img.jpg'
    },
    infoboxTextAlignment: 'left', // left, right, center
    infobox: {
        tagline: 'LOREM IPSUM',
        subtitle: 'Lorem ipsum dolor sit amet, consectetur',
        paragraph: 'Lorem ipsum dolor sit amet, consectetur<br>adipiscing elit, sed do eiusmod tempor incididunt<br>ut labore et dolore magna.',
        cta: {
            href: '#',
            label: 'CALL TO ACTION',
            variants: ['secondary']
        }
    },
    variants: ['right'], // left, right, center
}

const dataCenterInfobox = {
    images: {
        original: '/assets/images/banner-img.jpg',
        large: '/assets/images/banner-img.jpg',
        medium: '/assets/images/banner-img.jpg',
        small: '/assets/images/banner-img.jpg'
    },
    infoboxTextAlignment: 'left', // left, right, center
    infobox: {
        tagline: 'LOREM IPSUM',
        subtitle: 'Lorem ipsum dolor sit amet, consectetur',
        paragraph: 'Lorem ipsum dolor sit amet, consectetur<br>adipiscing elit, sed do eiusmod tempor incididunt<br>ut labore et dolore magna.',
    },
    variants: ['center'], // left, right, center
}

const dataCenterInfoboxCTA = {
    images: {
        original: '/assets/images/banner-img.jpg',
        large: '/assets/images/banner-img.jpg',
        medium: '/assets/images/banner-img.jpg',
        small: '/assets/images/banner-img.jpg'
    },
    infoboxTextAlignment: 'left', // left, right, center
    infobox: {
        tagline: 'LOREM IPSUM',
        subtitle: 'Lorem ipsum dolor sit amet, consectetur',
        paragraph: 'Lorem ipsum dolor sit amet, consectetur<br>adipiscing elit, sed do eiusmod tempor incididunt<br>ut labore et dolore magna.',
        cta: {
            href: '#',
            label: 'CALL TO ACTION',
            variants: ['secondary']
        }
    },
    variants: ['center'], // left, right, center
}

storiesOf('Components|Banner', module)
    .add('Left Infobox', () => renderBanner(dataLeftInfobox))
    .add('Left Infobox with CTA', () => renderBanner(dataLeftInfoboxCTA))
    .add('Right Infobox', () => renderBanner(dataRightInfobox))
    .add('Right Infobox with CTA', () => renderBanner(dataRightInfoboxCTA))
    .add('Center Infobox', () => renderBanner(dataCenterInfobox))
    .add('Center Infobox with CTA', () => renderBanner(dataCenterInfoboxCTA))
    .add('Infobox with Text left', () => renderBanner({...dataLeftInfoboxCTA, ...{infoboxTextAlignment: 'left'}}))
    .add('Infobox with Text right', () => renderBanner({...dataLeftInfoboxCTA, ...{infoboxTextAlignment: 'right'}}))
    .add('Infobox with Text center', () => renderBanner({...dataLeftInfoboxCTA, ...{infoboxTextAlignment: 'center'}}))

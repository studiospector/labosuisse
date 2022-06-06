// Storybook API
import { storiesOf } from '@storybook/html'
import { useEffect } from '@storybook/client-api'
// Okiba API
import Component from '@okiba/component'

// Components
import renderBanner from '../../views/components/banner.twig'

import AnimationReveal from '../../scripts/components/AnimationReveal'

const dataLeftInfobox = {
    images: {
        original: 'https://via.placeholder.com/1200x400',
        lg: 'https://via.placeholder.com/1200x400',
        md: 'https://via.placeholder.com/1200x400',
        sm: 'https://via.placeholder.com/1200x400',
        xs: 'https://via.placeholder.com/1200x400'
    },
    infoboxBgColorTransparent: false, // true, false
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
        original: 'https://via.placeholder.com/1200x400',
        lg: 'https://via.placeholder.com/1200x400',
        md: 'https://via.placeholder.com/1200x400',
        sm: 'https://via.placeholder.com/1200x400',
        xs: 'https://via.placeholder.com/1200x400'
    },
    infoboxBgColorTransparent: false, // true, false
    infoboxTextAlignment: 'left', // left, right, center
    infobox: {
        tagline: 'LOREM IPSUM',
        subtitle: 'Lorem ipsum dolor sit amet, consectetur',
        paragraph: 'Lorem ipsum dolor sit amet, consectetur<br>adipiscing elit, sed do eiusmod tempor incididunt<br>ut labore et dolore magna. ',
        cta: {
            url: '#',
            title: 'CALL TO ACTION',
            variants: ['secondary']
        }
    },
    variants: ['left'], // left, right, center
}

const dataRightInfobox = {
    images: {
        original: 'https://via.placeholder.com/1200x400',
        lg: 'https://via.placeholder.com/1200x400',
        md: 'https://via.placeholder.com/1200x400',
        sm: 'https://via.placeholder.com/1200x400',
        xs: 'https://via.placeholder.com/1200x400'
    },
    infoboxBgColorTransparent: false, // true, false
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
        original: 'https://via.placeholder.com/1200x400',
        lg: 'https://via.placeholder.com/1200x400',
        md: 'https://via.placeholder.com/1200x400',
        sm: 'https://via.placeholder.com/1200x400',
        xs: 'https://via.placeholder.com/1200x400'
    },
    infoboxBgColorTransparent: false, // true, false
    infoboxTextAlignment: 'left', // left, right, center
    infobox: {
        tagline: 'LOREM IPSUM',
        subtitle: 'Lorem ipsum dolor sit amet, consectetur',
        paragraph: 'Lorem ipsum dolor sit amet, consectetur<br>adipiscing elit, sed do eiusmod tempor incididunt<br>ut labore et dolore magna.',
        cta: {
            url: '#',
            title: 'CALL TO ACTION',
            variants: ['secondary']
        }
    },
    variants: ['right'], // left, right, center
}

const dataCenterInfobox = {
    images: {
        original: 'https://via.placeholder.com/1200x400',
        large: 'https://via.placeholder.com/1200x400',
        medium: 'https://via.placeholder.com/1200x400',
        small: 'https://via.placeholder.com/1200x400'
    },
    infoboxBgColorTransparent: false, // true, false
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
        original: 'https://via.placeholder.com/1200x400',
        lg: 'https://via.placeholder.com/1200x400',
        md: 'https://via.placeholder.com/1200x400',
        sm: 'https://via.placeholder.com/1200x400',
        xs: 'https://via.placeholder.com/1200x400'
    },
    infoboxBgColorTransparent: false, // true, false
    infoboxTextAlignment: 'left', // left, right, center
    infobox: {
        tagline: 'LOREM IPSUM',
        subtitle: 'Lorem ipsum dolor sit amet, consectetur',
        paragraph: 'Lorem ipsum dolor sit amet, consectetur<br>adipiscing elit, sed do eiusmod tempor incididunt<br>ut labore et dolore magna.',
        cta: {
            url: '#',
            title: 'CALL TO ACTION',
            variants: ['secondary']
        }
    },
    variants: ['center'], // left, right, center
}

const dataTransparentInfobox = {
    images: {
        original: 'https://via.placeholder.com/1200x400',
        lg: 'https://via.placeholder.com/1200x400',
        md: 'https://via.placeholder.com/1200x400',
        sm: 'https://via.placeholder.com/1200x400',
        xs: 'https://via.placeholder.com/1200x400'
    },
    infoboxBgColorTransparent: true, // true, false
    infoboxTextAlignment: 'left', // left, right, center
    infobox: {
        tagline: 'LOREM IPSUM',
        subtitle: 'Lorem ipsum dolor sit amet, consectetur',
        paragraph: 'Lorem ipsum dolor sit amet, consectetur<br>adipiscing elit, sed do eiusmod tempor incididunt<br>ut labore et dolore magna.',
    },
    variants: ['left'], // left, right, center
}

storiesOf('Components|Banner', module)
    .addDecorator(storyFn => {
        useEffect(() => {
            const app = new Component({
                el: document.body,
                components: [
                    {
                        selector: '.js-animation-reveal',
                        type: AnimationReveal,
                        optional: true
                    },
                ]
            })

            return () => app.destroy()
        }, [])

        return storyFn()
    })
    .add('Left Infobox', () => renderBanner(dataLeftInfobox))
    .add('Left Infobox with CTA', () => renderBanner(dataLeftInfoboxCTA))
    .add('Right Infobox', () => renderBanner(dataRightInfobox))
    .add('Right Infobox with CTA', () => renderBanner(dataRightInfoboxCTA))
    .add('Center Infobox', () => renderBanner(dataCenterInfobox))
    .add('Center Infobox with CTA', () => renderBanner(dataCenterInfoboxCTA))
    .add('Infobox with Text left', () => renderBanner({...dataLeftInfoboxCTA, ...{infoboxTextAlignment: 'left'}}))
    .add('Infobox with Text right', () => renderBanner({...dataLeftInfoboxCTA, ...{infoboxTextAlignment: 'right'}}))
    .add('Infobox with Text center', () => renderBanner({...dataLeftInfoboxCTA, ...{infoboxTextAlignment: 'center'}}))
    .add('Transparent Infobox', () => renderBanner(dataTransparentInfobox))

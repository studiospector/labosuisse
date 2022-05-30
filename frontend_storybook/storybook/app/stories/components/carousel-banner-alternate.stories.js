// Storybook API
import { storiesOf } from '@storybook/html'
import { useEffect } from '@storybook/client-api'
// Okiba API
import Component from '@okiba/component'

// Components
import renderCarouselBannerAlternate from '../../views/components/carousel-banner-alternate.twig'

import BannerAlternate from '../../scripts/components/BannerAlternate'
import CarouselBannerAlternate from '../../scripts/components/CarouselBannerAlternate'
import AnimationReveal from '../../scripts/components/AnimationReveal'

const dataRightInfobox = {
    slides: [
        {
            noContainer: true,
            images: {
                original: 'https://via.placeholder.com/570x400',
                lg: 'https://via.placeholder.com/570x400',
                md: 'https://via.placeholder.com/570x400',
                sm: 'https://via.placeholder.com/570x400',
                xs: 'https://via.placeholder.com/570x400'
            },
            infobox: {
                date: '00/00/00',
                subtitle: 'Lorem ipsum dolor sit amet, consectetur',
                paragraph: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
                cta: {
                    url: '#',
                    title: 'Scopri di più',
                    variants: ['tertiary']
                },
                variants: ['alternate'],
            },
            variants: ['infobox-left', 'infobox-centered'],
        },
        {
            noContainer: true,
            images: {
                original: 'https://via.placeholder.com/570x400',
                lg: 'https://via.placeholder.com/570x400',
                md: 'https://via.placeholder.com/570x400',
                sm: 'https://via.placeholder.com/570x400',
                xs: 'https://via.placeholder.com/570x400'
            },
            infobox: {
                date: '00/00/00',
                subtitle: 'Lorem ipsum dolor sit amet, consectetur',
                paragraph: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
                cta: {
                    url: '#',
                    title: 'Scopri di più',
                    variants: ['tertiary']
                },
                variants: ['alternate'],
            },
            variants: ['infobox-left', 'infobox-centered'],
        },
    ]
}

const dataLeftInfobox = {
    slides: [
        {
            noContainer: true,
            images: {
                original: 'https://via.placeholder.com/570x400',
                lg: 'https://via.placeholder.com/570x400',
                md: 'https://via.placeholder.com/570x400',
                sm: 'https://via.placeholder.com/570x400',
                xs: 'https://via.placeholder.com/570x400'
            },
            infobox: {
                date: '00/00/00',
                subtitle: 'Lorem ipsum dolor sit amet, consectetur',
                paragraph: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
                cta: {
                    url: '#',
                    title: 'Scopri di più',
                    variants: ['tertiary']
                },
                variants: ['alternate'],
            },
            variants: ['infobox-right', 'infobox-centered'],
        },
        {
            noContainer: true,
            images: {
                original: 'https://via.placeholder.com/570x400',
                lg: 'https://via.placeholder.com/570x400',
                md: 'https://via.placeholder.com/570x400',
                sm: 'https://via.placeholder.com/570x400',
                xs: 'https://via.placeholder.com/570x400'
            },
            infobox: {
                date: '00/00/00',
                subtitle: 'Lorem ipsum dolor sit amet, consectetur',
                paragraph: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
                cta: {
                    url: '#',
                    title: 'Scopri di più',
                    variants: ['tertiary']
                },
                variants: ['alternate'],
            },
            variants: ['infobox-right', 'infobox-centered'],
        },
    ]
}

storiesOf('Components|Carousel Banner Alternate', module)
    .addDecorator(storyFn => {
        useEffect(() => {
            const app = new Component({
                el: document.body,
                components: [
                    {
                        selector: '.js-banner-alternate',
                        type: BannerAlternate
                    },
                    {
                        selector: '.js-carousel-banner-alternate',
                        type: CarouselBannerAlternate
                    },
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
    .add('Right Infobox', () => renderCarouselBannerAlternate(dataRightInfobox))
    .add('Left Infobox', () => renderCarouselBannerAlternate(dataLeftInfobox))

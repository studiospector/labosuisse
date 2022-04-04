// Storybook API
import { storiesOf } from '@storybook/html'
import { useEffect } from '@storybook/client-api'
// Okiba API
import Component from '@okiba/component'

// Components
import renderHero from '../../views/components/hero.twig'

import Hero from '../../scripts/components/Hero'

const dataLeftInfobox = {
    images: {
        original: '/assets/images/hero-face-right.jpg',
        lg: '/assets/images/hero-face-right.jpg',
        md: '/assets/images/hero-face-right.jpg',
        sm: '/assets/images/hero-face-right.jpg',
        xs: '/assets/images/hero-face-right.jpg'
    },
    infoboxPosX: 'left',
    infoboxPosY: 'center',
    infobox: {
        title: 'Le linee viso',
        paragraph: 'Descrizione dell’universo in cui l’utente si trova. Breve overview delle tipologie di prodotto e trattamenti che potrà trovare. Il testo dovrà essere di minimo 2 e massimo 4 righe.',
    },
    container: false,
    variants: ['medium']
}

const dataLeftBottomInfobox = {
    images: {
        original: '/assets/images/hero-face-right.jpg',
        lg: '/assets/images/hero-face-right.jpg',
        md: '/assets/images/hero-face-right.jpg',
        sm: '/assets/images/hero-face-right.jpg',
        xs: '/assets/images/hero-face-right.jpg'
    },
    infoboxPosX: 'left',
    infoboxPosY: 'bottom',
    infobox: {
        title: 'Le linee viso',
        paragraph: 'Descrizione dell’universo in cui l’utente si trova. Breve overview delle tipologie di prodotto e trattamenti che potrà trovare. Il testo dovrà essere di minimo 2 e massimo 4 righe.',
    },
    container: false,
    variants: ['medium']
}

const dataRightInfobox = {
    images: {
        original: '/assets/images/hero-face-left.jpg',
        lg: '/assets/images/hero-face-left.jpg',
        md: '/assets/images/hero-face-left.jpg',
        sm: '/assets/images/hero-face-left.jpg',
        xs: '/assets/images/hero-face-left.jpg'
    },
    infoboxPosX: 'right',
    infoboxPosY: 'center',
    infobox: {
        title: 'Le linee viso',
        paragraph: 'Descrizione dell’universo in cui l’utente si trova. Breve overview delle tipologie di prodotto e trattamenti che potrà trovare. Il testo dovrà essere di minimo 2 e massimo 4 righe.',
    },
    container: false,
    variants: ['medium']
}

const dataRightBottomInfobox = {
    images: {
        original: '/assets/images/hero-face-left.jpg',
        lg: '/assets/images/hero-face-left.jpg',
        md: '/assets/images/hero-face-left.jpg',
        sm: '/assets/images/hero-face-left.jpg',
        xs: '/assets/images/hero-face-left.jpg'
    },
    infoboxPosX: 'right',
    infoboxPosY: 'bottom',
    infobox: {
        title: 'Le linee viso',
        paragraph: 'Descrizione dell’universo in cui l’utente si trova. Breve overview delle tipologie di prodotto e trattamenti che potrà trovare. Il testo dovrà essere di minimo 2 e massimo 4 righe.',
    },
    container: false,
    variants: ['medium']
}

const dataCenterInfobox = {
    images: {
        original: '/assets/images/carousel-hero-img-2.jpg',
        lg: '/assets/images/carousel-hero-img-2.jpg',
        md: '/assets/images/carousel-hero-img-2.jpg',
        sm: '/assets/images/carousel-hero-img-2.jpg',
        xs: '/assets/images/carousel-hero-img-2.jpg'
    },
    infoboxPosX: 'center',
    infoboxPosY: 'center',
    infobox: {
        tagline: 'LABEL',
        title: 'Linea Lifting',
        paragraph: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore.',
        cta: {
            url: '#',
            title: 'Scopri la linea',
            variants: ['secondary']
        }
    },
    container: false,
    variants: ['large']
}

const dataCenterBottomInfobox = {
    images: {
        original: '/assets/images/carousel-hero-img-2.jpg',
        lg: '/assets/images/carousel-hero-img-2.jpg',
        md: '/assets/images/carousel-hero-img-2.jpg',
        sm: '/assets/images/carousel-hero-img-2.jpg',
        xs: '/assets/images/carousel-hero-img-2.jpg'
    },
    infoboxPosX: 'center',
    infoboxPosY: 'bottom',
    infobox: {
        tagline: 'LABEL',
        title: 'Linea Lifting',
        paragraph: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore.'
    },
    container: false,
    variants: ['large']
}

const dataHeroWhiteText = {
    images: {
        original: '/assets/images/hero-crescina-right.jpg',
        lg: '/assets/images/hero-crescina-right.jpg',
        md: '/assets/images/hero-crescina-right.jpg',
        sm: '/assets/images/hero-crescina-right.jpg',
        xs: '/assets/images/hero-crescina-right.jpg'
    },
    infoboxPosX: 'left',
    infoboxPosY: 'center',
    infobox: {
        tagline: 'LABEL',
        title: 'Linea Lifting',
        paragraph: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore.'
    },
    container: false,
    whiteText: true,
    variants: ['small']
}

const dataImageInfobox = {
    images: {
        original: '/assets/images/hero-crescina-right.jpg',
        lg: '/assets/images/hero-crescina-right.jpg',
        md: '/assets/images/hero-crescina-right.jpg',
        sm: '/assets/images/hero-crescina-right.jpg',
        xs: '/assets/images/hero-crescina-right.jpg'
    },
    infoboxPosX: 'left',
    infoboxPosY: 'center',
    infobox: {
        image: '/assets/images/crescina-logo.png',
        paragraph: 'Descrizione dell’immagine e spiegazione del titolo. Questo testo può occupare fino a 5 o 6 righe, ma sarebbe ideale mantenerlo di tre. '
    },
    container: false,
    variants: ['small']
}

const dataHeroContainer = {
    images: {
        original: '/assets/images/hero-crescina-right.jpg',
        lg: '/assets/images/hero-crescina-right.jpg',
        md: '/assets/images/hero-crescina-right.jpg',
        sm: '/assets/images/hero-crescina-right.jpg',
        xs: '/assets/images/hero-crescina-right.jpg'
    },
    infoboxPosX: 'left',
    infoboxPosY: 'center',
    infobox: {
        image: '/assets/images/crescina-logo.png',
        paragraph: 'Descrizione dell’immagine e spiegazione del titolo. Questo testo può occupare fino a 5 o 6 righe, ma sarebbe ideale mantenerlo di tre. '
    },
    container: true,
    variants: ['small']
}

storiesOf('Components|Hero', module)
    .addDecorator(storyFn => {
        useEffect(() => {
            const app = new Component({
                el: document.body,
                components: [
                    {
                        selector: '.js-hero',
                        type: Hero
                    }
                ]
            })

            return () => app.destroy()
        }, [])

        return storyFn()
    })
    .add('Left Infobox', () => renderHero(dataLeftInfobox))
    .add('Left Bottom Infobox', () => renderHero(dataLeftBottomInfobox))
    .add('Right Infobox', () => renderHero(dataRightInfobox))
    .add('Right Bottom Infobox', () => renderHero(dataRightBottomInfobox))
    .add('Center Infobox', () => renderHero(dataCenterInfobox))
    .add('Center Bottom Infobox', () => renderHero(dataCenterBottomInfobox))
    .add('Hero with White text', () => renderHero(dataHeroWhiteText))
    .add('Image Infobox', () => renderHero(dataImageInfobox))
    .add('Hero with Container', () => renderHero(dataHeroContainer))

// Storybook API
import { storiesOf } from '@storybook/html'
import { useEffect } from '@storybook/client-api'
// Okiba API
import Component from '@okiba/component'

// Components
import render from '../../views/components/carousel-centered.twig'
import CarouselCentered from '../../scripts/components/CarouselCentered'

const dataDefault = {
    title: 'Le tappe fondamentali',
    items: [
        {
            images: {
                original: '/assets/images/carousel-hero-img.jpg',
                lg: '/assets/images/carousel-hero-img.jpg',
                md: '/assets/images/carousel-hero-img.jpg',
                sm: '/assets/images/carousel-hero-img.jpg',
                xs: '/assets/images/carousel-hero-img.jpg'
            },
            subtitle: '1989: il primo lancio',
            text: 'Lancio di Nicotenil Anti-Caduta, il primo trattamento cosmetico per prevenire la caduta dei capelli, con specifiche proprietà vasodilatatorie, sviluppate per stimolare la microcircolazione sanguigna del cuoio capelluto.',
        },
        {
            images: {
                original: '/assets/images/carousel-hero-img.jpg',
                lg: '/assets/images/carousel-hero-img.jpg',
                md: '/assets/images/carousel-hero-img.jpg',
                sm: '/assets/images/carousel-hero-img.jpg',
                xs: '/assets/images/carousel-hero-img.jpg'
            },
            subtitle: '1989: il primo lancio',
            text: 'Lancio di Nicotenil Anti-Caduta, il primo trattamento cosmetico per prevenire la caduta dei capelli, con specifiche proprietà vasodilatatorie, sviluppate per stimolare la microcircolazione sanguigna del cuoio capelluto.',
        },
        {
            images: {
                original: '/assets/images/carousel-hero-img.jpg',
                lg: '/assets/images/carousel-hero-img.jpg',
                md: '/assets/images/carousel-hero-img.jpg',
                sm: '/assets/images/carousel-hero-img.jpg',
                xs: '/assets/images/carousel-hero-img.jpg'
            },
            subtitle: '1989: il primo lancio',
            text: 'Lancio di Nicotenil Anti-Caduta, il primo trattamento cosmetico per prevenire la caduta dei capelli, con specifiche proprietà vasodilatatorie, sviluppate per stimolare la microcircolazione sanguigna del cuoio capelluto.',
        },
        {
            images: {
                original: '/assets/images/carousel-hero-img.jpg',
                lg: '/assets/images/carousel-hero-img.jpg',
                md: '/assets/images/carousel-hero-img.jpg',
                sm: '/assets/images/carousel-hero-img.jpg',
                xs: '/assets/images/carousel-hero-img.jpg'
            },
            subtitle: '1989: il primo lancio',
            text: 'Lancio di Nicotenil Anti-Caduta, il primo trattamento cosmetico per prevenire la caduta dei capelli, con specifiche proprietà vasodilatatorie, sviluppate per stimolare la microcircolazione sanguigna del cuoio capelluto.',
        },
        {
            images: {
                original: '/assets/images/carousel-hero-img.jpg',
                lg: '/assets/images/carousel-hero-img.jpg',
                md: '/assets/images/carousel-hero-img.jpg',
                sm: '/assets/images/carousel-hero-img.jpg',
                xs: '/assets/images/carousel-hero-img.jpg'
            },
            subtitle: '1989: il primo lancio',
            text: 'Lancio di Nicotenil Anti-Caduta, il primo trattamento cosmetico per prevenire la caduta dei capelli, con specifiche proprietà vasodilatatorie, sviluppate per stimolare la microcircolazione sanguigna del cuoio capelluto.',
        },
        {
            images: {
                original: '/assets/images/carousel-hero-img.jpg',
                lg: '/assets/images/carousel-hero-img.jpg',
                md: '/assets/images/carousel-hero-img.jpg',
                sm: '/assets/images/carousel-hero-img.jpg',
                xs: '/assets/images/carousel-hero-img.jpg'
            },
            subtitle: '1989: il primo lancio',
            text: 'Lancio di Nicotenil Anti-Caduta, il primo trattamento cosmetico per prevenire la caduta dei capelli, con specifiche proprietà vasodilatatorie, sviluppate per stimolare la microcircolazione sanguigna del cuoio capelluto.',
        },
    ],
}

storiesOf('Components|Carousel Centered', module)
    .addDecorator(storyFn => {
        useEffect(() => {
            const app = new Component({
                el: document.body,
                components: [
                    {
                        selector: '.js-carousel-centered',
                        type: CarouselCentered
                    }
                ]
            })

            return () => app.destroy()
        }, [])

        return storyFn()
    })
    .add('Default', () => render(dataDefault))

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
                original: 'https://via.placeholder.com/560x410',
                lg: 'https://via.placeholder.com/560x410',
                md: 'https://via.placeholder.com/560x410',
                sm: 'https://via.placeholder.com/560x410',
                xs: 'https://via.placeholder.com/560x410'
            },
            subtitle: '1989: il primo lancio',
            text: 'Lancio di Nicotenil Anti-Caduta, il primo trattamento cosmetico per prevenire la caduta dei capelli, con specifiche proprietà vasodilatatorie, sviluppate per stimolare la microcircolazione sanguigna del cuoio capelluto.',
        },
        {
            images: {
                original: 'https://via.placeholder.com/560x410',
                lg: 'https://via.placeholder.com/560x410',
                md: 'https://via.placeholder.com/560x410',
                sm: 'https://via.placeholder.com/560x410',
                xs: 'https://via.placeholder.com/560x410'
            },
            subtitle: '1989: il primo lancio',
            text: 'Lancio di Nicotenil Anti-Caduta, il primo trattamento cosmetico per prevenire la caduta dei capelli, con specifiche proprietà vasodilatatorie, sviluppate per stimolare la microcircolazione sanguigna del cuoio capelluto.',
        },
        {
            images: {
                original: 'https://via.placeholder.com/560x410',
                lg: 'https://via.placeholder.com/560x410',
                md: 'https://via.placeholder.com/560x410',
                sm: 'https://via.placeholder.com/560x410',
                xs: 'https://via.placeholder.com/560x410'
            },
            subtitle: '1989: il primo lancio',
            text: 'Lancio di Nicotenil Anti-Caduta, il primo trattamento cosmetico per prevenire la caduta dei capelli, con specifiche proprietà vasodilatatorie, sviluppate per stimolare la microcircolazione sanguigna del cuoio capelluto.',
        },
        {
            images: {
                original: 'https://via.placeholder.com/560x410',
                lg: 'https://via.placeholder.com/560x410',
                md: 'https://via.placeholder.com/560x410',
                sm: 'https://via.placeholder.com/560x410',
                xs: 'https://via.placeholder.com/560x410'
            },
            subtitle: '1989: il primo lancio',
            text: 'Lancio di Nicotenil Anti-Caduta, il primo trattamento cosmetico per prevenire la caduta dei capelli, con specifiche proprietà vasodilatatorie, sviluppate per stimolare la microcircolazione sanguigna del cuoio capelluto.',
        },
        {
            images: {
                original: 'https://via.placeholder.com/560x410',
                lg: 'https://via.placeholder.com/560x410',
                md: 'https://via.placeholder.com/560x410',
                sm: 'https://via.placeholder.com/560x410',
                xs: 'https://via.placeholder.com/560x410'
            },
            subtitle: '1989: il primo lancio',
            text: 'Lancio di Nicotenil Anti-Caduta, il primo trattamento cosmetico per prevenire la caduta dei capelli, con specifiche proprietà vasodilatatorie, sviluppate per stimolare la microcircolazione sanguigna del cuoio capelluto.',
        },
        {
            images: {
                original: 'https://via.placeholder.com/560x410',
                lg: 'https://via.placeholder.com/560x410',
                md: 'https://via.placeholder.com/560x410',
                sm: 'https://via.placeholder.com/560x410',
                xs: 'https://via.placeholder.com/560x410'
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

// Storybook API
import { storiesOf } from '@storybook/html'
import { useEffect } from '@storybook/client-api'
// Okiba API
import Component from '@okiba/component'

// Components
import render from '../../views/components/cards-grid.twig'

import CardsGrid from '../../scripts/components/CardsGrid'

const data = {
    title: 'Le ultime novità da labo magazine',
    cta: {
        title: "Vai a news e media",
        url: '#',
        variants: ['tertiary'],
    },
    items: [
        {
            images: {
                original: '/assets/images/card-img-6.jpg',
                lg: '/assets/images/card-img-6.jpg',
                md: '/assets/images/card-img-6.jpg',
                sm: '/assets/images/card-img-6.jpg',
                xs: '/assets/images/card-img-6.jpg'
            },
            date: '00/00/00',
            infobox: {
                subtitle: 'La più grande community di beauty lover ha testato Labo',
                paragraph: 'Incipit del contenuto editoriale. Può essere parte dell’articolo originale oppure un’introduzione. Lorem ipsum dolor sit amet.',
                cta: {
                    url: '#',
                    title: 'Leggi l’articolo',
                    iconEnd: { name: 'arrow-right' },
                    variants: ['quaternary']
                }
            },
            variants: ['type-2'],
        },
        {
            images: {
                original: '/assets/images/card-img-6.jpg',
                lg: '/assets/images/card-img-6.jpg',
                md: '/assets/images/card-img-6.jpg',
                sm: '/assets/images/card-img-6.jpg',
                xs: '/assets/images/card-img-6.jpg'
            },
            date: '00/00/00',
            infobox: {
                subtitle: 'Dopo l’estate, una cura per la pelle a tutto ossigeno: arriva Oxy-Treat',
                paragraph: 'Incipit del contenuto editoriale. Può essere parte dell’articolo originale oppure un’introduzione. Lorem ipsum dolor sit amet.',
                cta: {
                    url: '#',
                    title: 'Leggi l’articolo',
                    iconEnd: { name: 'arrow-right' },
                    variants: ['quaternary']
                }
            },
            variants: ['type-2'],
        },
        {
            images: {
                original: '/assets/images/card-img-6.jpg',
                lg: '/assets/images/card-img-6.jpg',
                md: '/assets/images/card-img-6.jpg',
                sm: '/assets/images/card-img-6.jpg',
                xs: '/assets/images/card-img-6.jpg'
            },
            date: '00/00/00',
            infobox: {
                subtitle: 'La formazione online firmata Labo',
                paragraph: 'Incipit del contenuto editoriale. Può essere parte dell’articolo originale oppure un’introduzione. Lorem ipsum dolor sit amet.',
                cta: {
                    url: '#',
                    title: 'Leggi l’articolo',
                    iconEnd: { name: 'arrow-right' },
                    variants: ['quaternary']
                }
            },
            variants: ['type-2'],
        },
        {
            images: {
                original: '/assets/images/card-img-6.jpg',
                lg: '/assets/images/card-img-6.jpg',
                md: '/assets/images/card-img-6.jpg',
                sm: '/assets/images/card-img-6.jpg',
                xs: '/assets/images/card-img-6.jpg'
            },
            date: '00/00/00',
            infobox: {
                subtitle: 'Titolo del contenuto editoriale che andrà nella sezione News',
                paragraph: 'Incipit del contenuto editoriale. Può essere parte dell’articolo originale oppure un’introduzione. Lorem ipsum dolor sit amet.',
                cta: {
                    url: '#',
                    title: 'Leggi l’articolo',
                    iconEnd: { name: 'arrow-right' },
                    variants: ['quaternary']
                }
            },
            variants: ['type-2'],
        },
    ],
}

storiesOf('Cards Grid|News', module)
    .addDecorator(storyFn => {
        useEffect(() => {
            const app = new Component({
                el: document.body,
                components: [
                    {
                        selector: '.js-cards-grid',
                        type: CardsGrid
                    }
                ]
            })

            return () => app.destroy()
        }, [])

        return storyFn()
    })
    .add('Default', () => render(data))

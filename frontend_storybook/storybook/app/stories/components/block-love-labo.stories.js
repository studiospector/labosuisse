// Storybook API
import { storiesOf } from '@storybook/html'
import { useEffect } from '@storybook/client-api'
// Okiba API
import Component from '@okiba/component'

// Components
import renderBlockLoveLabo from '../../views/components/block-love-labo.twig'

import BlocLoveLabo from '../../scripts/components/BlockLoveLabo'

const dataDefault = {
    infobox: {
        title: '#lovelabo',
        paragraph: 'Segui i profili social di Labo Suisse, interagisci e condividi le tue esperienze con<br>la community online.',
        cta: {
            url: '#',
            title: 'Vai al profilo instagram',
            variants: ['tertiary']
        }
    },
    items: [
        {
            images: {
                original: '/assets/images/love-labo-img-1.jpg',
                large: '/assets/images/love-labo-img-1.jpg',
                medium: '/assets/images/love-labo-img-1.jpg',
                small: '/assets/images/love-labo-img-1.jpg'
            },
            text: ''
        },
        {
            images: {
                original: '/assets/images/love-labo-img-2.jpg',
                large: '/assets/images/love-labo-img-2.jpg',
                medium: '/assets/images/love-labo-img-2.jpg',
                small: '/assets/images/love-labo-img-2.jpg'
            },
            text: ''
        },
        {
            images: {
                original: '/assets/images/love-labo-img-3.jpg',
                large: '/assets/images/love-labo-img-3.jpg',
                medium: '/assets/images/love-labo-img-3.jpg',
                small: '/assets/images/love-labo-img-3.jpg'
            },
            text: ''
        },
        {
            images: {
                original: '/assets/images/love-labo-img-4.jpg',
                large: '/assets/images/love-labo-img-4.jpg',
                medium: '/assets/images/love-labo-img-4.jpg',
                small: '/assets/images/love-labo-img-4.jpg'
            },
            text: ''
        },
        {
            images: {
                original: '/assets/images/love-labo-img-5.jpg',
                large: '/assets/images/love-labo-img-5.jpg',
                medium: '/assets/images/love-labo-img-5.jpg',
                small: '/assets/images/love-labo-img-5.jpg'
            },
            text: ''
        },
        {
            images: {
                original: '/assets/images/love-labo-img-6.jpg',
                large: '/assets/images/love-labo-img-6.jpg',
                medium: '/assets/images/love-labo-img-6.jpg',
                small: '/assets/images/love-labo-img-6.jpg'
            },
            text: ''
        },
    ],
    variants: ['default'], // default, full
}

const dataFull = {
    infobox: {
        title: '#lovelabo',
        paragraph: 'Segui i profili social di Labo Suisse, interagisci e condividi le tue esperienze con<br>la community online.',
        cta: {
            url: '#',
            title: 'Vai al profilo instagram',
            variants: ['tertiary']
        }
    },
    items: [
        {
            images: {
                original: '/assets/images/love-labo-img-1.jpg',
                large: '/assets/images/love-labo-img-1.jpg',
                medium: '/assets/images/love-labo-img-1.jpg',
                small: '/assets/images/love-labo-img-1.jpg'
            },
            text: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla eu metus velit.'
        },
        {
            images: {
                original: '/assets/images/love-labo-img-2.jpg',
                large: '/assets/images/love-labo-img-2.jpg',
                medium: '/assets/images/love-labo-img-2.jpg',
                small: '/assets/images/love-labo-img-2.jpg'
            },
            text: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla eu metus velit.'
        },
        {
            images: {
                original: '/assets/images/love-labo-img-3.jpg',
                large: '/assets/images/love-labo-img-3.jpg',
                medium: '/assets/images/love-labo-img-3.jpg',
                small: '/assets/images/love-labo-img-3.jpg'
            },
            text: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla eu metus velit.'
        },
        {
            images: {
                original: '/assets/images/love-labo-img-4.jpg',
                large: '/assets/images/love-labo-img-4.jpg',
                medium: '/assets/images/love-labo-img-4.jpg',
                small: '/assets/images/love-labo-img-4.jpg'
            },
            text: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla eu metus velit.'
        },
        {
            images: {
                original: '/assets/images/love-labo-img-5.jpg',
                large: '/assets/images/love-labo-img-5.jpg',
                medium: '/assets/images/love-labo-img-5.jpg',
                small: '/assets/images/love-labo-img-5.jpg'
            },
            text: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla eu metus velit.'
        },
        {
            images: {
                original: '/assets/images/love-labo-img-6.jpg',
                large: '/assets/images/love-labo-img-6.jpg',
                medium: '/assets/images/love-labo-img-6.jpg',
                small: '/assets/images/love-labo-img-6.jpg'
            },
            text: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla eu metus velit.'
        },
    ],
    variants: ['full'], // default, full
}

storiesOf('Components|Block Love Labo', module)
    .addDecorator(storyFn => {
        useEffect(() => {
            const app = new Component({
                el: document.body,
                components: [
                    {
                        selector: '.js-love-labo',
                        type: BlocLoveLabo
                    }
                ]
            })

            return () => app.destroy()
        }, [])

        return storyFn()
    })
    .add('Default', () => renderBlockLoveLabo(dataDefault))
    .add('Full', () => renderBlockLoveLabo(dataFull))

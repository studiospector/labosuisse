// Storybook API
import { storiesOf } from '@storybook/html'
import { useEffect } from '@storybook/client-api'
// Okiba API
import Component from '@okiba/component'

// Components
import renderBlockLoveLabo from '../../views/components/block-love-labo.twig'

const data = {
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
            original: '/assets/images/love-labo-img-1.jpg',
            large: '/assets/images/love-labo-img-1.jpg',
            medium: '/assets/images/love-labo-img-1.jpg',
            small: '/assets/images/love-labo-img-1.jpg'
        },
        {
            original: '/assets/images/love-labo-img-2.jpg',
            large: '/assets/images/love-labo-img-2.jpg',
            medium: '/assets/images/love-labo-img-2.jpg',
            small: '/assets/images/love-labo-img-2.jpg'
        },
        {
            original: '/assets/images/love-labo-img-3.jpg',
            large: '/assets/images/love-labo-img-3.jpg',
            medium: '/assets/images/love-labo-img-3.jpg',
            small: '/assets/images/love-labo-img-3.jpg'
        },
        {
            original: '/assets/images/love-labo-img-4.jpg',
            large: '/assets/images/love-labo-img-4.jpg',
            medium: '/assets/images/love-labo-img-4.jpg',
            small: '/assets/images/love-labo-img-4.jpg'
        },
        {
            original: '/assets/images/love-labo-img-5.jpg',
            large: '/assets/images/love-labo-img-5.jpg',
            medium: '/assets/images/love-labo-img-5.jpg',
            small: '/assets/images/love-labo-img-5.jpg'
        },
        {
            original: '/assets/images/love-labo-img-6.jpg',
            large: '/assets/images/love-labo-img-6.jpg',
            medium: '/assets/images/love-labo-img-6.jpg',
            small: '/assets/images/love-labo-img-6.jpg'
        },
    ]
}

storiesOf('Components|Block Love Labo', module)
    .add('Default', () => renderBlockLoveLabo(data))

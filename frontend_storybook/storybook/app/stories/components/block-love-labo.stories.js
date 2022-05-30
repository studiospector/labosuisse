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
                original: 'https://via.placeholder.com/160x160',
                lg: 'https://via.placeholder.com/160x160',
                md: 'https://via.placeholder.com/160x160',
                sm: 'https://via.placeholder.com/160x160',
                xs: 'https://via.placeholder.com/160x160'
            },
            text: ''
        },
        {
            images: {
                original: 'https://via.placeholder.com/160x160',
                lg: 'https://via.placeholder.com/160x160',
                md: 'https://via.placeholder.com/160x160',
                sm: 'https://via.placeholder.com/160x160',
                xs: 'https://via.placeholder.com/160x160'
            },
            text: ''
        },
        {
            images: {
                original: 'https://via.placeholder.com/160x160',
                lg: 'https://via.placeholder.com/160x160',
                md: 'https://via.placeholder.com/160x160',
                sm: 'https://via.placeholder.com/160x160',
                xs: 'https://via.placeholder.com/160x160'
            },
            text: ''
        },
        {
            images: {
                original: 'https://via.placeholder.com/160x160',
                lg: 'https://via.placeholder.com/160x160',
                md: 'https://via.placeholder.com/160x160',
                sm: 'https://via.placeholder.com/160x160',
                xs: 'https://via.placeholder.com/160x160'
            },
            text: ''
        },
        {
            images: {
                original: 'https://via.placeholder.com/160x160',
                lg: 'https://via.placeholder.com/160x160',
                md: 'https://via.placeholder.com/160x160',
                sm: 'https://via.placeholder.com/160x160',
                xs: 'https://via.placeholder.com/160x160'
            },
            text: ''
        },
        {
            images: {
                original: 'https://via.placeholder.com/160x160',
                lg: 'https://via.placeholder.com/160x160',
                md: 'https://via.placeholder.com/160x160',
                sm: 'https://via.placeholder.com/160x160',
                xs: 'https://via.placeholder.com/160x160'
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
                original: 'https://via.placeholder.com/160x160',
                lg: 'https://via.placeholder.com/160x160',
                md: 'https://via.placeholder.com/160x160',
                sm: 'https://via.placeholder.com/160x160',
                xs: 'https://via.placeholder.com/160x160'
            },
            text: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla eu metus velit.'
        },
        {
            images: {
                original: 'https://via.placeholder.com/160x160',
                lg: 'https://via.placeholder.com/160x160',
                md: 'https://via.placeholder.com/160x160',
                sm: 'https://via.placeholder.com/160x160',
                xs: 'https://via.placeholder.com/160x160'
            },
            text: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla eu metus velit.'
        },
        {
            images: {
                original: 'https://via.placeholder.com/160x160',
                lg: 'https://via.placeholder.com/160x160',
                md: 'https://via.placeholder.com/160x160',
                sm: 'https://via.placeholder.com/160x160',
                xs: 'https://via.placeholder.com/160x160'
            },
            text: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla eu metus velit.'
        },
        {
            images: {
                original: 'https://via.placeholder.com/160x160',
                lg: 'https://via.placeholder.com/160x160',
                md: 'https://via.placeholder.com/160x160',
                sm: 'https://via.placeholder.com/160x160',
                xs: 'https://via.placeholder.com/160x160'
            },
            text: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla eu metus velit.'
        },
        {
            images: {
                original: 'https://via.placeholder.com/160x160',
                lg: 'https://via.placeholder.com/160x160',
                md: 'https://via.placeholder.com/160x160',
                sm: 'https://via.placeholder.com/160x160',
                xs: 'https://via.placeholder.com/160x160'
            },
            text: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla eu metus velit.'
        },
        {
            images: {
                original: 'https://via.placeholder.com/160x160',
                lg: 'https://via.placeholder.com/160x160',
                md: 'https://via.placeholder.com/160x160',
                sm: 'https://via.placeholder.com/160x160',
                xs: 'https://via.placeholder.com/160x160'
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

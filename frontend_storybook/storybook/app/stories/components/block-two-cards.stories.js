// Storybook API
import { storiesOf } from '@storybook/html'
import { useEffect } from '@storybook/client-api'
// Okiba API
import Component from '@okiba/component'

// Components
import renderBlock from '../../views/components/block-launch-two-cards.twig'

const dataDefault = {
    infobox: {
        subtitle: 'Individua il trattamento adatto a te',
        paragraph: 'Per conoscere il tuo grado di diradamento e il dosaggio più indicato per te,<br>consulta la tabella.',
    },
    cards: [
        {
            images: {
                original: '/assets/images/banner-img.jpg',
                large: '/assets/images/banner-img.jpg',
                medium: '/assets/images/banner-img.jpg',
                small: '/assets/images/banner-img.jpg'
            },
            infobox: {
                subtitle: 'Scala di diradamento uomo',
                cta: {
                    url: '#',
                    title: 'Visualizza la scala',
                    iconEnd: { name: 'arrow-right' },
                    variants: ['quaternary']
                }
            }
        },
        {
            images: {
                original: '/assets/images/banner-img.jpg',
                large: '/assets/images/banner-img.jpg',
                medium: '/assets/images/banner-img.jpg',
                small: '/assets/images/banner-img.jpg'
            },
            infobox: {
                subtitle: 'Scala di diradamento donna',
                cta: {
                    url: '#',
                    title: 'Visualizza la scala',
                    iconEnd: { name: 'arrow-right' },
                    variants: ['quaternary']
                }
            }
        }
    ]
}

storiesOf('Components|Block Two Cards', module)
    .add('Default', () => renderBlock(dataDefault))

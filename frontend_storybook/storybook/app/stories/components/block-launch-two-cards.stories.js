// Storybook API
import { storiesOf } from '@storybook/html'
import { useEffect } from '@storybook/client-api'
// Okiba API
import Component from '@okiba/component'

// Components
import renderBlock from '../../views/components/block-launch-two-cards.twig'

const dataHorizontal = {
    infobox: {
        subtitle: 'Individua il trattamento adatto a te',
        paragraph: 'Per conoscere il tuo grado di diradamento e il dosaggio più indicato per te,<br>consulta la tabella.',
    },
    cards: [
        {
            images: {
                original: '/assets/images/banner-img.jpg',
                lg: '/assets/images/banner-img.jpg',
                md: '/assets/images/banner-img.jpg',
                sm: '/assets/images/banner-img.jpg',
                xs: '/assets/images/banner-img.jpg'
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
                lg: '/assets/images/banner-img.jpg',
                md: '/assets/images/banner-img.jpg',
                sm: '/assets/images/banner-img.jpg',
                xs: '/assets/images/banner-img.jpg'
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
    ],
    variants: ['horizontal'],
}

const dataVertical = {
    infobox: {
        subtitle: 'Le scale del diradamento dei capelli',
        paragraph: 'La classificazione del diradamento di Labo si basa sulla scala aggiornata di Hamilton/Norwood per<br>l’uomo e di Ludwig/Savin per la donna, elaborate per facilitare la scelta dei corretti dosaggi Crescina<br>(200-500-1300): ai diversi stadi del diradamento dei capelli corrispondono infatti concentrazioni crescenti dei principi attivi.',
    },
    cards: [
        {
            images: {
                original: '/assets/images/banner-img.jpg',
                lg: '/assets/images/banner-img.jpg',
                md: '/assets/images/banner-img.jpg',
                sm: '/assets/images/banner-img.jpg',
                xs: '/assets/images/banner-img.jpg'
            },
            infobox: {
                subtitle: 'Scala di diradamento uomo',
            }
        },
        {
            images: {
                original: '/assets/images/banner-img.jpg',
                lg: '/assets/images/banner-img.jpg',
                md: '/assets/images/banner-img.jpg',
                sm: '/assets/images/banner-img.jpg',
                xs: '/assets/images/banner-img.jpg'
            },
            infobox: {
                subtitle: 'Scala di diradamento donna',
            }
        }
    ],
    variants: ['vertical'],
}

storiesOf('Components|Block Launch Two Cards', module)
    .add('Horizontal', () => renderBlock(dataHorizontal))
    .add('Vertical', () => renderBlock(dataVertical))

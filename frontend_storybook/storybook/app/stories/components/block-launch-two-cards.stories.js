// Storybook API
import { storiesOf } from '@storybook/html'
import { useEffect } from '@storybook/client-api'
// Okiba API
import Component from '@okiba/component'

// Components
import renderBlock from '../../views/components/block-launch-two-cards.twig'
import AnimationReveal from '../../scripts/components/AnimationReveal'

const dataHorizontal = {
    infobox: {
        subtitle: 'Individua il trattamento adatto a te',
        paragraph: 'Per conoscere il tuo grado di diradamento e il dosaggio più indicato per te,<br>consulta la tabella.',
    },
    cards: [
        {
            images: {
                original: 'https://via.placeholder.com/570x310',
                lg: 'https://via.placeholder.com/570x310',
                md: 'https://via.placeholder.com/570x310',
                sm: 'https://via.placeholder.com/570x310',
                xs: 'https://via.placeholder.com/570x310'
            },
            infobox: {
                subtitle: 'Scala di diradamento uomo',
                cta: {
                    url: '#',
                    title: 'Visualizza la scala',
                    iconEnd: { name: 'arrow-right' },
                    variants: ['quaternary']
                }
            },
            type: 'type-3',
        },
        {
            images: {
                original: 'https://via.placeholder.com/570x310',
                lg: 'https://via.placeholder.com/570x310',
                md: 'https://via.placeholder.com/570x310',
                sm: 'https://via.placeholder.com/570x310',
                xs: 'https://via.placeholder.com/570x310'
            },
            infobox: {
                subtitle: 'Scala di diradamento donna',
                cta: {
                    url: '#',
                    title: 'Visualizza la scala',
                    iconEnd: { name: 'arrow-right' },
                    variants: ['quaternary']
                }
            },
            type: 'type-3',
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
                original: 'https://via.placeholder.com/1200x470',
                lg: 'https://via.placeholder.com/1200x470',
                md: 'https://via.placeholder.com/1200x470',
                sm: 'https://via.placeholder.com/1200x470',
                xs: 'https://via.placeholder.com/1200x470'
            },
            infobox: {
                subtitle: 'Scala di diradamento uomo',
            },
            type: 'type-3',
        },
        {
            images: {
                original: 'https://via.placeholder.com/1200x470',
                lg: 'https://via.placeholder.com/1200x470',
                md: 'https://via.placeholder.com/1200x470',
                sm: 'https://via.placeholder.com/1200x470',
                xs: 'https://via.placeholder.com/1200x470'
            },
            infobox: {
                subtitle: 'Scala di diradamento donna',
            },
            type: 'type-3',
        }
    ],
    variants: ['vertical'],
}

storiesOf('Components|Block Launch Two Cards', module)
    .addDecorator(storyFn => {
        useEffect(() => {
            const app = new Component({
                el: document.body,
                components: [
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
    .add('Horizontal', () => renderBlock(dataHorizontal))
    .add('Vertical', () => renderBlock(dataVertical))

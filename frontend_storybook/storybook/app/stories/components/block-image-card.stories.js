// Storybook API
import { storiesOf } from '@storybook/html'
import { useEffect } from '@storybook/client-api'
// Okiba API
import Component from '@okiba/component'

// Components
import renderBlock from '../../views/components/block-image-card.twig'
import AnimationReveal from '../../scripts/components/AnimationReveal'

const dataDefault = {
    images: {
        original: 'https://via.placeholder.com/535x500',
        lg: 'https://via.placeholder.com/535x500',
        md: 'https://via.placeholder.com/535x500',
        sm: 'https://via.placeholder.com/535x500',
        xs: 'https://via.placeholder.com/535x500'
    },
    card: {
        images: {
            original: 'https://via.placeholder.com/535x160',
            lg: 'https://via.placeholder.com/535x160',
            md: 'https://via.placeholder.com/535x160',
            sm: 'https://via.placeholder.com/535x160',
            xs: 'https://via.placeholder.com/535x160'
        },
        infobox: {
            subtitle: 'La tecnologia dietro l’efficacia',
            paragraph: 'Grazie alla Tecnologia Transdermica (Swiss Patent CH 711 466) – ispirata alla metodologia della medicina estetica e brevettata nel 2015 – Labo supera le frontiere della scienza dermo-cosmetica divenendo la prima azienda a sviluppare una nuova tecnica di penetrazione dei principi attivi, senza iniezioni, attraverso epidermide e derma.',
            cta: {
                url: '#',
                title: 'Scopri di più',
                iconEnd: { name: 'arrow-right' },
                variants: ['quaternary']
            }
        },
        variants: ['type-7']
    }
}

storiesOf('Components|Block Image Card', module)
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
    .add('Default', () => renderBlock(dataDefault))

// Storybook API
import { storiesOf } from '@storybook/html'
import { useEffect } from '@storybook/client-api'
// Okiba API
import Component from '@okiba/component'

// Components
import renderInfobox from '../../views/components/infobox.twig'

const data = {
    image: '/assets/images/crescina-logo.png',
    tagline: 'CRESCINA TRANSDERMIC RAPID-INTENSIVE',
    title: 'Favorisce la crescita<br>fisiologica dei capelli',
    subtitle: 'Labo suisse: ricerca e innovazione',
    paragraph: 'Dal 1898 Labo investe nella ricerca di tecnologie all’avanguardia per sviluppare prodotti innovativi e brevettati, per la cura dei capelli e della pelle.',
    cta: {
        href: '#',
        label: 'Scopri la linea',
        variants: ['primary']
    }
}

storiesOf('Components|Infobox', module)
    .add('Default', () => renderInfobox(data))

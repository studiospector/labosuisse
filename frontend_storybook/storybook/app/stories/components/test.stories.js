// Storybook API
import { storiesOf } from '@storybook/html'
import { useEffect } from '@storybook/client-api'
// Okiba API
import Component from '@okiba/component'

// Components
import renderInfobox from '../../views/components/infobox.twig'

const data = {
    image: '/assets/images/crescina-logo.png',
    tagline: 'Test CRESCINA TRANSDERMIC RAPID-INTENSIVE',
    title: 'Test Favorisce la crescita<br>fisiologica dei capelli',
    subtitle: 'Test Labo suisse: ricerca e innovazione',
    paragraph: 'Test Dal 1898 Labo investe nella ricerca di tecnologie all’avanguardia per sviluppare prodotti innovativi e brevettati, per la cura dei capelli e della pelle.',
    paragraph_small: 'Test Promozione valida, nei punti vendita aderenti. Fino a esaurimento scorte',
    cta: {
        url: '#',
        title: 'Test Scopri la linea',
        variants: ['primary']
    }
}

storiesOf('Components|Test', module)
    .add('Default', () => renderInfobox(data))

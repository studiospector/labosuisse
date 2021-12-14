// Storybook API
import { storiesOf } from '@storybook/html'
import { useEffect } from '@storybook/client-api'
// Okiba API
import Component from '@okiba/component'

// Components
import renderNumberList from '../../views/components/number-list-with-image.twig'

const dataDefault = {
    images: {
        original: '/assets/images/carousel-hero-img-2.jpg',
        large: '/assets/images/carousel-hero-img-2.jpg',
        medium: '/assets/images/carousel-hero-img-2.jpg',
        small: '/assets/images/carousel-hero-img-2.jpg'
    },
    numbersList: {
        title: 'Tre consigli utili',
        list: [
            {
                title: '',
                text: 'Si consiglia un trattamento di almeno 2 mesi.<br>Può essere ripetuto più volte l’anno.',
            },
            {
                title: '',
                text: 'Utilizza Crescina sul cuoio capelluto<br>completamente integro, senza escoriazioni.',
            },
            {
                title: '',
                text: 'Regola le tue abitudini di detersione:<br>lava i capelli almeno due volte a settimana.',
            },
        ],
        variants: ['vertical']
    }
}

storiesOf('Components|Number List with Image', module)
    .add('Default', () => renderNumberList(dataDefault))

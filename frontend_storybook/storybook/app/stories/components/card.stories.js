// Storybook API
import { storiesOf } from '@storybook/html'
import { useEffect } from '@storybook/client-api'
// Okiba API
import Component from '@okiba/component'

// Components
import renderCard from '../../views/components/card.twig'

const dataCardType1 = {
    images: {
        original: '/assets/images/card-img-4.jpg',
        large: '/assets/images/card-img-4.jpg',
        medium: '/assets/images/card-img-4.jpg',
        small: '/assets/images/card-img-4.jpg'
    },
    infobox: {
        tagline: 'TRATTAMENTO FACCIALE',
        subtitle: 'Lifting facciale: primo terzo del viso, sottomento e collo',
        cta: {
            href: '#',
            label: 'Scopri di più',
            iconEnd: { name: 'arrow-right' },
            variants: ['quaternary']
        }
    },
    variants: ['type-1']
}

const dataCardType2 = {
    images: {
        original: '/assets/images/card-img-5.jpg',
        large: '/assets/images/card-img-5.jpg',
        medium: '/assets/images/card-img-5.jpg',
        small: '/assets/images/card-img-5.jpg'
    },
    infobox: {
        subtitle: 'Magnetic Eyes',
        paragraph: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
        cta: {
            href: '#',
            label: 'Scopri di più',
            iconEnd: { name: 'arrow-right' },
            variants: ['quaternary']
        }
    },
    variants: ['type-2']
}

const dataCardType3 = {
    images: {
        original: '/assets/images/card-img-6.jpg',
        large: '/assets/images/card-img-6.jpg',
        medium: '/assets/images/card-img-6.jpg',
        small: '/assets/images/card-img-6.jpg'
    },
    date: '00/00/00',
    infobox: {
        subtitle: 'Titolo del contenuto editoriale che andrà nella sezione News',
        paragraph: 'Incipit del contenuto editoriale. Può essere parte dell’articolo originale oppure un’introduzione. Lorem ipsum dolor sit amet.',
        cta: {
            href: '#',
            label: 'Leggi l’articolo',
            iconEnd: { name: 'arrow-right' },
            variants: ['quaternary']
        }
    },
    variants: ['type-3']
}

const dataCardType4 = {
    images: {
        original: '/assets/images/card-img-6.jpg',
        large: '/assets/images/card-img-6.jpg',
        medium: '/assets/images/card-img-6.jpg',
        small: '/assets/images/card-img-6.jpg'
    },
    date: '00/00/00',
    infobox: {
        image: '/assets/images/crescina-logo.png',
        paragraph: 'Corriere della sera - Il Giorno - La Repubblica - Il Messaggero - Il Sole 24 Ore - Grazia',
        cta: {
            href: '#',
            label: 'Visualizza',
            iconEnd: { name: 'arrow-right' },
            variants: ['quaternary']
        }
    },
    variants: ['type-4']
}

storiesOf('Components|Cards', module)
    .add('Type 1', () => renderCard(dataCardType1))
    .add('Type 2', () => renderCard(dataCardType2))
    .add('Type 3', () => renderCard(dataCardType3))
    .add('Type 4', () => renderCard(dataCardType4))

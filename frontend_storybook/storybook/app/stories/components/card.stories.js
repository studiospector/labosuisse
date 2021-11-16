// Storybook API
import { storiesOf } from '@storybook/html'
import { useEffect } from '@storybook/client-api'
// Okiba API
import Component from '@okiba/component'

// Components
import renderCard from '../../views/components/card.twig'

// Lifting
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

// News
const dataCardType2 = {
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
    variants: ['type-2']
}

// Trattamenti
const dataCardType3 = {
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
    variants: ['type-3']
}

// Magazine
const dataCardType6 = {
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
    variants: ['type-6']
}

// Colored
const dataCardType8 = {
    color: '#E6D4B0',
    infobox: {
        subtitle: 'LABO TRANSDERMIC',
        paragraph: 'Una nuova generazione di skincare routine grazie alla Tecnologia Transdermica, innovazione mondiale che punta sulla penetrazione profonda dei principi attivi.',
        cta: {
            href: '#',
            label: 'Scopri il brand',
            iconEnd: { name: 'arrow-right' },
            variants: ['quaternary']
        }
    },
    variants: ['type-8']
}

storiesOf('Components|Cards', module)
    // Lifting
    .add('Type 1 --- Lifting', () => renderCard(dataCardType1))
    // News
    .add('Type 2 --- News', () => renderCard(dataCardType2))
    // Trattamenti
    .add('Type 3 --- Trattamenti', () => renderCard(dataCardType3))
    // Magazine
    .add('Type 6 --- Magazine', () => renderCard(dataCardType6))
    // Colored
    .add('Type 8 --- Colored', () => renderCard(dataCardType8))

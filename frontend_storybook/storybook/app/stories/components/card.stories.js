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
        lg: '/assets/images/card-img-4.jpg',
        md: '/assets/images/card-img-4.jpg',
        sm: '/assets/images/card-img-4.jpg',
        xs: '/assets/images/card-img-4.jpg'
    },
    infobox: {
        tagline: 'TRATTAMENTO FACCIALE',
        subtitle: 'Lifting facciale: primo terzo del viso, sottomento e collo',
        cta: {
            url: '#',
            title: 'Scopri di più',
            iconEnd: { name: 'arrow-right' },
            variants: ['quaternary']
        }
    },
    variants: ['type-1']
}

// Lifting Secondary
const dataCardType1Secondary = {
    images: {
        original: '/assets/images/card-img-4.jpg',
        lg: '/assets/images/card-img-4.jpg',
        md: '/assets/images/card-img-4.jpg',
        sm: '/assets/images/card-img-4.jpg',
        xs: '/assets/images/card-img-4.jpg'
    },
    infobox: {
        tagline: 'TRATTAMENTO FACCIALE',
        subtitle: 'Lifting facciale: primo terzo del viso, sottomento e collo',
        paragraph: 'Incipit del contenuto editoriale. Può essere parte dell’articolo originale oppure un’introduzione. Lorem ipsum dolor sit amet.',
        cta: {
            url: '#',
            title: 'Scopri di più',
            iconEnd: { name: 'arrow-right' },
            variants: ['quaternary']
        }
    },
    variants: ['type-1', 'type-1-secondary']
}

// News
const dataCardType2 = {
    images: {
        original: '/assets/images/card-img-6.jpg',
        lg: '/assets/images/card-img-6.jpg',
        md: '/assets/images/card-img-6.jpg',
        sm: '/assets/images/card-img-6.jpg',
        xs: '/assets/images/card-img-6.jpg'
    },
    date: '00/00/00',
    infobox: {
        subtitle: 'Titolo del contenuto editoriale che andrà nella sezione News',
        paragraph: 'Incipit del contenuto editoriale. Può essere parte dell’articolo originale oppure un’introduzione. Lorem ipsum dolor sit amet.',
        cta: {
            url: '#',
            title: 'Leggi l’articolo',
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
        lg: '/assets/images/card-img-5.jpg',
        md: '/assets/images/card-img-5.jpg',
        sm: '/assets/images/card-img-5.jpg',
        xs: '/assets/images/card-img-5.jpg'
    },
    infobox: {
        subtitle: 'Magnetic Eyes',
        paragraph: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
        cta: {
            url: '#',
            title: 'Scopri di più',
            iconEnd: { name: 'arrow-right' },
            variants: ['quaternary']
        }
    },
    variants: ['type-3']
}

// About
const dataCardType5 = {
    images: {
        original: '/assets/images/card-img-7.jpg',
        lg: '/assets/images/card-img-7.jpg',
        md: '/assets/images/card-img-7.jpg',
        sm: '/assets/images/card-img-7.jpg',
        xs: '/assets/images/card-img-7.jpg'
    },
    infobox: {
        subtitle: '1989: il primo lancio',
        paragraph: 'Lancio di Nicotenil Anti-Caduta, il primo trattamento cosmetico per prevenire la caduta dei capelli, con specifiche proprietà vasodilatatorie, sviluppate per stimolare la microcircolazione sanguigna del cuoio capelluto.',
    },
    variants: ['type-5']
}

// Magazine
const dataCardType6 = {
    images: {
        original: '/assets/images/card-img-6.jpg',
        lg: '/assets/images/card-img-6.jpg',
        md: '/assets/images/card-img-6.jpg',
        sm: '/assets/images/card-img-6.jpg',
        xs: '/assets/images/card-img-6.jpg'
    },
    date: '00/00/00',
    infobox: {
        image: '/assets/images/crescina-logo.png',
        paragraph: 'Corriere della sera - Il Giorno - La Repubblica - Il Messaggero - Il Sole 24 Ore - Grazia',
        cta: {
            url: '#',
            title: 'Visualizza',
            iconEnd: { name: 'arrow-right' },
            variants: ['quaternary']
        }
    },
    variants: ['type-6']
}

// FAQ
const dataCardType7 = {
    images: {
        original: '/assets/images/card-img-6.jpg',
        lg: '/assets/images/card-img-6.jpg',
        md: '/assets/images/card-img-6.jpg',
        sm: '/assets/images/card-img-6.jpg',
        xs: '/assets/images/card-img-6.jpg'
    },
    infobox: {
        image: '/assets/images/crescina-logo.png',
        subtitle: 'La tecnologia dietro l’efficacia',
        items: [
            'Il trattamento Fillerina può essere utilizzato anche per le rughe del labbro superiore della bocca?',
            'Si possono effettuare pulizia viso o lampade solari durante il trattamento?',
            'Ci si può esporre al sole durante il trattamento?',
            'Si può utilizzare Fillerina nei periodi di gravidanza o in allattamento?',
        ],
        cta: {
            url: '#',
            title: 'Scopri di più',
            iconEnd: { name: 'arrow-right' },
            variants: ['quaternary']
        }
    },
    variants: ['type-7']
}

// Colored
const dataCardType8 = {
    color: '#E6D4B0',
    infobox: {
        subtitle: 'LABO TRANSDERMIC',
        paragraph: 'Una nuova generazione di skincare routine grazie alla Tecnologia Transdermica, innovazione mondiale che punta sulla penetrazione profonda dei principi attivi.',
        cta: {
            url: '#',
            title: 'Scopri il brand',
            iconEnd: { name: 'arrow-right' },
            variants: ['quaternary']
        }
    },
    variants: ['type-8']
}

// Job
const dataCardType9 = {
    infobox: {
        subtitle: 'Agenti mono o plurimandatari (farmacie)',
        location: {
            isHeadquarter: true,
            label: 'Headquarter (Padova)',
        },
        scope: {
            label: 'Ambito:',
            value: 'Ampliare la propria storica divisione commercale'
        },
        paragraph: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque quis nunc felis. Sed at ligula diam.',
        cta: {
            url: '#',
            title: 'Leggi di più',
            variants: ['quaternary']
        }
    },
    variants: ['type-9']
}

// Brand
const dataCardType10 = {
    images: {
        original: '/assets/images/card-img-6.jpg',
        lg: '/assets/images/card-img-6.jpg',
        md: '/assets/images/card-img-6.jpg',
        sm: '/assets/images/card-img-6.jpg',
        xs: '/assets/images/card-img-6.jpg'
    },
    infobox: {
        subtitle: 'Labo Transdermic',
        paragraph: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque quis nunc felis. Sed at ligula diam.',
        cta: {
            url: '#',
            title: 'Vai al brand',
            variants: ['quaternary']
        }
    },
    variants: ['type-10']
}

storiesOf('Components|Cards', module)
    // Lifting
    .add('Type 1 --- Lifting', () => renderCard(dataCardType1))
    // Lifting Secondary
    .add('Type 1 --- Lifting Secondary', () => renderCard(dataCardType1Secondary))
    // News
    .add('Type 2 --- News', () => renderCard(dataCardType2))
    // Trattamenti
    .add('Type 3 --- Trattamenti', () => renderCard(dataCardType3))
    // About
    .add('Type 5 --- About', () => renderCard(dataCardType5))
    // Magazine
    .add('Type 6 --- Magazine', () => renderCard(dataCardType6))
    // FAQ
    .add('Type 7 --- FAQ', () => renderCard(dataCardType7))
    // Colored
    .add('Type 8 --- Colored', () => renderCard(dataCardType8))
    // Job
    .add('Type 9 --- Job', () => renderCard(dataCardType9))
    // Brand
    .add('Type 10 --- Brand', () => renderCard(dataCardType10))

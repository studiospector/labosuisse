// Storybook API
import { storiesOf } from '@storybook/html'
import { useEffect } from '@storybook/client-api'
// Okiba API
import Component from '@okiba/component'

// Components
import render from '../../views/components/focus-numbers.twig'

const dataDefault = {
    shield: '/assets/images/patent-1.png',
    image: '/assets/images/crescina-logo.png',
    subtitle: 'Aiuta la Ri-Crescita naturale dei capelli',
    text: 'Lo sai che diradamento e caduta dei capelli non sono la stessa cosa? Il problema più serio, infatti, è quello del diradamento che porta a intravedere il cuoio capelluto. Crescina, trattamento dermo-cosmetico in fiale, è la soluzione avanzata che favorisce la Ri-Crescita fisiologica dei capelli quando la loro qualità e quantità diminuiscono.',
    focuses: [
        {
            number: '100%',
            title: 'Test con risultati visibili',
            text: 'Risultato dopo 4 mesi di test clinico-strutturale in-vivo, in doppio cieco, randomizzato e controllato con placebo su 46 soggetti.',
        },
        {
            number: '6.300',
            title: 'Nuovi capelli in crescita',
            text: 'Secondo una rielaborazione matematica dei risultati verificati in un’area di 1/2cm ed estesi a tutta la superficie del cuoio capelluto.',
        },
        {
            number: '8',
            title: 'Brevetti:<br>svizzera, europa',
            text: 'Brevetti Svizzeri: CH 713 030, CH 711 466,<br>CH 697 229 B1, CH 693 814 A5, CH 703 390 B1;<br>CH 693 815 A5; CH 704 629 B1;<br>Brevetto EUROPEO EP 1 089 704 B1',
        },
    ],
}

storiesOf('Components|Focus numbers', module)
    .add('Default', () => render(dataDefault))

// Storybook API
import { storiesOf } from '@storybook/html'
import { useEffect } from '@storybook/client-api'
// Okiba API
import Component from '@okiba/component'

// Components
import renderNumberList from '../../views/components/number-list.twig'

const dataVertical = {
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

const dataHorizontal = {
    title: '',
    list: [
        {
            title: 'Eccellenza',
            text: 'in tutto ciò che concerne i propri brand: prodotti, promesse, comunicazioni, relazioni.  L’ordinarietà non fa parte del vocabolario di Labo. Ogni cosa è pensata e realizzata per distinguersi dalla concorrenza.',
        },
        {
            title: 'Innovazione',
            text: 'continua dei prodotti, metodi e applicazioni. I consumatori che comprano i trattamenti Labo sanno che questi rappresentano l’avanguardia dell’industria cosmetica e che la loro efficacia è provata da numerosi studi e test clinici. ',
        },
        {
            title: 'Cura e rispetto',
            text: 'per i consumatori, i collaboratori ed i partner commerciali. Labo crede e investe nelle relazioni di lungo periodo, che apportano benefici e creano valore aggiunto per tutte le persone coinvolte, siano esse clienti, dipendenti o partner commerciali.',
        },
        {
            title: 'Valore aggiunto',
            text: 'verso il raggiungimento di ciascuna delle promesse. Attraverso ogni scelta, comportamento e prodotto, Labo intende creare il massimo valore possibile per la società e i consumatori. Tale aspirazione permea ogni ramo dell’azienda: Ricerca e Sviluppo, Marketing, Servizio Clienti, Commerciale e tutti i nostri partner internazionali, accuratamente selezionati.',
        }
    ],
    variants: ['horizontal']
}

storiesOf('Components|Number List', module)
    .add('Vertical', () => renderNumberList(dataVertical))
    .add('Horizontal', () => renderNumberList(dataHorizontal))

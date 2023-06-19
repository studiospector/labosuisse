// Storybook API
import { useEffect } from "@storybook/client-api";
// Okiba API
import Component from "@okiba/component";
// Component
import render from "../../views/components/accordion.twig";
import Accordion from "../../scripts/components/Accordion";

export default {
  title: "Components/Accordion",
  decorators: [
    (Story) => {
      useEffect(() => {
        const app = new Component({
          el: document.body,
          components: [
            {
              selector: ".js-accordion",
              type: Accordion,
            },
          ],
        });

        return () => app.destroy();
      }, []);

      return `
        <div style="max-width: 700px;">
          ${Story().outerHTML || Story()}
        </div>
      `;
    },
  ],
  render: ({ ...args }) => {
    return render(args);
  },
};

export const Default = {
  args: {
    items: [
      {
        title: "Il trattamento Fillerina può essere utilizzato anche per le rughe del labbro superiore della bocca?",
        content: "Fillerina si presta particolarmente per il riempimento di rughe espressione: quelle verticali sopra le labbra sono per l’appunto rughette di espressione che vanno rimpolpate con il filler in gel. Gli acidi ialuronici contenuti nel prodotto andranno a riempire il vuoto delle rughe che risulteranno un po’ per volta meno visibili. Per la sola zona del contorno bocca si può utilizzare Fillerina Labbra e Bocca.",
      },
      {
        title: "Come viene smaltita Fillerina dall’organismo?",
        content: "Fillerina si presta particolarmente per il riempimento di rughe espressione: quelle verticali sopra le labbra sono per l’appunto rughette di espressione che vanno rimpolpate con il filler in gel. Gli acidi ialuronici contenuti nel prodotto andranno a riempire il vuoto delle rughe che risulteranno un po’ per volta meno visibili. Per la sola zona del contorno bocca si può utilizzare Fillerina Labbra e Bocca.",
      },
      {
        title: "Il trattamento potrebbe indurre una qualche forma di allergia? Contiene nickel?",
        content: "Fillerina si presta particolarmente per il riempimento di rughe espressione: quelle verticali sopra le labbra sono per l’appunto rughette di espressione che vanno rimpolpate con il filler in gel. Gli acidi ialuronici contenuti nel prodotto andranno a riempire il vuoto delle rughe che risulteranno un po’ per volta meno visibili. Per la sola zona del contorno bocca si può utilizzare Fillerina Labbra e Bocca.",
      },
    ],
  },
};

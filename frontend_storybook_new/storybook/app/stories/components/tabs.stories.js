// Storybook API
import { useEffect } from "@storybook/client-api";
// Okiba API
import Component from "@okiba/component";
// Component
// import render from "../../views/components/tabs.twig";
import Tabs from "../../scripts/components/Tabs";

export default {
  title: "Components/Tabs",
  decorators: [
    (Story) => {
      useEffect(() => {
        const app = new Component({
          el: document.body,
          components: [
            {
              selector: ".js-tabs",
              type: Tabs,
            },
          ],
        });

        return () => app.destroy();
      }, []);

      return `
        <div>
          ${Story().outerHTML || Story()}
        </div>
      `;
    },
  ],
  render: ({ ...args }) => {
    // return render(args);
    return "Blocco non disponibile";
  },
};

/**
 * Default
 */
export const Default = {
  args: {
    no_results: {
      paragraph:
        "Siamo spiacenti! nessun risultato corrisponde alla tua ricerca per quasta entità.",
    },
    tabs: [
      {
        id: "product",
        head: {
          label: "Prodotti",
          count: 21,
        },
        entries: [
          {
            type: "infobox",
            data: {
              subtitle: "Labo suisse: ricerca e innovazione",
              paragraph:
                "Dal 1898 Labo investe nella ricerca di tecnologie all’avanguardia per sviluppare prodotti innovativi e brevettati.",
            },
          },
        ],
      },
      {
        id: "faq",
        head: {
          label: "FAQ",
          count: 4,
        },
        entries: [
          {
            type: "infobox",
            data: {
              subtitle: "1 Labo suisse: ricerca e innovazione",
              paragraph:
                "1 Dal 1898 Labo investe nella ricerca di tecnologie all’avanguardia per sviluppare prodotti innovativi e brevettati.",
            },
          },
        ],
      },
    ],
  },
};

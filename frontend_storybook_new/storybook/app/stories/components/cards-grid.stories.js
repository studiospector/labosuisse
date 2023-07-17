// Storybook API
import { useEffect } from "@storybook/client-api";
// Okiba API
import Component from "@okiba/component";
// Component
import render from "../../views/components/cards-grid.twig";
import CardsGrid from "../../scripts/components/CardsGrid";

export default {
  title: "Cards Grid/News",
  decorators: [
    (Story) => {
      useEffect(() => {
        const app = new Component({
          el: document.body,
          components: [
            {
              selector: ".js-lb-posts-grid",
              type: CardsGrid,
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
    return render(args);
  },
};

/**
 * Default
 */
export const Default = {
  args: {
    title: "Le ultime novità da labo magazine",
    cta: {
      title: "Vai a news e media",
      url: "#",
      variants: ["tertiary"],
    },
    items: [
      {
        images: {
          original: "/images/card-img-6.jpg",
          lg: "/images/card-img-6.jpg",
          md: "/images/card-img-6.jpg",
          sm: "/images/card-img-6.jpg",
          xs: "/images/card-img-6.jpg",
        },
        date: "00/00/00",
        infobox: {
          subtitle: "La più grande community di beauty lover ha testato Labo",
          paragraph:
            "Incipit del contenuto editoriale. Può essere parte dell’articolo originale oppure un’introduzione. Lorem ipsum dolor sit amet.",
          cta: {
            url: "#",
            title: "Leggi l’articolo",
            iconEnd: { name: "arrow-right" },
            variants: ["quaternary"],
          },
        },
        type: "type-2",
      },
      {
        images: {
          original: "/images/card-img-6.jpg",
          lg: "/images/card-img-6.jpg",
          md: "/images/card-img-6.jpg",
          sm: "/images/card-img-6.jpg",
          xs: "/images/card-img-6.jpg",
        },
        date: "00/00/00",
        infobox: {
          subtitle:
            "Dopo l’estate, una cura per la pelle a tutto ossigeno: arriva Oxy-Treat",
          paragraph:
            "Incipit del contenuto editoriale. Può essere parte dell’articolo originale oppure un’introduzione. Lorem ipsum dolor sit amet.",
          cta: {
            url: "#",
            title: "Leggi l’articolo",
            iconEnd: { name: "arrow-right" },
            variants: ["quaternary"],
          },
        },
        type: "type-2",
      },
      {
        images: {
          original: "/images/card-img-6.jpg",
          lg: "/images/card-img-6.jpg",
          md: "/images/card-img-6.jpg",
          sm: "/images/card-img-6.jpg",
          xs: "/images/card-img-6.jpg",
        },
        date: "00/00/00",
        infobox: {
          subtitle: "La formazione online firmata Labo",
          paragraph:
            "Incipit del contenuto editoriale. Può essere parte dell’articolo originale oppure un’introduzione. Lorem ipsum dolor sit amet.",
          cta: {
            url: "#",
            title: "Leggi l’articolo",
            iconEnd: { name: "arrow-right" },
            variants: ["quaternary"],
          },
        },
        type: "type-2",
      },
      {
        images: {
          original: "/images/card-img-6.jpg",
          lg: "/images/card-img-6.jpg",
          md: "/images/card-img-6.jpg",
          sm: "/images/card-img-6.jpg",
          xs: "/images/card-img-6.jpg",
        },
        date: "00/00/00",
        infobox: {
          subtitle:
            "Titolo del contenuto editoriale che andrà nella sezione News",
          paragraph:
            "Incipit del contenuto editoriale. Può essere parte dell’articolo originale oppure un’introduzione. Lorem ipsum dolor sit amet.",
          cta: {
            url: "#",
            title: "Leggi l’articolo",
            iconEnd: { name: "arrow-right" },
            variants: ["quaternary"],
          },
        },
        type: "type-2",
      },
    ],
  },
};

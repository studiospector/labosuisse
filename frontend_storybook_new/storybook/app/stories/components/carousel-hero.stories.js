// Storybook API
import { useEffect } from "@storybook/client-api";
// Okiba API
import Component from "@okiba/component";
// Component
import render from "../../views/components/carousel-hero.twig";
import CarouselHero from "../../scripts/components/CarouselHero";
import Hero from "../../scripts/components/Hero";
import AnimationReveal from "../../scripts/components/AnimationReveal";

export default {
  title: "Components/Carousel Hero",
  decorators: [
    (Story) => {
      useEffect(() => {
        const app = new Component({
          el: document.body,
          components: [
            {
              selector: ".js-carousel-hero",
              type: CarouselHero,
            },
            {
              selector: ".js-hero",
              type: Hero,
            },
            {
              selector: ".js-animation-reveal",
              type: AnimationReveal,
              optional: true,
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
 * Right Infobox
 */
export const RightInfobox = {
  args: {
    slides: [
      {
        images: {
          original: "https://via.placeholder.com/2500x700",
          lg: "https://via.placeholder.com/2500x700",
          md: "https://via.placeholder.com/2500x700",
          sm: "https://via.placeholder.com/2500x700",
          xs: "https://via.placeholder.com/2500x700",
        },
        infoboxPosX: "right",
        infoboxPosY: "center",
        infobox: {
          tagline: "CRESCINA TRANSDERMIC RAPID-INTENSIVE",
          title: "Favorisce la crescita",
          paragraph:
            "Dal 1898 Labo investe nella ricerca di tecnologie all’avanguardia per sviluppare prodotti innovativi e brevettati, per la cura dei capelli e della pelle.",
          cta: {
            url: "#",
            title: "Scopri la linea",
            variants: ["secondary"],
          },
        },
        container: false,
        variants: ["large"],
      },
      {
        images: {
          original: "https://via.placeholder.com/2500x700",
          lg: "https://via.placeholder.com/2500x700",
          md: "https://via.placeholder.com/2500x700",
          sm: "https://via.placeholder.com/2500x700",
          xs: "https://via.placeholder.com/2500x700",
        },
        infoboxPosX: "right",
        infoboxPosY: "center",
        infobox: {
          tagline: "CRESCINA TRANSDERMIC RAPID-INTENSIVE",
          title: "Fisiologica dei Capelli",
          paragraph:
            "Dal 1898 Labo investe nella ricerca di tecnologie all’avanguardia per sviluppare prodotti innovativi e brevettati, per la cura dei capelli e della pelle.",
          cta: {
            url: "#",
            title: "Scopri la linea",
            variants: ["secondary"],
          },
        },
        container: false,
        variants: ["large"],
      },
      {
        images: {
          original: "https://via.placeholder.com/2500x700",
          lg: "https://via.placeholder.com/2500x700",
          md: "https://via.placeholder.com/2500x700",
          sm: "https://via.placeholder.com/2500x700",
          xs: "https://via.placeholder.com/2500x700",
        },
        infoboxPosX: "right",
        infoboxPosY: "center",
        infobox: {
          tagline: "Lorem Ipsum Dolor",
          title: "Lorem Ipsum",
          paragraph:
            "Dal 1898 Labo investe nella ricerca di tecnologie all’avanguardia per sviluppare prodotti innovativi e brevettati, per la cura dei capelli e della pelle.",
          cta: {
            url: "#",
            title: "Scopri la linea",
            variants: ["secondary"],
          },
        },
        container: false,
        variants: ["large"],
      },
    ],
  },
};

/**
 * Left Infobox
 */
export const LeftInfobox = {
  args: {
    slides: [
      {
        images: {
          original: "https://via.placeholder.com/2500x700",
          lg: "https://via.placeholder.com/2500x700",
          md: "https://via.placeholder.com/2500x700",
          sm: "https://via.placeholder.com/2500x700",
          xs: "https://via.placeholder.com/2500x700",
        },
        infoboxPosX: "left",
        infoboxPosY: "center",
        infobox: {
          tagline: "CRESCINA TRANSDERMIC RAPID-INTENSIVE",
          title: "Favorisce la crescita<br>fisiologica dei capelli",
          paragraph:
            "Dal 1898 Labo investe nella ricerca di tecnologie all’avanguardia per sviluppare prodotti innovativi e brevettati, per la cura dei capelli e della pelle.",
          cta: {
            url: "#",
            title: "Scopri la linea",
            variants: ["secondary"],
          },
        },
        container: false,
        variants: ["large"],
      },
      {
        images: {
          original: "https://via.placeholder.com/2500x700",
          lg: "https://via.placeholder.com/2500x700",
          md: "https://via.placeholder.com/2500x700",
          sm: "https://via.placeholder.com/2500x700",
          xs: "https://via.placeholder.com/2500x700",
        },
        infoboxPosX: "left",
        infoboxPosY: "center",
        infobox: {
          tagline: "CRESCINA TRANSDERMIC RAPID-INTENSIVE",
          title: "Favorisce la crescita<br>fisiologica dei capelli",
          cta: {
            url: "#",
            title: "Scopri la linea",
            variants: ["secondary"],
          },
        },
        container: false,
        variants: ["large"],
      },
    ],
  },
};

// Storybook API
import { useEffect } from "@storybook/client-api";
// Okiba API
import Component from "@okiba/component";
// Component
import render from "../../views/components/hero.twig";
import Hero from "../../scripts/components/Hero";
import AnimationReveal from "../../scripts/components/AnimationReveal";

export default {
  title: "Components/Hero",
  decorators: [
    (Story) => {
      useEffect(() => {
        const app = new Component({
          el: document.body,
          components: [
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

// Base Args
const baseArgs = {
  images: {
    original: "https://placehold.co/2500x520",
    lg: "https://placehold.co/2500x520",
    md: "https://placehold.co/800x500",
    sm: "https://placehold.co/800x500",
    xs: "https://placehold.co/800x500",
  },
  infobox: {
    title: "Le linee viso",
    paragraph:
      "Descrizione dell’universo in cui l’utente si trova. Breve overview delle tipologie di prodotto e trattamenti che potrà trovare. Il testo dovrà essere di minimo 2 e massimo 4 righe.",
    cta: {
      url: "#",
      title: "Scopri la linea",
      variants: ["secondary"],
    },
  },
};

/**
 * Left Infobox
 */
export const LeftInfobox = {
  args: {
    ...baseArgs,
    infoboxPosX: "left",
    infoboxPosY: "center",
    container: false,
    variants: ["medium"],
  },
};

/**
 * Left Bottom Infobox
 */
export const LeftBottomInfobox = {
  args: {
    ...baseArgs,
    infoboxPosX: "left",
    infoboxPosY: "bottom",
    container: false,
    variants: ["medium"],
  },
};

/**
 * Right Infobox
 */
export const RightInfobox = {
  args: {
    ...baseArgs,
    infoboxPosX: "right",
    infoboxPosY: "center",
    container: false,
    variants: ["medium"],
  },
};

/**
 * Right Bottom Infobox
 */
export const RightBottomInfobox = {
  args: {
    ...baseArgs,
    infoboxPosX: "right",
    infoboxPosY: "bottom",
    container: false,
    variants: ["medium"],
  },
};

/**
 * Center Infobox
 */
export const CenterInfobox = {
  args: {
    ...baseArgs,
    infobox: {
      tagline: "LABEL",
      title: "Linea Lifting",
      paragraph:
        "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore.",
      cta: {
        url: "#",
        title: "Scopri la linea",
        variants: ["secondary"],
      },
    },
    infoboxPosX: "center",
    infoboxPosY: "center",
    container: false,
    variants: ["large"],
  },
};

/**
 * Center Bottom Infobox
 */
export const CenterBottomInfobox = {
  args: {
    ...baseArgs,
    infobox: {
      tagline: "LABEL",
      title: "Linea Lifting",
      paragraph:
        "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore.",
      cta: {
        url: "#",
        title: "Scopri la linea",
        variants: ["secondary"],
      },
    },
    infoboxPosX: "center",
    infoboxPosY: "bottom",
    container: false,
    variants: ["large"],
  },
};

/**
 * Hero with White text
 */
export const HeroWithWhiteText = {
  args: {
    ...baseArgs,
    infobox: {
      tagline: "LABEL",
      title: "Linea Lifting",
      paragraph:
        "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore.",
      cta: {
        url: "#",
        title: "Scopri la linea",
        variants: ["secondary"],
      },
    },
    infoboxPosX: "left",
    infoboxPosY: "center",
    container: false,
    whiteText: true,
    variants: ["small"],
  },
};

/**
 * Image Infobox
 */
export const ImageInfobox = {
  args: {
    ...baseArgs,
    infobox: {
      image: "/images/crescina-logo.png",
      paragraph:
        "Descrizione dell’immagine e spiegazione del titolo. Questo testo può occupare fino a 5 o 6 righe, ma sarebbe ideale mantenerlo di tre. ",
      cta: {
        url: "#",
        title: "Scopri la linea",
        variants: ["secondary"],
      },
    },
    infoboxPosX: "left",
    infoboxPosY: "center",
    container: false,
    variants: ["small"],
  },
};

/**
 * Hero with Container
 */
export const HeroWithContainer = {
  args: {
    ...baseArgs,
    infobox: {
      image: "/images/crescina-logo.png",
      paragraph:
        "Descrizione dell’immagine e spiegazione del titolo. Questo testo può occupare fino a 5 o 6 righe, ma sarebbe ideale mantenerlo di tre. ",
      cta: {
        url: "#",
        title: "Scopri la linea",
        variants: ["secondary"],
      },
    },
    infoboxPosX: "left",
    infoboxPosY: "center",
    container: true,
    variants: ["small"],
  },
};

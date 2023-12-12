// Storybook API
import { useEffect } from "@storybook/client-api";
// Okiba API
import Component from "@okiba/component";
// Component
import render from "../../views/components/carousel-centered.twig";
import CarouselCentered from "../../scripts/components/CarouselCentered";

export default {
  title: "Components/Carousel Centered",
  decorators: [
    (Story) => {
      useEffect(() => {
        const app = new Component({
          el: document.body,
          components: [
            {
              selector: ".js-carousel-centered",
              type: CarouselCentered,
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
    title: "Le tappe fondamentali",
    items: [
      {
        images: {
          original: "https://placehold.co/560x410",
          lg: "https://placehold.co/560x410",
          md: "https://placehold.co/560x410",
          sm: "https://placehold.co/560x410",
          xs: "https://placehold.co/560x410",
        },
        subtitle: "1989: il primo lancio",
        text: "Lancio di Nicotenil Anti-Caduta, il primo trattamento cosmetico per prevenire la caduta dei capelli, con specifiche proprietà vasodilatatorie, sviluppate per stimolare la microcircolazione sanguigna del cuoio capelluto.",
      },
      {
        images: {
          original: "https://placehold.co/560x410",
          lg: "https://placehold.co/560x410",
          md: "https://placehold.co/560x410",
          sm: "https://placehold.co/560x410",
          xs: "https://placehold.co/560x410",
        },
        subtitle: "1989: il primo lancio",
        text: "Lancio di Nicotenil Anti-Caduta, il primo trattamento cosmetico per prevenire la caduta dei capelli, con specifiche proprietà vasodilatatorie, sviluppate per stimolare la microcircolazione sanguigna del cuoio capelluto.",
      },
      {
        images: {
          original: "https://placehold.co/560x410",
          lg: "https://placehold.co/560x410",
          md: "https://placehold.co/560x410",
          sm: "https://placehold.co/560x410",
          xs: "https://placehold.co/560x410",
        },
        subtitle: "1989: il primo lancio",
        text: "Lancio di Nicotenil Anti-Caduta, il primo trattamento cosmetico per prevenire la caduta dei capelli, con specifiche proprietà vasodilatatorie, sviluppate per stimolare la microcircolazione sanguigna del cuoio capelluto.",
      },
      {
        images: {
          original: "https://placehold.co/560x410",
          lg: "https://placehold.co/560x410",
          md: "https://placehold.co/560x410",
          sm: "https://placehold.co/560x410",
          xs: "https://placehold.co/560x410",
        },
        subtitle: "1989: il primo lancio",
        text: "Lancio di Nicotenil Anti-Caduta, il primo trattamento cosmetico per prevenire la caduta dei capelli, con specifiche proprietà vasodilatatorie, sviluppate per stimolare la microcircolazione sanguigna del cuoio capelluto.",
      },
      {
        images: {
          original: "https://placehold.co/560x410",
          lg: "https://placehold.co/560x410",
          md: "https://placehold.co/560x410",
          sm: "https://placehold.co/560x410",
          xs: "https://placehold.co/560x410",
        },
        subtitle: "1989: il primo lancio",
        text: "Lancio di Nicotenil Anti-Caduta, il primo trattamento cosmetico per prevenire la caduta dei capelli, con specifiche proprietà vasodilatatorie, sviluppate per stimolare la microcircolazione sanguigna del cuoio capelluto.",
      },
      {
        images: {
          original: "https://placehold.co/560x410",
          lg: "https://placehold.co/560x410",
          md: "https://placehold.co/560x410",
          sm: "https://placehold.co/560x410",
          xs: "https://placehold.co/560x410",
        },
        subtitle: "1989: il primo lancio",
        text: "Lancio di Nicotenil Anti-Caduta, il primo trattamento cosmetico per prevenire la caduta dei capelli, con specifiche proprietà vasodilatatorie, sviluppate per stimolare la microcircolazione sanguigna del cuoio capelluto.",
      },
    ],
  },
};

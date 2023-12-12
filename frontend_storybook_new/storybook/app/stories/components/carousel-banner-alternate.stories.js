// Storybook API
import { useEffect } from "@storybook/client-api";
// Okiba API
import Component from "@okiba/component";
// Component
import render from "../../views/components/carousel-banner-alternate.twig";
import BannerAlternate from "../../scripts/components/BannerAlternate";
import CarouselBannerAlternate from "../../scripts/components/CarouselBannerAlternate";
import AnimationReveal from "../../scripts/components/AnimationReveal";

export default {
  title: "Components/Carousel Banner Alternate",
  decorators: [
    (Story) => {
      useEffect(() => {
        const app = new Component({
          el: document.body,
          components: [
            {
              selector: ".js-banner-alternate",
              type: BannerAlternate,
            },
            {
              selector: ".js-carousel-banner-alternate",
              type: CarouselBannerAlternate,
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
        noContainer: true,
        images: {
          original: "https://placehold.co/570x400",
          lg: "https://placehold.co/570x400",
          md: "https://placehold.co/570x400",
          sm: "https://placehold.co/570x400",
          xs: "https://placehold.co/570x400",
        },
        infobox: {
          date: "00/00/00",
          subtitle: "Lorem ipsum dolor sit amet, consectetur",
          paragraph:
            "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.",
          cta: {
            url: "#",
            title: "Scopri di più",
            variants: ["tertiary"],
          },
          variants: ["alternate"],
        },
        variants: ["infobox-left", "infobox-centered"],
      },
      {
        noContainer: true,
        images: {
          original: "https://placehold.co/570x400",
          lg: "https://placehold.co/570x400",
          md: "https://placehold.co/570x400",
          sm: "https://placehold.co/570x400",
          xs: "https://placehold.co/570x400",
        },
        infobox: {
          date: "00/00/00",
          subtitle: "Lorem ipsum dolor sit amet, consectetur",
          paragraph:
            "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.",
          cta: {
            url: "#",
            title: "Scopri di più",
            variants: ["tertiary"],
          },
          variants: ["alternate"],
        },
        variants: ["infobox-left", "infobox-centered"],
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
        noContainer: true,
        images: {
          original: "https://placehold.co/570x400",
          lg: "https://placehold.co/570x400",
          md: "https://placehold.co/570x400",
          sm: "https://placehold.co/570x400",
          xs: "https://placehold.co/570x400",
        },
        infobox: {
          date: "00/00/00",
          subtitle: "Lorem ipsum dolor sit amet, consectetur",
          paragraph:
            "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.",
          cta: {
            url: "#",
            title: "Scopri di più",
            variants: ["tertiary"],
          },
          variants: ["alternate"],
        },
        variants: ["infobox-right", "infobox-centered"],
      },
      {
        noContainer: true,
        images: {
          original: "https://placehold.co/570x400",
          lg: "https://placehold.co/570x400",
          md: "https://placehold.co/570x400",
          sm: "https://placehold.co/570x400",
          xs: "https://placehold.co/570x400",
        },
        infobox: {
          date: "00/00/00",
          subtitle: "Lorem ipsum dolor sit amet, consectetur",
          paragraph:
            "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.",
          cta: {
            url: "#",
            title: "Scopri di più",
            variants: ["tertiary"],
          },
          variants: ["alternate"],
        },
        variants: ["infobox-right", "infobox-centered"],
      },
    ],
  },
};

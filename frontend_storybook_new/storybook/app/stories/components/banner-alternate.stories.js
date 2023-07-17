// Storybook API
import { useEffect } from "@storybook/client-api";
// Okiba API
import Component from "@okiba/component";
// Component
import render from "../../views/components/banner-alternate.twig";
import BannerAlternate from "../../scripts/components/BannerAlternate";
import AnimationReveal from "../../scripts/components/AnimationReveal";

export default {
  title: "Components/Banner Alternate",
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
              selector: ".js-animation-reveal",
              type: AnimationReveal,
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
    original: "https://via.placeholder.com/570x400",
    lg: "https://via.placeholder.com/570x400",
    md: "https://via.placeholder.com/570x400",
    sm: "https://via.placeholder.com/570x400",
    xs: "https://via.placeholder.com/570x400",
  },
  infobox: {
    tagline: "LOREM IPSUM",
    subtitle: "Lorem ipsum dolor sit amet, consectetur",
    paragraph:
      "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.<br>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.",
    cta: {
      url: "#",
      title: "Scopri di più",
      iconEnd: { name: "arrow-right" },
      variants: ["quaternary"],
    },
    variants: ["alternate"],
  },
  imageBig: false,
  // variants: [], // infobox-left, infobox-right AND infobox-fullheight, infobox-centered, infobox-bottom
};

/**
 * Right Infobox Full Height
 */
export const RightInfoboxFullHeight = {
  args: {
    ...baseArgs,
    variants: ["infobox-right", "infobox-fullheight"],
  },
};

/**
 * Left Infobox Full Height
 */
export const LeftInfoboxFullHeight = {
  args: {
    ...baseArgs,
    variants: ["infobox-left", "infobox-fullheight"],
  },
};

/**
 * Right Infobox Centered
 */
export const RightInfoboxCentered = {
  args: {
    ...baseArgs,
    infobox: {
      subtitle: "Lorem ipsum dolor sit amet, consectetur",
      paragraph:
        "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.<br>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.",
      variants: ["alternate"],
    },
    variants: ["infobox-right", "infobox-centered"],
  },
};

/**
 * Left Infobox Centered
 */
export const LeftInfoboxCentered = {
  args: {
    ...baseArgs,
    infobox: {
      subtitle: "Lorem ipsum dolor sit amet, consectetur",
      paragraph:
        "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.<br>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.",
      variants: ["alternate"],
    },
    variants: ["infobox-left", "infobox-centered"],
  },
};

/**
 * Right Infobox Image big
 */
export const RightInfoboxImageBig = {
  args: {
    ...baseArgs,
    infobox: {
      tagline: "Lorem Ipsum",
      subtitle: "Linea Solari",
      paragraph:
        "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.",
      paragraph_small:
        "Promozione valida, nei punti vendita aderenti. Fino a esaurimento scorte",
      cta: {
        url: "#",
        title: "Scopri di più",
        variants: ["tertiary"],
      },
      variants: ["alternate"],
    },
    imageBig: true,
    variants: ["infobox-right", "infobox-bottom"],
  },
};

/**
 * Left Infobox Image big
 */
export const LeftInfoboxImageBig = {
  args: {
    ...baseArgs,
    infobox: {
      tagline: "Lorem Ipsum",
      subtitle: "Linea Solari",
      paragraph:
        "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.",
      paragraph_small:
        "Promozione valida, nei punti vendita aderenti. Fino a esaurimento scorte",
      cta: {
        url: "#",
        title: "Scopri di più",
        variants: ["tertiary"],
      },
      variants: ["alternate"],
    },
    imageBig: true,
    variants: ["infobox-left", "infobox-bottom"],
  },
};

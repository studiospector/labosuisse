// Storybook API
import { useEffect } from "@storybook/client-api";
// Okiba API
import Component from "@okiba/component";
// Component
import render from "../../views/components/banner.twig";
import AnimationReveal from "../../scripts/components/AnimationReveal";

export default {
  title: "Components/Banner",
  decorators: [
    (Story) => {
      useEffect(() => {
        const app = new Component({
          el: document.body,
          components: [
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
    original: "https://placehold.co/1200x400",
    lg: "https://placehold.co/1200x400",
    md: "https://placehold.co/1200x400",
    sm: "https://placehold.co/1200x400",
    xs: "https://placehold.co/1200x400",
  },
  infoboxBgColorTransparent: false, // true, false
  infoboxTextAlignment: "left", // left, right, center
  infobox: {
    tagline: "LOREM IPSUM",
    subtitle: "Lorem ipsum dolor sit amet, consectetur",
    paragraph:
      "Lorem ipsum dolor sit amet, consectetur<br>adipiscing elit, sed do eiusmod tempor incididunt<br>ut labore et dolore magna.",
  },
  // variants: ['left'], // left, right, center
};

/**
 * Left Infobox
 */
export const LeftInfobox = {
  args: {
    ...baseArgs,
    variants: ["left"],
  },
};

/**
 * Left Infobox with CTA
 */
export const LeftInfoboxWithCTA = {
  args: {
    ...baseArgs,
    infobox: {
      tagline: "LOREM IPSUM",
      subtitle: "Lorem ipsum dolor sit amet, consectetur",
      paragraph:
        "Lorem ipsum dolor sit amet, consectetur<br>adipiscing elit, sed do eiusmod tempor incididunt<br>ut labore et dolore magna. ",
      cta: {
        url: "#",
        title: "CALL TO ACTION",
        variants: ["secondary"],
      },
    },
    variants: ["left"],
  },
};

/**
 * Right Infobox
 */
export const RightInfobox = {
  args: {
    ...baseArgs,
    variants: ["right"],
  },
};

/**
 * Right Infobox with CTA
 */
export const RightInfoboxWithCTA = {
  args: {
    ...baseArgs,
    infobox: {
      tagline: "LOREM IPSUM",
      subtitle: "Lorem ipsum dolor sit amet, consectetur",
      paragraph:
        "Lorem ipsum dolor sit amet, consectetur<br>adipiscing elit, sed do eiusmod tempor incididunt<br>ut labore et dolore magna. ",
      cta: {
        url: "#",
        title: "CALL TO ACTION",
        variants: ["secondary"],
      },
    },
    variants: ["right"],
  },
};

/**
 * Center Infobox
 */
export const CenterInfobox = {
  args: {
    ...baseArgs,
    infoboxTextAlignment: "center",
    variants: ["center"],
  },
};

/**
 * Center Infobox with CTA
 */
export const CenterInfoboxWithCTA = {
  args: {
    ...baseArgs,
    infoboxTextAlignment: "center",
    infobox: {
      tagline: "LOREM IPSUM",
      subtitle: "Lorem ipsum dolor sit amet, consectetur",
      paragraph:
        "Lorem ipsum dolor sit amet, consectetur<br>adipiscing elit, sed do eiusmod tempor incididunt<br>ut labore et dolore magna. ",
      cta: {
        url: "#",
        title: "CALL TO ACTION",
        variants: ["secondary"],
      },
    },
    variants: ["center"],
  },
};

/**
 * Infobox with Text left
 */
export const InfoboxWithTextLeft = {
  args: {
    ...baseArgs,
    infoboxTextAlignment: "left",
  },
};

/**
 * Infobox with Text right
 */
export const InfoboxWithTextRight = {
  args: {
    ...baseArgs,
    infoboxTextAlignment: "right",
  },
};

/**
 * Infobox with Text center
 */
export const InfoboxWithTextCenter = {
  args: {
    ...baseArgs,
    infoboxTextAlignment: "center",
  },
};

/**
 * Transparent Infobox
 */
export const TransparentInfobox = {
  args: {
    ...baseArgs,
    infoboxBgColorTransparent: true,
    variants: ["left"],
  },
};

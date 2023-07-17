// Storybook API
import { useEffect } from "@storybook/client-api";
// Okiba API
import Component from "@okiba/component";
// Component
import render from "../../views/components/number-list-with-image.twig";
import AnimationReveal from "../../scripts/components/AnimationReveal";

export default {
  title: "Components/Number List with Image",
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

/**
 * Default
 */
export const Default = {
  args: {
    images: {
      original: "https://via.placeholder.com/570x420",
      lg: "https://via.placeholder.com/570x420",
      md: "https://via.placeholder.com/570x420",
      sm: "https://via.placeholder.com/570x420",
      xs: "https://via.placeholder.com/570x420",
    },
    numbersList: {
      title: "Tre consigli utili",
      list: [
        {
          title: "",
          text: "Si consiglia un trattamento di almeno 2 mesi.<br>Può essere ripetuto più volte l’anno.",
        },
        {
          title: "",
          text: "Utilizza Crescina sul cuoio capelluto<br>completamente integro, senza escoriazioni.",
        },
        {
          title: "",
          text: "Regola le tue abitudini di detersione:<br>lava i capelli almeno due volte a settimana.",
        },
      ],
      variants: ["vertical"],
    },
  },
};

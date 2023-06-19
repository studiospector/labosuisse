// Component
import render from "../../views/components/checklist.twig";

export default {
  title: "Components/Checklist",
  render: ({ ...args }) => {
    return render(args);
  },
};

/**
 * Default
 */
export const Default = {
  args: {
    items: [
      {
        title: "Analisi del capello e del cuoio capelluto",
      },
      {
        title: "Analisi della pelle",
      },
      {
        title: "Prova Labo Make Up",
      },
      {
        title: "Prova dei trattamenti",
      },
      {
        title: "Appuntamenti di controllo",
      },
      {
        title: "Promozioni e distribuzione campioncini",
      },
    ],
  },
};

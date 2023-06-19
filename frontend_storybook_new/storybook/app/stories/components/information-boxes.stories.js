// Component
import render from "../../views/components/information-boxes.twig";

export default {
  title: "Components/Information boxes",
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
        subtitle: "SPEDIZIONI RAPIDE",
        paragraph:
          "Se decidi di spedire in Italia, puoi scegliere di ricevere il tuo ordine con modalità Express ed averlo in 2 giorni.",
      },
      {
        subtitle: "TRACCIAMENTI SICURI",
        paragraph:
          "Puoi controllare in qualsiasi momento lo stato del tuo ordine dal tuo account personale o da un link in mail.",
        cta: {
          url: "#",
          title: "Più informazioni",
          variants: ["secondary"],
        },
      },
      {
        subtitle: "RESI GRATUITI",
        paragraph:
          "Se i tuoi prodotti non ti soddisfano, le nostre politiche ti aiuteranno con un reso facile e veloce.",
      },
    ],
  },
};

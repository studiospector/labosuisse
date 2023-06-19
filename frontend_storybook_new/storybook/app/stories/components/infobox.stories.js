// Component
import render from "../../views/components/infobox.twig";

export default {
  title: "Components/Infobox",
  render: ({ ...args }) => {
    return render(args);
  },
};

/**
 * Default
 */
export const Default = {
  args: {
    image: "/images/crescina-logo.png",
    tagline: "CRESCINA TRANSDERMIC RAPID-INTENSIVE",
    title: "Favorisce la crescita<br>fisiologica dei capelli",
    subtitle: "Labo suisse: ricerca e innovazione",
    paragraph:
      "Dal 1898 Labo investe nella ricerca di tecnologie all’avanguardia per sviluppare prodotti innovativi e brevettati, per la cura dei capelli e della pelle.",
    paragraph_small:
      "Promozione valida, nei punti vendita aderenti. Fino a esaurimento scorte",
    cta: {
      url: "#",
      title: "Scopri la linea",
      variants: ["primary"],
    },
  },
};

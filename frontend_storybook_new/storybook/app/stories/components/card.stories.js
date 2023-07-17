// Component
import render from "../../views/components/card.twig";

export default {
  title: "Components/Cards",
  decorators: [
    (Story) => {
      return `
        <div style="max-width: 700px;">
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
 * Type 1 Lifting
 */
export const Type1Lifting = {
  args: {
    images: {
      original: "/images/card-img-4.jpg",
      lg: "/images/card-img-4.jpg",
      md: "/images/card-img-4.jpg",
      sm: "/images/card-img-4.jpg",
      xs: "/images/card-img-4.jpg",
    },
    infobox: {
      tagline: "TRATTAMENTO FACCIALE",
      subtitle: "Lifting facciale: primo terzo del viso, sottomento e collo",
      cta: {
        url: "#",
        title: "Scopri di più",
        iconEnd: { name: "arrow-right" },
        variants: ["quaternary"],
      },
    },
    type: "type-1",
    variants: null,
  },
};

/**
 * Type 1 Lifting Secondary
 */
export const Type1LiftingSecondary = {
  args: {
    images: {
      original: "/images/card-img-4.jpg",
      lg: "/images/card-img-4.jpg",
      md: "/images/card-img-4.jpg",
      sm: "/images/card-img-4.jpg",
      xs: "/images/card-img-4.jpg",
    },
    infobox: {
      tagline: "TRATTAMENTO FACCIALE",
      subtitle: "Lifting facciale: primo terzo del viso, sottomento e collo",
      paragraph:
        "Incipit del contenuto editoriale. Può essere parte dell’articolo originale oppure un’introduzione. Lorem ipsum dolor sit amet.",
      cta: {
        url: "#",
        title: "Scopri di più",
        iconEnd: { name: "arrow-right" },
        variants: ["quaternary"],
      },
    },
    type: "type-1-secondary",
    variants: null,
  },
};

/**
 * Type 2 News
 */
export const Type2News = {
  args: {
    images: {
      original: "/images/card-img-6.jpg",
      lg: "/images/card-img-6.jpg",
      md: "/images/card-img-6.jpg",
      sm: "/images/card-img-6.jpg",
      xs: "/images/card-img-6.jpg",
    },
    date: "00/00/00",
    infobox: {
      subtitle: "Titolo del contenuto editoriale che andrà nella sezione News",
      paragraph:
        "Incipit del contenuto editoriale. Può essere parte dell’articolo originale oppure un’introduzione. Lorem ipsum dolor sit amet.",
      cta: {
        url: "#",
        title: "Leggi l’articolo",
        iconEnd: { name: "arrow-right" },
        variants: ["quaternary"],
      },
    },
    type: "type-2",
    variants: null,
  },
};

/**
 * Type 3 Trattamenti
 */
export const Type3Trattamenti = {
  args: {
    images: {
      original: "/images/card-img-5.jpg",
      lg: "/images/card-img-5.jpg",
      md: "/images/card-img-5.jpg",
      sm: "/images/card-img-5.jpg",
      xs: "/images/card-img-5.jpg",
    },
    infobox: {
      subtitle: "Magnetic Eyes",
      paragraph:
        "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.",
      cta: {
        url: "#",
        title: "Scopri di più",
        iconEnd: { name: "arrow-right" },
        variants: ["quaternary"],
      },
    },
    type: "type-3",
    variants: null,
  },
};

/**
 * Type 5 About
 */
export const Type5About = {
  args: {
    images: {
      original: "/images/card-img-7.jpg",
      lg: "/images/card-img-7.jpg",
      md: "/images/card-img-7.jpg",
      sm: "/images/card-img-7.jpg",
      xs: "/images/card-img-7.jpg",
    },
    infobox: {
      subtitle: "1989: il primo lancio",
      paragraph:
        "Lancio di Nicotenil Anti-Caduta, il primo trattamento cosmetico per prevenire la caduta dei capelli, con specifiche proprietà vasodilatatorie, sviluppate per stimolare la microcircolazione sanguigna del cuoio capelluto.",
    },
    type: "type-5",
    variants: null,
  },
};

/**
 * Type 6 Magazine
 */
export const Type6Magazine = {
  args: {
    images: {
      original: "/images/card-img-6.jpg",
      lg: "/images/card-img-6.jpg",
      md: "/images/card-img-6.jpg",
      sm: "/images/card-img-6.jpg",
      xs: "/images/card-img-6.jpg",
    },
    date: "00/00/00",
    infobox: {
      image: "/images/crescina-logo.png",
      paragraph:
        "Corriere della sera - Il Giorno - La Repubblica - Il Messaggero - Il Sole 24 Ore - Grazia",
      cta: {
        url: "#",
        title: "Visualizza",
        iconEnd: { name: "arrow-right" },
        variants: ["quaternary"],
      },
    },
    type: "type-6",
    variants: null,
  },
};

/**
 * Type 7 FAQ
 */
export const Type7FAQ = {
  args: {
    images: {
      original: "/images/card-img-6.jpg",
      lg: "/images/card-img-6.jpg",
      md: "/images/card-img-6.jpg",
      sm: "/images/card-img-6.jpg",
      xs: "/images/card-img-6.jpg",
    },
    infobox: {
      image: "/images/crescina-logo.png",
      subtitle: "La tecnologia dietro l’efficacia",
      items: [
        "Il trattamento Fillerina può essere utilizzato anche per le rughe del labbro superiore della bocca?",
        "Si possono effettuare pulizia viso o lampade solari durante il trattamento?",
        "Ci si può esporre al sole durante il trattamento?",
        "Si può utilizzare Fillerina nei periodi di gravidanza o in allattamento?",
      ],
      cta: {
        url: "#",
        title: "Scopri di più",
        iconEnd: { name: "arrow-right" },
        variants: ["quaternary"],
      },
    },
    type: "type-7",
    variants: null,
  },
};

/**
 * Type 8 Colored
 */
export const Type8Colored = {
  args: {
    color: "#E6D4B0",
    infobox: {
      subtitle: "LABO TRANSDERMIC",
      paragraph:
        "Una nuova generazione di skincare routine grazie alla Tecnologia Transdermica, innovazione mondiale che punta sulla penetrazione profonda dei principi attivi.",
      cta: {
        url: "#",
        title: "Scopri il brand",
        iconEnd: { name: "arrow-right" },
        variants: ["quaternary"],
      },
    },
    type: "type-8",
    variants: null,
  },
};

/**
 * Type 9 Job
 */
export const Type9Job = {
  args: {
    infobox: {
      subtitle: "Agenti mono o plurimandatari (farmacie)",
      location: {
        isHeadquarter: true,
        label: "Headquarter (Padova)",
      },
      scope: {
        label: "Ambito:",
        value: "Ampliare la propria storica divisione commercale",
      },
      paragraph:
        "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque quis nunc felis. Sed at ligula diam.",
      cta: {
        url: "#",
        title: "Leggi di più",
        variants: ["quaternary"],
      },
    },
    type: "type-9",
    variants: null,
  },
};

/**
 * Type 10 Brand
 */
export const Type10Brand = {
  args: {
    images: {
      original: "/images/card-img-6.jpg",
      lg: "/images/card-img-6.jpg",
      md: "/images/card-img-6.jpg",
      sm: "/images/card-img-6.jpg",
      xs: "/images/card-img-6.jpg",
    },
    infobox: {
      subtitle: "Labo Transdermic",
      paragraph:
        "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque quis nunc felis. Sed at ligula diam.",
      cta: {
        url: "#",
        title: "Vai al brand",
        variants: ["quaternary"],
      },
    },
    type: "type-10",
    variants: null,
  },
};

/**
 * Type Loading
 */
export const TypeLoading = {
  args: {
    type: "loading",
  },
};

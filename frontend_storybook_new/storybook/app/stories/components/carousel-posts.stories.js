// Storybook API
import { useEffect } from "@storybook/client-api";
// Okiba API
import Component from "@okiba/component";
// Component
import render from "../../views/components/carousel-posts.twig";
import CarouselPosts from "../../scripts/components/CarouselPosts";
import AnimationReveal from "../../scripts/components/AnimationReveal";

export default {
  title: "Components/Carousel Posts",
  decorators: [
    (Story) => {
      useEffect(() => {
        const app = new Component({
          el: document.body,
          components: [
            {
              selector: ".js-carousel-posts",
              type: CarouselPosts,
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
  leftCard: {
    // color: '#f5f5f5',
    infobox: {
      subtitle: "Titolo di due righe come massimo",
      paragraph:
        "Nella sezione News, puoi trovare i nostri contenuti editoriali: sfoglia gli articoli, prendi ispirazione e lasciati guidare dal team Labo.",
      cta: {
        url: "#",
        title: "Vai a news",
        variants: ["secondary"],
      },
    },
    type: "type-8",
  },
  items: [
    {
      images: {
        original: "/images/card-img-6.jpg",
        lg: "/images/card-img-6.jpg",
        md: "/images/card-img-6.jpg",
        sm: "/images/card-img-6.jpg",
        xs: "/images/card-img-6.jpg",
      },
      date: "00/00/00",
      infobox: {
        subtitle:
          "Titolo del contenuto editoriale che andrà nella sezione News",
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
    },
    {
      images: {
        original: "/images/card-img-6.jpg",
        lg: "/images/card-img-6.jpg",
        md: "/images/card-img-6.jpg",
        sm: "/images/card-img-6.jpg",
        xs: "/images/card-img-6.jpg",
      },
      date: "00/00/00",
      infobox: {
        subtitle:
          "Titolo del contenuto editoriale che andrà nella sezione News",
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
    },
    {
      images: {
        original: "/images/card-img-6.jpg",
        lg: "/images/card-img-6.jpg",
        md: "/images/card-img-6.jpg",
        sm: "/images/card-img-6.jpg",
        xs: "/images/card-img-6.jpg",
      },
      date: "00/00/00",
      infobox: {
        subtitle:
          "Titolo del contenuto editoriale che andrà nella sezione News",
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
    },
  ],
};

/**
 * Full
 */
export const Full = {
  args: {
    ...baseArgs,
    variants: ["full"],
  },
};

/**
 * One Post per Slide
 */
export const OnePostPerSlide = {
  args: {
    ...baseArgs,
    variants: ["one-post"],
  },
};

/**
 * Two Posts per Slide
 */
export const TwoPostsPerSlide = {
  args: {
    ...baseArgs,
    variants: ["two-posts"],
  },
};
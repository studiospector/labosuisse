import render from "../../views/components/button.twig";

export default {
  title: "UIKIT/Buttons",
  render: ({ ...args }) => {
    return render(args);
  },
};

/**
 * Primary
 */
export const Primary = {
  args: { url: "#", title: "Lorem ipsum", variants: ["primary"] },
};

export const PrimaryDisabled = {
  args: {
    url: "#",
    title: "Lorem ipsum",
    variants: ["primary"],
    attributes: ["disabled"],
  },
};

export const PrimaryIcon = {
  args: {
    url: "#",
    title: "Lorem ipsum",
    iconEnd: { name: "arrow-right" },
    variants: ["primary"],
  },
};

/**
 * Secondary
 */
export const Secondary = {
  args: { url: "#", title: "Lorem ipsum", variants: ["secondary"] },
};

export const SecondaryDisabled = {
  args: {
    url: "#",
    title: "Lorem ipsum",
    variants: ["secondary"],
    attributes: ["disabled"],
  },
};

export const SecondaryIcon = {
  args: {
    url: "#",
    title: "Lorem ipsum",
    iconEnd: { name: "arrow-right" },
    variants: ["secondary"],
  },
};

/**
 * Tertiary
 */
export const Tertiary = {
  args: { url: "#", title: "Lorem ipsum", variants: ["tertiary"] },
};

export const TertiaryDisabled = {
  args: {
    url: "#",
    title: "Lorem ipsum",
    variants: ["tertiary"],
    attributes: ["disabled"],
  },
};

export const TertiaryIcon = {
  args: {
    url: "#",
    title: "Lorem ipsum",
    iconEnd: { name: "arrow-right" },
    variants: ["tertiary"],
  },
};

/**
 * Link
 */
export const Link = {
  args: { url: "#", title: "Lorem ipsum", variants: ["link"] },
};

export const LinkThin = {
  args: { url: "#", title: "Lorem ipsum", variants: ["link", "thin"] },
};

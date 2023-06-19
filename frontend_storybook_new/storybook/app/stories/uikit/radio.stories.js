import render from "../../views/components/fields/radio.twig";

export default {
  title: "UIKIT/Radio",
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
 * Vertical
 */
export const Vertical = {
  args: {
    name: "lorem-ipsum",
    label: "Lorem Ipsum",
    options: [
      { label: "Option 1", value: "option-1", checked: true },
      { label: "Option 2", value: "option-2" },
      { label: "Option 3", value: "option-3" },
      { label: "Option 4", value: "option-4" },
    ],
    variants: ["vertical"],
  },
};

/**
 * Horizontal
 */
export const Horizontal = {
  args: {
    name: "lorem-ipsum",
    label: "Lorem Ipsum",
    options: [
      { label: "Option 1", value: "option-1", checked: true },
      { label: "Option 2", value: "option-2" },
      { label: "Option 3", value: "option-3" },
      { label: "Option 4", value: "option-4" },
    ],
    variants: ["horizontal"],
  },
};

/**
 * Disabled
 */
export const Disabled = {
  args: {
    name: "lorem-ipsum",
    label: "Lorem Ipsum",
    options: [
      { label: "Option 1", value: "option-1" },
      { label: "Option 2", value: "option-2", disabled: true },
      { label: "Option 3", value: "option-3", disabled: true },
      { label: "Option 4", value: "option-4" },
    ],
    variants: ["vertical"],
  },
};

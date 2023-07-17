import render from "../../views/components/fields/checkbox.twig";

export default {
  title: "UIKIT/Checkbox",
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
      { label: "Option 1", name: "option-1", value: 1 },
      { label: "Option 2", name: "option-2", value: 2 },
      { label: "Option 3", name: "option-3", value: 3 },
      { label: "Option 4", name: "option-4", value: 3 },
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
      { label: "Option 1", name: "option-1", value: 1 },
      { label: "Option 2", name: "option-2", value: 2 },
      { label: "Option 3", name: "option-3", value: 3 },
      { label: "Option 4", name: "option-4", value: 3 },
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
      { label: "Option 1", name: "option-1", value: 1 },
      { label: "Option 2", name: "option-2", value: 2, disabled: true },
      { label: "Option 3", name: "option-3", value: 3, disabled: true },
      { label: "Option 4", name: "option-4", value: 3 },
    ],
    variants: ["vertical"],
  },
};

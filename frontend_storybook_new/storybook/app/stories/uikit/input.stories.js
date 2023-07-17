// Storybook API
import { useEffect } from "@storybook/client-api";
// Okiba API
import Component from "@okiba/component";
// Component
import LBCustomInput from "../../scripts/components/CustomInput";
import render from "../../views/components/fields/input.twig";

export default {
  title: "UIKIT/Input",
  decorators: [
    (Story) => {
      useEffect(() => {
        const app = new Component({
          el: document.body,
          components: [
            {
              selector: ".js-custom-input",
              type: LBCustomInput,
            },
          ],
        });

        return () => app.destroy();
      }, []);

      return `
        <div style="max-width: 300px;">
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
  id: "lorem-ipsum",
  name: "lorem-ipsum",
  label: "Test Label",
  disabled: false,
  required: false,
};

/**
 * Default
 */
export const Default = {
  args: {
    ...baseArgs,
    variants: ["default"],
  },
};

/**
 * Default Disabled
 */
export const DefaultDisabled = {
  args: {
    ...baseArgs,
    disabled: true,
    variants: ["default"],
  },
};

/**
 * Primary
 */
export const Primary = {
  args: {
    ...baseArgs,
    variants: ["primary"],
  },
};

/**
 * Primary Disabled
 */
export const PrimaryDisabled = {
  args: {
    ...baseArgs,
    disabled: true,
    variants: ["primary"],
  },
};

/**
 * Secondary
 */
export const Secondary = {
  args: {
    ...baseArgs,
    variants: ["secondary"],
  },
};

/**
 * Secondary Disabled
 */
export const SecondaryDisabled = {
  args: {
    ...baseArgs,
    disabled: true,
    variants: ["secondary"],
  },
};

/**
 * Tertiary
 */
export const Tertiary = {
  args: {
    ...baseArgs,
    variants: ["tertiary"],
  },
};

/**
 * Tertiary Disabled
 */
export const TertiaryDisabled = {
  args: {
    ...baseArgs,
    disabled: true,
    variants: ["tertiary"],
  },
};

/**
 * Search
 */
export const Search = {
  args: {
    ...baseArgs,
    type: "search",
    label: "Search",
    variants: ["primary"],
  },
};

/**
 * Search Secondary
 */
export const SearchSecondary = {
  args: {
    ...baseArgs,
    type: "search",
    label: "Search",
    variants: ["secondary"],
  },
};

/**
 * Search Tertiary
 */
export const SearchTertiary = {
  args: {
    ...baseArgs,
    type: "search",
    label: "Search",
    buttonTypeNext: "submit",
    buttonVariantNext: "primary",
    variants: ["tertiary"],
  },
};

/**
 * Email
 */
export const Email = {
  args: {
    ...baseArgs,
    type: "email",
    label: "Email",
    variants: ["primary"],
  },
};

/**
 * Password
 */
export const Password = {
  args: {
    ...baseArgs,
    type: "password",
    label: "Password",
    variants: ["primary"],
  },
};

/**
 * Datepicker
 */
export const Datepicker = {
  args: {
    ...baseArgs,
    type: "date",
    label: "Datepicker",
    variants: ["default"],
  },
};

/**
 * Number
 */
export const Number = {
  args: {
    ...baseArgs,
    type: "number",
    label: null,
    step: 1,
    min: 1,
    max: 5,
    value: 1,
    variants: ["secondary"],
  },
};

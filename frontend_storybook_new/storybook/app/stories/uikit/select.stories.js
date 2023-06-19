// Storybook API
import { useEffect } from '@storybook/client-api'
// Okiba API
import Component from '@okiba/component'
// Component
import LBCustomSelect from '../../scripts/components/CustomSelect'
import render from '../../views/components/fields/select.twig'

export default {
  title: "UIKIT/Select",
  decorators: [
    (Story) => {
      useEffect(() => {
        const app = new Component({
          el: document.body,
          components: [
            {
              selector: ".js-custom-select",
              type: LBCustomSelect,
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
  id: 'test-select',
  label: 'Test label',
  placeholder: 'Test placeholder',
  multiple: false,
  required: false,
  disabled: false,
  options: [
    {
      value: 'lorem',
      label: 'Lorem',
    },
    {
      value: 'ipsum',
      label: 'Ipsum',
    },
    {
      value: 'amet',
      label: 'Amet',
    },
  ],
}

/**
 * Default
 */
export const Default = {
  args: {
    ...baseArgs,
    variants: ['default']
  }
};

/**
 * Default Multiple
 */
export const DefaultMultiple = {
  args: {
    ...baseArgs,
    multiple: true,
    variants: ['default']
  }
};

/**
 * Default Disabled
 */
export const DefaultDisabled = {
  args: {
    ...baseArgs,
    disabled: true,
    variants: ['default']
  }
};

/**
 * Primary
 */
export const Primary = {
  args: {
    ...baseArgs,
    confirmBtnLabel: 'Applica',
    variants: ['primary']
  }
};

/**
 * Primary Multiple
 */
export const PrimaryMultiple = {
  args: {
    ...baseArgs,
    multiple: true,
    confirmBtnLabel: 'Applica',
    variants: ['primary']
  }
};

/**
 * Primary Disabled
 */
export const PrimaryDisabled = {
  args: {
    ...baseArgs,
    disabled: true,
    confirmBtnLabel: 'Applica',
    variants: ['primary']
  }
};

/**
 * Secondary
 */
export const Secondary = {
  args: {
    ...baseArgs,
    variants: ['secondary']
  }
};

/**
 * Secondary Multiple
 */
export const SecondaryMultiple = {
  args: {
    ...baseArgs,
    multiple: true,
    variants: ['secondary']
  }
};

/**
 * Secondary Disabled
 */
export const SecondaryDisabled = {
  args: {
    ...baseArgs,
    disabled: true,
    variants: ['secondary']
  }
};

/**
 * Tertiary
 */
export const Tertiary = {
  args: {
    ...baseArgs,
    variants: ['tertiary']
  }
};

/**
 * Tertiary Multiple
 */
export const TertiaryMultiple = {
  args: {
    ...baseArgs,
    multiple: true,
    variants: ['tertiary']
  }
};

/**
 * Tertiary Disabled
 */
export const TertiaryDisabled = {
  args: {
    ...baseArgs,
    disabled: true,
    variants: ['tertiary']
  }
};

/**
 * Quaternary
 */
export const Quaternary = {
  args: {
    ...baseArgs,
    placeholder: null,
    variants: ['quaternary']
  }
};

/**
 * Quaternary Multiple
 */
export const QuaternaryMultiple = {
  args: {
    ...baseArgs,
    placeholder: null,
    multiple: true,
    variants: ['quaternary']
  }
};

/**
 * Quaternary Disabled
 */
export const QuaternaryDisabled = {
  args: {
    ...baseArgs,
    placeholder: null,
    disabled: true,
    variants: ['quaternary']
  }
};

/**
 * Colors
 */
export const Colors = {
  args: {
    ...baseArgs,
    options: [
      {
        value: 'grey',
        label: 'Grigio',
        color: 'ccc',
      },
      {
        value: 'black',
        label: 'Nero',
        color: '000',
      },
      {
        value: 'bisque',
        label: 'Bisque',
        color: 'ffe4c4',
      },
    ],
    variants: ['tertiary']
  }
};

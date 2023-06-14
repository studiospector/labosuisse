module.exports = {
  env: {
    browser: true,
    commonjs: true,
    es2021: true,
  },
  extends: ['airbnb-base', 'plugin:react/recommended'],
  overrides: [
  ],
  parserOptions: {
    ecmaVersion: 'latest',
  },
  rules: {
    'no-alert': 0,
    'no-mixed-operators': 0,
    'import/extensions': 0,
    'no-shadow': 0,
    'no-plusplus': 0,
    'no-bitwise': 0,
    'no-use-before-define': 0,
    'no-continue': 0,
    'no-param-reassign': 0,
    'func-names': 0,
    'no-nested-ternary': 0,
  },
};

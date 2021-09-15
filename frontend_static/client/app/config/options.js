const options = {
  production: process.env.NODE_ENV === 'production',
  i18n: false,
  templateEngine: 'twig',
}

// options.i18n = {
//   locales: ['en', 'it'],
//   defaultLocale: 'en',
//   updateFiles: false,
//   syncFiles: false,
// };

module.exports = options

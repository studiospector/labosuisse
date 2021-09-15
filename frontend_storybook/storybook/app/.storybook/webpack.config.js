const path = require('path');

module.exports = async ({ config, mode }) => {
  // `mode` has a value of 'DEVELOPMENT' or 'PRODUCTION'
  // You can change the configuration based on that.
  // 'PRODUCTION' is used when building the static version of storybook.

  // Make whatever fine-grained changes you need
  // config.resolve.alias.assets = path.resolve(__dirname, '../common/assets/optimized/');

  // Return the altered config

  if (!config.module.rules) config.module.rules = []

  config.module.rules.push({
    test: /\.njk$/i,
    use: 'raw-loader',
  })

  config.module.rules.push({
    test: /\.twig$/i,
    use: 'twig-loader'
  })

  config.module.rules.push({
    test: /\.js$/,
        include: [/node_modules\/@okiba\/.*/],
        use: {
          loader: 'babel-loader',
          options: {
            presets: [
              [
                '@babel/preset-env',
                {
                  debug: true
                }
              ]
            ],
            plugins: [
              [
                '@babel/plugin-proposal-decorators', {
                  legacy: true
                }
              ],
              '@babel/plugin-proposal-class-properties',
              '@babel/plugin-syntax-dynamic-import'
            ]
          }
        }
  })

  config.module.rules.push({
    test: /\.scss$/,
    loaders: ['style-loader', 'css-loader', 'sass-loader'],
    include: path.resolve(__dirname, '../'),
  });

  delete config.resolve.alias['core-js'];

  return config;
};

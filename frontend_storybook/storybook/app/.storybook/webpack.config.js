const path = require('path');

const LEGACY_REGEXP = /^(\w+)::/;

/**
 * Transforms legacy namespace::template/path to @namespoace/template/path
 */
class LegacyNsResolverPlugin {
  apply(resolver) {
    const target = resolver.ensureHook('resolve');
    resolver
      .getHook('resolve')
      .tapAsync('LegacyNsResolverPlugin', (request, resolveContext, callback) => {
        const requestPath = request.request;
        if (!requestPath.match(LEGACY_REGEXP)) {
          callback();
          return;
        }

        const newRequest = {
          ...request,
          request: requestPath.replace(LEGACY_REGEXP, '@$1/'),
        };

        resolver.doResolve(target, newRequest, null, resolveContext, callback);
      });
  }
}

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
    use: 'twigjs-loader',
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

  config.resolve = {
    alias: {
      '@PathViews': path.resolve(__dirname, '../views')
    },
    plugins: [new LegacyNsResolverPlugin()],
  }

  return config;
};

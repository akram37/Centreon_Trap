const path = require('path');

const { merge } = require('webpack-merge');
const getBaseConfiguration = require('centreon-frontend/packages/frontend-config/webpack/base');
const getModuleConfiguration = require('centreon-frontend/packages/frontend-config/webpack/patch/module');

const moduleFederationConfiguration = require('./moduleFederation.json');

const baseOutputPath = path.resolve(`${__dirname}../../static`);

const moduleConfiguration = getModuleConfiguration({
  assetPublicPath: 'modules/trap/static/',
  federatedComponentConfiguration: moduleFederationConfiguration,
  outputPath: baseOutputPath,
});

const entries = {
  'hooks/header/topCounter/index': './hooks/header/topCounter/index.tsx',
  'pages/home/trap/index': './pages/home/trap/index.tsx',
};

module.exports = (jscTransformConfiguration) =>
  merge(
    getBaseConfiguration({
      jscTransformConfiguration,
      moduleFederationConfig: {
        exposes: {
          './trap': './pages/home/trap/index',
          './monitoring/hooks/topCounter': './hooks/header/topCounter/index',
        },
      },
      moduleName: 'centreonTrap',
    }),
    moduleConfiguration,
    {
      entry: entries,
    },
  );

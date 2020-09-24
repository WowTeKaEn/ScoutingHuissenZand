const CompressionPlugin = require('compression-webpack-plugin');
const CompressionPluginConfig = new CompressionPlugin({
    test: /\.js|.css(\?.*)?$/i,
});

module.exports = {
  productionSourceMap: false,
  indexPath: process.env.NODE_ENV === 'production'
    ? 'index.php'
    : 'index.html',
  chainWebpack(config) {
    config.plugins.delete('prefetch');
    
    // and this line 
    config.plugin('CompressionPlugin').use(CompressionPluginConfig);

    config.plugin('html').tap((args) => {
      args[0].minify = {
        ...args[0].minify,
        removeAttributeQuotes: false,
      }
      return args
    })
  }
};
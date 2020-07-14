const CompressionPlugin = require('compression-webpack-plugin');
const CompressionPluginConfig = new CompressionPlugin({
    test: /\.js|.css(\?.*)?$/i,
});

module.exports = {
  productionSourceMap: false,
  chainWebpack(config) {
    config.plugins.delete('prefetch');
    
    // and this line 
    config.plugin('CompressionPlugin').use(CompressionPluginConfig);
  }
};
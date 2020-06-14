const path = require('path');
const { CleanWebpackPlugin } = require('clean-webpack-plugin');
const HtmlPlugin = require('html-webpack-plugin');
const TerserPlugin = require('terser-webpack-plugin');

module.exports = {
  entry: './app/app.js',
  plugins: [
    new CleanWebpackPlugin(),
    new HtmlPlugin({
      template: 'app/index.template.html'
    }),
  ],
  optimization: {
    minimize: true,
    minimizer: [new TerserPlugin()],
  },
  output: {
    path: path.resolve(__dirname, 'app/dist'),
    filename: '[name].[contenthash].js'
  }
};
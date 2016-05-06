require('babel-core/register');

var path = require('path');
var webpack = require('webpack');
var ExtractTextPlugin = require('extract-text-webpack-plugin');
var SimpleHtmlPrecompiler = require('./scripts/simple-html-plugin.js');
var paths = require('./scripts/paths.js');

module.exports = {
  entry: "./client.jsx",
  output: {
    filename: '[name].js',
    chunkFilename: '[id].chunk.js',
    path: path.join('public', 'build'),
    publicPath: '/build/'
  },
  module: {
    loaders: [
      { test: /\.jsx$/, loaders: ['babel-loader'], exclude: ['node_modules'] },
      { test: /\.json$/, loaders: ['json-loader'] },
      {
        test: /\.css$/,
        loader: ExtractTextPlugin.extract('style-loader', 'css-loader')
      },
      {
        test: /\.(otf|eot|svg|ttf|woff|woff2)(\?.+)?$/,
        loader: 'url-loader?limit=8192'
      }
    ]
  },
  plugins: [
    new webpack.ProvidePlugin({
      'fetch': 'imports?this=>global!exports?global.fetch!whatwg-fetch'
    }),
    new ExtractTextPlugin('bundle.css'),
    new SimpleHtmlPrecompiler(paths)
  ]
};


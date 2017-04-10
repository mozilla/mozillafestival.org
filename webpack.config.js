var path = require(`path`);
var ExtractTextPlugin = require(`extract-text-webpack-plugin`);
var webpack = require(`webpack`);

module.exports = {
  entry: `./client.jsx`,

  output: {
    filename: `[name].js`,
    chunkFilename: `[id].chunk.js`,
    path: path.join(`public`, `build`),
    publicPath: `/build/`
  },

  module: {
    loaders: [
      {
        test: /\.js(x?)$/,
        exclude: /node_modules/,
        loader: `babel`,
        query: {
          presets: [`es2015`, `react`]
        }
      },
      { test: /\.json$/, loaders: [`json-loader`] },
      {
        test: /\.css$/,
        loader: ExtractTextPlugin.extract(`style-loader`, `css-loader`)
      },
      {
        test: /\.(otf|eot|svg|ttf|woff|woff2)(\?.+)?$/,
        loader: `url-loader?limit=8192`
      }
    ]
  },
  plugins: [
    new ExtractTextPlugin(`bundle.css`),
    new webpack.DefinePlugin({
      'process.env': {
        'NODE_ENV': JSON.stringify(`production`)
      }
    }),
    new webpack.optimize.UglifyJsPlugin()
  ]
};

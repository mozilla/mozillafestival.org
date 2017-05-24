var path = require(`path`);
var webpack = require(`webpack`);

var plugins = [];

if (process.env.NODE_ENV === `production`) {
  console.log(`bundling for production.`);
  plugins = [
    new webpack.DefinePlugin({
      'process.env': {
        NODE_ENV: JSON.stringify(`production`)
      }
    }),
    new webpack.optimize.UglifyJsPlugin()
  ];
}

module.exports = {
  entry: `./client.jsx`,

  output: {
    filename: `[name].js`,
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
      { test: /\.json$/, loaders: [`json-loader`] }
    ]
  },
  plugins: plugins
};

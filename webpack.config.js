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
  context: `${__dirname}`,
  entry: `./app.jsx`,
  output: {
    path: `${__dirname}/public/build`,
    filename: `bundle.js`,
    publicPath: `/build/`
  },
  module: {
    rules: [
      {
        test: /.jsx?$/,
        exclude: /node_modules/,
        use: [
          `babel-loader`
        ]
      }
    ]
  },
  plugins: plugins
};

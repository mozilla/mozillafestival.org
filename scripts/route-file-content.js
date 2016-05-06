var React = require('react');
var Router = require('react-router');
var routes = require('../components/routes.jsx');
var url = require('url');
var Path = require('path');
var FS = require("q-io/fs");

module.exports = function(outputPath, callback) {
  Router.run(routes, outputPath, function(Handler) {
    var index = React.createFactory(require('../pages/index.jsx'));
    var page = React.createFactory(Handler);

    FS.makeTree(Path.join(__dirname, '..', 'public', outputPath)).then(function() {
      var contentOfTheFile = React.renderToStaticMarkup(index({
        markup: React.renderToString(page())
      }));

      var nameOfTheFile = Path.join(__dirname, '..', 'public', outputPath, 'index.html');

      FS.write(nameOfTheFile, contentOfTheFile).then(function() {
        callback(undefined, nameOfTheFile);
      }).catch(function(err) {
        callback(err);
      });
    }).catch(function(e) {
      console.log(e);
    });
  });
};

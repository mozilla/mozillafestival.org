var React = require('react'),
    Router = require('react-router'),
    routes = require('componsents/routes.jsx');

Router.run(routes, Router.HistoryLocation, function (Handler) {
  React.render(<Handler/>, document.querySelector("#my-app"));
});

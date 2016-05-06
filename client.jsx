var React = require('react'),
    Router = require('react-router'),
    routes = require('./components/routes.jsx');

Router.run(routes, Router.HistoryLocation, function (Handler) {
  React.render(<Handler/>, document.querySelector("#my-app"));
});

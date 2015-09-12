var React = require('react'),
    Router = require('react-router'),
    Route = Router.Route;

var routes = (
  <Route>
    <Route name="home" path="/" handler={require('./pages/home.jsx')} />
    <Route name="proposals" path="/proposals" handler={require('./pages/proposals.jsx')} />
    <Route name="proposals-extended" path="/proposals-2015" handler={require('./pages/proposals-extended.jsx')} />
    <Route name="tickets" path="/tickets" handler={require('./pages/tickets.jsx')} />
    <Route name="location" path="/location" handler={require('./pages/location.jsx')} />
    <Route name="about" path="/about" handler={require('./pages/about.jsx')} />
    <Route name="contact" path="/contact" handler={require('./pages/contact.jsx')} />
    <Route name="expect" path="/expect" handler={require('./pages/expect.jsx')} />
    <Route name="guidelines" path="/guidelines" handler={require('./pages/guidelines.jsx')} />
    <Route name="fringe-events" path="/fringe-events" handler={require('./pages/fringe-events.jsx')} />
    <Route name="volunteer" path="/volunteer" handler={require('./pages/volunteer.jsx')} />
    <Route name="session-add-success" path="/session-add-success" handler={require('./pages/session-add-success.jsx')} />
  </Route>
);

var urls = [];
React.Children.forEach(routes.props.children, function(item) {
  var path = item.props.path;

  if (!path && item.props.from) {
    path = item.props.from;
    redirects[path] = item.props.to;
  }

  console.log(path);
  urls.push(path || '/');
});

console.log(Object.keys(routes)); 
// Array [ "type", "key", "ref", "_owner", "_context", "_store" ]

Router.run(routes, Router.HistoryLocation, function (Handler) {
  React.render(<Handler/>, document.querySelector("#my-app"));
});


exports.paths = urls;

var React = require('react'),
    Router = require('react-router'),
    Route = Router.Route,
    routesMeta = require('./routes').routesMeta;

var routes = (
  <Route>
    { 
      routesMeta.map(function(route) {
        console.log(route.path);
        return(
          <Route name={route.name} path={route.path} handler={route.handler} key={route.path} />
        );
      })
    }
  </Route>
);

Router.run(routes, Router.HistoryLocation, function (Handler) {
  React.render(<Handler/>, document.querySelector("#my-app"));
});

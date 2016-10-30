var React = require('react'),
    Router = require('react-router'),
    Route = Router.Route,
    Redirect = Router.Redirect;

var routes = (
  <Route>
    <Route name="home" path="/" handler={require('./pages/home.jsx')} />
    <Redirect from="/proposals" to="/" />
    <Route name="proposals" path="/late-proposals" handler={require('./pages/proposals.jsx')} />
    <Route name="location" path="/location" handler={require('./pages/location.jsx')} />
    <Route name="about" path="/about" handler={require('./pages/about.jsx')} />
    <Route name="contact" path="/contact" handler={require('./pages/contact.jsx')} />
    <Route name="expect" path="/expect" handler={require('./pages/expect.jsx')} />
    <Route name="guidelines" path="/guidelines" handler={require('./pages/guidelines.jsx')} />
    <Route name="volunteer" path="/volunteer" handler={require('./pages/volunteer.jsx')} />
    <Route name="projects" path="/projects" handler={require('./pages/projects.jsx')} />
    <Route name="team" path="/team" handler={require('./pages/team.jsx')} >
      <Route path=":tab" handler={require('./pages/team.jsx')} />
    </Route>
    <Route name="submission-process" path="/submission-process" handler={require('./pages/submission-process.jsx')} />
    <Route name="sessions" path="/spaces-and-sessions" handler={require('./pages/sessions.jsx')} />
    <Route name="fringe-events" path="/fringe" handler={require('./pages/fringe-events.jsx')} />
    <Route name="fringe-event-add-success" path="/fringe-event-add-success" handler={require('./pages/fringe-event-add-success.jsx')} />
    <Route name="tickets" path="/tickets" handler={require('./pages/tickets.jsx')} />
    <Route name="session-add-success" path="/session-add-success" handler={require('./pages/session-add-success.jsx')} />
  </Route>
);

/* ********************
* temporarily hiding these routes
* leaving code here so we can quickly turn these pages back on in June and September
******************** */

// <Route name="remote" path="/remote" handler={require('./pages/remote.jsx')} />
// <Route name="pathways" path="/pathways" handler={require('./pages/pathways.jsx')} />

Router.run(routes, Router.HistoryLocation, function (Handler) {
  React.render(<Handler/>, document.querySelector("#my-app"));
});

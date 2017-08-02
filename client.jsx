import React from 'react';
import {Router, Route, IndexRoute, Redirect, browserHistory} from 'react-router';
import ReactDOM from 'react-dom';
import Proposals from './pages/proposals/proposals.jsx';
import EnglishStrings from './pages/proposals/language/english.json';

const LANGUAGE = {
  german: {
    name: `deutsch`,
    stringSource: require('./pages/proposals/language/german.json')
  },
  spanish: {
    name: `espanol`,
    stringSource: require('./pages/proposals/language/spanish.json')
  },
  french: {
    name: `francais`,
    stringSource: require('./pages/proposals/language/french.json')
  }
};

const LOCALIZED_PROPOSAL_ROUTES = Object.keys(LANGUAGE).map(lang => {
  var component = () => (<Proposals lang={lang}
                                    stringSource={LANGUAGE[lang].stringSource} />);
  var name = LANGUAGE[lang].name;
  return <Route key={name} name={name} path={name} component={component} />;
});

var ProposalEnglish = () => <Proposals lang="english"
                                       stringSource={EnglishStrings} />;

var routes = (
  <Router history={browserHistory} onUpdate={() => {window.scrollTo(0, 0)}}>
    <Route name="home" path="/" component={require(`./pages/home.jsx`)} />
    <Route name="proposals" path="/proposals" component={require(`./pages/cfp-closed.jsx`)} />
    <Route name="late-proposals" path="/late-proposals">
      <IndexRoute component={ProposalEnglish} />
      { LOCALIZED_PROPOSAL_ROUTES }
    </Route>
    <Route name="location" path="/location" component={require(`./pages/location.jsx`)} />
    <Route name="about" path="/about" component={require(`./pages/about.jsx`)} />
    <Route name="contact" path="/contact" component={require(`./pages/contact.jsx`)} />
    <Route name="expect" path="/expect" component={require(`./pages/expect.jsx`)} />
    <Route name="guidelines" path="/guidelines" component={require(`./pages/guidelines.jsx`)} />
    <Route name="volunteer" path="/volunteer" component={require(`./pages/volunteer.jsx`)} />
    <Route name="projects" path="/projects" component={require(`./pages/projects.jsx`)} />
    <Route name="team" path="/team" component={require(`./pages/team.jsx`)} >
      <Route path=":tab" component={require(`./pages/team.jsx`)} />
    </Route>
    <Route name="submission-process" path="/submission-process" component={require(`./pages/submission-process.jsx`)} />
    <Route name="spaces" path="/spaces" component={require(`./pages/spaces.jsx`)} />
    <Route name="fringe-events" path="/fringe" component={require(`./pages/fringe-events.jsx`)} />
    <Route name="fringe-event-add-success" path="/fringe-event-add-success" component={require(`./pages/fringe-event-add-success.jsx`)} />
    <Route name="tickets" path="/tickets" component={require(`./pages/tickets.jsx`)} />
  </Router>
);

/* ********************
* temporarily hiding these routes
* leaving code here so we can quickly turn these pages back on in June and September
******************** */

// <Route name="remote" path="/remote" handler={require('./pages/remote.jsx')} />

ReactDOM.render(routes, document.querySelector(`#my-app`));

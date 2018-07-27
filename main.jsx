import React from 'react';
import { Switch, Route } from 'react-router';
import generateHelmet from './lib/helmet.jsx';
import ProposalsPage from './pages/proposals/proposals.jsx';
import EnglishStrings from './pages/proposals/language/english.json';
import NotFound from './pages/not-found.jsx';
import FringePage from './pages/fringe-events/fringe-events.jsx';
import HousePage from './pages/house.jsx';
import NotificationBar from './components/notification-bar.jsx';
import Footer from './components/footer.jsx';

let ProposalEnglish = () => <ProposalsPage lang="english" stringSource={EnglishStrings} />;

const Routes = () => (
  <Switch>
    <Route exact path="/" component={require(`./pages/home.jsx`)} />
    <Route exact path="/proposals" component={ProposalEnglish} />
    <Route path="/location" component={require(`./pages/location.jsx`)} />
    <Route path="/about" component={require(`./pages/about.jsx`)} />
    <Route path="/contact" component={require(`./pages/contact.jsx`)} />
    <Route path="/expect" component={require(`./pages/expect.jsx`)} />
    <Route path="/guidelines" component={require(`./pages/guidelines.jsx`)} />
    <Route path="/volunteer" component={require(`./pages/volunteer.jsx`)} />
    <Route path="/projects" component={require(`./pages/projects.jsx`)} />
    <Route exact path="/team" component={require(`./pages/team.jsx`)} />
    <Route path="/team/:tab" component={require(`./pages/team.jsx`)} />
    <Route path="/spaces" component={require(`./pages/spaces.jsx`)} />
    <Route exact path="/fringe" component={FringePage} />
    <Route path="/fringe/success" component={require(`./pages/fringe-events/fringe-event-add-success.jsx`)} />
    <Route path="/tickets" component={require(`./pages/tickets.jsx`)} />
    <Route path="/media" component={require(`./pages/media.jsx`)} />
    <Route exact path="/speakers" component={require(`./pages/speakers.jsx`)} />
    <Route path="/speakers/:tab" component={require(`./pages/speakers.jsx`)} />
    <Route path="/house" component={HousePage} />
    <Route path="*" component={NotFound}/>
  </Switch>
);

class Main extends React.Component {
  constructor(props) {
    super(props);
  }

  componentDidUpdate() {
    this.handleScroll();
  }

  handleScroll() {
    // if hash is presented in the url, scroll to the anchored element
    // otherwise scorll to top
    const hash = window.location.hash;
    if (hash !== ``) {
      // Push onto callback queue so it runs after the DOM is updated,
      // this is required when navigating from a different page so that
      // the element is rendered on the page before trying to getElementById.
      // (ref: https://github.com/rafrex/react-router-hash-link/tree/react-router-v2/3)
      setTimeout(() => {
        const element = document.getElementById(hash.replace(`#`, ``));
        if (element) element.scrollIntoView(true);
      }, 0);
    } else {
      window.scrollTo(0, 0);
    }
  }

  render() {
    return (
      <div>
        { generateHelmet() }
        <NotificationBar>
          <div className="d-inline-block mr-sm-2">
            <div className="d-inline-block emphasized">
              October 26-28, 2018 in London, England
            </div>
            <div className="d-inline-block emphasized">
              Submit your session now for MozFest 2018!
            </div>
          </div>
          <div className="d-inline-block">
            Submission deadline is August 1.
          </div>
        </NotificationBar>
        <Routes />
        <Footer />
      </div>
    );
  }
}

/* ********************
* temporarily hiding these routes
* leaving code here so we can quickly turn these pages back on in June and September
******************** */

// <Route name="remote" path="/remote" handler={require('./pages/remote.jsx')} />

export default Main;

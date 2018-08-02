var React = require('react');
var TabSwitcher = require('../components/tab-switcher.jsx');
var MemberProfile = require('../components/member-profile.jsx');
var PRODUCTION_MEMBERS = require('../team-bio/production-members');
var SPACE_WRANGLERS = require('../team-bio/space-wranglers');
var EXPERIENCES_WRANGLERS = require('../team-bio/experiences-wranglers');
var Sponsors = require('../components/sponsors.jsx');

import Jumbotron from '../components/jumbotron.jsx';
import Partners from '../components/partners.jsx';
import generateHelmet from '../lib/helmet.jsx';

const WRANGLER_SHOW_NAME_ONLY = false;

var TeamPage = React.createClass({
  pageMetaDescription: "",
  renderMembers: function(members, showNameOnly) {
    return members.map( member => {
      return ( <MemberProfile {...member} key={member.name} showNameOnly={showNameOnly} /> );
    });
  },
  renderIndividualSpaceSection(spaceName, type = `space`) {
    let members = type === `space` ? SPACE_WRANGLERS[spaceName] : EXPERIENCES_WRANGLERS[spaceName];
    return <div>
      <h3 className="text-center">{ spaceName }</h3>
      { this.renderMembers(members, WRANGLER_SHOW_NAME_ONLY) }
      <div className="horizontal-rule mb-5"></div>
    </div>;
  },
  render: function() {
    return (
      <div className="team-page">
        {generateHelmet(this.pageMetaDescription)}
        <Jumbotron image="/assets/images/hero/team.jpg"
          image2x="/assets/images/hero/team.jpg">
          <h1>Team</h1>
        </Jumbotron>
        <div className="content wide my-0">
          <TabSwitcher baseURL={`/team/`} initialTab={this.props.match.params.tab} ref="tabSwitcher" className="pull-up">
            <div name="Production" data-slug="production">
              <h1>Our 2018 Production Team</h1>
              <div className="horizontal-rule"></div>
              { this.renderMembers(PRODUCTION_MEMBERS) }
            </div>

            <div name="Wranglers" data-slug="wranglers">
              <h1>Our 2018 Space & Experience Wranglers</h1>
              <div className="horizontal-rule mb-5"></div>
              { this.renderIndividualSpaceSection(`Digital Inclusion`) }
              { this.renderIndividualSpaceSection(`Decentralization`) }
              { this.renderIndividualSpaceSection(`Openness`) }
              { this.renderIndividualSpaceSection(`Privacy and Security`) }
              { this.renderIndividualSpaceSection(`Web Literacy`) }
              { this.renderIndividualSpaceSection(`Youth Zone`) }
              { this.renderIndividualSpaceSection(`Queering MozFest`, `experiences`) }
            </div>

            <div name="Sponsors" data-slug="sponsors" className="sponsors">
              <Sponsors />
            </div>

            <div name="Partners" data-slug="partners" className="partners">
              <h1>2018 Partners</h1>
              <p>MozFest would like to thank the following partners for their support in creating the festival program.</p>
              <Partners />
            </div>
          </TabSwitcher>
        </div>
      </div>
    );
  }
});

module.exports = TeamPage;


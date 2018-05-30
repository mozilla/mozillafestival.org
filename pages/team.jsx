var React = require('react');
var TabSwitcher = require('../components/tab-switcher.jsx');
var Jumbotron = require('../components/jumbotron.jsx');
var MemberProfile = require('../components/member-profile.jsx');
var PRODUCTION_MEMBERS = require('../team-bio/production-members');
var SPACE_WRANGLERS = require('../team-bio/space-wranglers');
var EXPERIENCES_WRANGLERS = require('../team-bio/experiences-wranglers');
var Sponsors = require('../components/sponsors.jsx');

import generateHelmet from '../lib/helmet.jsx';

const WRANGLER_SHOW_NAME_ONLY = false;


var Partners = React.createClass({
  partnersInfo: [
    {
      name: `Youth Led EU`,
      logo: `/assets/images/team/partner/YouthLedEU.jpg`,
      link: `https://www.facebook.com/YouthLedEU/`
    },
    {
      name: `The Tate`,
      logo: `/assets/images/team/partner/Tate.jpg`,
      link: `http://www.tate.org.uk/`
    },
    {
      name: `The V&A`,
      logo: `/assets/images/team/partner/VA.jpg`,
      link: `https://www.vam.ac.uk/`
    },
    {
      name: `Arts Award`,
      logo: `/assets/images/team/partner/ArtsAward.jpg`,
      link: `http://www.artsaward.org.uk/`
    },
    {
      name: `BBC`,
      logo: `/assets/images/team/partner/BBC.jpg`,
      link: `http://www.bbc.co.uk/rd`
    },
    {
      name: `Ravensbourne`,
      logo: `/assets/images/team/partner/Ravensbourne.jpg`,
      link: `http://www.ravensbourne.ac.uk/`
    },
    {
      name: `Digital Me`,
      logo: `/assets/images/team/partner/DigitalMe.jpg`,
      link: `http://www.digitalme.co.uk/`
    },
    {
      name: `Translate`,
      logo: `/assets/images/team/partner/Translate.jpg`,
      link: `http://www.translate.org.za/`
    },
  ],
  renderPartnerLogos: function() {
    return this.partnersInfo.map((partner) => {
      return <div className="col-12 col-sm-4 partner" key={partner.name}>
        <a href={partner.link}>
          { partner.logo ? <img src={partner.logo} alt={partner.name} className="img-fluid mb-4" /> : partner.name }
        </a>
      </div>;
    });
  },
  render: function() {
    return <div className="row">{this.renderPartnerLogos()}</div>;
  }
});


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
              { this.renderIndividualSpaceSection(`Decentralization`) }
              { this.renderIndividualSpaceSection(`Digital Inclusion`) }
              { this.renderIndividualSpaceSection(`Openness`) }
              { this.renderIndividualSpaceSection(`Privacy and Security`) }
              { this.renderIndividualSpaceSection(`Web Literacy`) }
              { this.renderIndividualSpaceSection(`Youth Zone`) }
              { this.renderIndividualSpaceSection(`Queering MozFest`, `experiences`) }
            </div>

            <div name="Sponsors" data-slug="sponsors" className="sponsors">
              <Sponsors />
            </div>

            {
            /*
            <div name="Partners" data-slug="partners">
              <h1>2017 Partners</h1>
              <div className="horizontal-rule mb-5"></div>
              <Partners />
            </div>
            */
            }
          </TabSwitcher>
        </div>
      </div>
    );
  }
});

module.exports = TeamPage;


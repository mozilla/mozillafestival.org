var React = require('react');
var TabSwitcher = require('../components/tab-switcher.jsx');
var Header = require('../components/header.jsx');
var Footer = require('../components/footer.jsx');
var Jumbotron = require('../components/jumbotron.jsx');
var MemberProfile = require('../components/member-profile.jsx');
var PRODUCTION_MEMBERS = require('../team-bio/production-members');
var SPACE_WRANGLERS = require('../team-bio/space-wranglers');

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
  renderPartnerLogos: function(partner) {
    return this.partnersInfo.map((partner) => {
      return <div className="col-12 col-sm-4 partner" key={partner.name}>
                <a href={partner.link}>
                  { partner.logo ? <img src={partner.logo} alt={partner.name} className="img-fluid mb-4" /> : partner.name }
                </a>
              </div>
    });
  },
  render: function() {
    return <div className="row">{this.renderPartnerLogos()}</div>
  }
});


var TeamPage = React.createClass({
  renderMembers: function(members) {
    return members.map( member => {
            return ( <MemberProfile {...member} key={member.name} /> );
          });
  },
  renderIndividualSpaceSection(spaceName,members) {
    return <div>
            <h3 className="text-center">{ spaceName }</h3>
            { this.renderMembers(SPACE_WRANGLERS[spaceName]) }
            <div className="horizontal-rule mb-5"></div>
          </div>;
  },
  render: function() {
    return (
      <div className="team-page">
        <Header/>
        <Jumbotron image="/assets/images/hero/team.jpg"
                  image2x="/assets/images/hero/team.jpg">
          <h1>Team</h1>
        </Jumbotron>
        <div className="content wide mt-0">
          <TabSwitcher baseURL={`/team/`} initialTab={this.props.params.tab} ref="tabSwitcher" className="pull-up">
            <div name="Production" slug="production">
              <h1>Our 2017 Production Team</h1>
              <div className="horizontal-rule"></div>
              { this.renderMembers(PRODUCTION_MEMBERS) }
            </div>

            <div name="Wranglers" slug="wranglers">
              <h1>Our 2017 Space & Experience Wranglers</h1>
              <div className="horizontal-rule mb-5"></div>
              { this.renderIndividualSpaceSection(`Decentralization`) }
              { this.renderIndividualSpaceSection(`Digital Inclusion`) }
              { this.renderIndividualSpaceSection(`Open Innovation`) }
              { this.renderIndividualSpaceSection(`Privacy and Security`) }
              { this.renderIndividualSpaceSection(`Web Literacy`) }
              { this.renderIndividualSpaceSection(`Youth Zone`) }
            </div>

            <div name="Sponsors" slug="sponsors" className="sponsors">
              <h1>Our 2017 Sponsors</h1>
              <div className="horizontal-rule mb-5"></div>
              <div>
                <p>MozFest is the world’s leading event for and by the open Internet movement, and one of Mozilla’s largest annual networking opportunities. This three-day festival of interactive sessions, hands-on activities, and engaging talks brings together 1,800 passionate advocates of the open web from around the world for the flagship event in Mozilla’s leadership network.</p>
                <p>
                  For 2017, Mozilla is offering new sponsorship opportunities for supporters to help us deliver MozFest in London. The festival is growing at a phenomenal rate, as are the opportunities to collaborate, build, and convene with this dynamic network.
                </p>
                <p>If you would like to sponsor MozFest 2017, please reach out to us at <a href="mailto:festival@mozilla.org">festival@mozilla.org</a>. A festival team member will share with you the various options for support and next steps. We look forward to hearing from you.</p>
              </div>
            </div>

            <div name="Partners" slug="partners">
              <h1>Our 2017 Partners</h1>
              <div className="horizontal-rule mb-5"></div>
              <Partners />
            </div>
          </TabSwitcher>
        </div>
        <Footer/>
      </div>
    );
  }
});

module.exports = TeamPage;


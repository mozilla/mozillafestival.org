var React = require('react');
var TabSwitcher = require('../components/tab-switcher.jsx');
var Header = require('../components/header.jsx');
var Footer = require('../components/footer.jsx');
var Jumbotron = require('../components/jumbotron.jsx');
var MemberProfile = require('../components/member-profile.jsx');
var SPEAKERS_2016 = require('../team-bio/speakers/2016');

var TeamPage = React.createClass({
  renderMembers: function(members) {
    return members.map( member => <MemberProfile {...member} key={member.name} /> );
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
          <TabSwitcher baseURL={`/speakers/`} initialTab={this.props.params.tab} ref="tabSwitcher" className="pull-up">
            <div name="2017" slug="2017">
              <h1>2017</h1>
              <div className="horizontal-rule"></div>
              { this.renderMembers(SPEAKERS_2016) }
            </div>

            <div name="2016" slug="2016">
              <h1>2016</h1>
              <div className="horizontal-rule"></div>
              { this.renderMembers(SPEAKERS_2016) }
            </div>

          </TabSwitcher>
        </div>
        <Footer/>
      </div>
    );
  }
});

module.exports = TeamPage;


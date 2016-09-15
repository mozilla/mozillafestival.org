var React = require(`react`);
var TabSwitcher = require('../components/tab-switcher.jsx');
// var Link = require('react-router').Link;
var Header = require('../components/header.jsx');
var Footer = require('../components/footer.jsx');
var HeroUnit = require('../components/hero-unit.jsx');
// var ImageTag = require('../components/imagetag.jsx');


var TeamPage = React.createClass({
  render: function() {
    return (
      <div className="team-page">
        <Header/>
        <HeroUnit image="/assets/images/hero/team.jpg"
                  image2x="/assets/images/hero/team.jpg">
          <h1>Team</h1>
        </HeroUnit>
        <div className="white-background">
          <div className="content centered wide">
            <TabSwitcher baseURL={`/team`} initialTab={this.props.params.tab} ref="tabSwitcher" className="pull-up">
              <div className="p-y-3" name="Production" slug="production" iconDefault="" iconActive="">
                Production
              </div>
              <div className="p-y-3" name="Wranglers" slug="wranglers" iconDefault="" iconActive="">
                Wranglers
              </div>
              <div className="p-y-3" name="Partners" slug="partners" iconDefault="" iconActive="">
                Partners
              </div>
              <div className="p-y-3" name="Sponsors" slug="sponsors" iconDefault="" iconActive="">
                Sponsors
              </div>
            </TabSwitcher>
          </div>
        </div>
        <Footer/>
      </div>
    );
  }
});

module.exports = TeamPage;


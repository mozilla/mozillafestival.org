var React = require('react');
var Header = require('../components/header.jsx');
var Footer = require('../components/footer.jsx');
var Jumbotron = require('../components/jumbotron.jsx');
var generateHelmet = require('../lib/helmet.jsx');
var Link = require("react-router").Link;

var Guidelines = React.createClass({
  pageMetaDescription: "The Mozilla Festival respects Mozilla's community participation guidelines.",
  render: function() {
    return (
      <div className="guidelines-page">
        {generateHelmet(this.pageMetaDescription)}
        <Header/>
        <Jumbotron image="/assets/images/hero/guidelines.jpg"
                  image2x="/assets/images/hero/guidelines.jpg">
          <h1>participation<br/>guidelines</h1>
        </Jumbotron>
        <div className="white-background">
          <div className="centered content wide">
            <h1>R-E-S-P-E-C-T</h1>
            <p>The Mozilla Festival respects Mozilla's <a href="https://www.mozilla.org/en-US/about/governance/policies/participation/">community participation guidelines</a>. These guidelines cover our behaviour as participants, facilitators, space wranglers, staff, volunteers, vendors, and anyone else involved in making MozFest possible.</p>
            <div className="horizontal-rule"></div>
            <h1>How to treat each other</h1>
            <div className="treat-each-other-list">
              be respectful and welcoming
            </div>
            <div className="treat-each-other-list">
              try to understand different perspectives
            </div>
            <div className="treat-each-other-list">
              do not threaten violence
            </div>
            <div className="treat-each-other-list">
              empower others
            </div>
            <div className="treat-each-other-list">
              strive for excellence
            </div>
            <div className="treat-each-other-list">
              don&lsquo;t expect to agree with every decision
            </div>
          </div>
        </div>
        <div className="centered content wide">
          <h1>Inclusion and Diversity</h1>
          <div className="half-content">
            <p>The Mozilla Project welcomes and encourages participation by everyone. It doesn&lsquo;t matter how you identify yourself or how others perceive you: we welcome you.</p>
            <p>We welcome contributions from everyone as long as they interact constructively with our community, including, but not limited</p>
          </div>
          <div className="half-content">
            <p>to people of varied age, culture, ethnicity, gender, gender-identity, language, race, sexual orientation, geographical location and religious views.</p>
            <p>Mozilla-based activities should be inclusive and should support such diversity.</p>
          </div>
        </div>
        <div className="white-background">
          <div className="centered content wide">
            <h1>Raising Issues at MozFest</h1>
            <div className="half-content">
              <p>If you believe you‘re experiencing practices at MozFest which don‘t meet the above policies, or if you feel you are being harassed in any way, please immediately contact the Festival Producer, Sarah Allen.</p>
              <p>At the festival venue, contact the info desk and they will immediately find the Festival Producer for you.</p>
            </div>
            <div className="half-content">
              <p>MozFest organisers reserve the right to refuse admission to anyone violating these policies, and/or take further action including expulsion from the event.</p>
              <p className="boldish">
                Email: <a href="mailto:festival@mozilla.org">festival@mozilla.org</a>
              </p>
            </div>
          </div>
          <div className="centered content wide">
            <h1>Working in the Open</h1>
            <p>Because working open is one of our core values, MozFest program planning is done in the open on Github (check out our repo <a href="https://github.com/MozillaFoundation/mozfest-program-2016">here</a>). We hope participants will benefit from this culture of transparency and collaboration during the Festival, and will continue to work with an open ethos in their projects after Mozfest. Learn more about <a href="https://wiki.mozilla.org/Working_open">how we work open at Mozilla</a>.</p>
          </div>
        </div>
        <Footer/>
      </div>
    );
  }
});

module.exports = Guidelines;

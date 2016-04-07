var React = require('react');
var Header = require('../components/header.jsx');
var Footer = require('../components/footer.jsx');
var HeroUnit = require('../components/hero-unit.jsx');
var generateHelmet = require('../lib/helmet.jsx');
var Link = require("react-router").Link;

var Guidelines = React.createClass({
  pageMetaDescription: "The Mozilla Festival respects Mozilla's community participation guidelines.",
  render: function() {
    return (
      <div className="guidelines-page">
        {generateHelmet(this.pageMetaDescription)}
        <Header/>
        <HeroUnit image="/assets/images/guidelines.jpg"
                  image2x="/assets/images/guidelines.jpg">
          participation<br/>guidelines
        </HeroUnit>
        <div className="white-background">
          <div className="centered content wide">
            <h1>R-E-S-P-E-C-T</h1>
            <p>The Mozilla Festival respects Mozilla's community participation guidelines. These guidelines cover our behaviour as participants, facilitators, space wranglers, staff, volunteers, vendors, and anyone else involved in making MozFest possible.</p>
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
              <p>If you believe you&lsquo;re experiencing practices at MozFest which don&lsquo;t meet the above policies, or if you feel you are being harrassed in any way, please immediately contact the Festival Director, Michelle Thorne. Your concerns will be treated confidentially and respectfully.</p>
            </div>
            <div className="half-content">
              <p>At the festival venue, contact the info desk and they will immediately find the Festival Director for you.</p>
              <p className="boldish">
                Email: <a href="mailto:festival@mozilla.org">festival@mozilla.org</a>
              </p>
            </div>
          </div>
          <div className="centered content wide">
            <h1>Working in the Open</h1>
            <p>Because working open is one of our core values, MozFest program planning is done in the open on Github (check out our 2015 repo <a href="https://github.com/mozilla/mozfest-program">here</a>). We hope participants will benefit from this culture of transparency and collaboration during the Festival, and will continue to work with an open ethos in their projects after Mozfest. We recommend bookmarking WorkOpen.org and reading through the interviews with passionate advocates for open practices.</p>
          </div>
        </div>
        <Footer/>
      </div>
    );
  }
});

module.exports = Guidelines;


var React = require('react');
var Header = require('../components/header.jsx');
var Footer = require('../components/footer.jsx');
var HeroUnit = require('../components/hero-unit.jsx');

var Guidelines = React.createClass({
  render: function() {
    return (
      <div className="guidelines-page">
        <Header/>
        <HeroUnit image="/assets/images/guidelines.jpg"
                  image2x="/assets/images/guidelines.jpg">
          participation<br/>guidelines
        </HeroUnit>
        <div className="white-background">
          <div className="centered content wide">
            <h1>R-E-S-P-E-C-T</h1>
            <p>The Mozilla Festival respects Mozilla's community participation guidelines. These guidelines cover out behaviour as participants, facilitators, speace wranglers, staff, volunteers, bendors, and anyone else involved in making MozFest possible.</p>
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
            <p>The Mozilla Project welcome and encourages participation by everyone. It doesn&lsquo;t matter how you identify yourself or how others perceive you: we welcome you.</p>
            <p>We welcome contributions from everyone as long as they interact constructively with our community and everybody at the Mozilla Festival, including, but not</p>
          </div>
          <div className="half-content">
            <p>Limited to people of varied age, ability, culture, ethnicity, gender, gender-identity, language, race, sexual orientation, geographic location and religious & political views.</p>
            <p>Mozilla-based activities should be inclusive and should support such diversity.</p>
          </div>
        </div>
        <div className="white-background">
          <div className="centered content wide">
            <h1>Raising Issues at Mozfest</h1>
            <div className="half-content">
              <p>If you believe you&lsquo;re experiencing practices at Mozfest which don&lsquo;t meet the above policies, or if you feel you are being harrassed in any way, please immediately contact the Festival Director, Michelle Thorne. Your concerns will be treated confidentially and respectfully.</p>
            </div>
            <div className="half-content">
              <p>At the festival venue, contact the info desk and they will immediately find the Festival Director for you.</p>
              <p className="boldish">
                Email: michelle@mozillafoundation.org<br/>
                Phone: +49 172 711 Nine Eight Zero Seven
              </p>
            </div>
          </div>
        </div>
        <Footer/>
      </div>
    );
  }
});

module.exports = Guidelines;


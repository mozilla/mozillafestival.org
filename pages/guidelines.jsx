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
        </div>
        <div className="white-background">
          <div className="centered content wide">
          </div>
        </div>
        <Footer/>
      </div>
    );
  }
});

module.exports = Guidelines;


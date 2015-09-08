var React = require('react');
var Header = require('../components/header.jsx');
var Footer = require('../components/footer.jsx');
var HeroUnit = require('../components/hero-unit.jsx');

var Proposals = React.createClass({
  render: function() {
    return (
      <div className="proposals-page">
        <Header/>
        <HeroUnit image="/assets/images/proposals.jpg"
                  image2x="/assets/images/proposals.jpg">
          <div>call for proposals</div>
        </HeroUnit>
        <div className="content">
          <div className="proposals-form">
            <h1>Thanks for your interest!</h1> 
            <p>The Call for Proposals is now closed. We will be reviewing all submissions over the next three weeks. If you submitted a proposal, we will contact you before the end of September to let you know. If you have any questions, please contact the team at <a href="mailto:festival@mozilla.org">festival@mozilla.org</a>.</p>
          </div>
        </div>
        <Footer/>
      </div>
    );
  }
});

module.exports = Proposals;


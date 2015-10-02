var React = require('react');
var Header = require('../components/header.jsx');
var Footer = require('../components/footer.jsx');
var HeroUnit = require('../components/hero-unit.jsx');

var SubmissionProcessPage = React.createClass({
  render: function() {
    return (
      <div>
        <Header/>
        <HeroUnit image="/assets/images/submission-process.jpg"
                  image2x="/assets/images/submission-process.jpg">
          Submission Process
        </HeroUnit>
        <div className="centered content wide">
          <p>This year, we're using GitHub to manage, review and curate sessions for MozFest. GitHub allows us to better collaborate and coordinate across the program in an open fashion.</p>
          <p>Leading up to the event, MozFest's Space Wranglers will be reviewing all proposals and choosing sessions that best align with their Space objectives. You can follow this process on our <a href="https://github.com/mozilla/mozfest-program/issues">GitHub repo</a>.</p>
          <p>Unfortunately, not all proposals will be accepted; the weekend simply is not long enough. But there's good news: everyone who proposed a session will be gifted a free ticket to the festival. </p>
        </div>
        <Footer/>
      </div>
    );
  }
});

module.exports = SubmissionProcessPage;


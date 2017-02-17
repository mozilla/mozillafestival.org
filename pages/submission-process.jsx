var React = require('react');
var Header = require('../components/header.jsx');
var Footer = require('../components/footer.jsx');
var Jumbotron = require('../components/jumbotron.jsx');

var SubmissionProcessPage = React.createClass({
  render: function() {
    return (
      <div>
        <Header/>
        <Jumbotron image="/assets/images/submission-process.jpg"
                  image2x="/assets/images/submission-process.jpg">
          <h1>Submission Process</h1>
        </Jumbotron>
        <div className="centered content wide">
          <p>We use GitHub to manage, review and curate sessions for MozFest. GitHub allows us to openly collaborate, and it provides a record for how the festival program came together.</p>
          <p>When the Call for Proposals closes on August 1, Space Wranglers will review all proposals and choosing sessions that best align with their Space objectives. You can follow this process on our <a href="https://github.com/MozillaFoundation/mozfest-program-2016">GitHub repo</a>.</p>
          <a href="/proposals" className="button"><span>Submit a Proposal for MozFest 2016</span></a>
        </div>
        <Footer/>
      </div>
    );
  }
});

module.exports = SubmissionProcessPage;

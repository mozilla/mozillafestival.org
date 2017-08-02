var React = require('react');
var Link = require('react-router').Link;
var classnames = require('classnames');
var Header = require('../components/header.jsx');
var Footer = require('../components/footer.jsx');
var Jumbotron = require('../components/jumbotron.jsx');
var generateHelmet = require('../lib/helmet.jsx');

var Proposal = React.createClass({
  pageMetaDescription: "Session proposals",
  render: function() {
    let stringSource = this.props.stringSource;

    return (
      <div className={classnames(`proposals-page`)}>
        {generateHelmet(this.pageMetaDescription)}
        <Header/>
        <Jumbotron image="/assets/images/proposals.jpg"
                  image2x="/assets/images/proposals.jpg">
          <h1>Call for Proposals</h1>
          <div className="deadline">
            <span>Deadline for submissions is August 1, 2017</span>
          </div>
        </Jumbotron>
        <div className="content wide">
          <h1 className="text-center">The MozFest 2017 Call for Proposals has ended.</h1>
          <p>Curation takes place <a href="https://github.com/MozillaFoundation/mozfest-program-2017">here on Github</a> during the month of August. If you submitted a session, expect to hear from us in September. Thank you to everyone who proposed a session!</p>
        </div>
        <Footer />
      </div>
    );
  }
});

module.exports = Proposal;

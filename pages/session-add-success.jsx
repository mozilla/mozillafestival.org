var React = require('react');
var Header = require('../components/header.jsx');
var Footer = require('../components/footer.jsx');
var Jumbotron = require('../components/jumbotron.jsx');
var Router = require('react-router');
var Link = Router.Link;

var Success = React.createClass({
  render: function() {
    return (
      <div className="proposals-page">
        <Header/>
        <Jumbotron image="/assets/images/proposals.jpg"
                  image2x="/assets/images/proposals.jpg">
          <h1>call for proposals</h1>
        </Jumbotron>
        <div className="centered content wide">
          <h1 id="success">Success!</h1>
          <p>Thank you for your session proposal</p>
          <Link to="proposals" className="button"><span>Want to submit another?</span></Link>
        </div>
        <Footer/>
      </div>
    );
  }
});

module.exports = Success;



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
        <Jumbotron image="/assets/images/hero/fringe.jpg"
                  image2x="/assets/images/hero/fringe.jpg">
          <h1>Fringe Events</h1>
        </Jumbotron>
        <div className="centered content wide">
          <h1>Success!</h1>
          <p>Thank you for your submission.</p>
        </div>
        <Footer/>
      </div>
    );
  }
});

module.exports = Success;


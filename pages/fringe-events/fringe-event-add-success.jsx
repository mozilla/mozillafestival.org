var React = require('react');
var Header = require('../../components/header.jsx');
var Footer = require('../../components/footer.jsx');
var Jumbotron = require('../../components/jumbotron.jsx');
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
          <h1>Submitted!</h1>
          <p>Thank you for your submission.</p>
          <p>Your event will show up on the page after it has been reviewed by MozFest program team.</p>
          <div><Link to="/fringe" className="btn btn-primary-outline">See other fringe events</Link></div>
        </div>
        <Footer/>
      </div>
    );
  }
});

module.exports = Success;


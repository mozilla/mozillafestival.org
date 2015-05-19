var React = require('react');
var Header = require('../components/header.jsx');
var Footer = require('../components/footer.jsx');
var HeroUnit = require('../components/hero-unit.jsx');

var Volunteer = React.createClass({
  render: function() {
    return (
      <div>
        <Header/>
        <HeroUnit/>

        <Footer/>
      </div>
    );
  }
});

module.exports = Volunteer;


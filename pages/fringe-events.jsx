var React = require('react');
var Header = require('../components/header.jsx');
var Footer = require('../components/footer.jsx');
var HeroUnit = require('../components/hero-unit.jsx');

var Fringe = React.createClass({
  render: function() {
    return (
      <div>
        <HeroUnit image="/assets/images/fringe.jpg"
                  image2x="/assets/images/fringe.jpg"/>

        <Footer/>
      </div>
    );
  }
});

module.exports = Fringe;


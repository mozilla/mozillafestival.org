var React = require('react');
var Header = require('../components/header.jsx');
var Footer = require('../components/footer.jsx');
var HeroUnit = require('../components/hero-unit.jsx');

var Guidelines = React.createClass({
  render: function() {
    return (
      <div>
        <Header/>
        <HeroUnit image="/assets/images/guidelines.jpg"
                  image2x="/assets/images/guidelines.jpg"/>

        <Footer/>
      </div>
    );
  }
});

module.exports = Guidelines;


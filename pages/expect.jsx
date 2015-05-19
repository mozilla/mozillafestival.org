var React = require('react');
var Header = require('../components/header.jsx');
var Footer = require('../components/footer.jsx');
var HeroUnit = require('../components/hero-unit.jsx');

var Expect = React.createClass({
  render: function() {
    return (
      <div>
        <Header/>
        <HeroUnit image="/assets/images/placeholder-image.png"
                  image2x="/assets/images/placeholder-image.png">
          what to expect
        </HeroUnit>

        <Footer/>
      </div>
    );
  }
});

module.exports = Expect;


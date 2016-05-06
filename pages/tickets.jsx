var React = require('react');
var Header = require('../components/header.jsx');
var Footer = require('../components/footer.jsx');
var HeroUnit = require('../components/hero-unit.jsx');
var generateHelmet = require('../lib/helmet.jsx');

var Tickets = React.createClass({
  pageMetaDescription: "Tickets are available for youth and adults.",
  render: function() {
    return (
      <div className="tickets-page">
        {generateHelmet(this.pageMetaDescription)}
        <Header/>
        <HeroUnit image="/assets/images/tickets.jpg"
                  image2x="/assets/images/tickets.jpg">
          tickets
        </HeroUnit>
        <div className="content centered wide">
          <div ref="titoContainer"></div>
        </div>
        <Footer/>
      </div>
    );
  }
});

module.exports = Tickets;


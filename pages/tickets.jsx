var React = require('react');
var Header = require('../components/header.jsx');
var Footer = require('../components/footer.jsx');
var HeroUnit = require('../components/hero-unit.jsx');
var generateHelmet = require('../lib/helmet.jsx');

var Tickets = React.createClass({
  pageMetaDescription: "Tickets are available for youth and adults.",
  componentDidMount: function() {
    var titoContainer = this.refs.titoContainer.getDOMNode();
    var titoWidget = document.querySelector(".widgets .tito-tickets");
    titoContainer.appendChild(titoWidget);
  },
  componentWillUnmount: function() {
    var titoWidget = this.refs.titoContainer.getDOMNode().querySelector(".tito-tickets");
    var widgetContainer = document.querySelector(".widgets");
    widgetContainer.appendChild(titoWidget);
  },
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


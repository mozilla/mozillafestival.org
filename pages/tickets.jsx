var React = require('react');
var Header = require('../components/header.jsx');
var Footer = require('../components/footer.jsx');
var HeroUnit = require('../components/hero-unit.jsx');

var Tickets = React.createClass({
  componentDidMount: function() {
    var titoContainer = this.refs.titoContainer.getDOMNode();
    var titoWidget = document.querySelector(".widgets tito-widget");
    titoContainer.appendChild(titoWidget);
  },
  componentWillUnmount: function() {
    var titoWidget = this.refs.titoContainer.getDOMNode().querySelector("tito-widget");
    var widgetContainer = document.querySelector(".widgets");
    widgetContainer.appendChild(titoWidget);
  },
  render: function() {
    return (
      <div>
        <Header/>
        <HeroUnit image="/assets/images/placeholder-image.png"
                  image2x="/assets/images/placeholder-image.png">
          tickets
        </HeroUnit>
        <div className="content centered wide">
          <h1>Make it official</h1>
          <p>MozFest tickets go super fast, so we suggest securing your tickets as soon as possible</p>
          <div ref="titoContainer"></div>
        </div>
        <Footer/>
      </div>
    );
  }
});

module.exports = Tickets;


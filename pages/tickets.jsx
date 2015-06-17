var React = require('react');
var Header = require('../components/header.jsx');
var Footer = require('../components/footer.jsx');
var HeroUnit = require('../components/hero-unit.jsx');

var Tickets = React.createClass({
  /*componentDidMount: function() {
    var titoContainer = this.refs.titoContainer.getDOMNode();
    var titoWidget = document.querySelector(".widgets .tito-tickets");
    titoContainer.appendChild(titoWidget);
  },
  componentWillUnmount: function() {
    var titoWidget = this.refs.titoContainer.getDOMNode().querySelector(".tito-tickets");
    var widgetContainer = document.querySelector(".widgets");
    widgetContainer.appendChild(titoWidget);
  },*/
  render: function() {
    return (
      <div className="tickets-page">
        <Header/>
        <HeroUnit image="/assets/images/tickets.jpg"
                  image2x="/assets/images/tickets.jpg">
          tickets
        </HeroUnit>
        <div className="content centered wide">
          <h1>Coming soon</h1>
          <p className="strike">Tickets will go on sale June 17, 2015.</p>
          <p>Oops! We hit a snag with our ticketing software. Tickets will be available for purchase soon. <a href="https://sendto.mozilla.org/page/s/mozfest-2015-save-the-date">Sign up</a> to be notified about festival news, including ticket sales.</p>
          <div ref="titoContainer"></div>
        </div>
        <Footer/>
      </div>
    );
  }
});

module.exports = Tickets;


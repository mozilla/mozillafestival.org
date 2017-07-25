var React = require('react');
var Header = require('../components/header.jsx');
var Footer = require('../components/footer.jsx');
var Jumbotron = require('../components/jumbotron.jsx');
var generateHelmet = require('../lib/helmet.jsx');

var Tickets = React.createClass({
  pageMetaDescription: "Tickets are available for youth and adults.",
  componentDidMount: function() {
    var titoContainer = this.refs.titoContainer;
    var titoWidget = document.querySelector(".widgets .tito-tickets");
    titoContainer.appendChild(titoWidget);
  },
  componentWillUnmount: function() {
    var titoWidget = this.refs.titoContainer.querySelector(".tito-tickets");
    var widgetContainer = document.querySelector(".widgets");
    widgetContainer.appendChild(titoWidget);
  },
  render: function() {
    return (
      <div className="tickets-page">
        {generateHelmet(this.pageMetaDescription)}
        <Header/>
        <Jumbotron image="/assets/images/hero/tickets.jpg"
                  image2x="/assets/images/hero/tickets.jpg">
          <h1>tickets</h1>
        </Jumbotron>
        <div className="content centered wide">
          <div ref="titoContainer"></div>
          <div className="ticket-desc">
            <p className="boldish">Youth Ticket £3</p>
            <p>This ticket covers entry to the festival on Saturday and Sunday. It includes lunch, beverages, swag, and two days of building, making and learning. Youth tickets are for those under 18. Youth under 16 must be accompanied by an adult. If you are an educator bringing young people to the event, please email us at <a href="mailto:festival@mozilla.org">festival@mozilla.org</a> before you buy tickets as we can help with group discounts. <a href="https://ti.to/Mozilla/mozfest-2017/with/o-pwuh3rljs" target="_blank">Buy a youth ticket.</a></p>
            <p className="boldish">Adult Ticket £45</p>
            <p>This ticket covers entry to the festival on Friday night, Saturday and Sunday. It includes lunch, coffee, adult beverages, swag, and two days of building, making and learning (plus a party on Saturday evening!) If you are a parent with young children, we have a free creche onsite open Saturday and Sunday. <a href="https://ti.to/Mozilla/mozfest-2017/with/qlpzhjfsl80" target="_blank">Buy an adult ticket.</a></p>
          </div>
        </div>
        <div>

        </div>
        <Footer/>
      </div>
    );
  }
});

module.exports = Tickets;

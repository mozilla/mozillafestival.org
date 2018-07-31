import React from 'react';
import Jumbotron from '../components/jumbotron.jsx';
import generateHelmet from '../lib/helmet.jsx';

var Tickets = React.createClass({
  pageMetaDescription: "Tickets are available for youth and adults.",
  render: function() {
    return (
      <div className="tickets-page">
        {generateHelmet(this.pageMetaDescription)}
        <Jumbotron image="/assets/images/hero/tickets.jpg"
          image2x="/assets/images/hero/tickets.jpg">
          <h1>tickets</h1>
        </Jumbotron>
        <div className="content centered wide">
          <div className="confined-width-header">
            <h1>MozFest is the world's leading festival for the open internet movement.</h1>
            <p><strong>Join influential thinkers from around the world to build, debate, and explore the future of a healthy internet.</strong></p>
            <div className="horizontal-rule"></div>
          </div>
          <div className="ticket-desc mt-5">
            <p className="font-weight-bold">MozFest Weekend Ticket £45</p>
            <p>Join us for the full MozFest weekend. This ticket gains you entry to Friday evening's opening Science Fair, hundreds of sessions, talks and exhibits, a networking breakfast, and evening social events. Lunches and hot drinks are included on Saturday and Sunday. <a href="https://ti.to/Mozilla/mozfest-2017/with/qlpzhjfsl80" target="_blank">Buy a MozFest Weekend Ticket</a>.
            </p>
            <p className="font-weight-bold">Friday Night Science Fair Ticket £10</p>
            <p>Join us for the MozFest opening Science Fair on Friday 27th October from 18:00 - 21:00 for a night of exciting demos, prototypes, networking and drinks. This ticket is valid for entry on Friday evening only. <a href="https://ti.to/Mozilla/mozfest-2017/with/xvj7y1v7lmo" target="_blank">Buy a Friday Night Science Fair Ticket</a>.</p>
            <p className="font-weight-bold">Youth Ticket £3</p>
            <p>Join us for two days of hacking, building, and crafting at MozFest. This ticket covers entry for Saturday 28th and Sunday 29th October for youth aged 18 and under. It includes lunch for both days. Under 16's must be accompanied by one adult ticket or be part of a school group. <a href="https://ti.to/Mozilla/mozfest-2017/with/o-pwuh3rljs" target="_blank">Buy a Youth Ticket</a>.</p>
          </div>
        </div>
      </div>
    );
  }
});

module.exports = Tickets;

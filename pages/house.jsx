import React from 'react';
import moment from 'moment';
import Jumbotron from '../components/jumbotron.jsx';
import EventCardGroup from '../components/event-card-group.jsx';
import LoadingNotice from '../components/loading-notice.jsx';
import generateHelmet from '../lib/helmet.jsx';

import 'whatwg-fetch';

const SHOW_HOUSE_EVENT = true;

const DATE_FORMAT = `MMM DD, YYYY`;
const TIME_FORMAT = `h:mma`;
// make sure don't include space in between DATE_FORMAT and TIME_FORMAT
// as moment might have trouble understanding it
const DATE_TIME_FORMAT = `${DATE_FORMAT}@${TIME_FORMAT}`;

let sortByTime = function(a,b) {
  let timeA = moment(`${a.date}@${a.time}`, DATE_TIME_FORMAT);
  let timeB = moment(`${b.date}@${b.time}`, DATE_TIME_FORMAT);

  if (timeA < timeB) { return -1; }
  if (timeA > timeB) { return 1; }
  return 0;
};

const TICKETS_JSON_LD = {
  "@context": "http://schema.org",
  "@type": "Event",
  "name": "MozFest House 2018",
  "startDate": "2018-10-22",
  "location": {
    "@type": "Place",
    "name": "The RSA",
    "address": {
      "@type": "PostalAddress",
      "streetAddress": "8 John Adam St",
      "addressLocality": "London",
      "postalCode": "WC2N 6EZ",
      "addressCountry": "GB"
    }
  },
  "image": ["https://mozillafestival.org/assets/images/site-thumbnail.jpg"],
  "description": "MozFest House features films, workshops, conferences, and talks, all focusing on Internet health and showcasing the diversity of the Mozilla network. The venue also features a free co-working space.",
  "endDate": "2018-10-26",
  "offers" : [ {
    "@type" : "Offer",
    "name" : "Individual Tickets",
    "price" : "0",
    "priceCurrency" : "GBP",
    "availability" : "InStock",
    "url" : "https://mozillafestival.org/house",
    "validFrom" : "2018-07-01",
    "validThrough" : "2018-10-28T20:00"
  },{
    "@type" : "Offer",
    "name" : "MozFest All Access Pass",
    "price" : "175",
    "priceCurrency" : "GBP",
    "availability" : "InStock",
    "url" : "https://mozillafestival.org/tickets",
    "validFrom" : "2018-07-01",
    "validThrough" : "2018-10-21T23:59"
  } ]
};

class HousePage extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      events: null,
      eventsLoaded: false
    };
    this.pageClassName = `house-page`;
    this.pageMetaDescription = "";
  }

  componentDidMount() {
    if (SHOW_HOUSE_EVENT) {
      this.getHouseEvents();
    }
  }

  getHouseEvents() {
    fetch('/get-house-events', {
      method: 'get'
    })
      .then(response => response.json())
      .then(events => {
        this.setState({
          events: events.sort(sortByTime),
          eventsLoaded: true
        });
      })
      .catch(() => {
        this.setState({
          eventsLoaded: true
        });
      });
  }

  renderHouseEvents() {
    let events = <LoadingNotice />;

    if ( this.state.eventsLoaded ) {
      if (this.state.events) {
        events = this.state.events.length === 0 ? <p className="text-center">Please stay tuned for more to come!</p> : <EventCardGroup events={this.state.events} type="house" />;
      } else {
        events = <p className="text-center">Unable to load events.</p>;
      }

    }

    return <div>
      <h3>MozFest House 2018</h3>
      <div className="text-left white-background">
        { events }
      </div>
    </div>;
  }

  render() {
    return (
      <div className={this.pageClassName}>
        {generateHelmet(this.pageMetaDescription, TICKETS_JSON_LD)}
        <Jumbotron image="/assets/images/hero/house.jpg"
          image2x="/assets/images/hero/house.jpg">
          <h1>MozFest House</h1>
        </Jumbotron>
        <div className="white-background">
          <div className="content centered wide">
            <div className="confined-width-header text-center">
              <h1>Sometimes, the MozFest weekend just isn’t enough.</h1>
            </div>
            <div className="text-center">
              <p>MozFest House is a central London venue that extends MozFest into a week-long festival.</p>
              <p>From Monday 22nd - Friday 26th October, MozFest participants are invited to dig deeper into their work, trade ideas, swap code, and build solutions.</p>
              <p>MozFest House features films, workshops, conferences, and talks, all focusing on Internet health and showcasing the diversity of the Mozilla network. The venue also features a free co-working space.</p>
              <ul className="no-bullet text-left pl-0">
                <li className="mb-4"><strong>Where</strong>: MozFest House is located just off Trafalgar Square at the <a href="https://www.google.com/maps/place/RSA+House/@51.5093702,-0.1248943,17z/data=!3m1!4b1!4m5!3m4!1s0x487604c9572d71f1:0xc61aaa0727953544!8m2!3d51.5093669!4d-0.1227056" target="_blank">Royal Society of Arts</a> (RSA), 8 John Adam St, London WC2N 6EZ. RSA is a contemporary venue equipped with co-working areas, meeting rooms, and a speakers forum.</li>
                <li className="mb-4"><strong>When</strong>: MozFest House will be open Monday 22nd - Friday 26th October between 9:00 and 23:00.</li>
                <li className="mb-4"><strong>Who</strong>: Coders, journalists, teachers, hackers — anyone and everyone working toward a healthier Internet. All ages and skill levels are welcome. Please note you must register for MozHouse events separately from the weekend festival.</li>
              </ul>
              <p>Are you interested in hosting an event as part of MozFest House? Contact us at <a href="mailto:festival@mozilla.org">festival@mozilla.org</a></p>
            </div>
            { SHOW_HOUSE_EVENT && this.renderHouseEvents() }
          </div>
        </div>
      </div>
    );
  }
}

export default HousePage;

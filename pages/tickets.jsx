import React from 'react';
import Jumbotron from '../components/jumbotron.jsx';
import generateHelmet from '../lib/helmet.jsx';

const WEEKEND_TICKETS = [
  {
    type: `Weekend Ticket`,
    price: `£45`,
    description: `Be part of all the weekend activities from 26-28 October at MozFest. Join us for 3 days of sessions, talks, art exhibits and social events. This ticket includes:`,
    list: [
      `300+ workshops and sessions`,
      `Talks by leading voices from across the Internet health movement`,
      `Access to Friday, Saturday and Sunday night parties`,
      `Network and community building at our Sunday Breakfast`,
      `Engagement with artists as they respond to outcomes from sessions`,
      `Lunch and unlimited hot drinks`
    ],
    titoReleaseCode: `gpbruuajfs0`,
    note: `The cost of this ticket is subsidised by Mozilla`
  },
  {
    type: `Weekend (Youth) Ticket`,
    price: `£3`,
    description: `Join us on Saturday 27th and Sunday 28th for hands-on sessions designed to share and advocate young people's ideas about the future of a healthy Internet.`,
    list: [
      `Immersive hands-on experiences with the latest tech`,
      `Enough swag to last until MozFest 2019!`,
      `Saturday and Sunday parties`,
      `Lunch on both days`
    ],
    titoReleaseCode: `qlffigyaaj8`,
    note: `Under 18s only; under 16s must be accompanied by an adult`
  },
  {
    type: `Weekend (Benefactor) Ticket`,
    price: `£155`,
    description: `The Benefactor ticket offers the same access as our weekend ticket, and your purchase helps keep our regular MozFest admission costs super low — allowing participants from all backgrounds and life experiences to join MozFest, and the movement for a healthier Internet.`,
    titoReleaseCode: `vzgukz-4i7e`,
    note: `The cost of this ticket covers our stipend program`
  }
];

const DAY_TICKETS = [
  {
    type: `Science Fair Ticket`,
    price: `£15`,
    description: `Science Fair on Friday 26th October.`,
    list: [
      `Access from 18:00 - 21:00 at Ravensbourne College`,
      `Exciting demos`,
      `Interactive prototypes`,
      `Networking and drinks`
    ],
    titoReleaseCode: `5kme8qcju8i`,
    note: `This ticket is valid for entry on Friday evening only`
  },
  {
    type: `Saturday Ticket`,
    price: `£25`,
    description: `Sessions and talks on Saturday 27th October.`,
    list: [
      `Access from 08:00 - 18:00 at Ravensbourne College`,
      `A packed schedule with sessions across the day`,
      `Dialogues & Debates speaker series`,
      `Lunch and hot drinks`,
      `Saturday night party at MozFest House at the RSA, until late`
    ],
    titoReleaseCode: `xmlxqcmftlg`,
    note: `This ticket is for Saturday events only and is non-transferable`
  },
  {
    type: `Sunday Ticket`,
    price: `£20`,
    description: `Sessions and talks on Sunday 28th October.`,
    list: [
      `Access from 09:00 - 20:00 at Ravensbourne College`,
      `Networking breakfast to start the day`,
      `A day full of sessions and talks`,
      `Lunch and hot drinks`,
      `Celebrating MozFest with a closing party at Ravensbourne until 8pm`
    ],
    titoReleaseCode: `pqejxfdnzh8`,
    note: `This ticket is for Sunday events only and is non transferable`
  }
];

let TicketCardGroup = (props) => {
  return <div className="container mb-5 pt-3">
    <h1>{props.title}</h1>
    <div className="row">
      { props.tickets.map(ticket => <div className="col-sm-4 mt-4 mt-sm-0" key={props.type}><TicketCard {...ticket} /></div>) }
    </div>
  </div>;
};

let TicketCard = (props) => {
  let paddingClass = `px-3`;

  return <div className="ticket-card d-flex flex-column white-background py-4">
    <div className={`header-section d-flex flex-column justify-content-between ${paddingClass}`}>
      <h3>{props.type}</h3>
      <div className="price">{props.price}</div>
    </div>
    <div className={`description ${paddingClass}`}>
      <p>{props.description}</p>
      <ul className="pl-0">
        { props.list && props.list.map(item => <li key={item}>{item}</li>) }
      </ul>
    </div>
    <div className={paddingClass}>
      <tito-button
        event="mozilla/mozilla-festival-2018"
        releases={props.titoReleaseCode}
      >
        Buy Tickets
      </tito-button>
      <div className="note">
        <small><em>{props.note}</em></small>
      </div>
    </div>
  </div>;
};


class Tickets extends React.Component {
  constructor(props) {
    super(props);
  }

  render() {
    return (
      <div className="tickets-page">
        {generateHelmet(this.pageMetaDescription)}
        <Jumbotron image="/assets/images/hero/tickets.jpg"
          image2x="/assets/images/hero/tickets.jpg">
          <h1 className="highlight">Tickets</h1>
        </Jumbotron>
        <div className="content wide text-center mb-5">
          <TicketCardGroup title="Weekend Tickets" tickets={WEEKEND_TICKETS} />
          <TicketCardGroup title="Day Tickets" tickets={DAY_TICKETS} />
        </div>
      </div>
    );
  }
}

export default Tickets;

var React = require('react');
var moment = require('moment');

const DATE_FORMAT = `MMM DD, YYYY`;
const TIME_FORMAT = `h:mma`;

var EventCard = React.createClass({
  propTypes: {
    date: React.PropTypes.string.isRequired,
    time: React.PropTypes.string.isRequired,
    eventname: React.PropTypes.string.isRequired,
    location: React.PropTypes.string.isRequired,
    description: React.PropTypes.string.isRequired,
    link: React.PropTypes.string,
    registrationlink: React.PropTypes.string.isRequired
  },
  getDefaultProps() {
    return {
      date: "",
      time: "",
      eventname: "",
      location: "",
      description: "",
      link: "",
      registrationlink: ""
    }
  },
  renderDescription() {
    return this.props.description.split(`\n`).map((paragraph) => {
      if (!paragraph) return null;

      return <p key={paragraph}>{paragraph}</p>;
    });
  },
  render: function() {
    var link = this.props.link;
    var registrationLink = this.props.registrationlink;

    return (
      <li className="event-card col-12 col-sm-6 mb-4">
        <div className="inner-wrapper p-3">
          <header className="font-weight-bold mb-3">{this.props.eventname}</header>
          <div className="details">
            <div className="date-and-time">
              { moment(this.props.date, DATE_FORMAT).format(DATE_FORMAT)} {moment(this.props.time, TIME_FORMAT).format(TIME_FORMAT) }
            </div>
            { this.props.location && 
            <div className="location">{this.props.location}</div> 
            }
            { link && 
            <div className="url"><a href={link}>{link}</a></div>
            }
            { registrationLink && 
            <div className="registration-link"><a href={registrationLink}>Registration</a></div>
            }
            <div className="description">
              { this.renderDescription() }
            </div>
          </div>
        </div>
      </li>
    );
  }
});

var EventCardGroup = React.createClass({
  propTypes: {
    events: React.PropTypes.array.isRequired
  },
  getDefaultProps() {
    return {
      events: []
    }
  },
  renderEventCards() {
    if (!this.props.events) return null;
    return this.props.events.map((event,i) => <EventCard {...event} key={event.eventname} />);
  },
  render: function() {
    return <ul className="event-card-group row no-bullet pl-0 m-0">{this.renderEventCards()}</ul>
  }
});

module.exports = EventCardGroup;

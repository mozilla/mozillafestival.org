var React = require('react');
var moment = require('moment');

const DATE_FORMAT = `MMM DD, YYYY`;
const TIME_FORMAT = `h:mma`;

var FringeEventCard = React.createClass({
  renderDescription() {
    return this.props.description.split(`\n`).map((paragraph) => {
      if (!paragraph) return null;

      return <p key={paragraph}>{paragraph}</p>;
    });
  },
  render: function() {
    var link = this.props.link;
    return (
      <li className="col-12 col-sm-6 mb-4">
        <div className="inner-wrapper p-3">
          <header className="font-weight-bold mb-3">{this.props.eventname}</header>
          <div className="details">
            <div className="date-and-time">
              { moment(this.props.date, DATE_FORMAT).format(DATE_FORMAT)} {moment(this.props.time, TIME_FORMAT).format(TIME_FORMAT) }
            </div>
            <div className="location">
              {this.props.location}
            </div>
            { link && <div className="url"><a href={link}>{link}</a></div> }
            <div className="description">
              { this.renderDescription() }
            </div>
          </div>
        </div>
      </li>
    );
  }
});

module.exports = FringeEventCard;

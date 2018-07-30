import React from 'react';
import PropTypes from 'prop-types';

class Timetable extends React.Component {
  constructor(props) {
    super(props);
  }

  renderDaySchedule(daySchedule) {
    let timeslots = daySchedule.timeslots.map(ts => {
      return <li key={ts.time}>
        <time>{ts.time}</time>
        <div className="description">{ts.description}</div>
      </li>;
    });

    timeslots = <ol className="timeslots">{ timeslots }</ol>;

    return <div className="day" key={daySchedule.date}>
      <div className="header cell">{daySchedule.date}</div>
      <div className="cell text-left">
        { timeslots }
      </div>
    </div>;
  }

  renderSchedule() {
    return this.props.schedule.map(sch => {
      return this.renderDaySchedule(sch);
    });
  }

  render() {
    return (
      <div className="timetable container-fluid">
        <div className="row">
          <div className="col-12">
            <div className="row">
              <div className="col timetable-header header cell">
                {this.props.header}
              </div>
            </div>
            <div className="row mt-1">
              <div className="col-12 px-0">
                <div className="schedule">{this.renderSchedule()}</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    );
  }
}

Timetable.propTypes = {
  header: PropTypes.string.isRequired,
  schedule: PropTypes.array.isRequired
};

export default Timetable;

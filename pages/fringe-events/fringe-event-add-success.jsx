import React from 'react';
import { Link } from 'react-router-dom';
import Jumbotron from '../../components/jumbotron.jsx';

var Success = React.createClass({
  render: function() {
    return (
      <div className="proposals-page">
        <Jumbotron image="/assets/images/hero/fringe.jpg"
          image2x="/assets/images/hero/fringe.jpg">
          <h1>Fringe Events</h1>
        </Jumbotron>
        <div className="centered content wide">
          <h1>Submitted!</h1>
          <p>Thank you for your submission.</p>
          <p>Your event will show up on the MozFest Fringe Page after it has been reviewed by our team.</p>
          <div><Link to="/fringe" className="btn btn-primary-outline">See other fringe events</Link></div>
        </div>
      </div>
    );
  }
});

module.exports = Success;


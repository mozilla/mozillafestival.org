var React = require('react');
var Jumbotron = require('../components/jumbotron.jsx');

import generateHelmet from '../lib/helmet.jsx';

var Volunteer = React.createClass({
  pageMetaDescription: "Volunteers are integral to what we do at Mozilla, and MozFest is no different.",
  render: function() {
    return (
      <div className="volunteer-page">
        {generateHelmet(this.pageMetaDescription)}
        <Jumbotron image="/assets/images/hero/volunteer.jpg"
          image2x="/assets/images/hero/volunteer.jpg">
          <h1>volunteer</h1>
        </Jumbotron>
        <div className="content centered wide">
          <p>Volunteers are integral to what we do at Mozilla, and MozFest is no different. The annual festival could not take place without the time, dedication and love of our volunteers.</p>

          <p>Volunteer roles are diverse, from registering participants and running welcome desks on each floor to arranging swag bags and helping with speakers. We do our very best to place people in roles they're most interested in and rotate volunteers over the weekend in various roles.</p>

          <p>We host monthly meetups in the Mozilla London office, so volunteers can learn more about the festival, receive training and get to know each other.</p>

          <p>Volunteers are asked to share four hours of their time and as a thank you, we’ll cover the festival’s ticket cost. Volunteers also receive meals while on-site and a MozFest t-shirt. Unfortunately, we cannot cover travel to and from the event or provide accommodation. Read more on our <a href="https://wiki.mozilla.org/Mozfest/2018/Volunteers">volunteer wiki</a>.</p>
          <p>You can apply to be a Mozilla Festival Volunteer <a href="https://ti.to/mozfestvolunteers/mozfest-volunteer-program-2018" target="_blank">here</a>.</p>
        </div>
      </div>
    );
  }
});

module.exports = Volunteer;

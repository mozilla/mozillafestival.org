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
          <p>Volunteers are integral to what we do at Mozilla, and MozFest is no different. The annual festival could not take place without the time, dedication and love of our local volunteers.</p>

          <p>Drawn from the local community, volunteers at MozFest do so much, from running the welcome desk and arranging swag bags to assisting press and helping with programming. We try to place people in roles they’re most interested in, and rotation of roles is encouraged. We provide training before the event, and we host several volunteer socials. Read more on our <a href="https://wiki.mozilla.org/Mozfest/2017/Volunteers">volunteer wiki</a>.</p>

          <p>Volunteers are asked to share four hours of their time. As a thank you, we’ll cover the festival’s ticket cost. Volunteers also receive meals and a MozFest t-shirt. Unfortunately, we cannot cover travel to and from the event or provide accommodation.</p>
          
          <p>Applications for volunteer positions will open in the summer. In the meantime there are plenty of other ways to stay connected. Follow <a href="https://twitter.com/MozLDN">@mozldn</a> for free events in London and check out <a href="https://www.mozillapulse.org/featured">Mozilla Pulse</a> to find projects looking for collaborators.</p>
        </div>
      </div>
    );
  }
});

module.exports = Volunteer;

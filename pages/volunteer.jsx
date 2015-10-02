var React = require('react');
var Header = require('../components/header.jsx');
var Footer = require('../components/footer.jsx');
var HeroUnit = require('../components/hero-unit.jsx');
var generateHelmet = require('../lib/helmet.jsx');

var Volunteer = React.createClass({
  pageMetaDescription: "Volunteers are integral to what we do at Mozilla, and MozFest is no different.",
  componentDidMount: function() {
    var titoContainer = this.refs.titoContainer.getDOMNode();
    var titoWidget = document.querySelector(".widgets .tito-volunteer-tickets");
    titoContainer.appendChild(titoWidget);
  },
  componentWillUnmount: function() {
    var titoWidget = this.refs.titoContainer.getDOMNode().querySelector(".tito-volunteer-tickets");
    var widgetContainer = document.querySelector(".widgets");
    widgetContainer.appendChild(titoWidget);
  },
  render: function() {
    return (
      <div className="volunteer-page">
        {generateHelmet(this.pageMetaDescription)}
        <Header/>
        <HeroUnit image="/assets/images/volunteer.jpg"
                  image2x="/assets/images/volunteer.jpg">
          volunteer
        </HeroUnit>
        <div className="content centered wide">
          <p>Volunteers are integral to what we do at Mozilla, and MozFest is no different. The annual festival could not take place without the time, dedication and love our volunteers invest.</p>

          <p>Volunteers at MozFest do so much, from running the welcome desk and arranging schwag bags to assisting press and helping with programming. We try to place people in roles they’re most interested in, and rotation of roles is encouraged. We provide training before the event, and we host several volunteer socials.</p>

          <p>Volunteers are asked to share four hours of their time. As a thank you, we’ll cover the festival’s ticket cost. Volunteers also receive meals and a MozFest t-shirt. Unfortunately, we cannot cover travel to and from the event.</p>

          <p>The MozFest team meets bi-monthly in Mozilla’s London office, so come say hi and learn more. The next Mozpub is in October — email <a href="mailto:festival@mozilla.org">festival@mozilla.org</a> for details.</p>
          <div ref="titoContainer"></div>
        </div>
        <Footer/>
      </div>
    );
  }
});

module.exports = Volunteer;


var React = require('react');
var Header = require('../components/header.jsx');
var Footer = require('../components/footer.jsx');
var HeroUnit = require('../components/hero-unit.jsx');

var Contact = React.createClass({
  render: function() {
    return (
      <div>
        <Header/>
        <HeroUnit image="/assets/images/contact.jpg"
                  image2x="/assets/images/contact.jpg">
          contact us
        </HeroUnit>
        <div className="centered content wide">
          <h1>Hello there</h1>
          <p>Please email <a href="#">festival@mozilla.org</a> with your questions and suggestions! If you have a press inquiry, you can email us at <a href="#">mozfest@albiondrive.org</a>.</p>
          <div className="half-content">
            <h1>Make It Better!</h1>
            <p>Email us with suggestions to improve the Mozilla Festival. It's a collaborative event, and your feedback matters.</p>
          </div>
          <div className="half-content">
            <h1>Social Media</h1>
            <p>Use the hashtag #mozfest and join the conversation. Follow #mozfest on Twitter and Flickr.</p>
          </div>
        </div>
        <Footer/>
      </div>
    );
  }
});

module.exports = Contact;


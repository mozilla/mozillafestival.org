var React = require('react');

import Jumbotron from '../components/jumbotron.jsx';
import generateHelmet from '../lib/helmet.jsx';

var Contact = React.createClass({
  pageMetaDescription: "Please email festival@mozilla.org with your questions and suggestions!",
  render: function() {
    return (
      <div className="contact-page">
        {generateHelmet(this.pageMetaDescription)}
        <Jumbotron image="/assets/images/hero/contact.jpg"
          image2x="/assets/images/hero/contact.jpg">
          <h1>contact us</h1>
        </Jumbotron>
        <div className="centered content wide">
          <h1>Hello there</h1>
          <p><a href="https://ti.to/Mozilla/mozfest-2017">Sign up here</a> to receive festival news and updates by email.</p>
          <div className="half-content">
            <h1>Make It Better</h1>
            <p><a href="mailto:festival@mozilla.org">Email us</a> with suggestions to improve the Mozilla Festival. It's a collaborative event, and your feedback matters.</p>
          </div>
          <div className="half-content">
            <h1>Social Media</h1>
            <p>Use the hashtag <a href="https://twitter.com/search?f=realtime&q=%23mozfest&src=typd">#mozfest</a> on Twitter and join the conversation. Read the <a href="https://www.medium.com/mozilla-festival">MozFest blog</a> on Medium.
            </p>
          </div>
        </div>
      </div>
    );
  }
});

module.exports = Contact;

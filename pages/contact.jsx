import React from 'react';
import Jumbotron from '../components/jumbotron.jsx';
import generateHelmet from '../lib/helmet.jsx';

class Contact extends React.Component {
  constructor(props) {
    super(props);

    this.pageMetaDescription = `Please email festival@mozilla.org with your questions and suggestions!`;
  }

  render() {
    return (
      <div className="contact-page">
        {generateHelmet(this.pageMetaDescription)}
        <Jumbotron>
          <h1 className="highlight">Get In Touch</h1>
        </Jumbotron>
        <div className="white-background">
          <div className="centered content wide">
            <div className="container-fluid">
              <div className="row">
                <div className="col-12">
                  <h1>Contact Info</h1>
                </div>
                <div className="col-6">
                  <h2>MozFest Weekend</h2>
                  <p>
                    <a href="" target="_blank" className="d-block">Ravensbourne</a>
                    Greenwich Peninsula,<br/>
                    6 Penrose Way,<br/>
                    London SE10 0EW
                  </p>
                </div>
                <div className="col-6">
                  <h2>MozFest House</h2>
                  <p>
                    <a href="" target="_blank" className="d-block">Royal Society of Arts (RSA)</a>
                    8 John Adam St,<br/>
                    London WC2N 6EZ
                  </p>
                </div>
              </div>
              <div className="row">
                <div className="col-12">
                  <hr className="mt-4 mb-5" />
                  <h1>Raising Issues at the Festival</h1>
                  <p>If you experience any violation of the community participation guidelines while at Mozilla Festival please email us at [email] or contact us at [code].</p>
                  <p>In event of an emergency always dial 999.</p>
                </div>
              </div>
              <div className="row">
                <div className="col-12">
                  <hr className="mt-4 mb-5" />
                </div>
                <div className="col-6">
                  <h1>Email Us</h1>
                  <p>You can reach us with all enquiries at <a href="mailto:festival@mozilla.org">festival@mozilla.org</a>. During the festival weekend we will have a local emergency number, which will be updated closer to the event.</p>
                </div>
                <div className="col-6">
                  <h1>Social Media</h1>
                  <p>Use the hashtag <a href="https://twitter.com/search?f=realtime&q=%23mozfest&src=typd">#mozfest</a> on Twitter and join the conversation. Read the <a href="https://www.medium.com/mozilla-festival">MozFest blog</a> on Medium.
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div className="centered content wide my-0 py-5">
          We welcome suggestions to improve Mozilla Festival. It's a collaborative event and your feedback matters. Email us at <a href="mailto:festival@mozilla.org">festival@mozilla.org</a>
        </div>
      </div>
    );
  }
}

export default Contact;

var React = require('react');
var Header = require('../components/header.jsx');
var Footer = require('../components/footer.jsx');
var HeroUnit = require('../components/hero-unit.jsx');

var Proposals = React.createClass({
  onSubmit: function() {
    var sessionName = this.refs.sessionName.getDOMNode().value;
    var firstName = this.refs.firstName.getDOMNode().value;
    var surname = this.refs.surname.getDOMNode().value;
    var email = this.refs.email.getDOMNode().value;
    var organization = this.refs.organization.getDOMNode().value;
    var twitter = this.refs.twitter.getDOMNode().value;
    var otherFacilitators = this.refs.otherFacilitators.getDOMNode().value;
    var description = this.refs.description.getDOMNode().value;
    var agenda = this.refs.agenda.getDOMNode().value;
    var participants = this.refs.participants.getDOMNode().value;
    var outcome = this.refs.outcome.getDOMNode().value;
    function done(e) {
      console.log(e);
    }
    $.ajax({
      url: "/add-session",
      type: "POST",
      data: JSON.stringify({
        "sessionName": sessionName,
        "firstName": firstName,
        "surname": surname,
        "email": email,
        "organization": organization,
        "twitter": twitter,
        "otherFacilitators": otherFacilitators,
        "description": description,
        "agenda": agenda,
        "participants": participants,
        "outcome": outcome
      }),
      contentType: "application/json; charset=utf-8",
      statusCode: {
        0: done,
        200: done
      }
    });
  },
  render: function() {
    return (
      <div className="proposals-page">
        <Header/>
        <HeroUnit image="/assets/images/proposals.jpg"
                  image2x="/assets/images/proposals.jpg">
          call for proposals
        </HeroUnit>
        <div className="content">
          <div className="proposals-form">
            <h1>Share your idea</h1>
            <p>The Mozilla Festival is designed around three days of peer-led conversations, hands on workshops and skillshares.</p>

            <label htmlFor="sessionName">Session Name *</label>
            <input id="sessionName" ref="sessionName" type="text"/>

            <label htmlFor="firstName">First Name *</label>
            <input id="firstName" ref="firstName" type="text"/>
            <label htmlFor="surname">Surname *</label>
            <input id="surname" ref="surname" type="text"/>

            <label htmlFor="email">Email *</label>
            <input id="email" ref="email" type="text"/>
            <label htmlFor="organization">Organization</label>
            <input id="organization" ref="organization" type="text"/>
            <label htmlFor="twitter">What's your Twitter handle?</label>
            <input id="twitter" ref="twitter" type="text"/>

            <label htmlFor="otherFacilitators">Other facilitators</label>
            <input id="otherFacilitators" ref="otherFacilitators" type="text"/>

            <label htmlFor="description">What will your session or activity allow people to make, learn or do? Describe your session's goals in 150 words or less. *</label>
            <textarea id="description" ref="description"></textarea>

            <label htmlFor="agenda">How do you see that working? Describe your session's agenda in 150 words or less. *</label>
            <textarea id="agenda" ref="agenda"></textarea>

            <label htmlFor="participants">How will you accommodate varying numbers of participants in your session? Tell us what you'll do with 5 participants. 15? 50? *</label>
            <textarea id="participants" ref="participants"></textarea>

            <label htmlFor="outcome">What do you see as outcomes after the festival? How will you and your participants take the learning and activities forward? *</label>
            <textarea id="outcome" ref="outcome"></textarea>

            {/*<label>Tags - Click up to 7 that apply</label>
            <input type="text" placeholder="Other tags"/>*/}

            <input ref="privacy-policy" id="privacy-policy" type="checkbox"/>
            <label className="checkbox-input" htmlFor="privacy-policy">
              I&lsquo;m okay with Mozilla handling my info as explained in this <a href="https://www.mozilla.org/en-US/privacy/">Privacy Policy</a>.
            </label>
            <button onClick={this.onSubmit}>Submit</button>
          </div>
        </div>
        <Footer/>
      </div>
    );
  }
});

module.exports = Proposals;


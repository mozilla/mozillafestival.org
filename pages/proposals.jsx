var React = require('react');
var Header = require('../components/header.jsx');
var Footer = require('../components/footer.jsx');
var HeroUnit = require('../components/hero-unit.jsx');
var Icon = require('react-fa');

var RealInput = React.createClass({
  render: function() {
    if (this.props.type === "textarea") {
      return (
        <textarea onChange={this.props.updateFunction} id={this.props.for}/>
      );
    }
    return (
      <input onChange={this.props.updateFunction} id={this.props.for} type={this.props.type}/>
    );
  }
});

var InputCombo = React.createClass({
  updateFunction: function() {
    document.querySelector("#" + this.props.for + "Error").classList.remove("show");
  },
  render: function() {
    return (
      <div>
        <label id={this.props.for + "Link"} className={this.props.className} htmlFor={this.props.for}>
          {this.props.children}
        </label>
        <RealInput updateFunction={this.updateFunction} for={this.props.for} type={this.props.type}/>
        <div id={this.props.for + "Error"} className="error-message">{this.props.errorMessage}</div>
      </div>
    );
  }
});

var Proposals = React.createClass({
  onSubmit: function() {
    var self = this;
    document.querySelector("#generic-error").classList.remove("show");
    self.refs.submitButton.getDOMNode().classList.add("waiting");
    var fieldNames = "sessionName firstName surname email organization twitter otherFacilitators description agenda participants outcome".split(" ");
    var requiredFields = "sessionName firstName surname email description agenda participants outcome".split(" ");
    var fieldValues = {};
    var isError = "";

    var privacyPolicy = document.querySelector("#privacyPolicy").checked;

    for (var i = 0; i < fieldNames.length; i++) {
      fieldValues[fieldNames[i]] = document.querySelector("#" + fieldNames[i]).value;
      if (requiredFields.indexOf(fieldNames[i]) >= 0 && ( !fieldValues[fieldNames[i]] || !fieldValues[fieldNames[i]].trim() )) {
        if (!isError) {
          isError = "#" + fieldNames[i] + "Link";
        }
        if (document.querySelector("#" + fieldNames[i] + "Error")) {
          document.querySelector("#" + fieldNames[i] + "Error").classList.add("show");
        }
      }
    }

    if (!privacyPolicy) {
      if (!isError) {
        isError = "#privacyPolicyLink";
      }
      document.querySelector("#privacyPolicyError").classList.add("show");
    }

    if (isError) {
      self.refs.submitButton.getDOMNode().classList.remove("waiting");
      window.location.href = window.location.origin + window.location.pathname + isError;
      return;
    }
    $.ajax({
      url: "/add-session",
      type: "POST",
      data: JSON.stringify({
        "sessionName": fieldValues.sessionName,
        "firstName": fieldValues.firstName,
        "surname": fieldValues.surname,
        "email": fieldValues.email,
        "organization": fieldValues.organization,
        "twitter": fieldValues.twitter,
        "otherFacilitators": fieldValues.otherFacilitators,
        "description": fieldValues.description,
        "agenda": fieldValues.agenda,
        "participants": fieldValues.participants,
        "outcome": fieldValues.outcome
      }),
      contentType: "application/json; charset=utf-8",
      complete: function(e) {
        self.refs.submitButton.getDOMNode().classList.remove("waiting");
        if (e.responseJSON && e.responseJSON.error) {
          if (e.responseJSON.error === "ERR: Session Key 'test' already exists but has been deactivated! Use api/event/mod to set active=Y or to modify other data for this event.") {
            document.querySelector("#name-exists-error").classList.add("show");
            window.location.href = window.location.origin + window.location.pathname + "#sessionNameLink";
            var sessionNameInput = document.querySelector("#sessionName");
            function clearError() {
              document.querySelector("#name-exists-error").classList.remove("show");
              sessionNameInput.removeEventListener("input", clearError);
            }
            sessionNameInput.addEventListener("input", clearError);
          } else {
            document.querySelector("#generic-error").classList.add("show");
            window.location.href = window.location.origin + window.location.pathname + "#submit-button";
          }
        } else {
          window.location.href = "/session-add-success";
        }
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

            <InputCombo errorMessage="Session name is required." for="sessionName" type="text">
              Session Name *
            </InputCombo>
            <div id="name-exists-error" className="error-message">A session with that name already exists, please try another.</div>

            <InputCombo errorMessage="First name is required." for="firstName" type="text">
              First Name *
            </InputCombo>
            <InputCombo errorMessage="Surname is required." for="surname" type="text">
              Surname *
            </InputCombo>
            <InputCombo errorMessage="Email is required." for="email" type="text">
              Email *
            </InputCombo>

            <InputCombo for="organization" type="text">
              Organization
            </InputCombo>
            <InputCombo for="twitter" type="text">
              What's your Twitter handle?
            </InputCombo>
            <InputCombo for="otherFacilitators" type="text">
              Other facilitators
            </InputCombo>

            <InputCombo errorMessage="Description is required." for="description" type="textarea">
              What will your session or activity allow people to make, learn or do? Describe your session's goals in 150 words or less. *
            </InputCombo>
            <InputCombo errorMessage="Agenda is required." for="agenda" type="textarea">
              How do you see that working? Describe your session's agenda in 150 words or less. *
            </InputCombo>
            <InputCombo errorMessage="Participants is required." for="participants" type="textarea">
              How will you accommodate varying numbers of participants in your session? Tell us what you'll do with 5 participants. 15? 50? *
            </InputCombo>
            <InputCombo errorMessage="Outcome is required." for="outcome" type="textarea">
              What do you see as outcomes after the festival? How will you and your participants take the learning and activities forward? *
            </InputCombo>

            {/*<label>Tags - Click up to 7 that apply</label>
            <input type="text" placeholder="Other tags"/>*/}


            <InputCombo errorMessage="You must agree to our privacy policy." className="checkbox-input" for="privacyPolicy" type="checkbox">
              I&lsquo;m okay with Mozilla handling my info as explained in this <a href="https://www.mozilla.org/en-US/privacy/">Privacy Policy</a>.
            </InputCombo>
            <button id="submit-button" ref="submitButton" onClick={this.onSubmit}>Submit <Icon spin name="spinner"/></button>
            <div id="generic-error" className="error-message">Uh oh! There's an unknown error with your session proposal. Please try again later, or email <a href="mailto:festival@mozilla.org ">festival@mozilla.org</a> for help.</div>
          </div>
        </div>
        <Footer/>
      </div>
    );
  }
});

module.exports = Proposals;


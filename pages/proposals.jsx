var React = require('react');
var Header = require('../components/header.jsx');
var Footer = require('../components/footer.jsx');
var HeroUnit = require('../components/hero-unit.jsx');
var Icon = require('react-fa');

function ajax(url, data, callback) {
  var request = new XMLHttpRequest();
  request.open('POST', url, true);
  request.setRequestHeader('Content-Type', 'application/json');
  request.onload = function() {
    if (request.status >= 200 && request.status < 400) {
      // Success!
      if (request.response === "Ok") {
        callback();
      } else {
        callback(request.response);
      }
    } else {
      callback("error");
    }
  };
  request.send(JSON.stringify(data));
}

var RealInput = React.createClass({
  render: function() {
    if (this.props.type === "textarea") {
      return (
        <textarea className={this.props.className} maxLength={this.props.maxlength} onChange={this.props.updateFunction} id={this.props.for}/>
      );
    }
    return (
      <input maxLength={this.props.maxlength} onChange={this.props.updateFunction} id={this.props.for} type={this.props.type}/>
    );
  }
});

var InputCombo = React.createClass({
  updateFunction: function(e, other) {
    document.querySelector("#" + this.props.for + "Error").classList.remove("show");
    if (this.props.wordcount || this.props.wordcount === 0) {
      var value = e.target.value.trim();
      var wordcount = this.props.wordcount;
      if (value) {
        wordcount = this.props.wordcount - value.split(/\s+/).length;
      }
      this.setState({
        wordcount: wordcount
      });
    }
  },
  getInitialState: function() {
    if (this.props.wordcount || this.props.wordcount === 0) {
      return {
        wordcount: this.props.wordcount
      };
    } else {
      return {};
    }
  },
  render: function() {
    var self = this;
    var hasWordcount = (self.props.wordcount || self.props.wordcount === 0);
    var inputClassName = "";
    var wordcountClassName = "word-count";
    var inputContainerClassName = "input-container";
    if (hasWordcount) {
      inputClassName = "has-wordcount";
      inputContainerClassName += " input-container-has-wordcount";
      if (self.state.wordcount < 0) {
        wordcountClassName += " negative";
      }
    }
    return (
      <div className={this.props.for + "-container input-combo"}>
        <label id={this.props.for + "Link"} className={this.props.className} htmlFor={this.props.for}>
          {this.props.children}
        </label>
        <div className={inputContainerClassName}>
          {function() {
            if (hasWordcount) {
              return (<p className={wordcountClassName}>{self.state.wordcount}</p>);
            }
          }()}
          <RealInput className={inputClassName} maxlength={this.props.maxlength || 1650} updateFunction={this.updateFunction} for={this.props.for} type={this.props.type}/>
        </div>
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
      } else if (!isError && document.querySelector("." + fieldNames[i] + "-container .word-count") &&
                 parseInt(document.querySelector("." + fieldNames[i] + "-container .word-count").textContent, 10) < 0) {
        isError = "#" + fieldNames[i] + "Link";
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

    ajax("/add-session", {
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
        "outcome": fieldValues.outcome,
        "theme": self.refs.theme.getDOMNode().value,
        "mode": self.refs.mode.getDOMNode().value,
        "audience": self.refs.audience.getDOMNode().value
      },
      function(error) {
        self.refs.submitButton.getDOMNode().classList.remove("waiting");
        if (error) {
          if (error.contains("already exists")) {
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
    );
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
            <p>Please fill in the fields below to propose your session. Note: once you have submitted your session, you will not be able to edit it directly.</p>

            <p>Some planning tips: we believe in peer-to-peer sessions, learning through making, open dialog and hacking in small groups. Think participation, not PowerPoint.</p>

            <p>You should expect anywhere between five and 50 participants at your session. Be prepared for a range of group sizes, abilities and ages. As a facilitator, you will frame the session goals, team up small groups and ensure participants work productively and purposefully together.</p>

            <p>We're looking forward to reviewing your submission. If you have any questions, please email <a href="mailto:festival@mozilla.org">festival@mozilla.org</a>.</p>

            <InputCombo maxlength="120" errorMessage="Session name is required." for="sessionName" type="text">
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

            <InputCombo wordcount="150" errorMessage="Description is required." for="description" type="textarea">
              What will your session or activity allow people to make, learn or do? Describe your session's goals in 150 words or less. *
            </InputCombo>
            <InputCombo wordcount="150" errorMessage="Agenda is required." for="agenda" type="textarea">
              How do you see that working? Describe your session's agenda in 150 words or less. *
            </InputCombo>
            <InputCombo wordcount="150" errorMessage="Participants is required." for="participants" type="textarea">
              How will you accommodate varying numbers of participants in your session? Tell us what you'll do with 5 participants. 15? 50? In 150 words or less. *
            </InputCombo>
            <InputCombo wordcount="150" errorMessage="Outcome is required." for="outcome" type="textarea">
              What do you see as outcomes after the festival? How will you and your participants take the learning and activities forward? In 150 words or less. *
            </InputCombo>

            <label id="theme">Theme</label>
            <select ref="theme">
              <option value="make">Make</option>
              <option value="lead">Lead</option>
              <option value="teach">Teach</option>
              <option value="invent">Invent</option>
              <option value="play">Play</option>
            </select>
            <label id="mode">Mode</label>
            <select ref="mode">
              <option value="learning">Learning</option>
              <option value="craft">Craft</option>
              <option value="citizenship">Citizenship</option>
              <option value="science">Science</option>
              <option value="journalism">Journalism</option>
              <option value="arts">Arts</option>
            </select>
            <label id="audience">Audience</label>
            <select ref="audience">
              <option value="youth">Youth</option>
              <option value="developers">Developers</option>
              <option value="activists">Activists</option>
              <option value="educators">Educators</option>
              <option value="localizers">Localizers</option>
            </select>


            <InputCombo errorMessage="You must agree to our privacy policy." className="checkbox-input" for="privacyPolicy" type="checkbox">
              I&lsquo;m okay with Mozilla handling my info as explained in this <a href="https://www.mozilla.org/en-US/privacy/">Privacy Policy</a>.
            </InputCombo>
            <button className="button" id="submit-button" ref="submitButton" onClick={this.onSubmit}>Submit <Icon spin name="spinner"/></button>
            <div id="generic-error" className="error-message">Uh oh! There's an unknown error with your session proposal. Please try again later, or email <a href="mailto:festival@mozilla.org ">festival@mozilla.org</a> for help.</div>
          </div>
        </div>
        <Footer/>
      </div>
    );
  }
});

module.exports = Proposals;


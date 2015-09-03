var React = require('react');
var Header = require('../components/header.jsx');
var Footer = require('../components/footer.jsx');
var HeroUnit = require('../components/hero-unit.jsx');
var Icon = require('react-fa');
require('whatwg-fetch');

var RealInput = React.createClass({
  render: function() {
    if (this.props.type === "textarea") {
      return (
        <textarea className={this.props.className} maxLength={this.props.maxlength} onChange={this.props.updateFunction} id={this.props.for}/>
      );
    }
    return (
      <input maxLength={this.props.maxlength} onClick={this.props.onClick || function() {}} onChange={this.props.updateFunction} id={this.props.for} type={this.props.type}/>
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
    var hasWordcount = (this.props.wordcount || this.props.wordcount === 0);
    var inputClassName = "";
    var wordcountClassName = "word-count";
    var inputContainerClassName = "input-container";
    if (hasWordcount) {
      inputClassName = "has-wordcount";
      inputContainerClassName += " input-container-has-wordcount";
      if (this.state.wordcount < 0) {
        wordcountClassName += " negative";
      }
    }
    var wordcountElement;
    if (hasWordcount) {
      wordcountElement = (<p className={wordcountClassName}>{this.state.wordcount}</p>);
    }
    var labelElement = (
      <label id={this.props.for + "Link"} className={this.props.className} htmlFor={this.props.for}>
        {this.props.children}
      </label>
    );
    var topLabel;
    var bottomLabel;
    if (this.props.type !== "checkbox") {
      topLabel = labelElement;
    } else {
      bottomLabel = labelElement;
    }
    return (
      <div className={this.props.for + "-container input-combo " + this.props.type + "-container"}>
        {topLabel}
        <div className={inputContainerClassName}>
          {wordcountElement}
          <RealInput onClick={this.props.onClick || function() {}} className={inputClassName} maxlength={this.props.maxlength || 1650} updateFunction={this.updateFunction} for={this.props.for} type={this.props.type}/>
        </div>
        {bottomLabel}
        <div id={this.props.for + "Error"} className="error-message">{this.props.errorMessage}</div>
      </div>
    );
  }
});

var Proposals = React.createClass({
  otherInputClicked: function() {
    var checkbox = document.querySelector("#otherTheme");
    checkbox.checked = true;
    this.themeCheckboxClicked();
    document.querySelector("#theme-other-error-message").classList.remove("show");
  },
  otherCheckboxClicked: function() {
    var input = document.querySelector(".other-theme-input");
    input.focus();
    this.themeCheckboxClicked();
    document.querySelector("#theme-other-error-message").classList.remove("show");
  },
  themeCheckboxClicked: function() {
    document.querySelector("#theme-error-message").classList.remove("show");
  },
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

    var themeValues = "";
    var themeOtherError = false;
    var themes = "Advocacy,Citizenship,Data,Inclusion,Economics,Environment,Ethics and Values,Journalism,Leadership,Open Practices,Place,Privacy,Science,Sustainability,User Control,Web Literacy,Youth,Other".split(",");
    themes.forEach(function(val) {
      var dataVal = val.replace(/ /g, "");
      dataVal = dataVal[0].toLowerCase() + dataVal.slice(1);
      var theme = document.querySelector("#" + dataVal + "Theme");
      if (theme.checked) {
        if (dataVal === "other") {
          var otherValue = document.querySelector(".other-theme-input").value.trim();
          if (!otherValue) {
            themeOtherError = true;
          } else {
            themeValues += otherValue;
          }
        } else {
          themeValues += val;
        }
        themeValues += ", ";
      }
    });
    themeValues = themeValues.trim();
    // Remove last comma.
    themeValues = themeValues.substring(0, themeValues.length-1);

    if (themeOtherError) {
      document.querySelector("#theme-other-error-message").classList.add("show");
    }

    if (!themeValues) {
      if (!isError) {
        isError = "#otherTheme";
      }
      document.querySelector("#theme-error-message").classList.add("show");
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

    fetch('/add-session', {
      method: 'post',
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
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
        "theme": themeValues
      })
    }).then(function(response) {
      self.refs.submitButton.getDOMNode().classList.remove("waiting");
      if (response.ok) {
        window.location.href = "/session-add-success";
      } else {
        document.querySelector("#generic-error").classList.add("show");
        window.location.href = window.location.origin + window.location.pathname + "#submit-button";
      }
    });
  },
  render: function() {
    return (
      <div className="proposals-page">
        <Header/>
        <HeroUnit image="/assets/images/proposals.jpg"
                  image2x="/assets/images/proposals.jpg">
          <div>call for proposals</div>
          <div className="deadline">
            <span>Deadline for submission is 7th of September 2015 at 21:00 UTC</span>
          </div>
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
              How do you see that working? Describe your session's agenda in 150 words or less. Please also describe the format of the session, such as workshop, fireside chat or other. *
            </InputCombo>
            <InputCombo wordcount="150" errorMessage="Participants is required." for="participants" type="textarea">
              How will you accommodate varying numbers of participants in your session? Tell us what you'll do with 5 participants. 15? 50? In 150 words or less. *
            </InputCombo>
            <InputCombo wordcount="150" errorMessage="Outcome is required." for="outcome" type="textarea">
              What do you see as outcomes after the festival? How will you and your participants take the learning and activities forward? In 150 words or less. *
            </InputCombo>

            <label id="theme">Which topics are relevant for your session? You can choose multiple items. *</label>
            <InputCombo onClick={this.themeCheckboxClicked} className="checkbox-input theme-checkbox" for="advocacyTheme" type="checkbox">
              Advocacy
            </InputCombo>
            <InputCombo onClick={this.themeCheckboxClicked} className="checkbox-input theme-checkbox" for="citizenshipTheme" type="checkbox">
              Citizenship
            </InputCombo>
            <InputCombo onClick={this.themeCheckboxClicked} className="checkbox-input theme-checkbox" for="dataTheme" type="checkbox">
              Data
            </InputCombo>
            <InputCombo onClick={this.themeCheckboxClicked} className="checkbox-input theme-checkbox" for="inclusionTheme" type="checkbox">
              Inclusion
            </InputCombo>
            <InputCombo onClick={this.themeCheckboxClicked} className="checkbox-input theme-checkbox" for="economicsTheme" type="checkbox">
              Economics
            </InputCombo>
            <InputCombo onClick={this.themeCheckboxClicked} className="checkbox-input theme-checkbox" for="environmentTheme" type="checkbox">
              Environment
            </InputCombo>
            <InputCombo onClick={this.themeCheckboxClicked} className="checkbox-input theme-checkbox" for="ethicsandValuesTheme" type="checkbox">
              Ethics and Values
            </InputCombo>
            <InputCombo onClick={this.themeCheckboxClicked} className="checkbox-input theme-checkbox" for="journalismTheme" type="checkbox">
              Journalism
            </InputCombo>
            <InputCombo onClick={this.themeCheckboxClicked} className="checkbox-input theme-checkbox" for="leadershipTheme" type="checkbox">
              Leadership
            </InputCombo>
            <InputCombo onClick={this.themeCheckboxClicked} className="checkbox-input theme-checkbox" for="openPracticesTheme" type="checkbox">
              Open Practices
            </InputCombo>
            <InputCombo onClick={this.themeCheckboxClicked} className="checkbox-input theme-checkbox" for="placeTheme" type="checkbox">
              Place
            </InputCombo>
            <InputCombo onClick={this.themeCheckboxClicked} className="checkbox-input theme-checkbox" for="privacyTheme" type="checkbox">
              Privacy
            </InputCombo>
            <InputCombo onClick={this.themeCheckboxClicked} className="checkbox-input theme-checkbox" for="scienceTheme" type="checkbox">
              Science
            </InputCombo>
            <InputCombo onClick={this.themeCheckboxClicked} className="checkbox-input theme-checkbox" for="sustainabilityTheme" type="checkbox">
              Sustainability
            </InputCombo>
            <InputCombo onClick={this.themeCheckboxClicked} className="checkbox-input theme-checkbox" for="userControlTheme" type="checkbox">
              User Control
            </InputCombo>
            <InputCombo onClick={this.themeCheckboxClicked} className="checkbox-input theme-checkbox" for="webLiteracyTheme" type="checkbox">
              Web Literacy
            </InputCombo>
            <InputCombo onClick={this.themeCheckboxClicked} className="checkbox-input theme-checkbox" for="youthTheme" type="checkbox">
              Youth
            </InputCombo>
            <InputCombo onClick={this.otherCheckboxClicked} className="checkbox-input theme-checkbox" for="otherTheme" type="checkbox">
              Other: <input onClick={this.otherInputClicked} className="other-theme-input" type="text"/>
            </InputCombo>
            <div id="theme-other-error-message" className="error-message">Other cannot be empty.</div>
            <div id="theme-error-message" className="error-message">At least one theme is required.</div>

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


var React = require('react');
var Header = require('../components/header.jsx');
var Footer = require('../components/footer.jsx');
var HeroUnit = require('../components/hero-unit.jsx');

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
    var fieldNames = "sessionName firstName surname email organization twitter otherFacilitators description agenda participants outcome".split(" ");
    var requiredFields = "sessionName firstName surname email description agenda participants outcome".split(" ");
    var fieldValues = {};
    var isError = "";

    var privacyPolicy = document.querySelector("#privacyPolicy").checked;

    for (var i = 0; i < fieldNames.length; i++) {
      fieldValues[fieldNames[i]] = document.querySelector("#" + fieldNames[i]).value;
      if (requiredFields[fieldNames[i]] && ( !fieldValues[fieldNames[i]] || !fieldValues[fieldNames[i]].trim() )) {
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

    function done(e) {
      window.location.href = "/session-add-success";
    }
    if (isError) {
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

            <InputCombo errorMessage="session name error" for="sessionName" type="text">
              Session Name *
            </InputCombo>

            <InputCombo errorMessage="first name error" for="firstName" type="text">
              First Name *
            </InputCombo>
            <InputCombo errorMessage="surname error" for="surname" type="text">
              Surname *
            </InputCombo>
            <InputCombo errorMessage="email error" for="email" type="text">
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

            <InputCombo errorMessage="participants error" for="description" type="textarea">
              What will your session or activity allow people to make, learn or do? Describe your session's goals in 150 words or less. *
            </InputCombo>
            <InputCombo errorMessage="participants error" for="agenda" type="textarea">
              How do you see that working? Describe your session's agenda in 150 words or less. *
            </InputCombo>
            <InputCombo errorMessage="participants error" for="participants" type="textarea">
              How will you accommodate varying numbers of participants in your session? Tell us what you'll do with 5 participants. 15? 50? *
            </InputCombo>
            <InputCombo errorMessage="outcome error" for="outcome" type="textarea">
              What do you see as outcomes after the festival? How will you and your participants take the learning and activities forward? *
            </InputCombo>

            {/*<label>Tags - Click up to 7 that apply</label>
            <input type="text" placeholder="Other tags"/>*/}


            <InputCombo errorMessage="privacy policy error" className="checkbox-input" for="privacyPolicy" type="checkbox">
              I&lsquo;m okay with Mozilla handling my info as explained in this <a href="https://www.mozilla.org/en-US/privacy/">Privacy Policy</a>.
            </InputCombo>

            <button onClick={this.onSubmit}>Submit</button>
          </div>
        </div>
        <Footer/>
      </div>
    );
  }
});

module.exports = Proposals;


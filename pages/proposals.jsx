var React = require('react');
var Header = require('../components/header.jsx');
var Footer = require('../components/footer.jsx');
var HeroUnit = require('../components/hero-unit.jsx');
var Icon = require('react-fa');
require('whatwg-fetch');

var RealInput = React.createClass({
  render: function() {
    var otherClassName = "";
    if (this.props.isOther) {
      otherClassName = "other"
    }
    if (this.props.type === "textarea") {
      return (
        <textarea className={this.props.className} name={this.props.name || ""} maxLength={this.props.maxlength} onChange={this.props.updateFunction} id={this.props.for}/>
      );
    }
    return (
      <input className={otherClassName} maxLength={this.props.maxlength} name={this.props.name || ""} onClick={this.props.onClick || function() {}} onChange={this.props.updateFunction} id={this.props.for} type={this.props.type}/>
    );
  }
});

var InputCombo = React.createClass({
  updateFunction: function(e) {
    var errorElement = document.querySelector("#" + this.props.name + "Error");
    if (errorElement) {
      errorElement.classList.remove("show");
    }
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
    var errorElement = (<div></div>);
    if (this.props.errorMessage) {
      errorElement = (
        <div id={this.props.name + "Error"} className="error-message">{this.props.errorMessage}</div>
      );
    }
    var topLabel;
    var bottomLabel;
    if (this.props.type !== "checkbox" && this.props.type !== "radio") {
      topLabel = labelElement;
    } else {
      bottomLabel = labelElement;
    }
    return (
      <div className={this.props.for + "-container input-combo " + this.props.type + "-container"}>
        {topLabel}
        <div className={inputContainerClassName}>
          {wordcountElement}
          <RealInput isOther={this.props.isOther} onClick={this.props.onClick || function() {}} className={inputClassName} maxlength={this.props.maxlength || 1650} updateFunction={this.updateFunction} for={this.props.for} type={this.props.type} name={this.props.name}/>
        </div>
        {bottomLabel}
        {errorElement}
      </div>
    );
  }
});

var lazyDataMap = {
  "first-name": "firstName",
  "surname": "surname",
  "email": "email",
  "organization": "affiliationOrganization",
  "other-facilitators": "otherFacilitators",
  "twitter-handle": "twitter",
  "space-radio": "space",

  "exhibit-title": "exhibitTitle",
  "exhibit-method": "exhibitMethod",
  "exhibit-link": "exhibitLink",
  "desc-your-work": "exhibitDescription",
  "learn-and-reflect": "exhibitLearnReflect",
  "good-to-show": "exhibitWhyGoodMozfest",
  "another-space-radio": "exhibitAnotherSpace",

  "format-checkbox": "descWorkBest",
  "session-title": "sessionTitle",
  "desc-allow": "descMakeLearn",
  "desc-working": "descHowWorking",
  "participants": "descParticipants",
  "outcome": "descOutcome",
  "languge": "descAnotherLang",
  "travel-radio": "descTravel",
  "backup-space-radio": "descOtherSpace"
};

var Proposals = React.createClass({
  submit: function(fields) {
    var self = this;
    var fieldValues = {};
    fields.forEach(function(field) {
      var elements = document.querySelectorAll("[name=\"" + field + "\"]");
      var results = [];
      Array.prototype.forEach.call(elements, function(element) {
        var type = element.type;
        var value = "";
        if (type === "text" || type === "textarea") {
          // submit value.trim
          value = element.value.trim();
          if (value) {
            results.push(value);
          }
        }
        if (type === "radio" || type === "checkbox") {
          if (element.classList.contains("other")) {
            // get value if is other
            if (element.checked) {
              results.push(document.querySelector("." + field).value.trim());
            }
          } else {
            if (element.checked) {
              // get value if it's not other
              results.push(element.id);
            }
          }
        }
      });
      if (results.length) {
        fieldValues[lazyDataMap[field]] = results.join(", ");
      }
    });
    fetch('/add-session', {
      method: 'post',
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(fieldValues)
    }).then(function(response) {
      self.refs.submitButton1.getDOMNode().classList.remove("waiting");
      self.refs.submitButton2.getDOMNode().classList.remove("waiting");
      if (response.ok) {
        window.location.href = "/session-add-success#success";
      } else {
        document.querySelector("#generic-error-1").classList.add("show");
        document.querySelector("#generic-error-2").classList.add("show");
      }
    });
  },
  getInitialState: function() {
    return {
      section: "one"
    };
  },
  onSpaceClicked: function(e) {
    var isExhibit = false;
    if (e && e.target.id === "exhibit") {
      isExhibit = true;
    }
    this.setState({
      exhibit: isExhibit
    });
  },
  validateField: function(field) {
    var type = field.type;
    if (type === "text" || type === "textarea") {
      return !!field.value.trim();
    }
    if (type === "radio") {
      return field.checked;
    }
    if (type === "checkbox") {
      return field.checked;
    }
  },
  validateFields: function(fields) {
    var valid = false;
    var validateField = this.validateField;
    Array.prototype.forEach.call(fields, function(field) {
      if (!valid) {
        valid = validateField(field);
      }
    });
    return valid;
  },
  validate: function(fields) {
    var valid = true;
    var validateFields = this.validateFields;
    var toggleError = this.toggleError;
    fields.forEach(function(field) {
      var fieldValid = false;
      fieldValid = validateFields(document.querySelectorAll("[name=\"" + field + "\"]"));
      if (!fieldValid) {
        toggleError(field, true);
      }
      if (valid) {
        valid = fieldValid;
      }
    });
    return valid;
  },
  toggleError: function(field, on) {
    var ele = document.querySelector("#" + field + "Error");
    if (on) {
      ele.classList.add("show");
    } else {
      ele.classList.remove("show");
    }
  },
  otherFormatInputClicked: function() {
    var checkbox = document.querySelector("#other-format");
    checkbox.checked = true;
    this.formatCheckboxClicked();
  },
  otherFormatCheckboxClicked: function() {
    var input = document.querySelector(".format-other-input");
    input.focus();
    this.formatCheckboxClicked();
  },
  formatCheckboxClicked: function() {
    document.querySelector("#format-other-error-message").classList.remove("show");
  },
  descOnSubmit: function() {
    document.querySelector("#generic-error-2").classList.remove("show");
    var valid = this.validate("privacyPolicy-2, travel-radio, outcome, participants, desc-working, session-title, desc-allow".split(", "));
    var checkbox = document.querySelector("#other-format");
    if (checkbox.checked) {
      var input = document.querySelector(".format-other-input");
      var inputValid = !!input.value.trim();
      if (!inputValid) {
        document.querySelector("#format-other-error-message").classList.add("show");
        valid = false;
      }
    } else if (!this.validate(["format-checkbox"])) {
      valid = false;
    }
    if (!valid) {
      window.location.hash = "#anchor-form";
      return;
    }
    this.refs.submitButton2.getDOMNode().classList.add("waiting");
    this.submit("email, first-name, surname, organization, other-facilitators, twitter-handle, space-radio, format-checkbox, session-title, desc-allow, desc-working, participants, outcome, languge, travel-radio, backup-space-radio".split(", "));
  },
  exhibitOnSubmit: function() {
    document.querySelector("#generic-error-1").classList.remove("show");
    var valid = this.validate("privacyPolicy, original-work, good-to-show, learn-and-reflect, desc-your-work, exhibit-link, exhibit-method, exhibit-title".split(", "));
    if (!valid) {
      window.location.hash = "#anchor-form";
      return;
    }
    this.refs.submitButton1.getDOMNode().classList.add("waiting");
    this.submit("email, first-name, surname, organization, other-facilitators, twitter-handle, space-radio, exhibit-title, exhibit-method, exhibit-link, desc-your-work, learn-and-reflect, good-to-show, another-space-radio".split(", "));
  },
  onNext: function() {
    var valid = this.validate("email, first-name, surname, space-radio".split(", "));
    if (!valid) {
      window.location.hash = "#anchor-form";
      return;
    }

    if (this.state.exhibit) {
      this.setState({
        section: "exhibit"
      }, function() {
        window.location.hash = "#anchor-top";
      });
    } else {
      this.setState({
        section: "desc"
      }, function() {
        window.location.hash = "#anchor-form";
      });
    }
  },
  renderStepOne: function() {
    return (
      <div>
        <InputCombo errorMessage="First name is required." for="firstName" type="text" name="first-name">
          First Name *
        </InputCombo>
        <InputCombo errorMessage="Surname is required." for="surname" type="text" name="surname">
          Surname *
        </InputCombo>
        <InputCombo errorMessage="Email is required." for="email" type="text" name="email">
          Email *
        </InputCombo>
        <InputCombo for="organization" type="text" name="organization">
          <div>Organisation</div>
          <div>Please leave blank if you don't want it published.</div>
        </InputCombo>
        <InputCombo for="otherFacilitators" type="text" name="other-facilitators">
          <div>Other facilitators</div>
          <div>One per line, format is Name[comma] Twitter handle[comma] email (e.g. John Doe, @j.doe, j.doe@example.com)</div>
        </InputCombo>
        <InputCombo for="twitter" type="text" name="twitter-handle">
          <div>Twitter handle</div>
          <div>Please leave blank if you don't want it published.</div>
        </InputCombo>

        <label>What Space are you submitting your proposal to? Read details on each Space <a href="/spaces-and-sessions">here</a>. *</label>
        <InputCombo onClick={this.onSpaceClicked} name="space-radio" className="radio-input" for="digital" type="radio">
          Digital Arts and Culture
        </InputCombo>
        <InputCombo onClick={this.onSpaceClicked} name="space-radio" className="radio-input" for="localisation" type="radio">
          Localisation
        </InputCombo>
        <InputCombo onClick={this.onSpaceClicked}  name="space-radio" className="radio-input" for="science" type="radio">
          Open Science
        </InputCombo>
        <InputCombo onClick={this.onSpaceClicked}  name="space-radio" className="radio-input" for="journalism" type="radio">
          Journalism
        </InputCombo>
        <InputCombo onClick={this.onSpaceClicked}  name="space-radio" className="radio-input" for="exhibit" type="radio">
          Moz Ex (art exhibit)
        </InputCombo>
        <InputCombo onClick={this.onSpaceClicked}  name="space-radio" className="radio-input" for="cities" type="radio">
          A Tale of Two Cities: Dilemmas of Connected Spaces
        </InputCombo>
        <InputCombo onClick={this.onSpaceClicked}  name="space-radio" className="radio-input" for="demystify" type="radio">
          Demystify the Web
        </InputCombo>
        <InputCombo onClick={this.onSpaceClicked}  name="space-radio" className="radio-input" for="badges" type="radio">
          Open Badges
        </InputCombo>
        <InputCombo onClick={this.onSpaceClicked}  name="space-radio" className="radio-input" for="youth" type="radio">
          Youth Zone
        </InputCombo>
        <InputCombo onClick={this.onSpaceClicked}  name="space-radio" className="radio-input" for="movement" type="radio">
          Fuel the Movement
        </InputCombo>
        <div id="space-radioError" className="error-message">A Space is required.</div>

        <button className="button full-width" id="next-button" ref="nextButton" onClick={this.onNext}>Next <Icon spin name="spinner"/></button>
      </div>
    );
  },
  renderStepTwo: function() {
    return (
      <div>
        <InputCombo errorMessage="Title is required." for="exhibit-title" name="exhibit-title" type="text">
          Title of Piece *
        </InputCombo>
        <InputCombo errorMessage="Method is required." for="exhibit-method" name="exhibit-method" type="text">
          Method *
        </InputCombo>
        <InputCombo errorMessage="Link is required." for="exhibit-link" name="exhibit-link" type="text">
          Link to see your work *
        </InputCombo>

        <InputCombo wordcount="150" errorMessage="Description is required." for="desc-your-work" name="desc-your-work" type="textarea">
          Describe your work in 150 words or less. *
        </InputCombo>

        <InputCombo errorMessage="Field is required." for="learn-and-reflect" name="learn-and-reflect" type="text">
          What will your work allow people to learn and reflect on? *
        </InputCombo>

        <InputCombo errorMessage="Field is required." for="good-to-show" name="good-to-show" type="text">
          Why do you think Mozfest is a good place to show your work? *
        </InputCombo>

        <label>Is there another Space you believe this exhibit could be shown within? Read details on each Space <a href="/spaces-and-sessions">here</a>.</label>
        <InputCombo name="another-space-radio" className="radio-input" for="another-digital" type="radio">
          Digital  Arts and Culture
        </InputCombo>
        <InputCombo name="another-space-radio" className="radio-input" for="another-localisation" type="radio">
          Localisation
        </InputCombo>
        <InputCombo name="another-space-radio" className="radio-input" for="another-science" type="radio">
          Open Science
        </InputCombo>
        <InputCombo name="another-space-radio" className="radio-input" for="another-journalism" type="radio">
          Journalism
        </InputCombo>
        <InputCombo name="another-space-radio" className="radio-input" for="another-cities" type="radio">
          A Tale of Two Cities: Dilemmas in Connected Spaces
        </InputCombo>
        <InputCombo name="another-space-radio" className="radio-input" for="another-demystify" type="radio">
          Demystify the Web
        </InputCombo>
        <InputCombo name="another-space-radio" className="radio-input" for="another-badges" type="radio">
          Open Badges
        </InputCombo>
        <InputCombo name="another-space-radio" className="radio-input" for="another-youth" type="radio">
          Youth Zone
        </InputCombo>
        <InputCombo name="another-space-radio" className="radio-input" for="another-movement" type="radio">
          Fuel the Movement
        </InputCombo>
        <InputCombo name="another-space-radio" className="radio-input" for="another-none" type="radio">
          None
        </InputCombo>

        <div className="original-work-item-container">
          <h4 className="original-work-item">Terms and Conditions</h4>

          <div className="original-work-item">
            Anything you submit must be your own original work. If your content contains other’s material (e.g. images, video, music, etc) you must have obtained the necessary permissions to use the material.
          </div>

          <div className="original-work-item">
            As Mozilla is an advocate for working in the open, we recommend you licence your work under a Creative Commons Attribution-ShareAlike 3.0 Unported license, thus increasing the use and reuse of your work while preserving attribution and contributing to the commons.
          </div>

          <div className="original-work-item">
            Alongside the use of your work at MozFest 2016, the Digital Learning team at Tate and the V&A will select artworks to feature in a pop-up show-case as part of the Tate Exchange programme. Your content will not be used for commercial purposes but we may use your content to promote our platform or project using social media, websites, and other web-platforms.
          </div>
          <InputCombo errorMessage="You must agree to the terms and conditions above." className="checkbox-input" for="original-work" name="original-work" type="checkbox">
            Yes, I agree to the terms and conditions above.
          </InputCombo>
        </div>

        <InputCombo errorMessage="You must agree to our privacy policy." className="checkbox-input" for="privacyPolicy" name="privacyPolicy" type="checkbox">
          I&lsquo;m okay with Mozilla handling my info as explained in this <a href="https://www.mozilla.org/en-US/privacy/">Privacy Policy</a>.
        </InputCombo>
        <button className="button full-width" id="submit-button" ref="submitButton1" onClick={this.exhibitOnSubmit}>Submit <Icon spin name="spinner"/></button>
        <div id="generic-error-1" className="error-message">Uh oh! There's an unknown error with your session proposal. Please try again later, or email <a href="mailto:festival@mozilla.org ">festival@mozilla.org</a> for help.</div>
      </div>
    );
  },
  renderStepThree: function() {
    return (
      <div>
        <label>MozFest accepts many different session formats. What do you think would work best for your session? (you may select multiple formats) *</label>
        <InputCombo name="format-checkbox" onClick={this.formatCheckboxClicked} className="checkbox-input" for="demo" type="checkbox">
          Demo or Lightning talk - 5-15 minute showcase of completed work  or product
        </InputCombo>
        <InputCombo name="format-checkbox" onClick={this.formatCheckboxClicked} className="checkbox-input" for="learning-lab" type="checkbox">
          Learning Lab - 60 minute hands on skillshare or small group discussion (technical or other)
        </InputCombo>
        <InputCombo name="format-checkbox" onClick={this.formatCheckboxClicked} className="checkbox-input" for="fireside" type="checkbox">
          Fireside chat - 45 minute talk with time for questions and answers
        </InputCombo>
        <InputCombo name="format-checkbox" onClick={this.formatCheckboxClicked} className="checkbox-input" for="hands-on" type="checkbox">
          Hands-on workshop - 1-3 hours for hacking, making, designing or producing a 'thing'
        </InputCombo>
        <InputCombo name="format-checkbox" onClick={this.formatCheckboxClicked} className="checkbox-input" for="game" type="checkbox">
          Game
        </InputCombo>
        <InputCombo name="format-checkbox" onClick={this.formatCheckboxClicked} className="checkbox-input" for="format-not-sure" type="checkbox">
          I am not sure
        </InputCombo>
        <InputCombo onClick={this.otherFormatCheckboxClicked} name="format-checkbox" isOther={true} className="checkbox-input" for="other-format" type="checkbox">
          Other
        </InputCombo>
        <div id="format-checkboxError" className="error-message">Format is required.</div>

        <label>If you chose "other" above, please outline why.</label>
        <input onClick={this.otherFormatInputClicked} className="format-checkbox other-input format-other-input" type="text"/>
        <div id="format-other-error-message" className="error-message">Other cannot be empty.</div>

        <InputCombo errorMessage="Session title is required." for="sessionTitle" type="text" name="session-title">
          Session title *
        </InputCombo>

        <InputCombo wordcount="150" errorMessage="Field is required." for="desc-allow" name="desc-allow" type="textarea">
          Describe what your session or activity will allow people to make, learn or do in 150 words or less. *
        </InputCombo>

        <InputCombo wordcount="150" errorMessage="Field is required." for="desc-working" name="desc-working" type="textarea">
          Describe how you see that working in 150 words or less. *
        </InputCombo>

        <InputCombo errorMessage="Field is required." for="participants" name="participants" type="textarea">
          How will you accommodate varying numbers of participants in your session? Tell us what you'll do with 3 participants? 15? 25? *
        </InputCombo>

        <InputCombo wordcount="150" errorMessage="Outcome is required." for="outcome" name="outcome" type="textarea">
          What do you see as outcomes after the festival? How will you and your participants take the learning and activities forward? In 150 words or less. *
        </InputCombo>

        <InputCombo for="language" name="languge" type="text">
          Are you comfortable leading your session in another language? If yes, please outline below:
        </InputCombo>

        <label>MozFest offers limited places for travel sponsorship (stipends) covering flights and accommodation over the festival weekend. These places are offered to those who need support traveling to the event and would have no other means to attend through work or personal covering of costs. We offer these stipends to encourage diversity in facilitators. *</label>
        <InputCombo className="radio-input" name="travel-radio" for="travel-only" type="radio">
          I can only attend MozFest if I receive a travel stipend covering my travel and accommodation
        </InputCombo>
        <InputCombo className="radio-input" name="travel-radio" for="travel-none" type="radio">
          I do not require a travel stipend as I, or my work, can cover my costs
        </InputCombo>
        <div id="travel-radioError" className="error-message">Field is required.</div>

        <label>If your session is not accepted in your preferred Space is there another space you would like your session to be considered within? Read details on each Space  <a href="/spaces-and-sessions">here</a>.</label>
        <InputCombo name="backup-space-radio" className="radio-input" for="digital-backup" type="radio">
          Digital Art and Culture
        </InputCombo>
        <InputCombo name="backup-space-radio" className="radio-input" for="localisation-backup" type="radio">
          Localisation
        </InputCombo>
        <InputCombo name="backup-space-radio" className="radio-input" for="science-backup" type="radio">
          Open Science
        </InputCombo>
        <InputCombo name="backup-space-radio" className="radio-input" for="journalism-backup" type="radio">
          Journalism
        </InputCombo>
        <InputCombo name="backup-space-radio" className="radio-input" for="exhibit-backup" type="radio">
          Moz Ex (exhibit)
        </InputCombo>
        <InputCombo name="backup-space-radio" className="radio-input" for="cities-backup" type="radio">
          A Tale of Two Cities: Dilemmas in Connected Spaces
        </InputCombo>
        <InputCombo name="backup-space-radio" className="radio-input" for="demystify-backup" type="radio">
          Demystify the Web
        </InputCombo>
        <InputCombo name="backup-space-radio" className="radio-input" for="badges-backup" type="radio">
          Open Badges
        </InputCombo>
        <InputCombo name="backup-space-radio" className="radio-input" for="youth-backup" type="radio">
          Youth Zone
        </InputCombo>
        <InputCombo name="backup-space-radio" className="radio-input" for="movement-backup" type="radio">
          Fuel the Movement
        </InputCombo>
        <InputCombo name="backup-space-radio" className="radio-input" for="none-backup" type="radio">
          None
        </InputCombo>

        <InputCombo errorMessage="You must agree to our privacy policy." className="checkbox-input" for="privacyPolicy-2" name="privacyPolicy-2" type="checkbox">
          I&lsquo;m okay with Mozilla handling my info as explained in this <a href="https://www.mozilla.org/en-US/privacy/">Privacy Policy</a>.
        </InputCombo>
        <button className="button full-width" id="submit-button" ref="submitButton2" onClick={this.descOnSubmit}>Submit <Icon spin name="spinner"/></button>
        <div id="generic-error-2" className="error-message">Uh oh! There's an unknown error with your session proposal. Please try again later, or email <a href="mailto:festival@mozilla.org ">festival@mozilla.org</a> for help.</div>
      </div>
    );
  },
  renderHeaderText: function() {
    if (this.state.section === "exhibit") {
      return (
        <div>
          <h1>Moz Ex (art exhibit)</h1>
          <p>Call for artists, designers, creative technologists, makers, coders, scientists, visual journalists -- from techies to newbies! We are inviting submissions of original artwork that relates to our lives online.</p>

          <p>Submissions can be mixed-media, photography, video, games, animation or moving images, visual art, graphic design, or sculpture. Although the work may be performative or 3D in origin, please consider that it will be exhibited on screens and must be submitted digitally.</p>

          <p>On this call we are only accepting digital work. The set-up and curation of the exhibit will be done through an exploration of DIY solutions, mainly working with a variety of screens, open-source technology and experimenting with layouts.</p>

          <p>Please ensure images are 300 dpi. Videos should have a 4:3 or 16:9 ratio, and be 5 minutes max.</p>
        </div>
      );
    } else if (this.state.section === "one") {
      return (
        <div>
          <h1>Share your idea</h1>
          <p>MozFest is a diverse, interactive and highly inclusive event with sessions, activities and experiences suited for a range of interaction styles.</p>

          <p>You should expect anywhere between 3 and 25 participants at your session. Be prepared for all abilities and ages in the audience. As a facilitator, you will frame the session goals, team up small groups and ensure participants work productively and purposefully together.</p>

          <p>Anyone may submit a proposal for MozFest 2016. Once you have submitted your session, it will enter an open curation process on Github. </p>

          <p>This year we are also accepting proposals in <a href="https://bit.ly/MozFestSpanish">Spanish</a>, <a href="https://mzl.la/Fran%C3%A7ais">French</a> and <a href="https://mzl.la/MozFestArabic">Arabic</a>.</p>

          <p>If you have any questions, please email <a href="mailto:festival@mozilla.org">festival@mozilla.org</a>.</p>
        </div>
      );
    }
    return (<div></div>);
  },
  render: function() {
    var sectionOneClassName = "proposal-section section-1";
    var sectionExhibitClassName = "proposal-section section-exhibit";
    var sectionDescClassName = "proposal-section section-desc";
    if (this.state.section === "one") {
      sectionDescClassName += " hidden";
      sectionExhibitClassName += " hidden";
    } else if (this.state.section === "exhibit") {
      sectionOneClassName += " hidden";
      sectionDescClassName += " hidden";
    } else if (this.state.section === "desc") {
      sectionOneClassName += " hidden";
      sectionExhibitClassName += " hidden";
    }
    return (
      <div className="proposals-page">
        <Header/>
        <HeroUnit image="/assets/images/proposals.jpg"
                  image2x="/assets/images/proposals.jpg">
          <div>call for proposals</div>
          <div className="deadline">
            <span>Deadline for submissions is August 1, 2016 at 21:00 UTC</span>
          </div>
        </HeroUnit>
        <div className="content">
          <div className="proposals-form">
            <div id="anchor-top"></div>

            {this.renderHeaderText()}

            <div id="anchor-form"></div>
            <div className={sectionOneClassName}>
              {this.renderStepOne()}
            </div>

            <div className={sectionExhibitClassName}>
              {this.renderStepTwo()}
            </div>

            <div className={sectionDescClassName}>
              {this.renderStepThree()}
            </div>
          </div>
        </div>
        <Footer/>
      </div>
    );
  }
});

module.exports = Proposals;

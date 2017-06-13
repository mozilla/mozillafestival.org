var React = require('react');
var Link = require('react-router').Link;
var classnames = require('classnames');
var Header = require('../../components/header.jsx');
var Footer = require('../../components/footer.jsx');
var Jumbotron = require('../../components/jumbotron.jsx');
var Router = require('react-router');
var Form = require('react-formbuilder').Form;
var fields = require('./form/fields');
var generateHelmet = require('../../lib/helmet.jsx');
var EnglishStrings = require('./language/english.json');

const SUBMISSION_STATUS_SUCCESS = `success`;
const SUBMISSION_STATUS_FAIL = `fail`;

var Proposal = React.createClass({
  pageMetaDescription: "Session proposals",
  getInitialState() {
    return {
      formValues: {},
      submitting: false,
      submissionStatus: ``,
      showFormInvalidNotice: false
    };
  },
  handleSubmitAnother(event) {
    event.preventDefault();
    this.setState(this.getInitialState());
  },
  handleFormUpdate(evt, name, field, value) {
    let formValues = this.state.formValues;
    formValues[name] = value;
    this.setState({ 
      formValues,
      // hide notice once user starts typing again
      // this is a quick fix.
      showFormInvalidNotice: false
    });
  },
  handleFormSubmit(event) {
    event.preventDefault();

    this.refs.formPartOne.validates(partOneIsValid => {
      this.refs.formPartTwo.validates(partTwoIsValid => {
        this.refs.formPartThree.validates(partThreeIsValid => {
          this.refs.formPartFour.validates(partFourIsValid => {
            if (!partOneIsValid) console.error(`Form Part One does not pass validation!`);
            if (!partTwoIsValid) console.error(`Form Part Two does not pass validation!`);
            if (!partThreeIsValid) console.error(`Form Part Three does not pass validation!`);
            if (!partFourIsValid) console.error(`Form Part Four does not pass validation!`);

            if (partOneIsValid && partTwoIsValid && partThreeIsValid && partFourIsValid) {
              this.setState({
                submitting: true,
                showFormInvalidNotice: false
              }, () => {
                this.submitProposal(this.state.formValues);
              });
            } else {
              this.setState({showFormInvalidNotice: true});
            }
          });
        });
      });      
    });

  },
  formatProposal(proposal) {
    const DEFAULT_OPTIONS = EnglishStrings.form_field_options;
    const FIELD_OPTIONS = this.props.localizedStrings.form_field_options;
    const SPACES = FIELD_OPTIONS.spaces;
    const TIME = FIELD_OPTIONS.timeneeded;
    const LANGUAGES = FIELD_OPTIONS.languages;

    let formatted = Object.assign({}, proposal);

    // Although Space labels are localized, when we submit the entry to
    // Google Spreadsheet and GitHub we want them to be in English.
    // This is to make the curation process simpler.
    for (let key in SPACES) {
      if (SPACES[key] === formatted.space) {
        formatted.space = DEFAULT_OPTIONS.spaces[key];
      }
      if (SPACES[key] === formatted.secondaryspace) {
        formatted.secondaryspace = DEFAULT_OPTIONS.spaces[key];
      }
    }
    if (formatted.secondaryspace === DEFAULT_OPTIONS.spaces.none) {
      delete formatted.secondaryspace;
    }

    // Similarly, although Language labels are localized, when we submit the entry to
    // Google Spreadsheet and GitHub we want them to be in English.
    // This is to make the curation process simpler.
    let additionalLang = formatted.additionallanguage;
    let otherAdditionalLang = formatted.additionallanguageother;

    delete formatted.additionallanguage;
    delete formatted.additionallanguageother;

    if (additionalLang === LANGUAGES.other) {
      // we record "additionallanguage" only if user has specified the language
      if (otherAdditionalLang) {
        formatted.additionallanguage = `${DEFAULT_OPTIONS.languages.other}: ${otherAdditionalLang}`;
      }
    } else {
      for (let key in LANGUAGES) {
        if (LANGUAGES[key] === formatted.space) {
          formatted.additionallanguage = DEFAULT_OPTIONS.languages[key];
        }
      }
    }

    // Do the same for "timeneeded"
    for (let key in TIME) {
      if (TIME[key] === formatted.timeneeded) {
        formatted.timeneeded = DEFAULT_OPTIONS.timeneeded[key];
      }
    }

    // let's simplify the value for "travelstipend" from a long string
    // to just "required" or blank
    formatted.travelstipend = formatted.travelstipend === FIELD_OPTIONS.stipendrequired ? `required` : ``;

    return formatted;
  },
  submitProposal(proposal) {
    let formattedProposal = this.formatProposal(proposal);

    let request = new XMLHttpRequest();
    request.open(`POST`, `/add-proposal`, true);
    request.setRequestHeader("Content-type", "application/json");

    request.onload = (event) => {
      let resStatus = event.currentTarget.status;

      this.setState({ 
        submitting: false, 
        submissionStatus: (resStatus >= 200 && resStatus < 400) ? SUBMISSION_STATUS_SUCCESS : SUBMISSION_STATUS_FAIL
      });
    };

    request.onerror = (err) => {
      console.log(err);
    };

    request.send(JSON.stringify(formattedProposal));
  },
  renderForm() {
    let stringSource = this.props.localizedStrings;
    let formFields = fields.createFields(stringSource);

    return (
      <div className="content wide">
        <div className="form-section">
          {this.renderIntro(stringSource.form_section_intro.background)}
          <Form ref="formPartOne" 
            fields={formFields.partOne}
            inlineErrors={true}
            onUpdate={this.handleFormUpdate} />
        </div>
        <div className="form-section">
          {this.renderIntro(stringSource.form_section_intro.space)}
          <Form ref="formPartTwo" 
            fields={formFields.partTwo}
            inlineErrors={true}
            onUpdate={this.handleFormUpdate} />
        </div>
        <div className="form-section">
          {this.renderIntro(stringSource.form_section_intro.describe)}
          <Form ref="formPartThree" 
            fields={formFields.partThree}
            inlineErrors={true}
            onUpdate={this.handleFormUpdate} />
        </div>
        <div className="form-section">
          {this.renderIntro(stringSource.form_section_intro.travel)}
          <Form ref="formPartFour" 
            fields={formFields.partFour}
            inlineErrors={true}
            onUpdate={this.handleFormUpdate} />
        </div>
        <div className="form-section">
          {this.renderIntro(stringSource.form_section_intro.material)}
          <Form ref="formPartFive" 
            fields={formFields.partFive}
            inlineErrors={true}
            onUpdate={this.handleFormUpdate} />
        </div>
        <div>
          <button
            ref="submitBtn" 
            className="btn btn-primary-outline mr-3 my-5"
            type="submit"
            onClick={this.handleFormSubmit}
            disabled={this.state.submitting ? `disabled` : null}
          >{ this.state.submitting ? `Submiting...` : stringSource.form_field_controls.submit }</button>
          { this.state.showFormInvalidNotice && <div className="d-inline-block form-invalid-error">Something isn't right. Please fix the errors indicated above.</div> }
        </div>

        { !this.state.submitting && this.state.submissionStatus === SUBMISSION_STATUS_FAIL && this.renderSubmissionFail() }
      </div>
    )
  },
  renderIntro(content) {
    let paragraphs = content.body.map((paragraph, i) => {
      // using dangerouslySetInnerHTML is okay here since we are pulling strings from a static json file, not user content or anything change dynamically over time without our attention
      return <p dangerouslySetInnerHTML={{__html: paragraph}} key={i}></p>;
    });

    return (
      <div>
        <h1>{content.header}</h1>
        { paragraphs }
      </div>
    );
  },
  renderSubmissionSuccess() {
    return (
      <div className="centered content wide">
        <h1 id="success">Success!</h1>
        <p>Thank you for your session proposal.</p>
        <button className="btn-link submit-another" onClick={this.handleSubmitAnother}>Want to submit another?</button>
      </div>
    );
  },
  renderSubmissionFail() {
    return (
      <div className="text-center server-error mb-5 px-2 py-4">
        <p className="m-0"><strong>Sorry!</strong></p>
        <p className="m-0">We are unable to submit your proposal at the moment.</p>
        <p className="m-0">Please try again or <a href="mailto:festival@mozilla.org">contact us</a>.</p>
      </div>
    );
  },
  renderMainContent() {
    if (!this.state.submitting && this.state.submissionStatus === SUBMISSION_STATUS_SUCCESS) {
      return this.renderSubmissionSuccess();
    }

    return this.renderForm();
  },
  render: function() {
    let stringSource = this.props.localizedStrings;

    return (
      <div className={classnames(`proposals-page`, this.props.lang)}>
        <Header/>
        <Jumbotron image="/assets/images/proposals.jpg"
                  image2x="/assets/images/proposals.jpg">
          <h1>{stringSource.page_banner_header}</h1>
          <div className="deadline">
            <span dangerouslySetInnerHTML={{__html: stringSource.page_banner_subheader}}></span>
          </div>
        </Jumbotron>
        {generateHelmet(this.pageMetaDescription)}
        <div className="white-background">
          {this.renderMainContent()}
        </div>
        <Footer />
      </div>
    );
  }
});

module.exports = Proposal;

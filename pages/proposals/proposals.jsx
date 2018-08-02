var React = require('react');
var classnames = require('classnames');
var Form = require('react-formbuilder').Form;
var fields = require('./form/fields');
var EnglishStrings = require('./language/english.json');

import generateHelmet from '../../lib/helmet.jsx';
import Jumbotron from '../../components/jumbotron.jsx';

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

    if (name === 'l10nwish' && value === this.props.stringSource.form_field_options.l10nwish.no) {
      // reset l10n related fields if user no longer has wish for l10n support
      formValues.l10nlanguage = [];
      formValues.l10nlanguageother = ``;
      formValues.l10nsupport = ``;
      formValues.l10nsupportother = ``;
    }

    this.setState({
      formValues,
      // hide notice once user starts typing again
      // this is a quick fix.
      showFormInvalidNotice: false
    });
  },
  handleFormSubmit(event) {
    event.preventDefault();

    let numErrors = 0;

    // super nested but ... ¯\_(ツ)_/¯
    this.refs.formPartOne.validates((p1isValid, p1errors, p1errorElems) => {
      // console.log(`[PART 1]`, p1isValid, p1errors, p1errorElems);

      if (!p1isValid) {
        console.error(`Form Part 1 does not pass validation!`);
        numErrors += p1errorElems.length;
      }

      this.refs.formPartTwo.validates((p2isValid, p2errors, p2errorElems) => {
        // console.log(`[PART 2]`, p2isValid, p2errors, p2errorElems);

        if (!p2isValid) {
          console.error(`Form Part 2 does not pass validation!`);
          numErrors += p2errorElems.length;
        }

        this.refs.formPartThree.validates((p3isValid, p3errors, p3errorElems) => {
          // console.log(`[PART 3]`, p3isValid, p3errors, p3errorElems);

          if (!p3isValid) {
            console.error(`Form Part 3 does not pass validation!`);
            numErrors += p3errorElems.length;
          }

          this.refs.formPartFour.validates((p4isValid, p4errors, p4errorElems) => {
            // console.log(`[PART 4]`, p4isValid, p4errors, p4errorElems);

            if (!p4isValid) {
              console.error(`Form Part 4 does not pass validation!`);
              numErrors += p4errorElems.length;
            }

            this.refs.formPartFive.validates((p5isValid, p5errors, p5errorElems) => {
              // console.log(`[PART 5]`, p5isValid, p5errors, p5errorElems);

              if (!p5isValid) {
                console.error(`Form Part 5 does not pass validation!`);
                numErrors += p5errorElems.length;
              }

              this.refs.formPartSix.validates((p6isValid, p6errors, p6errorElems) => {
                // console.log(`[PART 6]`, p6isValid, p6errors, p6errorElems);

                if (!p6isValid) {
                  console.error(`Form Part 6 does not pass validation!`);
                  numErrors += p6errorElems.length;
                }

                if (p1isValid && p2isValid && p3isValid && p4isValid && p5isValid && p6isValid) {
                  this.setState({
                    submitting: true,
                    showFormInvalidNotice: false
                  }, () => {
                    this.submitProposal(this.state.formValues);
                  });
                } else {
                  this.setState({
                    showFormInvalidNotice: true,
                    numErrors: numErrors
                  });
                }
              });
            });
          });
        });
      });
    });
  },
  formatProposal(proposal) {
    const DEFAULT_OPTIONS = EnglishStrings.form_field_options;
    const FIELD_OPTIONS = this.props.stringSource.form_field_options;
    const SPACES = FIELD_OPTIONS.spaces;
    const TIME = FIELD_OPTIONS.timeneeded;
    const L10NLANGUAGE = FIELD_OPTIONS.l10nlanguage;
    const L10NSUPPORT = FIELD_OPTIONS.l10nsupport;

    let formatted = Object.assign({}, proposal);

    // Although Space names on the form are localized, when we submit the entry to
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

    // "l10nlanguage" field
    // Similarly, although Language labels are localized, when we submit the entry to
    // Google Spreadsheet and GitHub we want them to be in English.
    // This is to make the curation process simpler.
    if (formatted.l10nlanguage && formatted.l10nlanguage.length > 0) {
      let newLangArray = [];

      formatted.l10nlanguage.forEach(l10nLang => {
        if (l10nLang === L10NLANGUAGE.other) {
          // we record "l10nlanguage" only if user has specified the language
          if (formatted.l10nlanguageother) {
            newLangArray.push(formatted.l10nlanguageother);

            delete formatted.l10nlanguageother;
          }
        } else {
          for (let key in L10NLANGUAGE) {
            if (L10NLANGUAGE[key] === l10nLang) {
              newLangArray.push(DEFAULT_OPTIONS.l10nlanguage[key]);
            }
          }
        }
      });

      formatted.l10nlanguage = newLangArray;
    }

    // "l10nsupport" field
    // Similarly, make sure "l10nsupport" is posted in English on Google Spreadsheet and GitHub
    let l10nSupport = formatted.l10nsupport;
    let l10nSupportOther = formatted.l10nsupportother;

    delete formatted.l10nsupport;
    delete formatted.l10nlanguageother;

    if (l10nSupport === L10NSUPPORT.other) {
      // we record "l10nsupport" only if user has specified the support he/she needs
      if (l10nSupport) {
        formatted.l10nsupport = l10nSupportOther;
      }
    } else {
      for (let key in L10NSUPPORT) {
        if (L10NSUPPORT[key] === l10nSupport) {
          formatted.l10nsupport = DEFAULT_OPTIONS.l10nsupport[key];
        }
      }
    }

    // "proposallanguage"
    // We should record the language the proposal was written in.
    // Make sure the name of the language is recorded in English.
    // (e.g., record it as "German" but not "Deutsch")
    let proposalLang = this.props.lang;
    // Capitalize the first letter
    formatted.proposallanguage = proposalLang.charAt(0).toUpperCase() + proposalLang.slice(1);

    // Do the same for "timeneeded" - translate the values to English
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
    let stringSource = this.props.stringSource;
    let formFields = fields.createFields(stringSource);

    return (
      <div className="content wide">
        {this.renderIntro(stringSource.form_section_intro.background)}
        <div className="form-section">
          {this.renderIntro(stringSource.form_section_intro.facilitator_info)}
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
          {this.renderIntro(stringSource.form_section_intro.l10n)}
          <Form ref="formPartThree"
            fields={formFields.partThree}
            inlineErrors={true}
            onUpdate={this.handleFormUpdate} />
        </div>
        <div className="form-section">
          {this.renderIntro(stringSource.form_section_intro.describe)}
          <Form ref="formPartFour"
            fields={formFields.partFour}
            inlineErrors={true}
            onUpdate={this.handleFormUpdate} />
        </div>
        <div className="form-section">
          {this.renderIntro(stringSource.form_section_intro.format)}
          <Form ref="formPartFive"
            fields={formFields.partFive}
            inlineErrors={true}
            onUpdate={this.handleFormUpdate} />
        </div>
        <div className="form-section">
          {this.renderIntro(stringSource.form_section_intro.addtional_support)}
          <Form ref="formPartSix"
            fields={formFields.partSix}
            inlineErrors={true}
            onUpdate={this.handleFormUpdate} />
        </div>
        <div className="mt-2 mb-5">
          <button
            ref="submitBtn"
            className="btn btn-arrow mr-3 my-3 w-100"
            type="submit"
            onClick={this.handleFormSubmit}
            disabled={this.state.submitting ? `disabled` : null}
          ><span>{ this.state.submitting ? stringSource.form_field_controls.submitting : stringSource.form_field_controls.submit }</span></button>
          { this.state.showFormInvalidNotice && <div className="text-center"><div className="d-inline-block form-invalid-error my-3">{`Something isn't right. Please fix the ${this.state.numErrors} error${this.state.numErrors > 1 ? `s` : ``} indicated above.`}</div></div> }
          { !this.state.submitting && this.state.submissionStatus === SUBMISSION_STATUS_FAIL && this.renderSubmissionFail() }
        </div>
      </div>
    );
  },
  renderIntro(content) {
    let sections = content.body.map((section, i) => {
      // using dangerouslySetInnerHTML is okay here since we are pulling strings from a static json file, not user content or anything change dynamically over time without our attention

      if (section.charAt(0) === `<` && section.charAt(section.length-1) === `>`) {
        return <div dangerouslySetInnerHTML={{__html: section}} key={i}></div>;
      }

      return <p dangerouslySetInnerHTML={{__html: section}} key={i}></p>;
    });

    return (
      <div>
        <h1>{content.header}</h1>
        { sections }
      </div>
    );
  },
  renderSubmissionSuccess() {
    let messages = this.props.stringSource.form_system_messages;

    return (
      <div className="centered content wide">
        <h1 id="success">{messages.success}</h1>
        <p>{messages.thank_you}</p>
        <button className="btn-link submit-another"
          onClick={(event)=>this.handleSubmitAnother(event)}>
          {messages.submit_another}
        </button>
      </div>
    );
  },
  renderSubmissionFail() {
    let stringSource = this.props.stringSource;

    return (
      <div className="text-center server-error my-3 px-5 py-4">
        <p className="m-0" dangerouslySetInnerHTML={{__html: stringSource.form_system_messages.server_error}}></p>
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
    let stringSource = this.props.stringSource;

    return (
      <div className={classnames(`proposals-page`, this.props.lang)}>
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
      </div>
    );
  }
});

module.exports = Proposal;

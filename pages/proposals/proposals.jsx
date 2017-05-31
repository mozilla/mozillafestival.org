var React = require('react');
var Link = require('react-router').Link;
var Header = require('../../components/header.jsx');
var Footer = require('../../components/footer.jsx');
var Jumbotron = require('../../components/jumbotron.jsx');
var Router = require('react-router');
var Form = require('react-formbuilder').Form;
var fields = require('./form/fields');
var generateHelmet = require('../../lib/helmet.jsx');

const PRE_SUBMIT_LABEL = `Submit`;
const SUBMITTING_LABEL = `Submitting...`;
const SUBMISSION_STATUS_SUCCESS = `success`;
const SUBMISSION_STATUS_FAIL = `fail`;

var Proposal = React.createClass({
  pageMetaDescription: "Session proposals",
  getInitialState() {
    return {
      formValues: {},
      submitting: false,
      submissionStatus: ``
    };
  },
  handleSubmitAnother(event) {
    event.preventDefault();
    this.setState(this.getInitialState());
  },
  handleFormUpdate(evt, name, field, value) {
    let formValues = this.state.formValues;
    formValues[name] = value;
    this.setState({ formValues });
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
                submitting: true
              }, () => {
                this.submitProposal(this.state.formValues);
              });
            }
          });
        });
      });      
    });

  },
  formatProposal(proposal) {
    let formatted = Object.assign({}, proposal);

    if (formatted.additionallanguage && formatted.additionallanguage === `Other` && formatted.additionallanguageother) {
      formatted.additionallanguage = `Other: ${formatted.additionallanguageother}`;
      delete formatted.additionallanguageother;
    }

    if (formatted.alternatespace && formatted.alternatespace === `None`) {
      delete formatted.alternatespace;
    }

    formatted.travelstipend = formatted.travelstipend === fields.LABEL_STIPEND_REQUIRED ? `required` : ``;

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
    return (
      <div className="content wide">
        {this.renderIntro()}
        <div className="form-section">
          <Form ref="formPartOne" 
            fields={fields.partOne}
            inlineErrors={true}
            onUpdate={this.handleFormUpdate} />
        </div>
        <div className="form-section">
          <h1>Festival Spaces</h1>
          <p>At MozFest, sessions are thematically organized into spaces. Please read the brief descriptions of each space <Link to="/spaces">here</Link> before answering the following questions.</p>
          <Form ref="formPartTwo" 
            fields={fields.partTwo}
            inlineErrors={true}
            onUpdate={this.handleFormUpdate} />
        </div>
        <div className="form-section">
          <h1>Describe your session</h1>
          <Form ref="formPartThree" 
            fields={fields.partThree}
            inlineErrors={true}
            onUpdate={this.handleFormUpdate} />
        </div>
        <div className="form-section">
          <h1>Travel support</h1>
          <p>MozFest offers limited places for travel sponsorship covering flights and accommodation over the festival weekend. These stipends are offered to those who need support traveling to the event and would have no other means to attend through work or personal covering of costs. We offer these stipends to encourage diversity in facilitators.</p>
          <Form ref="formPartFour" 
            fields={fields.partFour}
            inlineErrors={true}
            onUpdate={this.handleFormUpdate} />
        </div>
        <div className="form-section">
          <h1>Session materials</h1>
          <p>Many attendees bring a personal device (smartphone, laptop or tablet) to the festival. We can provide you with a projector and office supplies (paper, pens, post-it notes). Please note we may not be capable of supporting all your needs but will work with you to provide the best solution possible.</p>
          <Form ref="formPartFive" 
            fields={fields.partFive}
            inlineErrors={true}
            onUpdate={this.handleFormUpdate} />
        </div>
        <button
          ref="submitBtn" 
          className="btn btn-primary-outline mr-3 my-5"
          type="submit"
          onClick={this.handleFormSubmit}
          disabled={this.state.submitting ? `disabled` : null}
        >{ this.state.submitting ? SUBMITTING_LABEL : PRE_SUBMIT_LABEL }</button>

        { !this.state.submitting && this.state.submissionStatus === SUBMISSION_STATUS_FAIL && this.renderSubmissionFail() }
      </div>
    )
  },
  renderIntro() {
    return (
      <div>
        <h1>Welcome to the MozFest 2017 Call for Proposals.</h1>
        <p>MozFest sessions cover a wide range of topics, but share two important qualities: they are <em>interactive</em> and <em>inclusive</em>.</p>
        <p>Sessions may have anywhere from 3 to 25 participants, with a vast range of abilities and ages. Everyone at the festival is welcome to attend your session.</p>
        <p>Anyone may submit a proposal for MozFest 2017. Once you have submitted your session, it will enter an open curation process on <a href="https://github.com/MozillaFoundation/mozfest-program-2017" target="_blank">GitHub</a>.</p>
        <p>In addition to English, this year we are accepting proposals in Spanish, French and German. Please check back in by June 15 to find localized versions of this form.</p>
        <p>If you would like to submit a session and have difficulty using this text-based form, please <a href="mailto:festival@mozilla.org">email us</a> to discuss accessibility options.</p>
        <p>If you have any questions, please email <a href="mailto:festival@mozilla.org">festival@mozilla.org</a>.</p>
        <p>Submission deadline: August 1, 2017</p>
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
    return (
      <div className="proposals-page">
        <Header/>
        <Jumbotron image="/assets/images/proposals.jpg"
                  image2x="/assets/images/proposals.jpg">
          <h1>Call for Proposals</h1>
          <div className="deadline">
            <span>Deadline for submissions is 12:00am BST August 1, 2017</span>
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

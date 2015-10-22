var React = require('react');
var Formation = require('react-formation');
var moment = require('moment');

var Header = require('../components/header.jsx');
var Footer = require('../components/footer.jsx');
var HeroUnit = require('../components/hero-unit.jsx');
var ErrorMessage = Formation.ErrorMessage;
var Validator = Formation.Validator;

var Event = React.createClass({
  render: function() {
    var link = this.props.linktoregisterfortheeventifavailable;
    return (
      <li>
        <h1>{this.props.nameofevent}</h1>
        <div className="details">
          <div className="time">{moment(this.props.dateandtime,"DD/MM/YYYY hh:mm A").format("MMM DD, YYYY hh:mm A")}</div>
          <div className="location">{this.props.eventlocation}</div>
          { link ? <div className="url"><a href={link}>{link}</a></div>
                 : null
          }
          <p className="description">{this.props.descriptionofyourevent}</p>
        </div>
      </li>
    );
  }
})

var Input = React.createClass({
  mixins: [Formation.FormMixin],
  render: function () {
    var fieldType = this.props.fieldType || "text";
    var fieldId = "fringe_form_" + this.props.name;
    var sharedProps = {
      type: fieldType,
      name: this.props.name,
      id: fieldId,
      placeholder: this.props.placeholder,
      valueLink: this.linkField(this.props.name)
    };
    var field = fieldType !== "textarea" ? <input {...sharedProps} />
                                         : <textarea {...sharedProps} rows="3" />;
    return (
      <fieldset>
        <label htmlFor={fieldId}>
          {this.props.label}
          {this.props.required ? <span>*</span> : null}
        </label>
        {this.props.additionalText ? <p className="additional-text">{this.props.additionalText}</p> : null }
        {field}
        {this.props.exampleValue ? <p className="example-value">{this.props.exampleValue}</p> : null }
        {this.props.label === "Privacy Policy" ? <span>I’m okay with Mozilla handling this info as you explain in your <a href="https://www.mozilla.org/privacy/">privacy policy</a>. Please note: the information in this form will be submitted via a private Google form.</span>
                                               : null
        }
        <ErrorMessage field={this.props.name} />
      </fieldset>
    );
  }
});

var FringeEventForm = Formation.CreateForm({
  getSchema: function () {
    return {
      "name": {
        required: true,
        label: 'Name of Fringe Event'
      },
      "time": {
        required: true,
        label: 'Date and Time',
        validations: this.dateTimeValidator,
        placeholder: "dd/mm/yyyy --:-- --",
        exampleValue: "Example: 31/10/2015 03:30 PM"
      },
      "location": {
        required: true,
        label: 'Event Location',
        additionalText: "Please add full address here, including post code"
      },
      "description": {
        required: true,
        label: 'Description of your Event',
        validations: this.wordCountValidator,
        additionalText: "Maximum 100 words / 1,000 characters",
        fieldType: "textarea"
      },
      "link": {
        required: false,
        label: 'Link to register for the event if available',
        validations: Validator.url()
      },
      "privacy": {
        required: true,
        label: 'Privacy Policy',
        fieldType: "checkbox"
      }
    };
  },
  wordCountValidator: function(value) {
    return (value.split(" ").length > 100 || value.length > 1000) ? "Maximum input length exceeded." : false;
  },
  dateTimeValidator: function(value) {
    var validDate = moment(value, "DD/MM/YYYY hh:mm A", true).isValid();
    return !validDate ? "Make sure the format of your input is correct" : false;
  },
  handleAddEvent: function(response) {
    this.refs.submitButton.getDOMNode().classList.remove("waiting");
    if (response.ok) {
      window.location.href = "/fringe-event-add-success";
    } else {
      window.location.href = window.location.origin + window.location.pathname + "#fringe-submit-button";
    }
  },
  onSuccess: function (data) {
    this.refs.submitButton.getDOMNode().classList.add("waiting");
    fetch('/add-fringe-event', {
      method: 'post',
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(data)
    }).then(this.handleAddEvent);
  },
  render: function () {
    var schema = this.getSchema();
    return (
      <form action="/add-fringe-event" method="POST" className="mozfest-form">
        {
          Object.keys(schema).map(function(formName) {
            var props = schema[formName];
            schema[formName].name = formName;
            return (
              <Input {...props} key={formName}>{formName}</Input>
            );
          })
        }
        <fieldset>
          <a onClick={this.submitForm} ref="submitButton" className="button centered" id="fringe-submit-button"><span>Submit</span></a>
        </fieldset>
      </form>
    );
  }
});

var FringePage = React.createClass({
  getInitialState: function() {
    return {
      events: [],
      eventsLoaded: false,
      unableToLoadEvents: false
    }
  },
  componentWillMount: function() {
    this.getFringeEvents();
  },
  handleEventResponse: function(response) {
    return response.json();
  },
  handleEventData: function(data) {
    this.setState({ 
      events: data, 
      eventsLoaded: true,
      unableToLoadEvents: false
    });
  },
  handleEventDataError: function(error) {
    console.log("Error: ", error);
    this.setState({ 
      eventsLoaded: true,
      unableToLoadEvents: true
    });
  },
  getFringeEvents: function() {
    var self = this;
    fetch('/get-fringe-events', {
      method: 'get'
    })
    .then(this.handleEventResponse)
    .then(this.handleEventData)
    .catch(this.handleEventDataError);
  },
  render: function() {
    var events = false;
    if ( this.state.eventsLoaded ) {
      events = this.state.events.map(function(event,i) {
                return (
                  <div className="event-block" key={event.nameofevent}>
                    <div className="content wide">
                      <Event {...event} />
                    </div>
                  </div>
                );
              });
    } else {
      events = ( 
        <div className="white-background">
          <div className="content wide">
            {
              this.state.unableToLoadEvents 
                ? <p>Unable to load Fringe Events.</p>
                : <p className="loading-message">Loading Fringe Events</p>
            }
          </div>
        </div>
      );
    }

    return (
      <div className="fringe-events-page">
        <Header/>
        <HeroUnit image="/assets/images/fringe.jpg"
                  image2x="/assets/images/fringe.jpg">
          Fringe Events
        </HeroUnit>
        <div className="white-background">
          <div className="content centered wide">
            <h1>If MozFest wasn’t enough for you&hellip;</h1>
            <p>MozFest is about working in the open and sharing ideas with the greater community. There are several innovative events taking place around the world with this same philosophy, and we would love to align them with MozFest.</p>
            <p>If you are running an aligned event independent of the festival, please fill out our Fringe Event form. We will share your event details on our Fringe Events page.</p>
            <p>How do you know if your event is a Fringe Event? It can be held anywhere in the world, but must align with the MozFest ethos: open, collaborative workshops or discussions focused on building tools and resources to keep the Web free and innovative.</p>
            <div className="cta">
              <h2>Be part of the Fringe scene and list your event!</h2>
              <a className="button" href="#fringe-form-section"><span>Submit Your Fringe Event</span></a>
            </div>
            <div className="horizontal-rule"></div>
          </div>
        </div>
        <div className="events">
          <ul>{events}</ul>
        </div>
        <div className="white-background" id="fringe-form-section">
          <div className="content wide">
            <div className="horizontal-rule"></div>
            <h1>Submit Your Fringe Event</h1>
            <p className="required-note">* Required</p>
            <FringeEventForm/>
          </div>
        </div>
        <Footer/>
      </div>
    );
  }
});

module.exports = FringePage;


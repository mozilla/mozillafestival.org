var React = require('react');
var Formation = require('react-formation');
var moment = require('moment');

var Header = require('../components/header.jsx');
var Footer = require('../components/footer.jsx');
var HeroUnit = require('../components/hero-unit.jsx');
var ErrorMessage = Formation.ErrorMessage;
var Validator = Formation.Validator;

var Input = React.createClass({
  mixins: [Formation.FormMixin],
  render: function () {
    var fieldType = this.props.fieldType || "text";
    var field = fieldType !== "textarea" ? <input type={this.props.fieldType || "text"}
                                                  name={this.props.name}
                                                  id={this.props.id}
                                                  placeholder={this.props.placeholder}
                                                  valueLink={this.linkField(this.props.name)} />
                                         : <textarea type={this.props.fieldType || "text"}
                                                     name={this.props.name}
                                                     id={this.props.id}
                                                     placeholder={this.props.placeholder}
                                                     valueLink={this.linkField(this.props.name)}
                                                     rows="3" />;
    return (
      <fieldset>
        <label htmlFor={this.props.id}>
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
      "entry.904055858": {
        required: true,
        label: 'Name of Fringe Event',
        id: "entry_904055858"
      },
      "entry.686031215": {
        required: true,
        label: 'Date and Time',
        validations: this.dateTimeValidator,
        id: "entry_686031215",
        placeholder: "dd/mm/yyyy --:-- --",
        exampleValue: "Example: 31/10/2015 03:30 PM"
      },
      "entry.1740904233": {
        required: true,
        label: 'Event Location',
        validations: "",
        id: "entry_1740904233",
        additionalText: "Please add full address here, including post code"
      },
      "entry.1100204806": {
        required: true,
        label: 'Description of your Event',
        validations: this.wordCountValidator,
        id: "entry_1100204806",
        additionalText: "Maximum 100 words",
        fieldType: "textarea"
      },
      "entry.1926329450": {
        required: false,
        label: 'Link to register for the event if available',
        validations: Validator.url(),
        id: "entry_1926329450"
      },
      "entry.774776501": {
        required: true,
        label: 'Privacy Policy',
        validations: "",
        id: "group_774776501_1",
        fieldType: "checkbox"
      }
    };
  },
  wordCountValidator: function(value) {
    return value.split(" ").length >= 100 ? "Description should be under 100 words." : false;
  },
  dateTimeValidator: function(value) {
    var validDate = moment(value, "DD/MM/YYYY hh:mm A", true).isValid();
    // console.log("date entered: ", value, ", parsed: ", validDate);
    return !validDate ? "Make sure the format of your input is correct" : false;
  },
  onSuccess: function (data) {
    console.log("onSuccess //////////", data);
    var self = this;
    self.refs.submitButton.getDOMNode().classList.add("waiting");

    fetch('/add-fringe-event', {
      method: 'post',
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(data)
    }).then(function(response) {
      self.refs.submitButton.getDOMNode().classList.remove("waiting");
      if (response.ok) {
        console.log("okkk");
        // window.location.href = "/fringe-event-add-success";
      } else {
        console.log("faileddd");
        // window.location.href = window.location.origin + window.location.pathname + "#fringe-submit-button";
      }
    });
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
    </form>);
  }
});

var FringePage = React.createClass({
  render: function() {
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
              <a className="button"><span>Submit Your Fringe Event</span></a>
            </div>
            <div className="horizontal-rule"></div>
          </div>
        </div>
        <div className="white-background">
          <div className="content wide">
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


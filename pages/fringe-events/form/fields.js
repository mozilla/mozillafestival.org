import React from 'react';
import moment from 'moment';
import DatePicker from 'react-datepicker';
import TimePicker from 'rc-time-picker';
import validator from './validator';
import Select from 'react-select';
import TimezoneList from 'timezones.json';

console.log(TimezoneList);

// *** IMPORTANT ***
//
// make sure all keys are all lowercase and contain no symbols or spaces
// reference: https://www.npmjs.com/package/google-spreadsheet#row-based-api-limitations
//
// **********************************************

const DATE_FORMAT = "MMM DD, YYYY";
const TIME_FORMAT = "h:mma";

let EventDate = React.createClass({
  getInitialState: function () {
   return {
     startDate: moment(),
     selected: ''
    };
  },
  handleSelectChange: function(date) {
    let selected = date.format(DATE_FORMAT);

    this.setState({
      startDate: date,
      selected
    }, () => {
      this.props.onChange(null,selected);
    });
  },
  render: function() {
    return <DatePicker selected={this.state.startDate}
                       onChange={(date) => this.handleSelectChange(date)}
                       className="form-control" />
  }
});

let Time = React.createClass({
  getInitialState: function () {
   return {
     selected: ''
    };
  },
  handleSelectChange: function(time) {
    let selected = time.format(TIME_FORMAT);
    // console.log(time && time.format(TIME_FORMAT));
    this.setState({ selected }, () => {
      this.props.onChange(null,selected);
    });
  },
  render: function() {
    return <TimePicker
              showSecond={false}
              defaultValue={moment().hour(0).minute(0)}
              className="time-selector"
              onChange={(value) => this.handleSelectChange(value)}
              format={TIME_FORMAT}
              use12Hours
          />;
  }
});

// let Timezone = React.createClass({
//   getInitialState: function () {
//    return {
//      selected: ''
//     };
//   },
//   handleSelectChange: function(time) {
//     let selected = time.format(TIME_FORMAT);
//     // console.log(time && time.format(TIME_FORMAT));
//     this.setState({ selected }, () => {
//       this.props.onChange(null,selected);
//     });
//   },
//   render: function() {
//     return null;
//   }
// });

var createPartOneFields = function() {
  return {
    'eventname': {
      type: `text`,
      label: `Name of Event`,
      labelClassname: `required`,
      fieldClassname: `form-control`,
      validator: [
        validator.emptyValueValidator()
      ]
    },
    'date': {
      type: EventDate,
      label: `Date`,
      labelClassname: `required`,
      fieldClassname: `form-control`
    },
    'time': {
      type: Time,
      label: `Time`,
      labelClassname: `required`,
      fieldClassname: `form-control`
    },
    // 'timezone': {
    //   type: Timezone,
    //   label: `Timezone`,
    //   labelClassname: `required`,
    //   fieldClassname: `form-control`
    // },
    'location': {
      type: `text`,
      label: `Location`,
      labelClassname: `required`,
      fieldClassname: `form-control`,
      validator: [
        validator.emptyValueValidator()
      ]
    },
    'email': {
      type: `text`,
      label: `Contact email`,
      placeholder: `hello@example.com`,
      labelClassname: `required`,
      fieldClassname: `form-control`,
      validator: [
        validator.emailValidator(),
        validator.maxWordsValidator(2)
      ]
    },
    'description': {
      type: `textarea`,
      label: `Description of your Event`,
      labelClassname: `required word-length-restriction max-50-words`,
      fieldClassname: `form-control`,
      validator: [
        validator.emptyValueValidator(),
        validator.maxWordsValidator(50)
      ]
    },
    'demonstrate': {
      type: `textarea`,
      label: `How your event demonstrate one or more of the following characteristics`,
      labelClassname: `required word-length-restriction max-120-words`,
      fieldClassname: `form-control`,
      validator: [
        validator.emptyValueValidator(),
        validator.maxWordsValidator(120)
      ]
    },
    'link': {
      type: `text`,
      label: `Link to register for the event if available`,
      fieldClassname: `form-control`,
      validator: [
        validator.urlValidator()
      ]
    }
  };
};

var createPartTwoFields = function() {
  return {
    'privacypolicy': {
      type: `checkbox`,
      label: `Yes, I’m okay with Mozilla handling this info as you explain in your privacy policy.`,
      labelClassname: `required`,
      fieldClassname: `form-control mr-2 d-inline-block`,
      validator: [
        validator.privacyPolicyAgreementValidator()
      ]
    }
  };
};

module.exports = {
  createFields: function() {
    return {
      partOne: createPartOneFields(),
      partTwo: createPartTwoFields()
    };
  }
};

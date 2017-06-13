import validator from './validator';

// *** IMPORTANT ***
//
// make sure all keys are all lowercase and contain no symbols or spaces
// reference: https://www.npmjs.com/package/google-spreadsheet#row-based-api-limitations
//
// **********************************************

var createPartOneFields = function(stringSource) {
  const EMPTY_VALUE_ERROR = stringSource.form_validation_errors.empty_value;
  const EMAIL_INVALID_ERROR = stringSource.form_validation_errors.email_invalid;

  return {
    'firstname': {
      type: `text`,
      label: stringSource.form_field_labels.firstname,
      labelClassname: `required`,
      fieldClassname: `form-control`,
      validator: [
        validator.emptyValueValidator(EMPTY_VALUE_ERROR)
      ]
    },
    'surname': {
      type: `text`,
      label: stringSource.form_field_labels.surname,
      labelClassname: `required`,
      fieldClassname: `form-control`,
      validator: [
        validator.emptyValueValidator(EMPTY_VALUE_ERROR)
      ]
    },
    'email': {
      type: `text`,
      label: stringSource.form_field_labels.email,
      placeholder: `hello@example.com`,
      labelClassname: `required`,
      fieldClassname: `form-control`,
      validator: [
        validator.emptyValueValidator(EMPTY_VALUE_ERROR),
        validator.emailValidator(EMAIL_INVALID_ERROR)
      ]
    },
    'organisation': {
      type: `text`,
      label: stringSource.form_field_labels.organisation,
      fieldClassname: `form-control`,
      validator: []
    },
    'twitterhandle': {
      type: `text`,
      label: stringSource.form_field_labels.twitterhandle,
      placeholder: `@twitterhandle`,
      fieldClassname: `form-control`
    },
    'githubhandle': {
      type: `text`,
      label: stringSource.form_field_labels.githubhandle,
      placeholder: `@githubhandle`,
      fieldClassname: `form-control`
    },
    'otherfacilitators': {
      type: `text`,
      label: stringSource.form_field_labels.otherfacilitators,
      placeholder: `${stringSource.form_field_labels.firstname} ${stringSource.form_field_labels.surname}`,
      fieldClassname: `form-control`,
      multiplicity: 1,
      addLabel: stringSource.form_field_controls.add_another
    }
  };
};

var createPartTwoFields = function(stringSource) {
  const EMPTY_VALUE_ERROR = stringSource.form_validation_errors.empty_value;
  const SPACES = stringSource.form_field_options.spaces;

  return {
    'space': {
      type: `choiceGroup`,
      label: stringSource.form_field_labels.space,
      options: [
        SPACES.decentralization,
        SPACES.digital_inclusion,
        SPACES.open_innovation,
        SPACES.privacy_and_security,
        SPACES.web_literacy,
        SPACES.youth_zone
      ],
      labelClassname: `required`,
      fieldClassname: `form-control choice-group`,
      validator: [
        validator.emptyValueValidator(EMPTY_VALUE_ERROR)
      ]
    },
    'secondaryspace': {
      type: `choiceGroup`,
      label: stringSource.form_field_labels.secondaryspace,
      options: [
        SPACES.decentralization,
        SPACES.digital_inclusion,
        SPACES.open_innovation,
        SPACES.privacy_and_security,
        SPACES.web_literacy,
        SPACES.youth_zone,
        SPACES.none
      ],
      colCount: 1,
      fieldClassname: `form-control choice-group`
    }
  };
};

var createPartThreeFields = function(stringSource) {
  const EMPTY_VALUE_ERROR = stringSource.form_validation_errors.empty_value;
  const MAX_WORD_REACHED_ERROR = stringSource.form_validation_errors.max_word_reached;
  const TIME = stringSource.form_field_options.timeneeded;
  const LANGUAGES = stringSource.form_field_options.languages;

  return {
    'name': {
      type: `text`,
      label: stringSource.form_field_labels.name,
      labelClassname: `required`,
      fieldClassname: `form-control`,
      validator: [
        validator.emptyValueValidator(EMPTY_VALUE_ERROR)
      ]
    },
    'description': {
      type: `textarea`,
      label: stringSource.form_field_labels.description,
      labelClassname: `required word-length-restriction max-120-words`,
      fieldClassname: `form-control`,
      validator: [
        validator.emptyValueValidator(EMPTY_VALUE_ERROR),
        validator.maxWordsValidator(120, MAX_WORD_REACHED_ERROR)
      ]
    },
    'outcome': {
      type: `textarea`,
      label: stringSource.form_field_labels.outcome,
      labelClassname: `required word-length-restriction max-120-words`,
      fieldClassname: `form-control`,
      validator: [
        validator.emptyValueValidator(EMPTY_VALUE_ERROR),
        validator.maxWordsValidator(120, MAX_WORD_REACHED_ERROR)
      ]
    },
    'afterfestival': {
      type: `textarea`,
      label: stringSource.form_field_labels.afterfestival,
      labelClassname: `required word-length-restriction max-120-words`,
      fieldClassname: `form-control`,
      validator: [
        validator.emptyValueValidator(EMPTY_VALUE_ERROR),
        validator.maxWordsValidator(120, MAX_WORD_REACHED_ERROR)
      ]
    },
    'timeneeded': {
      type: `choiceGroup`,
      label: stringSource.form_field_labels.timeneeded,
      options: [
        TIME[`less_than_60_mins`],
        TIME[`60_mins`],
        TIME[`90_mins`],
        TIME[`all_weekend`]
      ],
      labelClassname: `required`,
      fieldClassname: `form-control choice-group`,
      validator: [
        validator.emptyValueValidator(EMPTY_VALUE_ERROR)
      ]
    },
    'numsofparticipants': {
      type: `textarea`,
      label: stringSource.form_field_labels.numsofparticipants,
      labelClassname: `required word-length-restriction max-120-words`,
      fieldClassname: `form-control`,
      validator: [
        validator.emptyValueValidator(EMPTY_VALUE_ERROR),
        validator.maxWordsValidator(120, MAX_WORD_REACHED_ERROR)
      ]
    },
    'additionallanguage': {
      type: `choiceGroup`,
      label: stringSource.form_field_labels.additionallanguage,
      options: [
        LANGUAGES.spanish,
        LANGUAGES.german,
        LANGUAGES.french,
        LANGUAGES.other
      ],
      fieldClassname: `form-control choice-group`,
      validator: []
    },
    'additionallanguageother': {
      type: `text`,
      labelClassname: `required`,
      fieldClassname: `form-control`,
      controller: {
        name: `additionallanguage`,
        value: LANGUAGES.other
      }
    }
  };
};

var createPartFourFields = function(stringSource) {
  const EMPTY_VALUE_ERROR = stringSource.form_validation_errors.empty_value;

  return {
    'travelstipend': {
      type: `choiceGroup`,
      label: stringSource.form_field_labels.travelstipend,
      options: [
        stringSource.form_field_options.stipendnotrequired,
        stringSource.form_field_options.stipendrequired
      ],
      labelClassname: `required`,
      fieldClassname: `form-control choice-group`,
      colCount: 1,
      validator: [
        validator.emptyValueValidator(EMPTY_VALUE_ERROR)
      ]
    }
  };
};

var createPartFiveFields = function(stringSource) {
  const MAX_WORD_REACHED_ERROR = stringSource.form_validation_errors.max_word_reached;

  return {
    'needs': {
      type: `textarea`,
      label: stringSource.form_field_labels.needs,
      labelClassname: `word-length-restriction max-120-words`,
      fieldClassname: `form-control`,
      validator: [
        validator.maxWordsValidator(120, MAX_WORD_REACHED_ERROR)
      ]
    }
  };
};

module.exports = {
  createFields: function(stringSource) {
    return {
      partOne: createPartOneFields(stringSource),
      partTwo: createPartTwoFields(stringSource),
      partThree: createPartThreeFields(stringSource),
      partFour: createPartFourFields(stringSource),
      partFive: createPartFiveFields(stringSource)
    };
  }
};

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
      ],
      guideText: `Your information`,
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
      labelClassname: `required`,
      fieldClassname: `form-control`,
      validator: [
        validator.emptyValueValidator(EMPTY_VALUE_ERROR),
      ]
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
    // additional facilitator #1
    'otherfacilitator1firstname': {
      type: `text`,
      label: `${stringSource.form_field_labels.firstname}`,
      fieldClassname: `form-control`,
      guideText: `${stringSource.form_field_labels.additionalfacilitator} #1's information`,
    },
    'otherfacilitator1surname': {
      type: `text`,
      label: `${stringSource.form_field_labels.surname}`,
      fieldClassname: `form-control`
    },
    'otherfacilitator1email': {
      type: `text`,
      label: `${stringSource.form_field_labels.email}`,
      placeholder: `hello@example.com`,
      fieldClassname: `form-control`,
      validator: [
        validator.emailValidator(EMAIL_INVALID_ERROR)
      ]
    },
    'otherfacilitator1githubhandle': {
      type: `text`,
      label: `${stringSource.form_field_labels.githubhandle}`,
      placeholder: `@githubhandle`,
      fieldClassname: `form-control`
    },
    // additional facilitator #2
    'otherfacilitator2firstname': {
      type: `text`,
      label: `${stringSource.form_field_labels.firstname}`,
      fieldClassname: `form-control`,
      guideText: `${stringSource.form_field_labels.additionalfacilitator} #2's information`,
    },
    'otherfacilitator2surname': {
      type: `text`,
      label: `${stringSource.form_field_labels.surname}`,
      fieldClassname: `form-control`
    },
    'otherfacilitator2email': {
      type: `text`,
      label: `${stringSource.form_field_labels.email}`,
      placeholder: `hello@example.com`,
      fieldClassname: `form-control`,
      validator: [
        validator.emailValidator(EMAIL_INVALID_ERROR)
      ]
    },
    'otherfacilitator2githubhandle': {
      type: `text`,
      label: `${stringSource.form_field_labels.githubhandle}`,
      placeholder: `@githubhandle`,
      fieldClassname: `form-control`
    },
    // additional facilitator #3
    'otherfacilitator3firstname': {
      type: `text`,
      label: `${stringSource.form_field_labels.firstname}`,
      fieldClassname: `form-control`,
      guideText: `${stringSource.form_field_labels.additionalfacilitator} #3's information`,
    },
    'otherfacilitator3surname': {
      type: `text`,
      label: `${stringSource.form_field_labels.surname}`,
      fieldClassname: `form-control`
    },
    'otherfacilitator3email': {
      type: `text`,
      label: `${stringSource.form_field_labels.email}`,
      placeholder: `hello@example.com`,
      fieldClassname: `form-control`,
      validator: [
        validator.emailValidator(EMAIL_INVALID_ERROR)
      ]
    },
    'otherfacilitator3githubhandle': {
      type: `text`,
      label: `${stringSource.form_field_labels.githubhandle}`,
      placeholder: `@githubhandle`,
      fieldClassname: `form-control`
    },
    // 'otherfacilitators': {
    //   type: `text`,
    //   label: stringSource.form_field_labels.otherfacilitators,
    //   placeholder: `${stringSource.form_field_labels.firstname} ${stringSource.form_field_labels.surname}, email@example.com`,
    //   fieldClassname: `form-control`,
    //   multiplicity: 1,
    //   addLabel: stringSource.form_field_controls.add_another
    // }
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
        SPACES.openness,
        SPACES.privacy_and_security,
        SPACES.web_literacy,
        SPACES.youth_zone,
        SPACES.queering
      ],
      colCount: 1,
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
        SPACES.openness,
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
  const WISH_OPTIONS = stringSource.form_field_options.l10nwish;
  const SUPPORT_OPTIONS = stringSource.form_field_options.l10nsupport;
  const LANG_OPTIONS = stringSource.form_field_options.l10nlanguage;
  const WISH_OPTIONS_YES_MAYBE = `${WISH_OPTIONS.yes} / ${WISH_OPTIONS.maybe}`;

  return {
    'l10nwish': {
      type: `choiceGroup`,
      label: stringSource.form_field_labels.l10nwish,
      options: [
        WISH_OPTIONS_YES_MAYBE,
        WISH_OPTIONS.no
      ],
      colCount: 1,
      labelClassname: `required`,
      fieldClassname: `form-control choice-group`,
      validator: [
        validator.emptyValueValidator(EMPTY_VALUE_ERROR)
      ]
    },
    'l10nlanguage': {
      type: `choiceGroup`,
      label: stringSource.form_field_labels.l10nlanguage,
      options: [
        LANG_OPTIONS.spanish,
        LANG_OPTIONS.french,
        LANG_OPTIONS.italian,
        LANG_OPTIONS.hindi,
        LANG_OPTIONS.bengali,
        LANG_OPTIONS.german,
        LANG_OPTIONS.russian,
        LANG_OPTIONS.greek,
        LANG_OPTIONS.portugese,
        LANG_OPTIONS.mandarin,
        LANG_OPTIONS.english,
        LANG_OPTIONS.no,
        LANG_OPTIONS.other,
      ],
      colCount: 1,
      labelClassname: `required`,
      fieldClassname: `form-control choice-group`,
      validator: [
        validator.emptyValueValidator(EMPTY_VALUE_ERROR)
      ],
      controller: {
        name: `l10nwish`,
        value: WISH_OPTIONS_YES_MAYBE
      }
    },
    'l10nlanguageother': {
      type: `text`,
      labelClassname: `required`,
      fieldClassname: `form-control`,
      controller: {
        name: `l10nlanguage`,
        value: SUPPORT_OPTIONS.other,
      }
    },
    'l10nsupport': {
      type: `choiceGroup`,
      label: stringSource.form_field_labels.l10nsupport,
      options: [
        SUPPORT_OPTIONS.yes,
        SUPPORT_OPTIONS.no,
        SUPPORT_OPTIONS.other,
      ],
      colCount: 1,
      labelClassname: `required`,
      fieldClassname: `form-control choice-group`,
      validator: [
        validator.emptyValueValidator(EMPTY_VALUE_ERROR)
      ],
      controller: {
        name: `l10nwish`,
        value: WISH_OPTIONS_YES_MAYBE
      }
    },
    'l10nsupportother': {
      type: `text`,
      labelClassname: `required`,
      fieldClassname: `form-control`,
      controller: {
        name: `l10nsupport`,
        value: SUPPORT_OPTIONS.other,
      }
    },
    // 'additionallanguage': {
    //   type: `choiceGroup`,
    //   label: stringSource.form_field_labels.additionallanguage,
    //   options: [
    //     LANGUAGES.spanish,
    //     LANGUAGES.german,
    //     LANGUAGES.french,
    //     LANGUAGES.other
    //   ],
    //   fieldClassname: `form-control choice-group`,
    //   validator: []
    // },
    // 'additionallanguageother': {
    //   type: `text`,
    //   labelClassname: `required`,
    //   fieldClassname: `form-control`,
    //   controller: {
    //     name: `additionallanguage`,
    //     value: LANGUAGES.other
    //   }
    // }
  };
};


var createPartFourFields = function(stringSource) {
  const EMPTY_VALUE_ERROR = stringSource.form_validation_errors.empty_value;
  const MAX_WORD_REACHED_ERROR = stringSource.form_validation_errors.max_word_reached;
  // const LANGUAGES = stringSource.form_field_options.languages;

  return {
    'sessionname': {
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
      ],
      wordLimit: 120,
      wordLimitText: function(charCount, charLimit) {
        // show a twitter-style "characters remainig" count
        return charLimit - charCount;
      }
    },
    'outcome': {
      type: `textarea`,
      label: stringSource.form_field_labels.outcome,
      labelClassname: `required word-length-restriction max-120-words`,
      fieldClassname: `form-control`,
      validator: [
        validator.emptyValueValidator(EMPTY_VALUE_ERROR),
        validator.maxWordsValidator(120, MAX_WORD_REACHED_ERROR)
      ],
      wordLimit: 120,
      wordLimitText: function(charCount, charLimit) {
        // show a twitter-style "characters remainig" count
        return charLimit - charCount;
      }
    },
    'afterfestival': {
      type: `textarea`,
      label: stringSource.form_field_labels.afterfestival,
      labelClassname: `required word-length-restriction max-120-words`,
      fieldClassname: `form-control`,
      validator: [
        validator.emptyValueValidator(EMPTY_VALUE_ERROR),
        validator.maxWordsValidator(120, MAX_WORD_REACHED_ERROR)
      ],
      wordLimit: 120,
      wordLimitText: function(charCount, charLimit) {
        // show a twitter-style "characters remainig" count
        return charLimit - charCount;
      }
    },
    'numsofparticipants': {
      type: `textarea`,
      label: stringSource.form_field_labels.numsofparticipants,
      labelClassname: `required word-length-restriction max-120-words`,
      fieldClassname: `form-control`,
      validator: [
        validator.emptyValueValidator(EMPTY_VALUE_ERROR),
        validator.maxWordsValidator(120, MAX_WORD_REACHED_ERROR)
      ],
      wordLimit: 120,
      wordLimitText: function(charCount, charLimit) {
        // show a twitter-style "characters remainig" count
        return charLimit - charCount;
      }
    }
  };
};

var createPartFiveFields = function(stringSource) {
  const EMPTY_VALUE_ERROR = stringSource.form_validation_errors.empty_value;
  const FORMAT = stringSource.form_field_options.format;
  const TIME = stringSource.form_field_options.timeneeded;

  return {
    'format': {
      type: `choiceGroup`,
      label: stringSource.form_field_labels.format,
      options: [
        FORMAT.learning_forum,
        FORMAT.gallery,
        FORMAT.shed
      ],
      labelClassname: `required`,
      fieldClassname: `form-control choice-group`,
      colCount: 1,
      validator: [
        validator.emptyValueValidator(EMPTY_VALUE_ERROR)
      ]
    },
    'timeneeded': {
      type: `choiceGroup`,
      label: stringSource.form_field_labels.timeneeded,
      options: [
        TIME.less_than_60_mins,
        TIME[`60_mins`],
        TIME[`90_mins`],
        TIME.all_weekend
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

var createPartSixFields = function(stringSource) {
  const MAX_WORD_REACHED_ERROR = stringSource.form_validation_errors.max_word_reached;
  const EMPTY_VALUE_ERROR = stringSource.form_validation_errors.empty_value;

  return {
    'needs': {
      type: `textarea`,
      label: stringSource.form_field_labels.needs,
      labelClassname: `word-length-restriction max-120-words`,
      fieldClassname: `form-control`,
      guideText: stringSource.form_field_labels.needs_hint,
      validator: [
        validator.maxWordsValidator(120, MAX_WORD_REACHED_ERROR)
      ],
      wordLimit: 120,
      wordLimitText: function(charCount, charLimit) {
        // show a twitter-style "characters remainig" count
        return charLimit - charCount;
      }
    },
    'travelstipend': {
      type: `choiceGroup`,
      guideText: stringSource.form_field_labels.travelstipend_hint,
      label: stringSource.form_field_labels.travelstipend,
      options: [
        stringSource.form_field_options.stipendrequired,
        stringSource.form_field_options.stipendnotrequired
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

module.exports = {
  createFields: function(stringSource) {
    return {
      partOne: createPartOneFields(stringSource),
      partTwo: createPartTwoFields(stringSource),
      partThree: createPartThreeFields(stringSource),
      partFour: createPartFourFields(stringSource),
      partFive: createPartFiveFields(stringSource),
      partSix: createPartSixFields(stringSource)
    };
  }
};

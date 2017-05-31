import validator from './validator';

const LABEL_STIPEND_NOT_REQUIRED = `I do not require a travel stipend as I, or my work, can cover my costs`;
const LABEL_STIPEND_REQUIRED = `I can only attend MozFest if I receive a travel stipend covering my travel and accommodation`;

// *** IMPORTANT ***
//
// make sure all keys are all lowercase and contain no symbols or spaces
// reference: https://www.npmjs.com/package/google-spreadsheet#row-based-api-limitations
//
// **********************************************

let partOneFields = {
  'firstname': {
    type: `text`,
    label: `First name`,
    labelClassname: `required`,
    fieldClassname: `form-control`,
    validator: [
      validator.emptyValueValidator()
    ]
  },
  'surname': {
    type: `text`,
    label: `Surname`,
    labelClassname: `required`,
    fieldClassname: `form-control`,
    validator: [
      validator.emptyValueValidator()
    ]
  },
  'email': {
    type: `text`,
    label: `Email address`,
    placeholder: `hello@example.com`,
    labelClassname: `required`,
    fieldClassname: `form-control`,
    validator: [
      validator.emptyValueValidator(),
      validator.emailValidator()
    ]
  },
  'organisation': {
    type: `text`,
    label: `Organisation`,
    fieldClassname: `form-control`,
    validator: []
  },
  'twitterhandle': {
    type: `text`,
    label: `Twitter handle`,
    placeholder: `@twitterhandle`,
    fieldClassname: `form-control`
  },
  'githubhandle': {
    type: `text`,
    label: `GitHub handle`,
    placeholder: `@githubhandle`,
    fieldClassname: `form-control`
  },
  'otherfacilitators': {
    type: `text`,
    label: `Additional facilitators for your session`,
    placeholder: `Firstname Surname`,
    fieldClassname: `form-control`,
    multiplicity: 1,
    addLabel: `+ Add another`
  }
};

let partTwoFields = {
  'space': {
    type: `choiceGroup`,
    label: `What space do feel your session will best contribute to?`,
    options: [
      `Decentralization`,
      `Digital Inclusion`,
      `Open Innovation`,
      `Privacy and Security`,
      `Web Literacy`,
      `Youth Zone`,
    ],
    labelClassname: `required`,
    fieldClassname: `form-control choice-group`,
    validator: [
      validator.emptyValueValidator()
    ]
  },
  'secondaryspace': {
    type: `choiceGroup`,
    label: `Is there an alternate space your session could contribute to?`,
    options: [
      `Decentralization`,
      `Digital Inclusion`,
      `Open Innovation`,
      `Privacy and Security`,
      `Web Literacy`,
      `Youth Zone`,
      `None`
    ],
    colCount: 1,
    fieldClassname: `form-control choice-group`,
  }
};

let partThreeFields = {
  'name': {
    type: `text`,
    label: `Session name`,
    labelClassname: `required`,
    fieldClassname: `form-control`,
    validator: [
      validator.emptyValueValidator()
    ]
  },
  'description': {
    type: `textarea`,
    label: `What will happen in your session?`,
    labelClassname: `required word-length-restriction max-120-words`,
    fieldClassname: `form-control`,
    validator: [
      validator.emptyValueValidator(),
      validator.maxWordsValidator(120)
    ]
  },
  'outcome': {
    type: `textarea`,
    label: `What is the goal or outcome of your session?`,
    labelClassname: `required word-length-restriction max-120-words`,
    fieldClassname: `form-control`,
    validator: [
      validator.emptyValueValidator(),
      validator.maxWordsValidator(120)
    ]
  },
  'afterfestival': {
    type: `textarea`,
    label: `After the festival, how will you and your participants take the learning and activities forward?`,
    labelClassname: `required word-length-restriction max-120-words`,
    fieldClassname: `form-control`,
    validator: [
      validator.emptyValueValidator(),
      validator.maxWordsValidator(120)
    ]
  },
  'timeneeded': {
    type: `choiceGroup`,
    label: `How much time you will need?`,
    options: [
      `Less than 60 mins`,
      `60 mins`,
      `90 mins`,
      `All weekend, as an installation, exhibit or drop-in session`
    ],
    labelClassname: `required`,
    fieldClassname: `form-control choice-group`,
    validator: [
      validator.emptyValueValidator()
    ]
  },
  'numsofparticipants': {
    type: `textarea`,
    label: `How will you deal with varying numbers of participants in your session? What if 30 participants attend? What if there are 3?`,
    labelClassname: `required word-length-restriction max-120-words`,
    fieldClassname: `form-control`,
    validator: [
      validator.emptyValueValidator(),
      validator.maxWordsValidator(120)
    ]
  },
  'additionallanguage': {
    type: `choiceGroup`,
    label: `Would you like to deliver this session bilingually in one of the following languages?`,
    options: [
      `Spanish`,
      `German`,
      `French`,
      `Other`
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
      value: `Other`
    }
  }
};

let partFourFields = {
  'travelstipend': {
    type: `choiceGroup`,
    label: `Do you require a travel stipend?`,
    options: [
      LABEL_STIPEND_NOT_REQUIRED,
      LABEL_STIPEND_REQUIRED
    ],
    labelClassname: `required`,
    fieldClassname: `form-control choice-group`,
    colCount: 1,
    validator: [
      validator.emptyValueValidator()
    ]
  }
};

let partFiveFields = {
  'needs': {
    type: `textarea`,
    label: `If your session requires additional materials or electronic equipment, please outline your needs.`,
    labelClassname: `word-length-restriction max-120-words`,
    fieldClassname: `form-control`,
    validator: [
      validator.maxWordsValidator(120)
    ]
  }
};

module.exports = {
  partOne: partOneFields,
  partTwo: partTwoFields,
  partThree: partThreeFields,
  partFour: partFourFields,
  partFive: partFiveFields,
  LABEL_STIPEND_NOT_REQUIRED: LABEL_STIPEND_NOT_REQUIRED,
  LABEL_STIPEND_REQUIRED: LABEL_STIPEND_REQUIRED
};

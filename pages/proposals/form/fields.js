import validator from './validator';

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
    placeholder: `First name`,
    labelClassname: `required`,
    fieldClassname: `form-control`,
    validator: [
      validator.emptyValueValidator()
    ]
  },
  'surname': {
    type: `text`,
    label: `Surname`,
    placeholder: `Surname`,
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
    label: `Organisation `,
    placeholder: `Organisation `,
    fieldClassname: `form-control`,
    validator: []
  },
  'twitterhandle': {
    type: `text`,
    label: `Twitter handle`,
    placeholder: `Twitter handle`,
    fieldClassname: `form-control`
  },
  'githubhandle': {
    type: `text`,
    label: `GitHub handle`,
    placeholder: `Github handle`,
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
      `Web Literacy`,
      `Decentralisation`,
      `Open Innovation`,
      `Digital Inclusion`,
      `Privacy and Security`,
      `Youth Zone`
    ],
    labelClassname: `required`,
    fieldClassname: `form-control choice-group`,
    validator: [
      validator.emptyValueValidator()
    ]
  },
  'alternatespace': {
    type: `choiceGroup`,
    label: `Is there an alternate space your session could contribute to?`,
    options: [
      `Web Literacy`,
      `Decentralisation`,
      `Open Innovation`,
      `Digital Inclusion`,
      `Privacy and Security`,
      `Youth Zone`
    ],
    fieldClassname: `form-control choice-group`
  }
};

let partThreeFields = {
  'name': {
    type: `text`,
    label: `Session name`,
    placeholder: `Session name`,
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
  'duration': {
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
  'bilingual': {
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
  'bilingualother': {
    type: `text`,
    labelClassname: `required`,
    fieldClassname: `form-control`,
    controller: {
      name: `bilingual`,
      value: `Other`
    }
  }
};

let partFourFields = {
  'travelstipend': {
    type: `choiceGroup`,
    label: `MozFest offers limited places for travel sponsorship covering flights and accommodation over the festival weekend. These stipends are offered to those who need support traveling to the event and would have no other means to attend through work or personal covering of costs. We offer these stipends to encourage diversity in facilitators.`,
    options: [
      `I do not require a travel stipend as I, or my work, can cover my costs`,
      `I can only attend MozFest if I receive a travel stipend covering my travel and accommodation`
    ],
    labelClassname: `required`,
    fieldClassname: `form-control choice-group`,
    colCount: 1,
    validator: [
      validator.emptyValueValidator()
    ]
  },
  'needs': {
    type: `textarea`,
    label: `Many attendees bring a personal device (smartphone, laptop or tablet) to the festival. We can provide you with a projector, and office supplies (paper, pens, post-it notes). If your session requires additional materials or electronic equipment, please outline your needs below. Please note we may not be capable of supporting all your needs but will work with you to provide the best solution possible`,
    placeholder: ``,
    fieldClassname: `form-control`
  }
};

module.exports = {
  partOne: partOneFields,
  partTwo: partTwoFields,
  partThree: partThreeFields,
  partFour: partFourFields
};

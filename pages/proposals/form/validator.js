import validator from 'validator';

const Validator = {
  emptyValueValidator() {
    return {
      error: `This field cannot be left blank.`,
      validate: function(value) {
        return !value;
      }
    };
  },
  maxWordsValidator(maxWordsLength) {
    return {
      error: `Maximum ${maxWordsLength} words.`,
      validate: function(value) {
        if (!value) return false;

        let wordLength = value.split(` `).length;
        this.error = `${wordLength}/${maxWordsLength}`;

        return wordLength > maxWordsLength;
      }
    };
  },
  emailValidator() {
    return {
      error: `Not an valid email.`,
      validate: function(value) {
        return !value || !validator.isEmail(value);
      }
    };
  }
};

export default Validator;

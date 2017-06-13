import validator from 'validator';

const Validator = {
  emptyValueValidator(error = `This field cannot be left blank.`) {
    return {
      error,
      validate: function(value) {
        return !value;
      }
    };
  },
  maxWordsValidator(maxWordsLength = 120, error = `You have typed more words than allowed.`) {
    return {
      error,
      validate: function(value) {
        if (!value) return false;

        let wordLength = value.split(` `).length;
        this.error = `${wordLength}/${maxWordsLength}`;

        return wordLength > maxWordsLength;
      }
    };
  },
  emailValidator(error = `Not an valid email.`) {
    return {
      error,
      validate: function(value) {
        return !value || !validator.isEmail(value);
      }
    };
  }
};

export default Validator;

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

        return value.trim().split(` `).length > maxWordsLength;
      }
    };
  },
  emailValidator(error = `Not a valid email.`, allowedEmpty) {
    return {
      error,
      validate: function(value) {
        if (allowedEmpty && !value) return false;
        if (!value) return true;

        return !validator.isEmail(value); // validator library only validates string ('null' will throw error)
      }
    };
  },
  checkboxValidator(error = `Please check this box if you would like to proceed.`) {
    return {
      error,
      validate: function(value) {
        return !value;
      }
    };
  }
};

export default Validator;

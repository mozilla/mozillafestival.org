import validator from 'validator';
import url from 'url';

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

        let wordLength = value.trim().split(` `).length;
        // this.error = `${wordLength}/${maxWordsLength}`;

        return wordLength > maxWordsLength;
      }
    };
  },
  urlValidator(error = `Not a valid URL. Remember to include protocol (http:// or https://).`) {
    return {
      error,
      validate: function(value) {
        try {
          let parsedUrl = url.parse(value);

          if (parsedUrl.href.indexOf(`https://`) !== 0 && parsedUrl.href.indexOf(`http://`) !== 0) {
            return true;
          }
        } catch (e) {
          // Do nothing.
          // To check if a field is empty or not use Validator.emptyValueValidator() instead.
        }
      }
    };
  },
  emailValidator(error = `Not a valid email.`) {
    return {
      error,
      validate: function(value) {
        if (!value) return true;
        return !validator.isEmail(value); // validator library only validates string ('null' will throw error)
      }
    };
  },
  privacyPolicyAgreementValidator(error = `Please check this box if you would like to proceed.`) {
    return {
      error,
      validate: function(value) {
        return !value;
      }
    };
  },
};

export default Validator;

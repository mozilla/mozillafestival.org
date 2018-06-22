import React from 'react';
import Select from 'react-select';
import CountryList from '../../../lib/country-list';

const PREFER_NOT_TO_SAY_TEXT = `I prefer not to specify`;

let countryOptions = CountryList.map(country => {
  return { value: country, label: country };
});

countryOptions.unshift( { value: PREFER_NOT_TO_SAY_TEXT, label: PREFER_NOT_TO_SAY_TEXT } );

class CountrySelect extends React.Component {
  constructor(props) {
    super(props);
    this.state = {};
  }

  handleChange(selectedOption) {
    this.setState({ selectedOption });
    this.props.onChange(null, selectedOption ? selectedOption.value : ``);
  }

  render() {
    const { selectedOption } = this.state;

    return (
      <Select
        name="form-field-name"
        value={selectedOption}
        onChange={(selected) => this.handleChange(selected) }
        options={countryOptions}
        clearable={false}
      />
    );
  }
}

export default CountrySelect;

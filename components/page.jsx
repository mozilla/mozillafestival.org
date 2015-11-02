var React = require('react');

var Page = React.createClass({
  render: function() {
    return <div className={this.props.name + "-page"}>{ this.props.children }</div>;
  }
});

module.exports = Page;

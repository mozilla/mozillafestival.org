var React = require('react');

var HalfColumn = React.createClass({
  render: function() {
    var className = "half-content";
    if (this.props.ragged) { className += " ragged"; }
    return <div className={className}>{this.props.children}</div>;
  }
});

module.exports = HalfColumn;

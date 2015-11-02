var React = require('react');

var FullWidth = React.createClass({
  render: function() {
    var className = "content wide";
    if (this.props.center) { className += " centered"; }
    var content = <div className={className}>{ this.props.children }</div>;
    if (this.props.white) {
      content = <div className="white-background">{ content }</div>;
    }
    return content;
  }
});

module.exports = FullWidth;

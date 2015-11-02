var React = require('react');
var ImageTag = require('./imagetag.jsx');

var ImageText = React.createClass({
  render: function() {
    var imagetag = <ImageTag src1x={this.props.src1x} width={this.props.width||null} height={this.props.height||null} alt={this.props.alt}/>;
    var img = <div className="illustration-image-container">{imagetag}</div>;
    var text = <div className="illustration-text">{ this.props.children }</div>;
    var className = "illustration" + (this.props.right ? " flip-it": "");
    return <div className={className}>{img}{text}</div>;
  }
});

module.exports = ImageText;

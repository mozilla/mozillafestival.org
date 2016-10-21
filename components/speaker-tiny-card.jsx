var React = require('react');

var SpeakerTinyCard = React.createClass({
  propTypes: {
    name: React.PropTypes.string.isRequired,
    twitter: React.PropTypes.string,
    pic: React.PropTypes.string
  },
  render: function() {
    return (
      <div className="speaker-tiny-card">
        <img className="img-circle" src={this.props.pic || `/assets/images/team/placeholder.jpg` }/>
        <p className="m-b-0 name">{this.props.name}</p>
        { 
          this.props.twitter ? 
          <p className="twitter"><a href={"https://twitter.com/"+this.props.twitter}>{this.props.twitter}</a></p> 
          : null 
        }
      </div>
    );
  }
});

module.exports = SpeakerTinyCard;

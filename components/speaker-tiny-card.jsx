var React = require('react');

var SpeakerTinyCard = React.createClass({
  propTypes: {
    name: React.PropTypes.string.isRequired,
    twitter: React.PropTypes.string,
    pic: React.PropTypes.string,
    link: React.PropTypes.string
  },
  render: function() {
  var headshot = <img className="rounded-circle" src={this.props.pic || `/assets/images/team/placeholder.jpg` }/>;

    return (
      <div className="speaker-tiny-card">
        {this.props.link ? <a href={this.props.link}>{headshot}</a>:headshot}
        <p className="mb-0 name">{this.props.name}</p>
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

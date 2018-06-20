var React = require('react');

var SpeakerTinyCard = React.createClass({
  propTypes: {
    name: React.PropTypes.string.isRequired,
    shortIntro: React.PropTypes.string.isRequired,
    pic: React.PropTypes.string.isRequired,
    link: React.PropTypes.string.isRequired
  },
  render: function() {
    return (
      <div className="speaker-tiny-card mb-5">
        <div className="mb-2">
          <img className="rounded-circle" src={this.props.pic || `/assets/images/team/placeholder.jpg` }/>
        </div>
        <p className="my-0 name">{this.props.name}</p>
        <p className="mt-0 mb-2 subhead">
          <small>{this.props.shortIntro}</small>
        </p>
        <p className="mb-0">
          <a href={this.props.link}>{this.props.talkName}</a>
        </p>
      </div>
    );
  }
});

module.exports = SpeakerTinyCard;

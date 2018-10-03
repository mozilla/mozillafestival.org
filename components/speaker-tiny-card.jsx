var React = require('react');

var SpeakerTinyCard = React.createClass({
  propTypes: {
    name: React.PropTypes.string.isRequired,
    shortIntro: React.PropTypes.string,
    twitter: React.PropTypes.string,
    pic: React.PropTypes.string.isRequired,
    link: React.PropTypes.string,
    talkName: React.PropTypes.string.isRequired
  },
  renderShortIntro: function() {
    if (!this.props.shortIntro) return null;

    return <p className="mt-0 mb-2 subhead">
      <small>{this.props.shortIntro}</small>
    </p>;
  },
  renderTwitter: function() {
    if (!this.props.twitter) return null;

    return <p className="mt-0 mb-2 subhead">
      <a href={`https://twitter.com/${this.props.twitter}`} target="_blank"><small>{this.props.twitter}</small></a>
    </p>;
  },
  renderTalk: function() {
    let talk = this.props.talkName;

    if (this.props.link) {
      talk = <a href={this.props.link} target="_blank">{talk}</a>;
    }

    return <p className="mb-0">
      {talk}
    </p>;
  },
  render: function() {
    return (
      <div className="speaker-tiny-card mb-5">
        <div className="mb-2">
          <img className="rounded-circle" alt={this.props.name} src={this.props.pic || `/assets/images/team/placeholder.jpg` }/>
        </div>
        <p className="my-0 name">{this.props.name}</p>
        { this.renderShortIntro() }
        { this.renderTwitter() }
        { this.renderTalk() }
      </div>
    );
  }
});

module.exports = SpeakerTinyCard;

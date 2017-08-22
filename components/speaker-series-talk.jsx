var React = require('react');
var slugify = require('slugify');

var Speaker = React.createClass({
  propTypes: {
    name: React.PropTypes.string.isRequired
  },
  renderTwitter: function() {
    if (!this.props.twitter) return null;

    return <p className="twitter my-0"><small><a href={"https://twitter.com/"+this.props.twitter}>{this.props.twitter}</a></small></p>;
  },
  renderTitle: function() {
    if (!this.props.title) return null;

    return <p className="title my-0"><small>{this.props.title}</small></p>;
  },
  render: function() {
    return (
      <div className="row my-3">
        <div className="col-6 col-sm-2 mb-5 mb-sm-0">
          <img className="img-fluid rounded-circle" src={ this.props.pic || `/assets/images/team/placeholder.jpg` }/>
        </div>
        <div className="col-sm-10 d-flex flex-column justify-content-center">
          <p className="m-0 text-center text-sm-left"><strong>{this.props.name}</strong></p>
          <div className="subhead text-center text-sm-left">
            { this.renderTwitter() }
            { this.renderTitle() }
          </div>
        </div>
      </div>
    );
  }
});


var SpeakerSeriesTalk = React.createClass({
  propTypes: {
    name: React.PropTypes.string.isRequired,
    videoLink: React.PropTypes.string.isRequired,
    transcriptLink: React.PropTypes.string,
    thumbnail: React.PropTypes.string.isRequired,
    speakers: React.PropTypes.arrayOf(React.PropTypes.shape({
      title: React.PropTypes.string,
      twitter: React.PropTypes.string,
      pic: React.PropTypes.string,
    })).isRequired
  },
  renderTalkLinks: function() {
    return <div>
            <a className="talk-link video" href={this.props.videoLink}>Video</a>
            { this.props.transcriptLink && <a className="talk-link transcript ml-4" href={this.props.transcriptLink}>Transcript</a> }
          </div>;
  },
  renderSpeakers: function() {
    return this.props.speakers.map(speaker => <Speaker {...speaker} key={speaker.name} />);
  },
  render: function() {
    let id = slugify(this.props.name);

    return (
      <div className="row my-5 justify-content-center speaker-series-talk" id={id}>
        <div className="col-12 text-center text-sm-left">
          <h3 className="name mb-0">{this.props.name}</h3>
          { this.renderTalkLinks() }
          <div className="subhead">
            { this.renderSpeakers() }
          </div>
        </div>
      </div>
    );
  }
});

module.exports = SpeakerSeriesTalk;

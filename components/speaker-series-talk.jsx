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
  renderDescription: function() {
    if (!this.props.description || this.props.description.length === 0) return null;

    let description;

    // Usually we would avoid using "dangerouslySetInnerHTML" but since the description came from
    // a static file we control, it is okay to do this here
    if ( typeof(this.props.description) === `string` ) {
      description = <p dangerouslySetInnerHTML={{__html: this.props.description}}></p>;
    } else if (Array.isArray(this.props.description)) {
      description = this.props.description.map((p, i) => <p dangerouslySetInnerHTML={{__html: p}} key={i}></p>);
    }

    return <div className="description">{description}</div>;
  },
  render: function() {
    return (
      <div className="row my-3">
        <div className="col-6 col-sm-2 mb-5 mb-sm-0 mx-auto">
          <img className="img-fluid rounded-circle" 
                src={ this.props.pic || `/assets/images/team/placeholder.jpg` }
                alt={`${this.props.name} Pic`}
          />
        </div>
        <div className="col-sm-10 d-flex flex-column justify-content-center">
          <p className="m-0 text-center text-sm-left"><strong>{this.props.name}</strong></p>
          <div className="subhead text-center text-sm-left">
            { this.renderTwitter() }
            { this.renderDescription() }
          </div>
        </div>
      </div>
    );
  }
});


var SpeakerSeriesTalk = React.createClass({
  propTypes: {
    name: React.PropTypes.string,
    videoLink: React.PropTypes.string,
    transcriptLink: React.PropTypes.string,
    speakers: React.PropTypes.arrayOf(React.PropTypes.shape({
      title: React.PropTypes.string,
      twitter: React.PropTypes.string,
      pic: React.PropTypes.string,
    })).isRequired
  },
  renderName: function() {
    if (!this.props.name) return null;

    return <h3 className="name mb-0">{this.props.name}</h3>;
  },
  renderTalkLinks: function() {
    return <div>
            { this.props.videoLink && <a className="talk-link video" href={this.props.videoLink}>Video</a> }
            { this.props.transcriptLink && <a className="talk-link transcript ml-4" href={this.props.transcriptLink}>Transcript</a> }
          </div>;
  },
  renderSpeakers: function() {
    return this.props.speakers.map(speaker => <Speaker {...speaker} key={speaker.name} />);
  },
  render: function() {
    let identifier = this.props.name ? this.props.name : `${this.props.speakers[0].name}-talk`;
    let id = slugify(identifier);

    return (
      <div className="row my-5 pb-4 justify-content-center speaker-series-talk" id={id}>
        <div className="col-12 text-center text-sm-left">
          { this.renderName() }
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

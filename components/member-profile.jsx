var React = require('react');
var slugify = require('slugify');

var MemberProfile = React.createClass({
  propTypes: {
    name: React.PropTypes.string.isRequired,
    title: React.PropTypes.string,
    twitter: React.PropTypes.string,
    pic: React.PropTypes.string,
    bio: React.PropTypes.array
  },
  renderBio: function() {
    if (!this.props.bio) return null;

    let paragraphs = this.props.bio.map((paragraph) => {
    if (!paragraph) return null;

    // Usually we would avoid using "dangerouslySetInnerHTML" but since the bio came from
    // a static file we control, it is okay to do this here
    return <p key={paragraph} dangerouslySetInnerHTML={{__html: paragraph}}></p>;
  });

  if (paragraphs.length < 1) return null;

  return <div>{paragraphs}</div>;
  },
  render: function() {
    let id = slugify(this.props.name);

    return (
      <div className="row my-5 justify-content-center member-profile" id={id}>
        <div className="col-6 col-sm-3 mb-5 mb-sm-0 center-vertical">
          <img className="rounded-circle" src={ this.props.pic || `/assets/images/team/placeholder.jpg` }/>
        </div>
        <div className="col-sm-9">
          <h3 className="mb-0 name text-center text-sm-left"><a href={`#`+id}>{this.props.name}</a></h3>
          <div className="subhead text-center text-sm-left">
            { this.props.title ? <p className="title my-0">{this.props.title}</p> : null }
            { this.props.twitter ? <p className="twitter my-0"><a href={"https://twitter.com/"+this.props.twitter}>{this.props.twitter}</a></p> : null }
          </div>
          { this.renderBio() }
        </div>
      </div>
    );
  }
});

module.exports = MemberProfile;

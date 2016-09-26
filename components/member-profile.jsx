var React = require('react');
var slug = require(`slug`);

slug.defaults.mode = `rfc3986`;

var MemberProfile = React.createClass({
  propTypes: {
    name: React.PropTypes.string.isRequired,
    title: React.PropTypes.string,
    twitter: React.PropTypes.string,
    pic: React.PropTypes.string,
    bio: React.PropTypes.object
  },
  render: function() {
    return (
      <div className="row m-y-3 member-profile" id={slug(this.props.name)}>
        <div className="col-sm-3 center-vertical">
          <img className="circle" src={this.props.pic}/>
        </div>
        <div className="col-sm-9">
          <h3 className="m-b-0 name">{this.props.name}</h3>
          <div className="subhead">
            { this.props.title ? <h4 className="title">{this.props.title}</h4> : null }
            { this.props.twitter ? <h4 className="twitter"><a href={"https://twitter.com/"+this.props.twitter}>{this.props.twitter}</a></h4> : null }
          </div>
          { this.props.bio ? <div>{this.props.bio}</div> : null }
        </div>
      </div>
    );
  }
});

module.exports = MemberProfile;

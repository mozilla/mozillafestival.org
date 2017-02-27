var React = require(`react`);

var ProjectCard = React.createClass({
  render: function() {
    return (
      <div className="col-xs-12 col-md-6 card-container">
        <div className="project-card mb-3">
          <img src={this.props.image} className="thumbnail" />
          <div className="content project-card">
            <h2 className="mt-3">{this.props.title}</h2>
            <h3>{this.props.creators}</h3>
            <p className="description">{this.props.description}</p>
            <p className="interest">{this.props.interest}</p>
            <p className="get-involved">
              {this.props.getInvolved}
            </p>
            <div><a href={this.props.getInvolvedURL} target="_blank" className="get-involved-link">Get Involved</a></div>
            <div className="project-links">
              <a href={this.props.url} target="_blank" className="btn btn-primary-outline my-3">Visit</a>
            </div>
          </div>
        </div>
      </div>
    );
  }
});

module.exports = ProjectCard;

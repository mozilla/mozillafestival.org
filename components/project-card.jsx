var React = require(`react`);

var ProjectCard = React.createClass({
  render: function() {
    return (
      <div className="col-xs-12 col-md-6 card-container">
        <div className="project-card m-b-1">
          <img src={this.props.image} className="thumbnail" />
          <div className="content project-card">
            <h2 className="m-t-1">{this.props.title}</h2>
            <h3>{this.props.creators}</h3>
            <p className="description">{this.props.description}</p>
            <p className="interest">{this.props.interest}</p>
            <p className="get-involved">
              {this.props.getInvolved} <div><a href={this.props.getInvolvedURL} target="_blank" className="get-involved-link">Get Involved</a></div>
            </p>
            <div className="project-links">
              <div className="btn-group m-b-1"> 
                <a href={this.props.url} target="_blank" className="btn visit-btn">Visit</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    );
  }
});

module.exports = ProjectCard;

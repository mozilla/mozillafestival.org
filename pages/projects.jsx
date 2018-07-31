var React = require(`react`);
var ProjectCard = require(`../components/project-card.jsx`);
var projectData = require(`../mozfest-projects.json`);

import Jumbotron from '../components/jumbotron.jsx';

var ProjectPage = React.createClass({
  render: function() {
    return (
      <div className="projects-page">
        <Jumbotron image="/assets/images/hero/projects.jpg"
          image2x="/assets/images/hero/projects.jpg">
          <h1>MozFest Projects</h1>
        </Jumbotron>
        <div className="content wide">
          <p>MozFest attracts and generates dozens of projects that thrive long after the festival weekend. See the examples below and find a catalyst for collaboration with our vibrant network. <a href="https://mzl.la/pulse">Browse more projects on Pulse</a>.</p>
          <div className="pb-3" name="Production" slug="production">
            <div className="horizontal-rule"></div>
            <div className="row">
              {projectData.map((project, index)=>{ return <ProjectCard key={index} {...project } />; } )}
            </div>
          </div>
          <p>See more projects & assets from our network on <a href="http://mzl.la/pulse">Pulse</a>.</p>
        </div>
      </div>
    );
  }
});

module.exports = ProjectPage;


var React = require(`react`);
var Header = require(`../components/header.jsx`);
var Footer = require(`../components/footer.jsx`);
var ProjectCard = require(`../components/project-card.jsx`);
var projectData = require(`../mozfest-projects.json`);

var ProjectPage = React.createClass({
  render: function() {
    return (
      <div className="projects-page">
        <Header/>
        <div className="content wide">
            <div className="p-y-3" name="Production" slug="production">
              <h1>Mozfest Projects</h1>
              <div className="horizontal-rule"></div>
                <div className="row">
                  {projectData.map((project, index)=>{ return <ProjectCard key={index} {...project } />; } )}
                </div>
            </div>
        </div>
        <Footer/>
      </div>
    );
  }
});

module.exports = ProjectPage;


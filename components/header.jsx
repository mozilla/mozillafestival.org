var React = require('react');
var Router = require('react-router');
var Link = Router.Link;
var ImageTag = require('./imagetag.jsx');

var Header = React.createClass({
  render: function() {
    var logoImage = this.props.logoImage || "/assets/images/mozilla-festival_wordmark-interim_horizontal.svg";
    return (
      <div className="header">
        <div className="header-content">
          <div className="nav-home">
            <Link to="/">
              <ImageTag src1x={logoImage}
                alt="mozfest logo"/>
            </Link>
          </div>
          <div className="nav-items">
            <div className="nav-link-container">
              <Link to="/expect" activeClassName="active">What to Expect</Link>
            </div>
            <div className="nav-link-container">
              <Link to="/spaces-and-sessions" activeClassName="active">Spaces & Sessions</Link>
            </div>
            <div className="nav-link-container">
              <Link to="/projects" activeClassName="active">Projects</Link>
            </div>
            <div className="nav-link-container">
              <a href="https://app.mozillafestival.org/">Schedule</a>
            </div>
            <div className="nav-link-container">
              <a href="https://medium.com/mozilla-festival/tagged/mozfest2016">Blog</a>
            </div>
          </div>
          <div id="tabzilla">
            <a href="https://www.mozilla.org/">Mozilla</a>
          </div>
        </div>
      </div>
    );
  }
});

module.exports = Header;

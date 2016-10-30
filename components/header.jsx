var React = require('react');
var Router = require('react-router');
var Link = Router.Link;
var ImageTag = require('./imagetag.jsx');

var Header = React.createClass({
  render: function() {
    var logoImage = this.props.logoImage || "/assets/images/logo-mozfest-pink.svg";
    return (
      <div className="header">
        <div className="header-content">
          <div className="nav-home">
            <Link to="home">
              <ImageTag src1x={logoImage}
                alt="mozfest logo"/>
            </Link>
          </div>
          <div className="nav-items">
            <div className="nav-link-container">
              <a href="https://mzl.la/mozfest-pulse?keyword=mozfest">Projects</a>
            </div>
            <div className="nav-link-container">
              <a href="https://app.mozillafestival.org/">Schedule</a>
            </div>
            <div className="nav-link-container">
              <a href="https://medium.com/mozilla-festival/tagged/mozfest2016">Blog</a>
            </div>
            <div className="nav-link-container">
              <Link to="expect">What to Expect</Link>
            </div>
            <div className="nav-link-container">
              <Link to="sessions">Spaces & Sessions</Link>
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

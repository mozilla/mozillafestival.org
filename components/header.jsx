var React = require('react');
var Router = require('react-router');
var Link = Router.Link;
var ImageTag = require('./imagetag.jsx');

var Header = React.createClass({
  render: function() {
    var logoImage = this.props.logoImage || "/assets/images/logo-mozilla-festival.svg";
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
            <Link to="proposals">call for proposals</Link>
            <Link to="tickets">tickets</Link>
            <Link to="location">location</Link>
            <div className="mozfest-tabzilla-container">
              <a href="https://www.mozilla.org/" id="tabzilla">mozilla</a>
            </div>
          </div>
        </div>
      </div>
    );
  }
});

module.exports = Header;

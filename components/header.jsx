var React = require('react');
var Router = require('react-router');
var Link = Router.Link;

var Header = React.createClass({
  render: function() {
    return (
      <div className="header">
        <div className="nav-home">
          <Link to="home">home placeholder</Link>
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
    );
  }
});

module.exports = Header;

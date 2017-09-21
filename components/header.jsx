var React = require('react');
var Router = require('react-router');
var Link = Router.Link;
var ImageTag = require('./imagetag.jsx');

const NAV_LINKS = [
  { path: `/tickets`, label: `Tickets` },
  { path: `/about`, label: `About` },
  { path: `/spaces`, label: `Spaces` },
  { path: `/speakers`, label: `Speakers` },
  { path: `/expect`, label: `What to expect` },
  { path: `/house`, label: `House` },
  { path: `/team/sponsors`, label: `Sponsors` }
];

var Header = React.createClass({
  renderNavLinks: function() {
    return NAV_LINKS.map(link => {
      return <div className="nav-link-container d-inline-block" key={link.label}>
              <Link to={link.path} activeClassName="active">{link.label}</Link>
            </div>;
    });
  },
  render: function() {
    var logoImage = this.props.logoImage || "/assets/images/mozilla-festival_wordmark-interim_horizontal.svg";
    return (
      <div className="page-header">
        <div className="header-content">
          <div className="nav-home">
            <Link to="/">
              <ImageTag src1x={logoImage}
                alt="mozfest logo"/>
            </Link>
          </div>
          <div className="nav-items">
            { this.renderNavLinks() }
          </div>
        </div>
      </div>
    );
  }
});

module.exports = Header;

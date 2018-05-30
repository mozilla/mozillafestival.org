import React from 'react';
import { NavLink } from 'react-router-dom';

var ImageTag = require('./imagetag.jsx');

const NAV_LINKS = [
  // { path: `/tickets`, label: `Tickets` },
  // { externalLink: `https://guidebook.com/guide/114124/`, label: `schedule` },
  // { externalLink: `https://www.mozillapulse.org/tags/mozfest`, label: `Projects` },
  // { path: `/speakers`, label: `Speakers` },
  { path: `/about`, label: `About` },
  { path: `/proposals`, label: `Proposals` },
  { path: `/expect`, label: `What to Expect` },
  { path: `/spaces`, label: `Spaces & Experiences` },
  { path: `/house`, label: `House` },
  { path: `/team/sponsors`, label: `Sponsors` }
];

class Header extends React.Component {
  renderNavLinks() {
    return NAV_LINKS.map(link => {
      return <div className="nav-link-container d-inline-block mx-2 mb-2 my-sm-0" key={link.label}>
        { link.path && <NavLink to={link.path} activeClassName="active">{link.label}</NavLink> }
        { link.externalLink && <a href={link.externalLink}>{link.label}</a> }
      </div>;
    });
  }

  render() {
    return (
      <div className="page-header">
        <div className="header-content justify-content-end">
          <div className="nav-home my-4">
            <NavLink to="/" className="d-block">
              <ImageTag src1x="/assets/images/Mozilla-Festival-2018.svg" alt="mozfest logo"/>
            </NavLink>
          </div>
          <div className="nav-items">
            { this.renderNavLinks() }
          </div>
        </div>
      </div>
    );
  }
}

export default Header;

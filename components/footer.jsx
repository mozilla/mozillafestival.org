import React from 'react';
import {Router, Link} from 'react-router';
import ImageTag from './imagetag.jsx';
import { MofoFooter } from 'mofo-ui';

const FOOTER_LINKS = [
  {
    iconType: `chat`,
    link: `https://medium.com/mozilla-festival/tagged/mozfest2016`,
    text: `Blog`
  },
  {
    iconType: `info`,
    link: `/team`,
    text: `Festival Team`
  },
  {
    iconType: `code-of-conduct`,
    link: `/guidelines`,
    text: `Participation Guidelines`
  },
  {
    iconType: `info`,
    link: `/media`,
    text: `Media`
  },
  {
    iconType: `legal`,
    link: `https://www.mozilla.org/about/legal/terms/mozilla/`,
    text: `Terms`
  },
  {
    iconType: `privacy`,
    link: `https://www.mozilla.org/privacy/websites`,
    text: `Privacy`
  },
  {
    iconType: `cookies`,
    link: `https://www.mozilla.org/privacy/websites/#cookies`,
    text: `Cookies`
  }
];

const ORGS = [
  {
    name: `mozilla`,
    link: `https://mozilla.org`,
    description: (<p>Mozilla is a global non-profit dedicated to putting you in control of your online experience and shaping the future of the web for the public good. Visit us at <a href="https://mozilla.org">mozilla.org</a>.</p>),
    className: `mozilla`
  }
];

const SAMPLE_TWEET = encodeURIComponent(` I'm attending #MozFest, the world's leading festival for the open Internet movement. Get your ticket here:`);
const SAMPLE_TWEET_URL = `https://mozillafestival.org/tickets`;

var Linker = React.createClass({
  render: function() {
    // Swap out Link or a simple anchor depending on the props we have.
    if (this.props.to) {
      return (
        <Link to={this.props.to}>
          {this.props.children}
        </Link>
      )
    }
    return (
      <a href={this.props.href} target={this.props.target}>
        {this.props.children}
      </a>
    )
  }
});

var FooterIcon = React.createClass({
  render: function() {
    return (
      <div className="footer-icon col">
        <Linker to={this.props.to} href={this.props.href} target={this.props.target}>
          <div className="icon-circle">
            <ImageTag src1x={this.props.icon}
              alt={this.props.alt}/>
          </div>
          {this.props.children}
        </Linker>
      </div>
    );
  }
});

var Footer = React.createClass({
  render: function() {
    return (
      <div>
        <nav className="site-footer">
          <div className="footer-content container">
            <div className="footer-icons row">
              <FooterIcon icon="/assets/images/img-envelope.svg" alt="contact icon" to="/contact">
                get in touch
              </FooterIcon>
              <FooterIcon icon="/assets/images/img-twitter.svg" alt="tweet icon" href={`https://twitter.com/intent/tweet?text=${SAMPLE_TWEET}&url=${SAMPLE_TWEET_URL}`}>
                tweet #mozfest
              </FooterIcon>
              <FooterIcon icon="/assets/images/img-hand.svg" alt="volunteer icon" to="/volunteer">
                volunteer with us
              </FooterIcon>
              <FooterIcon icon="/assets/images/img-fringe.svg" alt="fringe event icon" to="/fringe">
                fringe events
              </FooterIcon>
            </div>
          </div>
        </nav>
        <MofoFooter footerLinks={FOOTER_LINKS} orgs={ORGS} />
      </div>
    );
  }
});

module.exports = Footer;

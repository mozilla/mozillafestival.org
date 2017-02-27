import React from 'react';
import {Router, Link} from 'react-router';
import ImageTag from './imagetag.jsx';
import { MofoFooter } from 'mofo-ui';

const FOOTER_LINKS = [
  {
    link: `/about`,
    text: `About`
  },
  {
    link: `/team`,
    text: `Festival Team`
  },
  {
    link: `/guidelines`,
    text: `Participation Guidelines`
  },
  {
    link: `https://www.mozilla.org/en-US/about/legal/terms/mozilla/`,
    text: `Terms`
  },
  {
    link: `https://www.mozilla.org/privacy/websites`,
    text: `Privacy`
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
              <FooterIcon icon="/assets/images/img-twitter.svg" alt="tweet icon" href="https://twitter.com/intent/tweet?text=I'm%20attending%20%23mozfest%2C%20the%20biggest%20event%20for%20%26%20by%20the%20open%20Internet%20movement.%20Join%20me%20%26%20%40Mozilla%20in%20London%3A%20mzl.la%2Fmozfest&source=webclient">
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

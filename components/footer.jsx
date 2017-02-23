var React = require('react');
var Router = require('react-router');
var Link = Router.Link;
var ImageTag = require('./imagetag.jsx');

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
      <div className="footer-icon">
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

var FooterIcons = React.createClass({
  render: function() {
    return (
      <div className="footer-icons">
        {this.props.children}
      </div>
    );
  }
});

var Footer = React.createClass({
  render: function() {
    return (
      <footer>
        <div className="footer-content">
          <FooterIcons>
            <FooterIcon icon="/assets/images/img-envelope.svg" alt="contact icon" to="/contact">
              get in touch
            </FooterIcon>
            <FooterIcon icon="/assets/images/img-twitter.svg" alt="tweet icon" href="https://twitter.com/intent/tweet?text=I'm%20attending%20%23mozfest%2C%20the%20biggest%20event%20for%20%26%20by%20the%20open%20Internet%20movement.%20Join%20me%20%26%20%40Mozilla%20in%20London%3A%20mzl.la%2Fmozfest&source=webclient">
              tweet #mozfest
            </FooterIcon>
            <FooterIcon icon="/assets/images/img-blog.svg" alt="blog icon" target="_blank" href="https://www.medium.com/mozilla-festival">
              read the blog
            </FooterIcon>
            <FooterIcon icon="/assets/images/img-hand.svg" alt="volunteer icon" to="/volunteer">
              volunteer with us
            </FooterIcon>
            <FooterIcon icon="/assets/images/img-fringe.svg" alt="fringe event icon" to="/fringe">
              fringe events
            </FooterIcon>
          </FooterIcons>

          <div className="link-container">
            <div className="nav-link-container">
              <Link to="/about">About</Link>
            </div>
            <div className="nav-link-container">
              <Link to="/team">Festival Team</Link>
            </div>
            <div className="nav-link-container">
              <Link to="/guidelines">Participation Guidelines</Link>
            </div>
            <div className="nav-link-container">
              <a href="https://beta.webmaker.org/#/legal" target="_blank">Terms</a>
            </div>
            <div className="nav-link-container">
              <a href="https://www.mozilla.org/privacy/websites" target="_blank">Privacy</a>
            </div>
          </div>
        </div>
      </footer>
    );
  }
});

module.exports = Footer;

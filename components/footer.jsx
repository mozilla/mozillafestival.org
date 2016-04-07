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
      <a href={this.props.href}>
        {this.props.children}
      </a>
    )
  }
});

var FooterIcon = React.createClass({
  render: function() {
    return (
      <div className="footer-icon">
        <Linker to={this.props.to} href={this.props.href}>
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
      <div className="footer">
        <div className="footer-content">
          <FooterIcons>
            <FooterIcon icon="/assets/images/img-envelope.svg" alt="contact icon" to="contact">
              get in touch
            </FooterIcon>
            <FooterIcon icon="/assets/images/img-twitter.svg" alt="tweet icon" href="https://twitter.com/intent/tweet?text=%23mozfest&source=webclient">
              tweet #mozfest
            </FooterIcon>
            <FooterIcon icon="/assets/images/img-hand.svg" alt="volunteer icon" to="volunteer">
              volunteer with us
            </FooterIcon>
          </FooterIcons>

          <div className="horizontal-rule"></div>

          <div className="link-container">
            <div className="nav-link-container">
              <Link to="about">About</Link>
            </div>
            <div className="nav-link-container">
              <Link to="team">Festival Team</Link>
            </div>
            <div className="nav-link-container">
              <Link to="guidelines">Participation Guidelines</Link>
            </div>
          </div>
        </div>
      </div>
    );
  }
});

module.exports = Footer;

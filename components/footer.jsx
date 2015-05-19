var React = require('react');
var Router = require('react-router');
var Link = Router.Link;

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
          <div className="icon-circle"></div>
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
            <FooterIcon to="contact">
              get in touch
            </FooterIcon>
            <FooterIcon href="https://twitter.com/intent/tweet?text=%23mozfest&source=webclient">
              tweet #mozfest
            </FooterIcon>
            <FooterIcon to="volunteer">
              volunteer with us
            </FooterIcon>
          </FooterIcons>
          <div className="horizontal-rule"></div>
          <div className="link-container">
            <Link to="about">About</Link>
            <Link to="expect">What to Expect</Link>
            <Link to="guidelines">Participation Guidelines</Link>
            {/*<Link to="fringe-events">Fringe Events</Link>*/}
          </div>
        </div>
      </div>
    );
  }
});

module.exports = Footer;

var React = require('react');
var Header = require('../components/header.jsx');
var Footer = require('../components/footer.jsx');
var HeroUnit = require('../components/hero-unit.jsx');
var Router = require('react-router');
var Link = Router.Link;
var ImageTag = require('../components/imagetag.jsx');
var generateHelmet = require('../lib/helmet.jsx');

var Home = React.createClass({
  pageMetaDescription: "Three days of building a truly global web together. Come with an idea, leave with a community.",
  getInitialState: function() {
    return {
      playVideo: false
    };
  },
  playVideo: function() {
    this.setState({
      playVideo: true
    });
  },
  render: function() {
    var self = this;
    return (
      <div className="home-page">
        {generateHelmet(this.pageMetaDescription)}
        <Header logoImage="/assets/images/logo-mozfest-white.svg"/>
        <HeroUnit className="home-hero-unit" image="/assets/images/hero/home/home.jpg"
                  image2x="/assets/images/hero/home/home.jpg"
                  hideBackgroundLines={true}>
          <div className="content-wrapper">
            <h1>MozFest</h1>
            <h2>The world's leading festival for the open internet movement.</h2>
            <div className="horizontal-rule"></div>
            <p>Join influential thinkers from around the world to build, debate, and explore the future of a healthy internet.</p>
            <h3>October 28-30, 2016 • Ravensbourne College, London</h3>
            {function() {
              if (self.state.playVideo) {
                return (
                  <div className="iframe-container">
                    <iframe src="https://player.vimeo.com/video/154774646?color=ffffff&title=0&byline=0&portrait=0" frameBorder="0" allowFullScreen></iframe>
                  </div>
                );
              } else {
                return (
                  <a onClick={self.playVideo} className="video-play-link button">
                    Watch Video
                  </a>
                );
              }
            }()}
          </div>
        </HeroUnit>
        <div className="centered content wide">
          <h1>Buy a Ticket for MozFest 2016</h1>
          <p>MozFest 2016 is a chance to build something, grow your network and get inspired. Sound interesting?</p>
          <Link to="tickets" className="button"><span>Buy a Ticket</span></Link>
        </div>
        <Footer/>
      </div>
    );
  }
});

module.exports = Home;

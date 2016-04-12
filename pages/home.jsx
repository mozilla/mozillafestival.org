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
        <Header logoImage="/assets/images/logo-mozilla-festival-white.svg"/>
        <HeroUnit className="home-hero-unit" image="/assets/images/home.jpg"
                  image2x="/assets/images/home.jpg">
          <div className="video-wrapper">
          {function() {
            if (self.state.playVideo) {
              return (
                <div className="iframe-container">
                  <iframe src="https://player.vimeo.com/video/154774646?color=ffffff&title=0&byline=0&portrait=0" frameBorder="0" allowFullScreen></iframe>
                </div>
              );
            } else {
              return (
                <div>
                  <div onClick={self.playVideo} className="video-link">
                    <ImageTag src1x="/assets/images/img-play.svg"
                      alt="play button icon"/>
                  </div>
                </div>
              );
            }
          }()}
          </div>
          <h1>mozilla festival</h1>
          <h2>October 28-30, 2016<br/>Ravensbourne, London</h2>
          <div className="horizontal-rule"></div>
          <div>Three days of building a truly global web together.</div>
          <div>Come with an idea, leave with a community.</div>
        </HeroUnit>
        <div className="centered content wide">
          <h1>Help Make the Web a Better Place</h1>
          <p>Receive festival news and updates by email</p>
          <a href="https://ti.to/Mozilla/mozfest-2016" className="button"><span>Sign up</span></a>
        </div>
        <Footer/>
      </div>
    );
  }
});

module.exports = Home;


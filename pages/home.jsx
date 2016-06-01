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
        <HeroUnit className="home-hero-unit" image="/assets/images/mozfest_2016.jpg"
                  image2x="/assets/images/mozfest_2016.jpg">
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
          <h1>MozFest</h1>
          <h2>October 28-30, 2016<br/>Ravensbourne, London</h2>
          <div className="horizontal-rule"></div>
          <div>The world’s leading event for and by the open Internet movement.</div>
          <div>Join us to build, debate, and explore the future of our lives online.</div>
        </HeroUnit>
        <div className="centered content wide">
          <h1>Submit a Session for MozFest 2016</h1>
          <p>We invite people from all fields: policy, science, journalism, technology, arts and more. MozFest is for everyone, regardless of your gender, geography, age or language. </p>
          <Link to="proposals" className="button"><span>Submit a Session</span></Link>
        </div>
        <Footer/>
      </div>
    );
  }
});

module.exports = Home;

var React = require('react');
var Header = require('../components/header.jsx');
var Footer = require('../components/footer.jsx');
var HeroUnit = require('../components/hero-unit.jsx');
var Router = require('react-router');
var Link = Router.Link;
var ImageTag = require('../components/imagetag.jsx');

var Home = React.createClass({
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
        <Header logoImage="/assets/images/logo-mozilla-festival-white.svg"/>
        <HeroUnit className="home-hero-unit" image="/assets/images/home.jpg"
                  image2x="/assets/images/home.jpg">
          {function() {
            if (self.state.playVideo) {
              return (
                <div className="iframe-container">
                  <iframe width="640" height="390" src="https://www.youtube.com/embed/dYoiHhsdHCk?autoplay=1" frameborder="0" allowfullscreen></iframe>
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
          <h1>mozilla festival</h1>
          <h2>november 6-8 in london</h2>
          <div className="horizontal-rule"></div>
          <div>Three days of building a truly global web together.</div>
          <div>Come with an idea, leave with a community.</div>
        </HeroUnit>
        <div className="centered content wide">
          <h1>Help Make the Web a Better Place</h1>
          <p>Share your ideas for improving the open Internet</p>
          <Link to="proposals" className="button"><span>Submit a Proposal</span></Link>
        </div>
        <Footer/>
      </div>
    );
  }
});

module.exports = Home;


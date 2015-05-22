var React = require('react');
var Header = require('../components/header.jsx');
var Footer = require('../components/footer.jsx');
var HeroUnit = require('../components/hero-unit.jsx');
var Router = require('react-router');
var Link = Router.Link;
var ImageTag = require('../components/imagetag.jsx');

var Home = React.createClass({
  render: function() {
    return (
      <div className="home-page">
        <Header logoImage="/assets/images/logo-mozilla-festival-white.svg"/>
        <HeroUnit image="/assets/images/home.jpg"
                  image2x="/assets/images/home.jpg">
          <ImageTag src1x="/assets/images/img-play.svg"
            alt="play button icon"/>
          <h1>mozilla festival</h1>
          <h2>november 6-8 in london</h2>
          <div className="horizontal-rule"></div>
          <div>Three days of building a truly global web&mdash;together.</div>
          <div>Come with an idea, leave with a community.</div>
        </HeroUnit>
        <div className="centered content wide">
          <h1>Help Make the Web a Better Place</h1>
          <p>Share your ideas for improving the open Internet</p>
          <Link to="proposals" className="button">Submit a Proposal</Link>
        </div>
        <Footer/>
      </div>
    );
  }
});

module.exports = Home;


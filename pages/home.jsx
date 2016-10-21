var React = require('react');
var Header = require('../components/header.jsx');
var Footer = require('../components/footer.jsx');
var HeroUnit = require('../components/hero-unit.jsx');
var Router = require('react-router');
var Link = Router.Link;
var ImageTag = require('../components/imagetag.jsx');
var SpeakersPromo = require('../components/speakers-promo.jsx');
var generateHelmet = require('../lib/helmet.jsx');


var speakersInfo = [
  {
    name: `Zeynep Tufekci`,
    twitter: `@zeynep`,
    pic: `/assets/images/speakers/Tufekci.jpg`
  },
  {
    name: `Brian Behlendorf`,
    twitter: `@brianbehlendorf`,
    pic: `/assets/images/speakers/Behlendorf.jpg`
  },
  {
    name: `Maggie Vail`,
    twitter: `@magicbeans`,
    pic: `/assets/images/speakers/Vail.jpg`
  },
  {
    name: `Mark Surman`,
    twitter: `@msurman`,
    pic: `/assets/images/speakers/Surman.jpg`
  },
  {
    name: `Sahar Aziz`,
    twitter: `@saharazizlaw`,
    pic: `/assets/images/speakers/Aziz.jpg`
  },
  {
    name: `Katherine Maher`,
    twitter: `@krmaher`,
    pic: `/assets/images/speakers/Maher.jpg`
  },
  {
    name: `Chris Soghoian`,
    twitter: `@csoghoian`,
    pic: `/assets/images/speakers/Soghoian.jpg`
  },
  {
    name: `Ashe Dryden`,
    twitter: `@ashedryden`,
    pic: `/assets/images/speakers/Dryden.jpg`
  },
  {
    name: `Volker Birk`,
    pic: `/assets/images/speakers/Birk.jpg`
  },
  {
    name: `Katharina Borchert`,
    twitter: `@lyssaslounge`,
    pic: `/assets/images/speakers/Borchert.jpg`
  },
  {
    name: `Simone Browne`,
    pic: `/assets/images/speakers/Browne.jpg`
  },
  {
    name: `Eliot Higgins`,
    twitter: `@EliotHiggins`,
    pic: `/assets/images/speakers/Higgins.jpg`
  }
];

var Home = React.createClass({
  pageMetaDescription: "Three days of building a truly global web together. Come with an idea, leave with a community.",
  render: function() {
    var self = this;
    return (
      <div className="home-page">
        {generateHelmet(this.pageMetaDescription)}
        <Header logoImage="/assets/images/logo-mozfest-white.svg"/>
        <HeroUnit className="home-hero-unit" image="/assets/images/hero/home/home2-cropped.jpg"
                  image2x="/assets/images/hero/home/home2-cropped.jpg"
                  hideBackgroundLines={true}>
          <div className="content-wrapper">
            <h1>Welcome to MozFest</h1>
            <h2>The world's leading festival for the open internet movement.</h2>
            <div className="horizontal-rule"></div>
            <p>Watch MozFest's speaker series live, beginning Oct 29, 11am BST</p>
            <a href="https://www.facebook.com/mozilla" className="button">Watch Live</a>
          </div>
        </HeroUnit>
        <div className="white-background">
          <div className="centered content wide">
            <h1>Speaker Series Presenters</h1>
            <SpeakersPromo speakersInfo={speakersInfo} />
          </div>
        </div>
        <div className="centered content wide">
          <h1>Missed MozFest this year?</h1>
          <p>Sign up for MozFest updates and be first in line to get your tickets for 2017.</p>
          <a href="https://ti.to/Mozilla/mozfest-2017" className="button"><span>Sign Up</span></a>
        </div>
        <Footer/>
      </div>
    );
  }
});

module.exports = Home;

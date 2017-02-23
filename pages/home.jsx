var React = require('react');
var Header = require('../components/header.jsx');
var Footer = require('../components/footer.jsx');
var Jumbotron = require('../components/jumbotron.jsx');
var Router = require('react-router');
var Link = Router.Link;
var ImageTag = require('../components/imagetag.jsx');
var SpeakersPromo = require('../components/speakers-promo.jsx');
var generateHelmet = require('../lib/helmet.jsx');


var speakersInfo = [
  {
    name: `Zeynep Tufekci`,
    twitter: `@zeynep`,
    pic: `/assets/images/speakers/Tufekci.jpg`,
    link: `https://air.mozilla.org/mozfest-speaker-series-our-digital-lives/#@25s`
  },
  {
    name: `Brian Behlendorf`,
    twitter: `@brianbehlendorf`,
    pic: `/assets/images/speakers/Behlendorf.jpg`,
    link: `https://air.mozilla.org/mozfest-speaker-series-our-digital-lives/#@28m32s`
  },
  {
    name: `Maggie Vail`,
    twitter: `@magicbeans`,
    pic: `/assets/images/speakers/Vail.jpg`,
    link: `https://air.mozilla.org/mozfest-speaker-series-our-digital-lives/#@50m43s`
  },
  {
    name: `Mark Surman`,
    twitter: `@msurman`,
    pic: `/assets/images/speakers/Surman.jpg`,
    link: `https://air.mozilla.org/mozfest-speaker-series-surveillance-and-privacy-online/#@1m4s`
  },
  {
    name: `Sahar Aziz`,
    twitter: `@saharazizlaw`,
    pic: `/assets/images/speakers/Aziz.jpg`,
    link: `https://air.mozilla.org/mozfest-speaker-series-surveillance-and-privacy-online/#@24m54s`
  },
  {
    name: `Katherine Maher`,
    twitter: `@krmaher`,
    pic: `/assets/images/speakers/Maher.jpg`,
    link: `https://air.mozilla.org/mozfest-speaker-series-privacy-and-harassment-on-the-internet/#@43s`
  },
  {
    name: `Chris Soghoian`,
    twitter: `@csoghoian`,
    pic: `/assets/images/speakers/Soghoian.jpg`,
    link: `https://air.mozilla.org/mozfest-speaker-series-privacy-and-harassment-on-the-internet/#@19m15s`
  },
  {
    name: `Ashe Dryden`,
    twitter: `@ashedryden`,
    pic: `/assets/images/speakers/Dryden.jpg`,
    link: `https://air.mozilla.org/mozfest-speaker-series-privacy-and-harassment-on-the-internet/#@41m14s`
  },
  {
    name: `Volker Birk`,
    pic: `/assets/images/speakers/Birk.jpg`,
    link: `https://air.mozilla.org/mozfest-speaker-series-building-a-secure-and-equal-web/#@1s`
  },
  {
    name: `Katharina Borchert`,
    twitter: `@lyssaslounge`,
    pic: `/assets/images/speakers/Borchert.jpg`,
    link: `https://air.mozilla.org/mozfest-speaker-series-building-a-secure-and-equal-web/#@14m59s`
  },
  {
    name: `Simone Browne`,
    pic: `/assets/images/speakers/Browne.jpg`,
    link: `https://air.mozilla.org/mozfest-speaker-series-inclusion-and-secrets-online/#@`
  },
  {
    name: `Eliot Higgins`,
    twitter: `@EliotHiggins`,
    pic: `/assets/images/speakers/Higgins.jpg`,
    link: `https://air.mozilla.org/mozfest-speaker-series-inclusion-and-secrets-online/#@17m27s`
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
        <Jumbotron className="home-jumbotron" image="/assets/images/hero/home/home2-cropped.jpg"
                  image2x="/assets/images/hero/home/home2-cropped.jpg"
                  hideBackgroundLines={true}>
          <div className="content-wrapper">
            <h1>MozFest</h1>
            <h2>The world's leading festival for the open internet movement.</h2>
            <div className="horizontal-rule"></div>
            <p>Watch videos from MozFest 2016</p>
            <a href="https://air.mozilla.org/mozfest-2016/" className="btn p-3 m-3">Opening Keynote</a>
            <a href="https://air.mozilla.org/search/?ss=20" className="btn p-3 m-3">Speaker Series</a>
          </div>
        </Jumbotron>
        <div className="white-background">
          <div className="centered content wide">
            <h1>Speaker Series Presenters</h1>
            <SpeakersPromo speakersInfo={speakersInfo} />
          </div>
        </div>
        <div className="centered content wide">
          <h1>Missed MozFest this year?</h1>
          <p>Sign up for MozFest updates and be first in line to get your tickets for 2017.</p>
          <a href="https://ti.to/Mozilla/mozfest-2017" className="btn btn-arrow"><span>Sign Up</span></a>
        </div>
        <Footer/>
      </div>
    );
  }
});

module.exports = Home;

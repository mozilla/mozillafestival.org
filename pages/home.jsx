import React from 'react';
import Jumbotron from '../components/jumbotron.jsx';
import { Link } from 'react-router-dom';
import ImageTag from '../components/imagetag.jsx';
import SpeakersPromo from '../components/speakers-promo.jsx';
import speakersInfo from '../speakers/2017';
import talksInfo from '../talks/2017';

import Header from '../components/header.jsx';
import generateHelmet from '../lib/helmet.jsx';

var Home = React.createClass({
  pageMetaDescription: "Three days of building a truly global web together. Come with an idea, leave with a community.",
  render: function () {
    var self = this;
    return (
      <div className="home-page">
        {generateHelmet(this.pageMetaDescription)}
        <Header />
        <Jumbotron className="home-jumbotron" image="/assets/images/hero/home/banner-home_5.jpg"
                  image2x="/assets/images/hero/home/banner-home_5.jpg">
          <div className="content-wrapper">
            <h1>MozFest 2018</h1>
            <p className="mb-0">The world's leading festival for the open Internet movement.</p>
            <p className="mb-0">October 26-28, 2018</p>
            <p className="mb-0">Ravensbourne College, London</p>
            <a href="https://ti.to/mozilla/mozilla-festival-2018" className="btn p-3 m-3">Get Updates</a>
          </div>
        </Jumbotron>
        <div className="white-background">
          <div className="content wide centered">
            <h1>What is MozFest like?</h1>
            <div className="embed-responsive embed-responsive-32by15">
              <iframe src="https://player.vimeo.com/video/258268373?color=ffffff&title=0&byline=0&portrait=0" className="embed-responsive-item" frameBorder="0" allowFullScreen></iframe>
            </div>
            <p>
              MozFest is for advocates of a healthy Internet. Explore the intersecton of the web with civil society, journalism, public policy, and art through interactive sessions.
            </p>
            <Link to="/expect" className="btn btn-arrow">
              <span>What to Expect</span>
            </Link>
          </div>
        </div>
        <div>
          <div className="centered content wide">
            <h1>Dialogues and Debates</h1>
            <SpeakersPromo speakersInfo={speakersInfo} talksInfo={talksInfo}/>
          </div>
        </div>
        {/*<div className="news content wide">
          <h1 className="text-center">Latest News</h1>
          <div className="row mt-5">
            <div className="col-12 col-md-6 text-left">
              <div className="news-widget mb-3">
                <img src="assets/images/MaggieVail.jpg" alt="Brian Behlendorf speaking on stage at MozFest" className="mb-2" style={{ "maxWidth": "100%" }} />
                <div className="source text-muted">Mozilla Festival</div>
                <a href="https://medium.com/mozilla-festival/indie-music-and-the-web-fbd0dfa1fef" className="title">Indie Music and the Web</a>
                <div className="publish-date text-muted">Feb 10, 2017</div>
                <p className="mt-2">Maggie Vail is Executive Director at CASH Music. She spoke at MozFest 2016 about empowering musicians on the web.</p>
              </div>
            </div>
            <div className="col-12 col-md-6">
              <div className="news-widget">
                <div className="source text-muted">Mozilla</div>
                <a href="https://internethealthreport.org/v01/" className="title">Internet Health Report</a>
              </div>
              <div className="news-widget my-4">
                <div className="source text-muted">El País</div>
                <a href="http://tecnologia.elpais.com/tecnologia/2016/10/31/actualidad/1477905484_625075.html" className="title">Mozilla planta cara a los ‘trolls’</a>
                <div className="author">by José Mendiola Zuriarrain</div>
                <div className="publish-date text-muted">Nov 3, 2016</div>
              </div>
              <div className="news-widget">
                <div className="source text-muted">CBC</div>
                <a href="http://www.cbc.ca/radio/spark/internet-health-as-a-social-issue-1.4053202" className="title">Internet health as a social issue</a>
                <div className="author">by Nora Young</div>
                <div className="publish-date text-muted">April 9, 2017</div>
              </div>
            </div>
          </div>
        </div>*/}
        <div className="white-background">
          <div className="centered content wide">
          <h1>Join Us at MozFest</h1>
          <p>Sign up for MozFest updates and be first in line to get your tickets for 2018.</p>
          <a href="https://ti.to/mozilla/mozilla-festival-2018" className="btn btn-arrow"><span>Sign Up</span></a>
          </div>
        </div>
      </div>
    );
  }
});

module.exports = Home;

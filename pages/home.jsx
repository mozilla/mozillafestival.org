import React from 'react';
import Jumbotron from '../components/jumbotron.jsx';
import SpeakersPromo from '../components/speakers-promo.jsx';
import talksInfo from '../talks/2017';

var Home = React.createClass({
  render: function () {
    return (
      <div className="home-page">
        <Jumbotron className="home-jumbotron"
          image=""
          image2x=""
          videoJumbotron={true}>
          <h1>Where Web Meets World</h1>
          <p className="mb-0">A seven day celebration for, by, and about people who love the internet, showcasing world-changing ideas and technology through workshops, talks, and interactive sessions.</p>
        </Jumbotron>
        <div className="white-background shift-up">
          <div className="content wide centered shift-up pt-4">
            <h1>Mozilla Festival (MozFest) 2018, London</h1>
            <p>Festival Weekend, October 26-28, North Greenwich</p>
            <p>MozFest House Events, October 22-26,  Central London</p>
            <hr className="mt-5 mb-4" />
            <SpeakersPromo talksInfo={talksInfo}/>
          </div>
        </div>
        <div className="light-grey-bg">
          <div className="centered content wide pt-4">
            <h1>Join Us at MozFest</h1>
            <p>Be first in line to get your tickets for 2018. Sign up for MozFest updates.</p>
            <a href="https://ti.to/mozilla/mozilla-festival-2018" className="btn btn-arrow"><span>Sign Up</span></a>
          </div>
        </div>
      </div>
    );
  }
});

module.exports = Home;

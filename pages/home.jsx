import React from 'react';
import classNames from 'classnames';
import Jumbotron from '../components/jumbotron.jsx';
import SpeakersPromo from '../components/speakers-promo.jsx';
import talksInfo from '../talks/2017';

class Home extends React.Component {
  constructor(props) {
    super(props);

    this.state = {
      videoTakeover: false
    };
  }

  handlePlayVideoClick() {
    this.setState({
      videoTakeover: !this.state.videoTakeover
    }, () => {
      document.body.classList.toggle(`no-scroll`, this.state.videoTakeover);
    });
  }

  renderVideoTakeover() {
    if (!this.state.videoTakeover) return null;

    return <div className="video-screen-takeover">
      <div className="d-flex flex-column justify-content-center align-items-center h-100">
        <div>
          <button className="close mb-4" onClick={() => this.handlePlayVideoClick()}></button>
        </div>
        <iframe src="https://player.vimeo.com/video/258268373?color=ffffff&title=0&byline=0&portrait=0" width="100%" frameBorder="0" allowFullScreen className="mozfest-vimeo"></iframe>
      </div>
    </div>;
  }

  render() {
    return (
      <div className={classNames({"has-video-takeover": this.state.videoTakeover}, `home-page`)}>
        <Jumbotron className="home-jumbotron"
          image=""
          image2x=""
          videoJumbotron={true}
          toggleVideoTakeover={() => this.handlePlayVideoClick()}
        >
          <h1 className="highlight">Where Web Meets World</h1>
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
            <p>Get your ticket for MozFest weekend.</p>
            <a href="https://ti.to/mozilla/mozilla-festival-2018" className="btn btn-arrow"><span>Buy Tickets</span></a>
          </div>
        </div>
        { this.renderVideoTakeover() }
      </div>
    );
  }
}

export default Home;

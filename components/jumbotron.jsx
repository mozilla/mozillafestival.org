import React from 'react';
import classNames from 'classnames';
import NavBar from '../components/nav-bar.jsx';
import PropTypes from 'prop-types';

class Jumbotron extends React.Component {
  constructor(props) {
    super(props);

    var image = props.image;
    if (this.calculateDensity() === 2) {
      image = props.image2x || props.image;
    }

    this.state = {
      image: image
    };
  }

  calculateDensity() {
    let ratio = 1;

    if (typeof window !== 'undefined' && window.devicePixelRatio > 1.5) {
      ratio = 2;
    }

    return ratio;
  }

  renderVideoJumbotron() {
    return <div className="bg-video-wrapper">
      <div className="video-overlay"></div>
      <video autoPlay loop muted preload="auto" poster="" width="100%">
        <source src="/assets/video/mozilla-festival_home-page-video-cut.mp4" type="video/mp4" />
        <p>Your browser doesn't support video</p>
      </video>
    </div>;
  }

  render() {
    // backgroundLines are line patterns to layer on hero banner image,
    // one at bottom left and one at top right.
    // Ordering in this array matters as CSS rules are set correspondingly
    var backgroundLines = [
      `/assets/images/hero/lines-left.png`,
      `/assets/images/hero/lines-right.png`,
    ];
    var backgroundImages = [ this.state.image ];

    // Layers line patterns on top of hero banner image unless
    // this.props.hideBackgroundLines is set to true
    if ( !this.props.hideBackgroundLines ) {
      backgroundImages = backgroundLines.concat(backgroundImages);
    }

    backgroundImages = backgroundImages.map(imageUrl => {
      return `url(` + imageUrl + `)`;
    }).join(`,`);

    let content = <div className="jumbotron-content d-flex flex-column justify-content-center text-center">
      { this.props.children }
      { this.props.videoJumbotron && <div>
        <button className="btn btn-link btn-video-play">

        </button>
      </div>}
    </div>;

    return (
      <div className={classNames({"has-video-bg": this.props.videoJumbotron},`jumbotron-container`)}>
        { this.props.videoJumbotron && this.renderVideoJumbotron() }
        <div className="jumbotron d-flex flex-column" style={{ backgroundImage: backgroundImages }}>
          <NavBar />
          { content }
        </div>
      </div>
    );
  }
}

Jumbotron.propTypes = {
  imgage: PropTypes.string
};

export default Jumbotron;

var React = require('react');

var Jumbotron = React.createClass({
  calculateDensity: function () {
    var
      ratio;

    if (typeof window !== 'undefined' && window.devicePixelRatio > 1.5) {
      ratio = 2;
    } else {
      ratio = 1;
    }

    return ratio;
  },

  getInitialState: function () {
    var image = this.props.image;
    if (this.calculateDensity() === 2) {
      image = this.props.image2x || this.props.image;
    }
    return {
      image: image
    };
  },

  propTypes: {
    'image': React.PropTypes.string.isRequired
  },

  render: function() {
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

    return (
      <div className="jumbotron-container">
        <div className="jumbotron m-0" style={{
          backgroundImage: backgroundImages
        }}>
          {this.props.children}
        </div>
      </div>
    );
  }
});

module.exports = Jumbotron;

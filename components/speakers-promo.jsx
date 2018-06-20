var React = require('react');
var SpeakerTinyCard = require('../components/speaker-tiny-card.jsx');

import { Link } from 'react-router-dom';

const FEATURED_SPEAKERS = [
  {
    name: `Zeynep Tufekci`,
    shortIntro: `professor and author`,
    talkName: `The Algorithmic Spiral of Silence`,
    link: `https://www.youtube.com/watch?v=pO-brBVRyN8&list=PLYiaJo7rYNXLQSEAa2RdyyiS28Ke2Rl60&index=12`,
    pic: `/assets/images/speakers/2016/Tufekci.jpg`
  },
  {
    name: `Ashley Black`,
    shortIntro: `comedienne`,
    talkName: `A Pep Talk for Nerds`,
    link: `https://www.youtube.com/watch?v=MFOdGSKSWDY&t=0s&list=PLYiaJo7rYNXInD71OAShY4xfl6fhskoUV&index=4`,
    pic: `/assets/images/speakers/2017/AshleyBlack.jpeg`
  },
  {
    name: `Gisela Pérez de Acha`,
    shortIntro: `lawyer and activist`,
    talkName: `How to Hack an Earthquake`,
    link: `https://www.youtube.com/watch?v=atYKpi_74Ak&t=0s&list=PLYiaJo7rYNXInD71OAShY4xfl6fhskoUV&index=2`,
    pic: `/assets/images/speakers/2017/GiselaPerezdeAcha.jpeg`
  },
  {
    name: `Audrey Tang`,
    shortIntro: `Taiwan’s digital minister`,
    talkName: `Stories from the Future of Democracy`,
    link: `https://www.youtube.com/watch?v=mzjHu2izFn8&t=0s&list=PLYiaJo7rYNXInD71OAShY4xfl6fhskoUV&index=9`,
    pic: `/assets/images/speakers/2017/AudreyTang.jpeg`
  },
  {
    name: `Eliot Higgins`,
    shortIntro: `Bellingcat founder`,
    talkName: `Open Source and Flight MH17`,
    link: `https://www.youtube.com/watch?v=iFx6eJF94Ew&index=2&list=PLYiaJo7rYNXLQSEAa2RdyyiS28Ke2Rl60`,
    pic: `/assets/images/speakers/2016/Higgins.jpg`
  },
  {
    name: `Julia Angwin`,
    shortIntro: `investigative journalist`,
    talkName: `What Algorithms Taught Me About Forgiveness`,
    link: `https://www.youtube.com/watch?v=Yei7SS8nEqw&t=0s&list=PLYiaJo7rYNXInD71OAShY4xfl6fhskoUV&index=12`,
    pic: `/assets/images/speakers/2017/JuliaAngwin.jpeg`
  },
  {
    name: `Emily May`,
    shortIntro: `Hollaback! co-founder`,
    talkName: `Being Human in the Dark Days of the Internet`,
    link: `https://www.youtube.com/watch?v=zzJ0KAvpdVw&t=0s&list=PLYiaJo7rYNXInD71OAShY4xfl6fhskoUV&index=8`,
    pic: `/assets/images/speakers/2017/EmilyMay.jpeg`
  },
  {
    name: `Matt Mitchell`,
    shortIntro: `Cryptoharlem founder`,
    talkName: `It Takes a Hacker to Catch a Hacker`,
    link: `https://www.youtube.com/watch?v=_mMhDA4z4BA&t=0s&list=PLYiaJo7rYNXInD71OAShY4xfl6fhskoUV&index=10`,
    pic: `/assets/images/speakers/2017/MattMitchell.jpeg`
  }
];

var SpeakersPromo = React.createClass({
  propTypes: {
    talksInfo: React.PropTypes.array.isRequired
  },
  render: function() {
    return (
      <div className="speakers-promo">
        <div className="row">
          {
            FEATURED_SPEAKERS.map(speaker => {
              return <div className="col-md-4" key={speaker.name}>
                <SpeakerTinyCard {...speaker} />
              </div>;
            })
          }
        </div>
        <div className="row">
          <div className="col text-center">
            <Link to="/speakers" className="btn btn-arrow">
              <span>Watch All Previous Dialogues & Debates</span>
            </Link>
          </div>
        </div>
      </div>
    );
  }
});

module.exports = SpeakersPromo;

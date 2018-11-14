var React = require('react');
var SpeakerTinyCard = require('../components/speaker-tiny-card.jsx');

const FEATURED_SPEAKERS = [
  {
    name: `Amba Kak`,
    shortIntro: ``,
    twitter: `@ambaonadventure`,
    talkName: `Talk: Data and Digital Rights in Asia`,
    link: ``,
    pic: `/assets/images/speakers/2018/Amba_Kak.jpg`
  },
  {
    name: `Zara Rahman`,
    shortIntro: ``,
    twitter: `@zararah`,
    talkName: `Panel: The Data Balancing Act`,
    link: ``,
    pic: `/assets/images/speakers/2018/Zara_Rahman.jpg`
  },
  {
    name: `Malavika Jayaram`,
    shortIntro: ``,
    twitter: `@maljayaram`,
    talkName: `Panel: The Data Balancing Act`,
    link: ``,
    pic: `/assets/images/speakers/2018/Malavika_Jayaram.jpg`
  },
  {
    name: `Renee DiResta`,
    shortIntro: ``,
    twitter: `@noUpside`,
    talkName: `Talk: Flaws in the Data-driven Digital Economy`,
    link: ``,
    pic: `/assets/images/speakers/2018/Renee_DiResta.jpg`
  },
  {
    name: `Tim Berners-Lee`,
    shortIntro: ``,
    twitter: `@timberners_lee`,
    talkName: `Talk`,
    link: ``,
    pic: `/assets/images/speakers/2018/Tim_Berners-Lee.jpg`
  },
  {
    name: `Julie Owono`,
    shortIntro: ``,
    twitter: `@JulieOwono`,
    talkName: `Talk: Privacy in Sub-Saharan Africa`,
    link: ``,
    pic: `/assets/images/speakers/2018/Julie_Owono.jpg`
  },
  {
    name: `Esra’a Al-Shafei`,
    shortIntro: ``,
    twitter: `@ealshafei`,
    talkName: `Panel: Data in Oppressive Regimes`,
    link: ``,
    pic: `/assets/images/speakers/2018/Esra'a_Al_Shafei.png`
  },
  {
    name: `Mahsa Alimardani`,
    shortIntro: ``,
    twitter: `@maasalan`,
    talkName: `Panel: Data in Oppressive Regimes`,
    link: ``,
    pic: `/assets/images/speakers/2018/Mahsa_Alimardani.jpg`
  },
  {
    name: `Camille Francois`,
    shortIntro: ``,
    twitter: `@camillefrancois`,
    talkName: `Panel: AI’s Collateral Damage`,
    link: ``,
    pic: `/assets/images/speakers/2018/Camille_Francois.jpg`
  },
  {
    name: `Guillaume Chaslot`,
    shortIntro: ``,
    twitter: `@gchaslot`,
    talkName: `Panel: AI’s Collateral Damage`,
    link: ``,
    pic: `/assets/images/speakers/2018/Guillaume_Chaslot.png`
  },
  {
    name: `Alondra Nelson`,
    shortIntro: ``,
    twitter: `@alondra`,
    talkName: `Panel: AI’s Collateral Damage`,
    link: ``,
    pic: `/assets/images/speakers/2018/Alondra_Nelson.jpg`
  },
  {
    name: `Clinton Watts`,
    shortIntro: ``,
    twitter: `@selectedwisdom`,
    talkName: `Panel: AI’s Collateral Damage`,
    link: ``,
    pic: `/assets/images/speakers/2018/Clinton_Watts.jpg`
  },
  {
    name: `Nathalie Richards`,
    shortIntro: ``,
    twitter: `@nathrich`,
    talkName: `Talk: Tech and Inclusion`,
    link: ``,
    pic: `/assets/images/speakers/2018/Nathalie_Richards.jpg`
  },
  {
    name: `Maryant Fernandez`,
    shortIntro: ``,
    twitter: `@maryantfp`,
    talkName: `Panel: Who Controls the Internet?`,
    link: ``,
    pic: `/assets/images/speakers/2018/Maryant_Fernandez.jpg`
  },
  {
    name: `Chris Riley`,
    shortIntro: ``,
    twitter: `@MChrisRiley`,
    talkName: `Panel: Who Controls the Internet?`,
    link: ``,
    pic: `/assets/images/speakers/2018/Chris_Riley.jpg`
  },
  {
    name: `Soudeh Rad`,
    shortIntro: ``,
    twitter: `@soudehrad`,
    talkName: `Talk: Privacy, Identity, and Gender Online`,
    link: ``,
    pic: `/assets/images/speakers/2018/Soudeh_Rad.jpg`
  },
  {
    name: `Mitchell Baker`,
    shortIntro: ``,
    twitter: `@MitchellBaker`,
    talkName: `Talk`,
    link: ``,
    pic: `/assets/images/speakers/2018/Mitchell_Baker.jpg`
  }
];

var SpeakersPromo = React.createClass({
  render: function() {
    return (
      <div className="speakers-promo">
        <div className="row mb-5">
          <div className="col">
            <h1>Dialogues & Debates</h1>
            <p>Each year, MozFest features talks from luminaries at the intersection of technology and society. We host hackers, journalists, activists, and others on our stage. Here are just a few:</p>
          </div>
        </div>
        <div className="row">
          {
            FEATURED_SPEAKERS.map(speaker => {
              return <div className="col-md-3" key={speaker.name}>
                <SpeakerTinyCard {...speaker} />
              </div>;
            })
          }
        </div>
      </div>
    );
  }
});

module.exports = SpeakersPromo;

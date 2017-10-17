var React = require('react');
var classNames = require(`classnames`);

const SPONSORS_INFO = {
  "Supporting": [
    {
      name: `Flattr`,
      logo: `/assets/images/team/sponsor/Flattr.png`
    }
  ],
  "Festival Lunch": [
    {
      name: `eLife`,
      logo: `/assets/images/team/sponsor/eLife.png`
    }
  ],
  "Science Fair": [
    {
      name: `Fetch`,
      logo: `/assets/images/team/sponsor/Fetch.png`
    }
  ],
  "Saturday Night": [
    {
      name: `WeTransfer`,
      logo: `/assets/images/team/sponsor/WeTransfer.png`
    }
  ],
  "MozFest Scholarship": [
    {
      name: `GitHub`,
      logo: `/assets/images/team/sponsor/GitHub.png`
    }
  ],
  "In-Kind": [
    {
      name: `Sticker Mule`,
      logo: `/assets/images/team/sponsor/StickerMule.png`
    },
    {
      name: `Micro:Bit Foundation`,
      logo: `/assets/images/team/sponsor/MicroBitFoundation.png`
    }
  ]
};

var Sponsors = React.createClass({
  renderSponsor: function(type = ``, sponsor, classnames = ``) {
    classnames = classNames(classnames, `align-self-center logo d-flex align-items-center`);
    return <div className={classnames} key={sponsor.name}>
      <img src={sponsor.logo} alt={sponsor.name} />
    </div>;
  },
  renderSponsorOfAType: function(type) {
    var sponsors = SPONSORS_INFO[type];
    sponsors = sponsors.map((sponsor, i) => {
      return this.renderSponsor(type, sponsor, `col-12 col-sm-${12/sponsors.length}`);
    });

    return <div className={`col-${sponsors.length*4} mt-3 mb-5`}>
      <div className="row mb-2">
        <div className="col-12"><h2 className="mb-2">{`${type} Partner${sponsors.length > 1 ? `s` : ``}`}</h2></div>
      </div>
      <div className="row">
        { sponsors }
      </div>
    </div>
  },
  render: function() {
    return <div>
      <h1>Our 2017 Sponsors</h1>
      <div className="horizontal-rule mb-5"></div>
      <div>
        <p>MozFest is the world’s leading event for and by the open Internet movement, and one of Mozilla’s largest annual networking opportunities. This three-day festival of interactive sessions, hands-on activities, and engaging talks brings together 1,800 passionate advocates of the open web from around the world for the flagship event in Mozilla’s leadership network.</p>
        <p>
          For 2017, Mozilla is offering new sponsorship opportunities for supporters to help us deliver MozFest in London. The festival is growing at a phenomenal rate, as are the opportunities to collaborate, build, and convene with this dynamic network.
        </p>
        <p>If you would like to sponsor MozFest 2017, please reach out to us at <a href="mailto:festival@mozilla.org">festival@mozilla.org</a>. A festival team member will share with you the various options for support and next steps. We look forward to hearing from you.</p>
        <div className="row">
          { this.renderSponsorOfAType(`Supporting`) }
        </div>
        <div className="row">
          { this.renderSponsorOfAType(`Festival Lunch`) }
          { this.renderSponsorOfAType(`Science Fair`) }
          { this.renderSponsorOfAType(`Saturday Night`) }
        </div>
        <div className="row">
          { this.renderSponsorOfAType(`MozFest Scholarship`) }
        </div>
        <div className="row">
          { this.renderSponsorOfAType(`In-Kind`) }
        </div>
      </div>
    </div>;
  }
});

module.exports = Sponsors;


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
      <h1>MozFest Sponsors</h1>
      <div className="horizontal-rule mb-5"></div>
      <div>
        <p>
          MozFest is the world’s leading event for and by the open Internet movement, and one of Mozilla’s largest annual networking opportunities. This weeklong festival of interactive sessions, hands-on activities, and engaging talks brings together more than 2,500 passionate advocates of the open web from around the world.
        </p>
        <p>
          In 2018, Mozilla is offering opportunities for mission-aligned companies to showcase their commitment to a healthy Internet with our 2,500+ guests and the millions of engaged supporters online. By partnering with us, you can:
        </p>
        <ul>
          <li>
            Engage Mozilla audiences with your brand in an authentic way providing real insights on your products and reach new customers
          </li>
          <li>
            Align your message with Mozilla to help showcase your support for protecting the Internet as a global public resource
          </li>
          <li>
            Help shape smart policy that is good for innovation and good for the Internet
          </li>
          <li>
            Provide your employees and clients with ways to teach, learn and get involved in key issues affecting the Internet today
          </li>
        </ul>
        <p>
          Together, we can protect the Internet as a resource that will help grow your business, spark your next great idea, and deliver you the best tools and resources to get the job done.
        </p>
        <p>
          To learn more, please contact us at <a href="mailto:sponsor@mozilla.org">sponsor@mozilla.org</a>
        </p>
      </div>
      <h3 className="mt-5">Our 2017 Sponsors</h3>
      <div>
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


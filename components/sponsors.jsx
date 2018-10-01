var React = require('react');
var classNames = require(`classnames`);

const SPONSORS_INFO = {
  "Closing Party": [
    {
      name: `Sticker Mule`,
      logo: `/assets/images/team/sponsor/2018/StickerMule.svg`,
      logoClass: `sticker-mule`,
      link: `https://mule.to/mozfest-2018`
    }
  ],
  "Presenting Festival": [
    {
      name: `Private Internet Access`,
      logo: `/assets/images/team/sponsor/2018/PrivateInternetAccess.png`,
      logoClass: `private-internet-access`,
      link: `https://www.privateinternetaccess.com/`
    }
  ],
  "Science Fair": [
    {
      name: `Internet Society`,
      logo: `/assets/images/team/sponsor/2018/InternetSociety.png`,
      logoClass: `internet-society`,
      link: `https://www.internetsociety.org/`
    }
  ],
  "Youth Zone": [
    {
      name: `Samsung`,
      logo: `/assets/images/team/sponsor/2018/Samsung.jpg`,
      logoClass: `samsung`,
      link: `https://www.samsung.com/uk/apps/samsung-internet/`
    }
  ]
};

var Sponsors = React.createClass({
  renderSponsor: function(sponsor, classnames = ``) {
    classnames = classNames(classnames, `align-self-center logo d-flex align-items-center`);

    return <a className={classnames} key={sponsor.name} href={sponsor.link} target="_blank">
      <img src={sponsor.logo} alt={sponsor.name} className={sponsor.logoClass ? sponsor.logoClass : ``} />
    </a>;
  },
  renderSponsorOfAType: function(type) {
    var sponsors = SPONSORS_INFO[type].map((sponsor) => {
      return this.renderSponsor(sponsor, `col-12`);
    });

    return <div className="col-12 mt-3 mb-5">
      <div className="row mb-2">
        <div className="col-12"><h2 className="mb-2">{`${type} Partner${sponsors.length > 1 ? `s` : ``}`}</h2></div>
      </div>
      <div className="row">
        { sponsors }
      </div>
    </div>;
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
        <div className="d-flex flex-column flex-md-row">
          <ul className="d-inline-block w-md-50 mr-sm-3">
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
          <div className="d-inline-block w-md-50">
            <img src="/assets/images/20171027-mozfest-52.jpg" className="img-fluid" />
          </div>
        </div>
        <p>
          Together, we can protect the Internet as a resource that will help grow your business, spark your next great idea, and deliver you the best tools and resources to get the job done.
        </p>
        <p>
          To learn more, please contact us at <a href="mailto:sponsor@mozilla.org">sponsor@mozilla.org</a>
        </p>
      </div>
      <h3 className="mt-5">Our 2018 Sponsors</h3>
      <div>
        <div className="row">
          { this.renderSponsorOfAType(`Presenting Festival`) }
        </div>
        <div className="row">
          { this.renderSponsorOfAType(`Closing Party`) }
        </div>
        <div className="row">
          { this.renderSponsorOfAType(`Science Fair`) }
        </div>
        <div className="row">
          { this.renderSponsorOfAType(`Youth Zone`) }
        </div>
      </div>
    </div>;
  }
});

module.exports = Sponsors;


import React from 'react';

const PARTNERS_LIST = [
  {
    name: `Aletheia`,
    logo: `/assets/images/team/partner/partner_aletheia_grayscale.png`,
    link: `https://github.com/aletheia-foundation/aletheia-admin`
  },
  {
    name: `Causa y Efecto`,
    logo: `/assets/images/team/partner/partner_causa-y-efecto_grayscale.png`,
    link: `https://www.causayefecto.com.co/`
  },
  {
    name: `Code Is Science`,
    logo: `/assets/images/team/partner/partner_code-is-science_grayscale.png`,
    link: `http://www.codeisscience.com/`
  },
  {
    name: `Universidade Federal de Alagoas`,
    logo: `/assets/images/team/partner/partner_universidade-federal-de-alagoas_grayscale.png`,
    link: ``
  },
  {
    name: `Media Party`,
    logo: `/assets/images/team/partner/parnter_media-party-2016_grayscale.png`,
    link: ``
  },
  {
    name: `Upstart Projects`,
    logo: `/assets/images/team/partner/partner_upstart-project_grayscale.png`,
    link: `https://upstartprojects.uk`
  },
  {
    name: `Ada, National College for Digital Skills`,
    logo: `/assets/images/team/partner/partner_ada_grayscale.png`,
    link: `https://ada.ac.uk/`
  },
  {
    name: `VnA`,
    logo: `/assets/images/team/partner/partner_v_a_grayscale.png`,
    link: `https://www.vam.ac.uk/`
  },
  {
    name: `Pichoun`,
    logo: `/assets/images/team/partner/partner_pichoun_grayscale.png`,
    link: `http://www.pichoun.fr`
  },
  {
    name: `UAL`,
    logo: `/assets/images/team/partner/partner_ual copy_grayscale.png`,
    link: ``
  },
  {
    name: `Ravensbourne`,
    logo: `/assets/images/team/partner/Ravensbourne.jpg`,
    link: `https://www.ravensbourne.ac.uk`
  },
  {
    name: `BBC`,
    logo: `/assets/images/team/partner/BBC.jpg`,
    link: `https://www.bbc.co.uk/rd`
  }
];

class Partners extends React.Component {
  renderPartnerLogos() {
    let sortAlphabetically = (a, b) => {
      if (a.name < b.name)
        return -1;
      if (a.name > b.name)
        return 1;
      return 0;
    };

    return PARTNERS_LIST.slice().sort(sortAlphabetically).map(partner => {
      let logo = partner.logo ? <img src={partner.logo} alt={partner.name} className="img-fluid" /> : partner.name;

      if (partner.link) {
        logo = <a href={partner.link} className="d-flex justify-content-center align-items-center h-100">{logo}</a>;
      }

      return <div className="col-12 col-sm-4 mb-3" key={partner.name}>
        <div className="partner d-flex justify-content-center align-items-center">{logo}</div>
      </div>;
    });
  }

  render() {
    return <div className="row">{this.renderPartnerLogos()}</div>;
  }
}

export default Partners;

var React = require('react');
var Link = require('react-router').Link;
var Header = require('../components/header.jsx');
var Footer = require('../components/footer.jsx');
var HeroUnit = require('../components/hero-unit.jsx');
var ImageTag = require('../components/imagetag.jsx');

var MembersInfo = [
  {
    name: "Sarah Allen",
    imgSrc: "/assets/images/team/SarahAllen.jpg",
    title: "Festival Producer",
    bio: <p>Sarah Allen is this year’s festival producer. When she is not focused on MozFest, she helps with other Mozilla convenings in London and beyond. Sarah draws her experience from a diverse events background having worked with creative and inspiring companies, such as Movember Europe, Secret Cinema and CSM iLUKA. MozFest 2016 will be Sarah’s fourth festival.</p>
  },
  {
    name: "Erika Drushka",
    imgSrc: "/assets/images/team/ErikaDrushka.jpg",
    title: "Program Designer",
    bio: <p>Erika Drushka is the Program Designer for MozFest 2016. With a background in documentary film making, advocacy and fundraising, Erika has spent the last three years on the MozFest communications team. This year, Erika has shifted her focus at Mozilla to building network strength in the open internet movement through convenings.</p>
  },
  {
    name: "Spike",
    imgSrc: "/assets/images/team/Spike.jpg",
    title: "Volunteer Coordinator",
    bio: <p>Chris Foote (known as “Spike”) is the Volunteer Coordinator for MozFest 2016. A life-long professional Electronic Engineer with a passion for science and technology, he is a volunteer with global organisations such as CrisisCommons, CrisisMappers, OpenStreetMap (OSM), Humanitarian OSM Team (HOT), StandbyTaskForce (SBTF). He has co-organised a very successful London-based monthly Meetup group for people involved in or interested in ICT4D (Information and Communication Technologies for Development) as well as organising countless BarCamps and Hackathons. This is Spike's sixth MozFest, and his fourth as Volunteer Coordinator.</p>
  },
  {
    name: "Allen “Gunner” Gunn",
    imgSrc: "/assets/images/team/AllenGunn.jpg",
    title: "Co-designer and Emcee",
    bio: <p><a href="https://aspirationtech.org/about/people/gunner">Gunner</a> is Executive Director of <a href="https://aspirationtech.org/">Aspiration</a> in San Francisco, USA, and works to help NGOs, activists, foundations and software developers make more effective use of technology for social change. He is an experienced facilitator with a passion for designing collaborative open learning processes.</p>
  },
];

var MemberProfile = React.createClass({
  render: function() {
    return (
      <div className={"member-profile " + this.props.className}>
        <div className="image-container">
          <ImageTag src1x={this.props.imgSrc} alt={"photo of " + this.props.name} />
        </div>
        <div className="details">
          <h1>{this.props.name}</h1>
          <h2>{this.props.title}</h2>
          <div>{this.props.bio}</div>
        </div>
      </div>
    );
  }
})

var TeamPage = React.createClass({
  members: MembersInfo,
  componentWillMount: function() {
    this.members = this.members.map(function(member,i) {
      var whiteBg = (i%2==0) ? "white-background" : "";
      return (
        <MemberProfile {...member} className={whiteBg} key={member.name} />
      );
    });
  },
  render: function() {
    return (
      <div className="team-page">
        <Header/>
        <HeroUnit image="/assets/images/team.png"
                  image2x="/assets/images/team.png">
          Team
        </HeroUnit>
        <div className="white-background">
          <div className="content centered wide">
            <h1>Meet the MozFest Team</h1>
            <div className="horizontal-rule"></div>
          </div>
          <div className="content wide">
            <div className="member-profiles">
              {this.members}
            </div>
          </div>
        </div>
        <Footer/>
      </div>
    );
  }
});

module.exports = TeamPage;


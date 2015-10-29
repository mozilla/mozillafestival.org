var React = require('react');
var Link = require('react-router').Link;
var Header = require('../components/header.jsx');
var Footer = require('../components/footer.jsx');
var HeroUnit = require('../components/hero-unit.jsx');
var ImageTag = require('../components/imagetag.jsx');

var MembersInfo = [
  {
    name: "Michelle Thorne",
    imgSrc: "/assets/images/team/MichelleThorne.jpg",
    title: "Festival Director",
    bio: <p><a href="http://michellethorne.cc/">Michelle Thorne</a> is Mozilla’s Director for Web Literacy programs, aiming to grow communities and best practices to teach web literacy. Through <a href="https://teach.mozilla.org/events/">participatory events</a> and professional development, she serves volunteer mentors around the world to teach their communities how to read, write and participate on the web. Michelle curates Mozfest and is proud to see the festival into its sixth year.</p>
  },
  {
    name: "Allen “Gunner” Gunn",
    imgSrc: "/assets/images/team/AllenGunn.jpg",
    title: "Co-designer and Emcee",
    bio: <p><a href="https://aspirationtech.org/about/people/gunner">Gunner</a> is Executive Director of <a href="https://aspirationtech.org/">Aspiration</a> in San Francisco, USA, and works to help NGOs, activists, foundations and software developers make more effective use of technology for social change. He is an experienced facilitator with a passion for designing collaborative open learning processes.</p>
  },
  {
    name: "Misty Avila",
    imgSrc: "/assets/images/team/MistyAvila.jpg",
    title: "Festival Curator",
    bio: <p>Misty Avila is Director of Programs at Aspiration, an NGO based in San Francisco, California that supports community-based organizations to use technology effectively to achieve their social change efforts. She oversees Aspiration's program and communications activities and has experience training diverse audiences from urban and rural areas around the globe. With her background in both social justice groundwork and online advocacy, she is passionate about bringing people together and honored to be part of the Mozfest community.</p>
  },
  {
    name: "Sarah Allen",
    imgSrc: "/assets/images/team/SarahAllen.jpg",
    title: "Mozfest Producer",
    bio: <p>Sarah Allen is an event manager at Mozilla London and this year’s Mozfest Producer. When she is not focused on Mozfest she is helping out with the <a href="https://teach.mozilla.org/events/">Maker Party</a> campaign and other Webmaker events. Sarah draws her experience from a diverse events background having worked with creative and inspiring companies, such as Movember Europe, Secret Cinema and CSM iLUKA. Mozfest 2015 will be Sarah’s fourth festival.</p>
  },
  {
    name: "Spike",
    imgSrc: "/assets/images/team/Spike.jpg",
    title: "Volunteer Coordinator",
    bio: <p>Chris Foote (always known as “Spike”) is the Volunteer Coordinator for MozFest 2015. A life-long professional Electronic Engineer with a passion for science and technology, he is a volunteer with global organisations such as CrisisCommons, CrisisMappers, OpenStreetMap (OSM), Humanitarian OSM Team (HOT), StandbyTaskForce (SBTF). He has co-organised a very successful London-based monthly Meetup group for people involved in or interested in ICT4D (Information and Communication Technologies for Development) as well as organising countless BarCamps and Hackathons. This is Spike's fifth MozFest, and his third as Volunteer Coordinator.</p>
  },
  {
    name: "Marc Walsh",
    imgSrc: "/assets/images/team/MarcWalsh.jpg",
    title: "Festival Production Assistant",
    bio: <p><a href="http://words.marcwalsh.co.uk/">Marc Walsh</a> is a Production Assistant at the Mozilla Foundation. His experience is divided between events and television, having previously worked on Young Rewired State’s Festival of Code, and IBC in Holland to work on National Television Awards and the Great British Bake Off. This is Marc’s fifth MozFest having previously been a volunteer. Even though it’s been awhile, he's going to miss jumping into the fox suit.</p>
  }
];

var MemberProfile = React.createClass({
  render: function() {
    return (
      <div className="member-profile">
        <div className="image-container">
          <ImageTag src1x={this.props.imgSrc} alt={"photo of " + this.props.name} width={200} />
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
        <div className={whiteBg} key={member.name}>
          <div className="content wide">
            <MemberProfile {...member} />
          </div>
        </div>
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
        </div>
        {this.members}
        <Footer/>
      </div>
    );
  }
});

module.exports = TeamPage;


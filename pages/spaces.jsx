import React from 'react';
import { Link } from 'react-router-dom';
import Jumbotron from '../components/jumbotron.jsx';

var SpaceProfile = require('../components/space-profile.jsx');
var SPACE_WRANGLERS = require('../team-bio/space-wranglers');
var EXPERIENCES_WRANGLERS = require('../team-bio/experiences-wranglers');
var SpacesInfo = [
  {
    name: "Decentralisation",
    description:
    (<div>
      <p>Can the world be decentralised?</p>
      <p>In this parallel dimension, people self-organise into open groups that create art, write code, and even build cities. Their technology runs on consensus and their society is fuelled by data. But data is not just a resource — it’s an extension of individual identity and collective culture. People give informed consent to data gathering and enjoy transparency of use.</p>
      <p>Journey to a new world and bring back powerful, resilient technology; explore radical, paradigm-shifting ideas; and take part in cutting-edge discourse. Explore protocols like DAT, IPFS and ActivityPub, alongside ideas such as net neutrality and proof of stake. Experience decentralised platforms like Matrix and Mastodon, and support the equal commons of all.</p>
      <p>Let’s discover this wonderland, together.</p>
    </div>),
    contacts: SPACE_WRANGLERS.Decentralization
  },
  {
    name: "Digital Inclusion",
    description:
    (<div>
      <p>For some of us, the web is a space for collective creativity, invention, and expression. We use it to study, conduct science, find jobs, make money, eat, grow food, have sex, and more. Imagine the web in 50 years, and the amazing things we'll do with the technology to come.</p>
      <p>Now imagine that you are left out of this future.</p>
      <p>When we exclude people and communities, we prevent web content and technology from growing and changing in new, surprising, and inventive ways. It's our job now to ensure we all have access, and we all get the best, most vibrant, and dynamic web possible.</p>
      <p>In the Digital Inclusion space, we will discover the forces — of history, of society, of technology — that keep people off the web and prevent the web from evolving. We'll witness how new, unheard voices and ideas can transform the web. Together, we'll explore, invent, and share technology, design, and activism to bring more people than ever online.</p>
    </div>),
    contacts: SPACE_WRANGLERS[`Digital Inclusion`]
  },
  {
    name: "Openness",
    // name: "Open Innovation",
    description:
    (<div>
      <p>Openness on the web extends to many disciplines, from software and hardware to education, research, journalism, and politics.</p>
      <p>Working open allows us to share, collaborate on, promote, revise, and refine our work. But being open in a safe, effective way isn’t always straightforward. In an era of personal data misuse and breaches, we need to think carefully about how open we are, with whom, and for what purposes.</p>
      <p>Openness on the web impacts everyone, so this space welcomes sessions and participants from different disciplines, experience levels, and backgrounds. Join us to discuss, share experiences, and learn how to work open. Come with an open mind!</p>
    </div>),
    contacts: SPACE_WRANGLERS.Openness
  },
  {
    name: "Privacy and Security",
    description:
    (<div>
      <p>Do you want to feel more safe online? You’re not alone: privacy and security have never been more top-of-mind for everyday Internet users. Due to recent scandals involving the collection, use, and theft of sensitive information, individuals around the world now understand that the data trail they leave online could be anything but private.</p>
      <p>As a result, the conversation around privacy and security has shifted. Now that Internet users understand the risks, how can we empower them to take control of their own data? What tools are available to citizens wanting to protect their own security, and how can we make them approachable, engaging, and fun? What mechanisms can we use to demand that companies and governments respect our privacy? How might we work together to envision a future where having a private life and an online life are not mutually exclusive?</p>
      <p>Participants in this year’s Privacy & Security space will leave feeling informed, empowered, and ready to lead themselves and others to secure their personal data.</p>
    </div>),
    contacts: SPACE_WRANGLERS[`Privacy and Security`],
  },
  {
    name: "Web Literacy",
    description:
    (<div>
      <p>Do you remember what a computer looked like in the 1950s? Can you recall what a floppy disk looked like? From those first machines to today’s super-fast computers, we've come a long way. The same is true for the Internet. The Internet is no longer a luxury — it's a necessity, like clean water or shelter. Today, with the entire world coming online, it's more important than ever for users to understand the power and limitations of the web.</p>
      <p>In the Web Literacy space, we'll help you understand and harness the power of today’s Internet. Whether you're a new user just starting to use the web or are a power user who's always online, you'll learn something new in this space. We'll help you understand the importance of controlling your data, and how it relates to your online experience.</p>
      <p>When you come to the Web Literacy space, bring your web experiences along with you — good or bad. This year’s Web Literacy space is a place for sharing, discussing, and learning the superpowers of the Internet.</p>
    </div>),
    contacts: SPACE_WRANGLERS[`Web Literacy`]
  },
  {
    name: "Youth Zone",
    description:
    (<div>
      <p>The future of the Internet is in our hands.</p>
      <p>Young people and those who support them are excited to build a healthier Internet that empowers us to solve problems and achieve our dreams. We see a future that defies expectations and promises more inclusion, agency, and shared ownership online. By learning and playing with all kinds of potential solutions in this space, we young people and our allies will share what we think of the Internet and work to make it better.</p>
      <p>Join us for a weekend of hands-on experiences that immerse participants in young people's ideas about what the Internet can be. Participants will bring home new creations, new understandings of Internet health, and ways to stay connected via youth-led conversations and projects.</p>
    </div>),
    contacts: SPACE_WRANGLERS[`Youth Zone`]
  }
];

var ExpereincesInfo = [
  {
    name: "Queering MozFest",
    description:
    (<div>
      <p>Oh deer! This year, we are opening a queer territory at MozFest.</p>
      <p>Across the main six spaces, we are exploring how Internet issues intersect with our gender and our sexuality. Drawing on a queer perspective, we will reflect on the relationships between technology and the processes of normalisation and marginalisation.</p>
      <p>Queering MozFest is staging interventions that invite queer-identified and -allied attendees to craft safe spaces for other queer attendees to convene, reflect, and relax. Fostering thought-provoking experiences throughout the festival, this queer experiment aims at stimulating our imagination while resisting normalisation.</p>
    </div>),
    contacts: EXPERIENCES_WRANGLERS[`Queering MozFest`]
  }
];

var SessionsPage = React.createClass({
  renderInfoSection(info) {
    return info.map((space,i) => {
      var classname = (i%2===0) ? "white-background" : "";
      return (
        <SpaceProfile {...space}
          contactTitle="wrangler"
          className={classname}
          key={space.name}
        />
      );
    });
  },
  render: function() {
    return (
      <div className="spaces-page">
        <Jumbotron image="/assets/images/hero/sessions.jpg"
          image2x="/assets/images/hero/sessions.jpg">
          <h1>Spaces & Experiences</h1>
        </Jumbotron>
        <div className="white-background">
          <div className="content centered wide">
            <h1>2018 Spaces</h1>
            <p>Spaces are physical and thematic learning hubs based around a broad topic, like web literacy or digital inclusion. A Space is made up of sessions, which are hands-on, educational gatherings based around a specific topic, like “Using Crafts to Teach Localization Processes.” Sessions generally run 30-90 minutes.</p>
            <div><Link to="/proposals" className="btn btn-arrow"><span>Propose a Session</span></Link></div>
            <div className="horizontal-rule"></div>
          </div>
        </div>
        { this.renderInfoSection(SpacesInfo) }

        <div className="white-background">
          <div className="content centered wide">
            <h1 className="mt-5">2018 Experiences</h1>
            <p>Experiences are artworks, exhibits, activities and interactions that bridge Spaces by weaving together many of the themes present at the festival.</p>
            <div className="horizontal-rule"></div>
          </div>
        </div>
        { this.renderInfoSection(ExpereincesInfo) }
      </div>
    );
  }
});

module.exports = SessionsPage;

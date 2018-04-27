var React = require('react');
var Link = require('react-router').Link;
var Jumbotron = require('../components/jumbotron.jsx');
var SpaceProfile = require('../components/space-profile.jsx');
var SPACE_WRANGLERS = require('../team-bio/space-wranglers');
var EXPERIENCES_WRANGLERS = require('../team-bio/experiences-wranglers');
var SpacesInfo = [
  {
    name: "Decentralization",
    description:
    (<div>
      <p>The year is 2027: Who owns the Internet?</p>
      <p>In the dystopian version of 2027, the Internet is owned by a powerful few. Big tech corporations, select media companies and closed governments control the content on the Internet, the data that flows across the Internet and how people connect to the Internet. This dystopian future is closer than you may think.</p>
      <p>On the flip side, what is the utopian version of the Internet in 2027? What future do we want to build? Where do emerging technologies like AI, mesh networking and Blockchain fit in? How do we ensure people are the most important part of the Internet?</p>
      <p>Join us at Mozfest as we look into the future. Dystopian, utopian or somewhere in between &mdash; let’s explore the Internet of 2027.</p>
    </div>),
    contacts: SPACE_WRANGLERS.Decentralization
  },
  {
    name: "Digital Inclusion",
    description:
    (<div>
      <p>Imagine an Internet that truly puts people first &mdash; where individuals can shape their own experience and are empowered, safe and independent. </p>
      <p>The Digital Inclusion space explores ways of increasing equity of access and participation across the web. We’re addressing restrictive infrastructure and finding ways to overcome the cost of technology; celebrating linguistic diversity and sharing ways to make the Internet more representative of the planet’s many cultures; and developing solutions to make all people safer and empowered in their digital lives. As you move through this space, you will see how an inclusive web truly benefits humanity.</p>
    </div>),
    contacts: SPACE_WRANGLERS[`Digital Inclusion`]
  },
  {
    name: "Open Innovation",
    description:
    (<div>
      <p>An open web provides the opportunity to publish and invent online without permission. To keep it this way, the technologies used to run the web should remain transparent and understandable.</p>
      <p>Open Innovation is a space for challenging and learning about open source, open projects and growth on the web. We’ll investigate the challenges to open production (misinformation, draconian copyright) and the values of open creativity (working open techniques, tools and tactical transparency). The space is programmed as a highly-interactive platform for producing and protecting the principles of the open web. Join us in evolving our epically-open online world.</p>
    </div>),
    contacts: SPACE_WRANGLERS[`Open Innovation`]
  },
  {
    name: "Privacy and Security",
    description:
    (<div>
      <p>Do you feel safe online?</p>
      <p>Sometimes, online security can feel unattainable. State-sponsored surveillance, theft of personal information, trolls arrogantly abusing others—this has become normal. And every day, many of us trade personal information for convenience.</p>
      <p>It’s time to flip the script. Not everyone knows how to install a VPN or use encryption—but everyone should feel safe online. Can we imagine a future where manufacturers respect our safety by default when they build products? Where governments take steps to protect us? Can we find fun, new ways to engage our friends and neighbours around these issues?</p>
      <p>At Mozfest, we’ll build, teach and play with these new approaches to Privacy & Security. Let’s put safety first.</p>
    </div>),
    contacts: SPACE_WRANGLERS[`Privacy and Security`],
  },
  {
    name: "Web Literacy",
    description:
    (<div>
      <p>The web is like a garden that we all plant and eat from. We need to consider what we sow as well as what, and how much, we consume.</p>
      <p>In the Web Literacy space, we’ll dig into the skills that help us read, write and participate on the web in a way that enriches both it and ourselves. Web literacy isn’t just writing Python—it’s knowing how to wield a search engine, how to craft a strong password and when our online activities are being tracked.</p>
      <p>Through hands-on workshops, participants will explore, make and play with the tools and technologies that form the fabric of the web. And through discussion and talks, we’ll invite our guests to reflect on, and improve, their relationship with the web. Our space will instill the skills and confidence in both young and old, students and teachers, to make the web a more vibrant, diverse ecosystem.</p>
    </div>),
    contacts: SPACE_WRANGLERS[`Web Literacy`]
  },
  {
    name: "Youth Zone",
    description:
    (<div>
      <p>
        For our collective sake<br/>
        Let your passion create a story<br/>
        Let your passion design the day<br/>
        Let that passion fight the cause<br/>
        Let our voice be heard
      </p>
      <p>The Youth Zone is like a poem &mdash; creative, unexpected, powerful. Join this space for youth leaders and their mentors who are creating art, technology and positive social change. From a Raspberry Pi-coded singing potato to our teen artists-in-residence to our fireside chat on Internet health, come explore our collective tomorrow.</p>
    </div>),
    contacts: SPACE_WRANGLERS[`Youth Zone`]
  }
];

var ExpereincesInfo = [
  {
    name: "Artist Open Studio",
    description:
    (<div>
      <p>Upholding a healthy internet requires diverse voices and approaches&mdash;like art and design.
      </p>
      <p>Art and design play key roles in engaging new audiences around issues like privacy and inclusion, and in shaping critical thinking around a healthy web. To highlight these artistic perspectives, MozFest&mdash;in collaboration with Digital Learning at V&A and Tate&mdash;has invited artists to address each of the five thematic issue areas of the festival through an installation and on-site lab:</p>
      <ul>
        <li>An Xiao Mina (Web Literacy)</li>
        <li>Paolo Cirio (Privacy and Security)</li>
        <li>Archana Prasad (Decentralization)</li>
        <li>Gretta Louw (Open Innovation)</li>
        <li>Brooklyn Pakathi (Digital Inclusion)</li>
      </ul>
      <p>Participants will engage with the artists in an open lab setting. Artists will share their practices, and also develop collaborative work with the public.</p>
    </div>),
    contacts: EXPERIENCES_WRANGLERS[`Artist Open Studio`]
  },
  {
    name: "Wayfinding and Recognition",
    description:
    (<div>
      <p>Recognise your participation at Mozfest and understand how your contribution makes a difference to the future of the open web. You’ll have the chance to use an augmented reality app to explore and expose different pathways throughout the festival, unlock hidden experiences and swag! We want to recognise your participation at Mozfest, your contribution to the future of the open web and provide you with a record of your experience and the skills you’ve learned along the way that you can share online. After the festival we’ll also direct you to organisations where you can continue your adventure.</p>
    </div>),
    contacts: EXPERIENCES_WRANGLERS[`Wayfinding and Recognition`]
  },
  {
    name: "Dialogues and Debates",
    description:
    (<div>
      <p> On MozFest's Dialogues & Debates stage, luminaries at the intersection of technology and society discuss bright spots and threats to Internet health. Each speaker delivers a captivating 20-minute, TED-style talk. Our 2017 speakers are activists, journalists, ethical hackers and comedians hailing from eight countries on four continents.</p>
    </div>),
    contacts: EXPERIENCES_WRANGLERS[`Dialogues and Debates`]
  },
  {
    name: "Theme Wrangling",
    description:
    (<div>
      <p>MozFest 2017 will feature a set of thematic sessions distributed across each of the Spaces. These sessions will explore Policy & Advocacy, Journalism, and Youth Leadership.</p>
    </div>),
    contacts: EXPERIENCES_WRANGLERS.Theme
  }
];

var SessionsPage = React.createClass({
  renderInfoSection(info) {
    return info.map((space,i) => {
      var whiteBg = (i%2==0) ? "white-background" : "";
      return (
        <div className={whiteBg} key={space.name}>
          <div className="content wide">
            <SpaceProfile {...space} contactTitle="wrangler" />
          </div>
        </div>
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
            <h1>2017 Spaces</h1>
            <p>Spaces are physical and thematic learning hubs based around a broad topic, like web literacy or digital inclusion. A Space is made up of sessions, which are hands-on, educational gatherings based around a specific topic, like “Using Crafts to Teach Localization Processes.” Sessions generally run 30-90 minutes.</p>
            <div className="horizontal-rule"></div>
          </div>
        </div>
        { this.renderInfoSection(SpacesInfo) }

        <div className="white-background">
          <div className="content centered wide">
            <h1 className="mt-5">2017 Experiences</h1>
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

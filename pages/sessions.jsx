var React = require('react');
var Header = require('../components/header.jsx');
var Footer = require('../components/footer.jsx');
var HeroUnit = require('../components/hero-unit.jsx');
var ImageTag = require('../components/imagetag.jsx');

var SpacesInfo = [
  {
    iconUrl: "/assets/images/space-icon/Journalism.png",
    icon2xUrl: "/assets/images/space-icon/Journalism@2x.png",
    iconAlt: "Journalism icon",
    name: "Journalism",
    description: 
      "<p>Journalism has an indelible impact on the Web. Code developed in newsrooms — like Django and Backbone — shape our experiences online; reporting amuses us, enrages us and leads to meaningful change in our communities; and millions of readers engage with online coverage daily.</p>" +
      "<p>In the Journalism Space, we explore how journalism carried out both in and out of newsrooms can strengthen the open Web and improve people’s lives. Participants will learn how closely linked journalism and the Web truly are, and how they can contribute to the fourth estate online.</p>",
    wranglers: [
      {
        name: "Erika Owens",
        twitter: "@erika_owens"
      },
      {
        name: "Ryan Pitts",
        twitter: "@ryanpitts"
      }
    ]
  },
  {
    iconUrl: "/assets/images/space-icon/Science.png",
    icon2xUrl: "/assets/images/space-icon/Science@2x.png",
    iconAlt: "Science icon",
    name: "Science",
    description:
      "<p>Science and the Web both help us understand the world around us. In the Science Space, participants learn, tinker, explore and hack at the intersection of science and the Internet, and learn how the Web can advance scientific work.</p>" +
      "<p>We cover topics like citizen science, open data, open mapping and open research, all the while learning news skills and tools. We also collaborate on each other’s projects and empower one another: our Open Research Accelerator provides one-on-one mentorship.</p>" +
      "<p>The Science Space is open to everyone: we invite hobbyists, researchers, librarians, data scientists, developers and all others to join us.</p>",
    wranglers: [
      {
        name: "Arliss Collins",
        twitter: "@arlissc99"
      },
      {
        name: "Abby Cabunoc Mayes",
        twitter: "@abbycabs"
      }
    ]
  },
  {
    iconUrl: "/assets/images/space-icon/DigitalCitizenship.png",
    icon2xUrl: "/assets/images/space-icon/DigitalCitizenship@2x.png",
    iconAlt: "Digital Citizenship icon",
    name: "Digital Citizenship",
    description:
      "<p>We believe everyone should be able to read, write and participate on the Web. The Digital Citizenship Space is all about unpacking how we achieve this.</p>" +
      "<p>Limitations to digital citizenship are many: access, lack of privacy, marginalization, and socio-cultural beliefs that bleed into online life. Join a collection of policy experts, legal practitioners, activists and technologists from around the globe eager to solve these problems and increase online participation. Together we’ll discuss campaigns, tools and other avenues that can make a difference.</p>",
    wranglers: [
      {
        name: "Melissa Romaine",
        twitter: "@melechuga"
      },
      {
        name: "Stacy Martin"
      },
      {
        name: "Shane Caraveo",
        twitter: "@mixedpuppy"
      },
      {
        name: "Sara Haghdoosti",
        twitter: "@sarahaghdoosti"
      }
    ]
  },
  {
    iconUrl: "/assets/images/space-icon/MozillaLearningNetworks.png",
    icon2xUrl: "/assets/images/space-icon/MozillaLearningNetworks@2x.png",
    iconAlt: "Mozilla Learning Networks icon",
    name: "Mozilla Learning Networks",
    description:
      "<p>The key to empowering individuals online and cementing the Web as a public resource is web literacy. In the Mozilla Learning Networks Space, we explore how to best teach and spread web literacy.</p>" +
      "<p>We will convene and empower leaders from diverse backgrounds who are eager to teach the Web in their communities. Help us achieve the spread, scale and adoption of web literacy networks and programs around the globe.</p>",
    wranglers: [
      {
        name: "Lindsey Frost",
        twitter: "@lindsey_in_cha"
      },
      {
        name: "Sam Dyson",
        twitter: "@samueledyson"
      }
    ]
  },
  {
    iconUrl: "/assets/images/space-icon/GlobalVillages.png",
    icon2xUrl: "/assets/images/space-icon/GlobalVillages@2x.png",
    iconAlt: "Global Villages icon",
    name: "Global Villages",
    description:
      "<p>What is the Global Village? A collection of self-contained but interconnected places from around the world where participants at Mozfest meet, learn and tinker with tomorrow’s places. The Global Village cultivates leading practitioners to build, teach and advocate for an Internet of things that empowers its users.</p>" +
      "<p>Turn off your screen. Close your book. End that meeting. Pick up a sketchpad, a pair of scissors, a hot-glue gun, some parcel tape and come cry “If We Build It They Will Come.” This is a springboard for tomorrow and welcoming place for those inclusive citizens and communities.</p>",
    wranglers: [
      {
        name: "Jon Rogers"
      },
      {
        name: "Ian Forrester"
      },
      {
        name: "Jasmine Cox"
      }
    ]
  },
  {
    iconUrl: "/assets/images/space-icon/Participation.png",
    icon2xUrl: "/assets/images/space-icon/Participation@2x.png",
    iconAlt: "Participation icon",
    name: "Participation",
    description:
      "<p>Participation is at the heart of Mozilla’s approach to learning. But participation doesn’t just happen — it’s built through great design and great leadership.</p>" +
      "<p>The Participation Space will be a mixture of workshops, hack sessions, open challenges and product tinkering aimed at building participation and developing leaders who can inspire others. Participants will cover personal leadership exploration, skill development and community building.</p>",
    wranglers: [
      {
        name: "George Roter",
        twitter: "@geroter"
      },
      {
        name: "Emma Irwin",
      }
    ]
  },
  {
    iconUrl: "/assets/images/space-icon/YouthZone.png",
    icon2xUrl: "/assets/images/space-icon/YouthZone@2x.png",
    iconAlt: "Youth Zone icon",
    name: "Youth Zone",
    description:
      "<p>There’s sometimes a fundamental clash between youth and adults on the Web. Young people discover platforms and activities that are important and meaningful to them — but adults disagree, wishing youngsters would invest their time elsewhere. In the Youth Zone Space, we’ll explore and aim to reconcile this conflict.</p>" +
      "<p>Young people and adults will work in tandem across three workshops and 30 sessions. Together, we’ll explore new technology and how it’s relevant to all participants. We’ll also build things together, and learn about fulfilling careers based in new media.</p>",
    wranglers: [
      {
        name: "Dorine Flies",
        twitter: "@dorineflies"
      },
      {
        name: "Harry Smith"
      }
    ]
  },
  {
    iconUrl: "/assets/images/space-icon/Localization.png",
    icon2xUrl: "/assets/images/space-icon/Localization@2x.png",
    iconAlt: "Localization icon",
    name: "Localization",
    description:
      "<p>All languages and cultures are uniquely different and valuable — and they should be treated this way on the Web, too. In order for the Web to empower everyone, it must be adapted to diverse communities and peoples. The next billion people to come online shouldn’t find an English-only Web.</p>" +
      "<p>In the Localization Space, participants learn language localization skills and nuances, how to include diversity in products, and how to bring this philosophy back to their local communities.</p>",
    wranglers: [
      {
        name: "Heather Bailey"
      },
      {
        name: "Dwayne Bailey"
      }
    ]
  }
];

var Space = React.createClass({
  render: function() {
    return (
      <div className="space-card">
        <div className="detail">
          <div className="header">
            <div className="img-container">
              <ImageTag src1x={this.props.iconUrl}
                        src2x={this.props.icon2xUrl}
                        alt={this.props.iconAlt}/>
            </div>
            <h1>{this.props.name}</h1>
          </div>
          <div className="description" dangerouslySetInnerHTML={{__html: this.props.description}}></div>
        </div>
        <div className="wranglers">
          <h2>Wranglers:</h2>
          <ul>
          {
            this.props.wranglers.map(function(wrangler,i) {
              return (
                <li key={i}>
                  <div className="name">{wrangler.name}</div>
                  { wrangler.twitter ? <div className="twitter"><a href={"https://twitter.com/"+wrangler.twitter}>{wrangler.twitter}</a></div> : null }
                </li>
              )
            })
          }
          </ul>
        </div>
      </div>
    );
  }
});

var SessionsPage = React.createClass({
  spaces: SpacesInfo,
  componentWillMount: function() {
    this.spaces = this.spaces.map(function(space,i) {
      var whiteBg = (i%2==0) ? "white-background" : "";
      return (
        <div className={whiteBg} key={i}>
          <div className="content wide">
            <Space {...space} />
          </div>
        </div>
      );
    });
  },
  render: function() {
    return (
      <div className="sessions-page">
        <Header/>
        <HeroUnit image="/assets/images/spaces.jpg"
                  image2x="/assets/images/spaces.jpg">
          Sessions
        </HeroUnit>
        <div className="white-background">
          <div className="content centered wide">
            <h1>Spaces and Pathways</h1>
            <p>To help you get the most out of MozFest, we’ve introduced two new concepts to the program: Spaces and Pathways. Spaces are physical hubs, typically located on a single floor, with sessions exploring a shared theme. Pathways are a series of three or more complementary sessions and experiences that may cross multiple Spaces. Each Pathway has been tailored for participants to learn a particular set of skills. Participants are encouraged to make use of the recommended Pathways or design their own unique MozFest experience.</p>
            <div className="horizontal-rule"></div>
          </div>
        </div>
        <div className="white-background">
          <div className="content centered wide no-vertical-margin">
            <h1>Sessions</h1>
          </div>
        </div>
        {this.spaces}
        <Footer/>
      </div>
    );
  }
});

module.exports = SessionsPage;


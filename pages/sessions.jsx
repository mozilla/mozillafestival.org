var React = require('react');
var Link = require('react-router').Link;
var Header = require('../components/header.jsx');
var Footer = require('../components/footer.jsx');
var HeroUnit = require('../components/hero-unit.jsx');
var SpacePathwayProfile = require('../components/space-pathway-profile.jsx');
var ImageTag = require('../components/imagetag.jsx');

var SpacesInfo = [
  {
    name: "Journalism",
    description: [ 
      "Journalism has an indelible impact on the Web. Code developed in newsrooms — like Django and Backbone — shape our experiences online; reporting amuses us, enrages us and leads to meaningful change in our communities; and millions of readers engage with online coverage daily.",
      "In the Journalism Space, we explore how journalism carried out both in and out of newsrooms can strengthen the open Web and improve people’s lives. Participants will learn how closely linked journalism and the Web truly are, and how they can contribute to the fourth estate online.",
    ],
    contacts: [
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
    name: "Science",
    description: [
      "Science and the Web both help us understand the world around us. In the Science Space, participants learn, tinker, explore and hack at the intersection of science and the Internet, and learn how the Web can advance scientific work.",
      "We cover topics like citizen science, open data, open mapping and open research, all the while learning news skills and tools. We also collaborate on each other’s projects and empower one another: our Open Research Accelerator provides one-on-one mentorship.",
      "The Science Space is open to everyone: we invite hobbyists, researchers, librarians, data scientists, developers and all others to join us.",
    ],
    contacts: [
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
    name: "Digital Citizenship",
    description: [
      "We believe everyone should be able to read, write and participate on the Web. The Digital Citizenship Space is all about unpacking how we achieve this.",
      "Limitations to digital citizenship are many: access, lack of privacy, marginalization, and socio-cultural beliefs that bleed into online life. Join a collection of policy experts, legal practitioners, activists and technologists from around the globe eager to solve these problems and increase online participation. Together we’ll discuss campaigns, tools and other avenues that can make a difference.",
    ],
    contacts: [
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
    name: "Mozilla Learning Networks",
    description: [
      "The key to empowering individuals online and cementing the Web as a public resource is web literacy. In the Mozilla Learning Networks Space, we explore how to best teach and spread web literacy.",
      "We will convene and empower leaders from diverse backgrounds who are eager to teach the Web in their communities. Help us achieve the spread, scale and adoption of web literacy networks and programs around the globe.",
    ],
    contacts: [
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
    name: "Global Villages",
    description: [
      "What is the Global Village? A collection of self-contained but interconnected places from around the world where participants at Mozfest meet, learn and tinker with tomorrow’s places. The Global Village cultivates leading practitioners to build, teach and advocate for an Internet of things that empowers its users.",
      "Turn off your screen. Close your book. End that meeting. Pick up a sketchpad, a pair of scissors, a hot-glue gun, some parcel tape and come cry “If We Build It They Will Come.” This is a springboard for tomorrow and welcoming place for those inclusive citizens and communities.",
    ],
    contacts: [
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
    name: "Participation",
    description: [
      "Participation is at the heart of Mozilla’s approach to learning. But participation doesn’t just happen — it’s built through great design and great leadership.",
      "The Participation Space will be a mixture of workshops, hack sessions, open challenges and product tinkering aimed at building participation and developing leaders who can inspire others. Participants will cover personal leadership exploration, skill development and community building.",
    ],
    contacts: [
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
    name: "Youth Zone",
    description: [
      "There’s sometimes a fundamental clash between youth and adults on the Web. Young people discover platforms and activities that are important and meaningful to them — but adults disagree, wishing youngsters would invest their time elsewhere. In the Youth Zone Space, we’ll explore and aim to reconcile this conflict.",
      "Young people and adults will work in tandem across three workshops and 30 sessions. Together, we’ll explore new technology and how it’s relevant to all participants. We’ll also build things together, and learn about fulfilling careers based in new media.",
    ],
    contacts: [
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
    name: "Localization",
    description: [
      "All languages and cultures are uniquely different and valuable — and they should be treated this way on the Web, too. In order for the Web to empower everyone, it must be adapted to diverse communities and peoples. The next billion people to come online shouldn’t find an English-only Web.",
      "In the Localization Space, participants learn language localization skills and nuances, how to include diversity in products, and how to bring this philosophy back to their local communities.",
    ],
    contacts: [
      {
        name: "Heather Bailey"
      },
      {
        name: "Dwayne Bailey"
      }
    ]
  }
];

var SessionsPage = React.createClass({
  spaces: SpacesInfo,
  componentWillMount: function() {
    this.spaces = this.spaces.map(function(space,i) {
      var whiteBg = (i%2==0) ? "white-background" : "";
      return (
        <div className={whiteBg} key={space.name}>
          <div className="content wide">
            <SpacePathwayProfile {...space} contactTitle="wrangler" />
          </div>
        </div>
      );
    });
  },
  render: function() {
    return (
      <div className="sessions-page">
        <Header/>
        <HeroUnit image="/assets/images/sessions.jpg"
                  image2x="/assets/images/sessions.jpg">
          Sessions
        </HeroUnit>
        <div className="white-background">
          <div className="content centered wide">
            <h1>Spaces and Pathways</h1>
            <p>To help you get the most out of MozFest, we’ve introduced two new concepts to the program: Spaces and <Link to="pathways">Pathways</Link>. Spaces are physical hubs, typically located on a single floor, with sessions exploring a shared theme. Pathways are a series of three or more complementary sessions and experiences that may cross multiple Spaces.</p>
            <div className="horizontal-rule"></div>
          </div>
        </div>
        <div className="white-background">
          <div className="content centered wide no-vertical-margin">
            <h1>Spaces</h1>
          </div>
        </div>
        {this.spaces}
        <Footer/>
      </div>
    );
  }
});

module.exports = SessionsPage;


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
    iconPath: "/assets/images/space-icons/Journalism.svg",
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
    iconPath: "/assets/images/space-icons/Science.svg",
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
        twitter: "@abbycabs",
        bio: {
          photoSrc: "/assets/images/bio/AbigailCabunocMayes.jpg",
          bio: <p>Abigail Cabunoc Mayes is the lead developer for Mozilla Science Lab, which is a community of researchers, tool developers, librarians, and publishers working to leverage the open web in research. Prior to joining Mozilla, Abby worked as a bioinformatics software developer at the Ontario Institute for Cancer Research where she was the technical lead on the WormBase website.</p>
        }
      }
    ]
  },
  {
    name: "Digital Citizenship",
    iconPath: "/assets/images/space-icons/Digital_Citizenship.svg",
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
        name: "Stacy Martin",
        bio: {
          photoSrc: "/assets/images/bio/StacyMartin.png",
          bio: <p>Stacy Martin is part of the Policy Team at Mozilla. As a Senior Manager of Privacy and Engagement, Stacy focuses on building privacy community and education / awareness materials. She has created several teaching kits on topics such as privacy, passwords, and DRM. Stacy also organizes Mozilla’s Privacy Lab, team teaches New Hire Privacy Training and leads Mozilla’s Privacy Day planning. She has been with Mozilla for 4 years. Her latest passion is building a Global Advocacy and Teaching Task Force for privacy and other key Internet issues. If you’d like to get involved with privacy issues or advocacy task forces, she invites you to come talk to her!</p>
        }
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
    iconPath: "/assets/images/space-icons/Mozilla_Learning_Networks.svg",
    description: [
      "Leaders demonstrate what the Web can do. The Mozilla Learning Network Space at MozFest will convene and catalyze leaders who equip others to advance their lives on the Web.",
      "Our leadership Space and Pathways are part of a broad effort to cultivate a global network of web literacy leaders, people of all stripes and ages who are teaching others to read, write and participate on the Web while still actively learning themselves.",
    ],
    contacts: [
      {
        name: "Lindsey Frost",
        twitter: "@lindsey_in_cha",
        bio: {
          photoSrc: "/assets/images/bio/LindseyFrost.jpg",
          bio: <p>Lindsey is the Gigabit City Lead for the MLN team, leading Mozilla's five-city effort to explore how high-speed networks can be leveraged for learning. A native of Chattanooga, Tennessee, Lindsey is passionate about empowering leaders and learners to read, write, and participate on the (super fast!) Web. She's previously worked with the University of Tennessee and the Public Education Foundation and is a founder of DEV DEV, Chattanooga's citywide digital literacy initiative.</p>
        }
      },
      {
        name: "Sam Dyson",
        twitter: "@samueledyson",
        bio: {
          photoSrc: "/assets/images/bio/SamDyson.jpeg",
          bio: <p>Sam is Director of Hive Chicago on the Mozilla Learning Network team. He's exploring the power of human networks to meet the opportunities and challenges inherent in the work of teaching and learning in the digital age. He provides strategic direction and sustainability for Hive Chicago as part of Mozilla's global leadership development and web literacy efforts. Sam has worked elsewhere in education including 10 years teaching high school physics.</p>
        }
      }
    ]
  },
  {
    name: "Global Village",
    iconPath: "/assets/images/space-icons/Global_Village.svg",
    description: [
      "What is the Global Village? A collection of self-contained but interconnected places from around the world where participants at Mozfest meet, learn and tinker with tomorrow’s places. The Global Village cultivates leading practitioners to build, teach and advocate for an Internet of things that empowers its users.",
      "Turn off your screen. Close your book. End that meeting. Pick up a sketchpad, a pair of scissors, a hot-glue gun, some parcel tape and come cry “If We Build It They Will Come.” This is a springboard for tomorrow and welcoming place for those inclusive citizens and communities.",
    ],
    contacts: [
      {
        name: "Jon Rogers"
      },
      {
        name: "Ian Forrester",
        twitter: "@cubicgarden",
        bio: {
          photoSrc: "/assets/images/bio/IanForrester.jpg",
          bio: <p>Ian Forrester is a well known and likeable character on the digital scene in the UK. He has now made Manchester, UK his home, where he works for the BBC's R&D north lab. He focuses on open innovation and new disruptive opportunities via open engagement and collaborations with startups, early adopters and hackers</p>
        }
      },
      {
        name: "Jasmine Cox"
      }
    ]
  },
  {
    name: "Participation",
    iconPath: "/assets/images/space-icons/Participation.svg",
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
        twitter: "@sunnydeveloper"
      }
    ]
  },
  {
    name: "Youth Zone",
    iconPath: "/assets/images/space-icons/Youth_Zone.svg",
    description: [
      "There’s sometimes a fundamental clash between youth and adults on the Web. Young people discover platforms and activities that are important and meaningful to them — but adults disagree, wishing youngsters would invest their time elsewhere. In the Youth Zone Space, we’ll explore and aim to reconcile this conflict.",
      "Young people and adults will work in tandem across three pathways and 30 sessions. Together, we’ll explore new technology and how it’s relevant to all participants. We’ll also build things together, and learn about fulfilling careers based in new media.",
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
    iconPath: "/assets/images/space-icons/Localization.svg",
    description: [
      "All languages and cultures are uniquely different and valuable — and they should be treated this way on the Web, too. In order for the Web to empower everyone, it must be adapted to diverse communities and peoples. The next billion people to come online shouldn’t find an English-only Web.",
      "In the Localization Space, participants learn language localization skills and nuances, how to include diversity in products, and how to bring this philosophy back to their local communities.",
    ],
    contacts: [
      {
        name: "Heather Bailey",
        bio: {
          photoSrc: "/assets/images/bio/HeatherBailey.JPG",
          bio: <p>Heather Bailey works for Translate, working behind the scenes for the last 15 years on localisations projects trying not to let them take over her house and her life. Born and raised in Southern Africa she loves the variety of people and culture in it and will always be a part of her identity. She has Illustrated a number of children’s books makes wedding dresses for fun and mostly loves connecting with people.</p>
        }
      },
      {
        name: "Dwayne Bailey",
        bio: {
          photoSrc: "/assets/images/bio/DwayneBailey.JPG",
          bio: <p>Dwayne Bailey is a proud father to two beautiful girls. His mission in life is to create a world where people can access and create in their own language. His motivation behind starting the Translate (a specialised localisation company) was to remove the language barrier between people and technology. For all the effort we put into getting information on screens, it might as well be miles away from the eyes that can’t read it. So he travels the world passing on knowledge and giving localisers the tools to be able to make the changes they need for their languages. He loves Africa despite potholes and politics and would just like people to be able to get on with it without limitation.</p>
        }
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


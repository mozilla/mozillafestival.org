var React = require('react');
var Header = require('../components/header.jsx');
var Footer = require('../components/footer.jsx');
var HeroUnit = require('../components/hero-unit.jsx');
var SpacePathwayProfile = require('../components/space-pathway-profile.jsx');

var PathwaysInfo = [
  {
    name: "Online Learning Done Face-to-Face",
    type: "Independent Pathway",
    description: [
      "Online learning offers tremendous opportunity, but often lacks a key component — the ability to collaborate with those around us. In this Pathway, we explore “Learning Circles,” or in-person study groups that complement online learning.",
    ],
    contacts: [
      {
        name: "Bekka Kahn",
        twitter: "@rebamex",
        bio: {
          photoSrc: "/assets/images/bio/BekkaKahn.jpg",
          bio: <p>Bekka Kahn works for P2PU and The Open Coalition, and is has been working on open education, open practice and free culture for 10 years. She’s also a recovering librarian, a PhD student at King’s College London, and an avid maker of jam.</p>
        }
      },
      {
        name: "Grif Peterson",
        twitter: "@grifpeterson"
      },
      {
        name: "Mark Anderson"
      },
      {
        name: "Carl Ruppin"
      }
    ]
  },
  {
    name: "Seeing, Drawing, Producing Smileys",
    type: "Independent Pathway",
    description: [
      "It’s visual literacy meets graphic design. In this pathway, participants identify materials and oddities around the MozFest venue that resemble faces. Then, we reproduce the faces on paper and through vector graphics to create digital emoticons.",
    ],
    contacts: [
      {
        name: "Metod Blejec",
        twitter: "@metodb"
      }
    ]
  },
  {
    name: "Mapping Your MozFest Learning Journey",
    type: "Independent Pathway",
    description: [
      "MozFest can seem chaotic; this pathway is here to help. Chart your own unique route for the festival, and mark your learning by earning Open Badges along the way.",
    ],
    contacts: [
      {
        name: "Grainne Hamilton",
        twitter: "@grainnehamilton",
        bio: {
          photoSrc: "/assets/images/bio/GrainneHamilton.jpg",
          bio: <p>Grainne’s varied background includes leading the development of the Jisc Open Badges Toolkit, contributing to the Mozilla Discover project, which involved developing badge-based pathways to employment, as well as founding and facilitating the Open Badges in Scottish Education Group. Most recently, she was Senior Consultant at Blackboard, which involved helping a global range of clients make best use of their teaching and learning systems.</p>
        }
      },
      {
        name: "Matt Rogers",
        twitter: "@mattdigitalme",
        bio: {
          photoSrc: "/assets/images/bio/MattRogers.jpg",
          bio: <p>Matt is passionate about working with young people, particularly when it comes to using and experimenting with existing and emerging technologies. Before working for DigitalMe, Matt was a KS1-KS4 teacher for eight years most recently at a Primary School in South East London, where he also held the roles of CAS Lead Educatorand Mathletics Lead Educator. This experience directly feeds into Matt’s work on Safe, Naace CPD badges and Scout badges at DigitalMe.</p>
        }
      }
    ]
  },
  {
    name: "Storytellers and Coders Unite",
    type: "Independent Pathway",
    description: [
      "This is a pathway for those interested in creating factual interactive stories that can change the world. Join others to create new interfaces and interactive dynamics for stories to feel meaningful on the Web. Brainstorm on real projects and help building a community of professionals that want to work together and create amazing projects!",
    ],
    contacts: [
      {
        name: "Sandra Gaudenzi",
        twitter: "@sgaudenzi",
        bio: {
          photoSrc: "/assets/images/bio/SandraGaudenzi.jpeg",
          bio: <div>
                  <p>Sandra consults, researches, lectures, writes and blogs about interactive factual narratives.</p>
                  <p>She is one of the co-directors of the i-Docs conference (i-docs.org) and the Head of Studies of IF Lab (iflab.net), an EU training initiative for interactive documentary makers. She also hosts a regular IF Meetup in London, with the aim to create a community of professionals that want to work together and make their projects become reality!</p>
                </div>
        }
      }
    ]
  },
  {
    name: "Research",
    type: "Independent Pathway",
    description: [
      "This Pathway touches on research insight, anecdotes and best practices. It includes an exhibition of recent web literacy studies from Chicago, India, Bangladesh, Kenya and Rio; a Webmaker for Android workshop and design session; and a look at tools and tips.",
    ],
    contacts: [
      {
        name: "Laura de Reynal",
        twitter: "@lau_nk"
      }
    ]
  },
  {
    name: "Building Online Communities",
    type: "Journalism Pathway",
    description: [
      "In this Pathway, participants learn to better conceptualize, manage and maintain online communities."
    ]
  },
  {
    name: "Data and Impact",
    type: "Journalism Pathway",
    description: [
      "Learn how data is being harnessed to affect policy in societies with low digital literacy, limited access and/or entrenched corruption."
    ]
  },
  {
    name: "Mobile and Accessibility",
    type: "Journalism Pathway",
    description: [
      "In this Pathway, participants will learn how they can make their journalism and other work more accessible and effective on mobile devices."
    ]
  },
  {
    name: "Games and Understanding",
    type: "Journalism Pathway",
    description: [
      "Explore alternative — and entertaining — ways of imparting information. From games that tell news stories to folk dances that illustrate computer algorithms, this Pathway focuses on fun and interactive engagement."
    ]
  },
  {
    name: "Building Better Events and Networks",
    type: "Journalism Pathway",
    description: [
      "In-person events in the realm of journalism should be safe, inclusive and diverse. This Pathway helps participants hold events that better serve their communities."
    ]
  },
  {
    name: "Tools, Analysis and Storytelling",
    type: "Journalism Pathway",
    description: [
      "This Pathway focuses on open-source tools for mapping, data extraction and data analysis. It also explores how we can draw meaningful stories out of data."
    ]
  },
  {
    name: "R-E-S-P-E-C-T",
    type: "Digital Citizenship Pathway",
    description: [
      "Learn about social exclusion and marginalization online. This Pathway pays close attention to gender equality, human rights abuses, government surveillance and other issues on the Web."
    ]
  },
  {
    name: "Building a Crowd",
    type: "Digital Citizenship Pathway",
    description: [
      "Here, the theme is mobilizing people to protect the open Internet. We’ll look at strategies for growing our advocacy community, and tools that empower people to speak up."
    ]
  },
  {
    name: "Don’t Feed the Trolls",
    type: "Digital Citizenship Pathway",
    description: [
      "Learn how to protect yourself and your family from cyberbullying and online harassment. We’ll also design tools for better protection."
    ]
  },
  {
    name: "Backdoors and Cryptowars",
    type: "Digital Citizenship Pathway",
    description: [
      "Tech tools can protect our freedom of expression. Play with new features in Firefox 42, learn how to teach Tor, and design the privacy tools we want."
    ]
  },
  {
    name: "What’s Your Policy?",
    type: "Digital Citizenship Pathway",
    description: [
      "Learn about Internet policies worldwide, explore Mozilla’s stance on the issues, and uncover how we can best engage communities around the globe."
    ]
  },
  {
    name: "Voices of Diverse Leaders",
    type: "MLN Pathway",
    description: [
      "This Pathway convenes leaders and educators in order to catalyze new relationships, innovate, and create a more inclusive Web. We’ll create leadership opportunities for participants to empower others to read, write and participate online.",
    ],
    contacts: [
      {
        name: "Simona Ramkisson",
        twitter: "@SimonaRamkisson"
      },
      {
        name: "Julia Vallera",
        twitter: "@colorwheelz",
        bio: {
          photoSrc: "/assets/images/bio/JuliaVallera.jpg",
          bio: <p>Julia Vallera (<a href="https://twitter.com/@colorwheelz">@colorwheelz</a>) has been working in the arts as an educator, printmaker, public engagement artist, illustrator, consultant and designer for 16 years. She is a technology enthusiast and collaborates with many people to invent new ways to use it for STEAM (Science, Tech, Engineering, Arts, Math) education. As Network Manager at Mozilla Hive NYC Julia works with more than 70 cultural organizations to create new learning pathways and innovative educational practices for youth, educators, families, artists, technologists and more.</p>
        }
      }
    ]
  },
  {
    name: "Teaching Web Literacy with Mozilla Clubs",
    type: "MLN Pathway",
    description: [
      "Learn how to teach like Mozilla and facilitate successful events or clubs that are open, participatory, collaborative and engaging.",
    ],
    contacts: [
      {
        name: "Amira Dhalla",
        twitter: "@amirad"
      },
      {
        name: "Andre Garzia",
        twitter: "@soapdog"
      }
    ]
  },
  {
    name: "Learning and Leading",
    type: "MLN Pathway",
    description: [
      "Educators, librarians, and community builders of all experience levels will work toward goals in three interrelated areas: community engagement, technical skills and teach like Mozilla.",
    ],
    contacts: [
      {
        name: "Chad Sansing",
        twitter: "@chadsansing",
        bio: {
          photoSrc: "/assets/images/bio/ChadSansing.jpg",
          bio: <p>Chad Sansing works as a curriculum developer for the Mozilla Foundation and has a special interest in playful, participatory, and production-centered teaching and learning. Chad joined Mozilla after serving 14 years as a middle school language arts, social studies, and technology teacher and after several years of activity in the Mozilla and National Writing Project communities.</p>
        }
      },
      {
        name: "An-Me Chung",
        twitter: "@anmechung"
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
    name: "Open Research Accelerator",
    type: "Open Science Pathway",
    description: [
      "Have a project you'd like help with? We'll be running an Open Research Accelerator at the Festival, to provide 1-on-1 mentorship around open source and project skills to help you run your very own session at the Festival.",
    ],
    contacts: [
      {
        name: "Robert Davey",
        twitter: "@froggleston"
      },
      {
        name: "Billy Meinke",
        twitter: "@billymeinke"
      }
    ]
  },
  {
    name: "Open Data",
    type: "Open Science Pathway",
    description: [
      "Do you like data? Do you like data out in the open? Then the Open Data Pathway is for you! This pathway for all things open data- creating, curating, sharing, and using data out in the open! We'll be exploring ideas around using open data, open data ethics, open science, reproducibility, and collaboration.",
    ],
    contacts: [
      {
        name: "Christie Bahlai",
        twitter: "@cbahlai"
      }
    ]
  },
  {
    name: "Citizen Science",
    type: "Open Science Pathway",
    description: [
      "We all need a little science in our lives. If you celebrate that notion, then you'll love this pathway which is designed to highlight sessions that might be useful to individuals looking to roll-up their sleeves and engage with or lead a scientific research project.",
    ],
    contacts: [
      {
        name: "Jason Bobe",
        twitter: "@jasonbobe"
      }
    ]
  },
  {
    name: "Science Communication",
    type: "Open Science Pathway",
    description: [
      "The Science Communication Pathway is an tasty mix of sessions ranging topics in data visualization, research and analysis tools, collaboration building, and outreach methods. Hop on this pathway if you're looking to blend your exposure to the tools that support science communication, science visualization, and community building and outreach.",
    ],
    contacts: [
      {
        name: "Joey Lee",
        twitter: "@leejoeyk"
      }
    ]
  }
];


var PathwaysPage = React.createClass({
  pathways: PathwaysInfo,
  componentWillMount: function() {
    this.pathways = this.pathways.map(function(pathway,i) {
      var whiteBg = (i%2==0) ? "white-background" : "";
      return (
        <div className={whiteBg} key={pathway.name}>
          <div className="content wide">
            <SpacePathwayProfile {...pathway} contactTitle="pathfinder" />
          </div>
        </div>
      );
    });
  },
  render: function() {
    return (
      <div className="sessions-page">
        <Header/>
        <HeroUnit image="/assets/images/pathways.jpg"
                  image2x="/assets/images/pathways.jpg">
          Pathways
        </HeroUnit>
        <div className="white-background">
          <div className="content centered wide">
            <h1>Pathways</h1>
            <p>Each Pathway has been tailored for participants to learn a particular set of skills. Participants are encouraged to make use of the recommended Pathways or design their own unique MozFest experience.</p>
            <div className="horizontal-rule"></div>
          </div>
        </div>
        <div className="white-background">
          <div className="content centered wide no-vertical-margin">
          </div>
        </div>
        {this.pathways}
        <Footer/>
      </div>
    );
  }
});

module.exports = PathwaysPage;


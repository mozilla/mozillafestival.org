var React = require('react');
var Header = require('../components/header.jsx');
var Footer = require('../components/footer.jsx');
var HeroUnit = require('../components/hero-unit.jsx');
var SpacePathwayProfile = require('../components/space-pathway-profile.jsx');
var ImageTag = require('../components/imagetag.jsx');

var SpacesInfo = [
  {
    name: "Digital Arts and Culture",
    iconPath: "/assets/images/space-icons/digitalArts.svg",
    iconWidth: "84",
    description: 
    (<div>
      <p>21st Century, the relationship between art and technology has grown deeper and more nuanced than ever before. We use technology to create art. We view and share art using new and impressive digital tools. And often, technology itself — from products to Web pages — is art
      </p>
      <p>Join the Digital Arts and Culture Space for workshops, demos, and discussions that unpack the relationship between art, technology, and the Web. We’ll explore how vlogging is becoming an art form of its own; discover how digital tech is making the arts accessible to people with all abilities; ask when code is at its most creative; and understand the role of digital arts in society. Whether you’re a professional artist or a DIY maker, everyone will have an opportunity to create and be inspired.</p>
    </div>),
    sessionsLink: "https://github.com/MozillaFoundation/mozfest-program-2016/issues?q=is%3Aopen+is%3Aissue+milestone%3A%22Session+accepted%22+label%3A%22%5BSpace%5D+Digital+arts+and+culture%22",
    contacts: [
      {
        name: "Julie Neville"
      }
    ]
  },
  {
    name: "Journalism",
    iconPath: "/assets/images/space-icons/journalism.svg",
    iconWidth: "86",
    description:
    (<div>
      <p>Journalism doesn't just tell the story of the Internet — it's part of it. Software like Django, D3, and Backbone has emerged from newsrooms to power some of the most innovative work on the open Web. And reporting entertains and challenges millions of readers online every day, giving them the information they need to engage with their communities.</p>
      <p>In the Journalism Space, we explore how journalism can change our lives, both in person and online. Participants will learn how developers, designers, and data analysts collaborate to cover the most important stories of the day, and how they can contribute to the kind of work that protects the open Web and drives it forward.</p>
    </div>),
    sessionsLink: "https://github.com/MozillaFoundation/mozfest-program-2016/issues?q=is%3Aopen+is%3Aissue+milestone%3A%22Session+accepted%22+label%3A%22%5BSpace%5D+Journalism%22",
    contacts: [
      {
        name: "Erika Owens"
      },
      {
        name: "Ryan Pitts"
      },
      {
        name: "Eva Constantaras"
      }
    ]
  },
  {
    name: "Open Science",
    iconPath: "/assets/images/space-icons/openScience.svg",
    iconWidth: "90",
    description:
    (<div>
      <p>Science and the Web both help us understand the world around us. In the Open Science Space, participants explore, remix, and hack at the intersection of science and the Internet, and learn how the Web is transforming research and discovery.</p>
      <p>We cover topics like open data and research, citizen science, and science communication — all the while learning news skills and tools. We also collaborate on each other’s projects, and our Open Research Accelerator provides one-on-one mentorship for making your project open source. The Open Science Space is open to everyone: We invite hobbyists, researchers, librarians, data scientists, developers, and all others to join us.</p>
    </div>),
    sessionsLink: "https://github.com/MozillaFoundation/mozfest-program-2016/issues?q=is%3Aopen+is%3Aissue+milestone%3A%22Session+accepted%22+label%3A%22%5BSpace%5D+Open+science%22",
    contacts: [
      {
        name: "Arliss Collins"
      },
      {
        name: "Joey Lee"
      },
      {
        name: "Richard Smith-Unna"
      },
      {
        name: "Kirstie Whitaker"
      }
    ]
  },
  {
    name: "Open Badges",
    iconPath: "/assets/images/space-icons/openBadges.svg",
    iconWidth: "76",
    description:
    (<div>
      <p>Open Badges are transforming how we recognise and reward learning. These digital credentials showcase and communicate learners’ skills — from HTML to design — in a way that traditional CVs and transcripts cannot.</p>
      <p>Open Badges are taking root around the world, from non-profits to major employers and universities. And the technical infrastructure that makes badges possible is constantly evolving. In the Open Badges Space, we’ll showcase exciting badges projects, hack on and build badge infrastructure, and encourage new partners and networks adopt badges. We’re also plugging Open Badges into other spaces and sessions across MozFest.</p>
    </div>),
    sessionsLink: "https://github.com/MozillaFoundation/mozfest-program-2016/issues?q=is%3Aopen+is%3Aissue+milestone%3A%22Session+accepted%22+label%3A%22%5BSpace%5D+Open+badges%22",
    contacts: [
      {
        name: "Matt Rogers"
      },
      {
        name: "Tim Riches"
      },
      {
        name: "Lucy Lewis"
      },
      {
        name: "Grainne Hamilton"
      }
    ]
  },
  {
    name: "Fuel The Movement",
    iconPath: "/assets/images/space-icons/fuelMovement.svg",
    iconWidth: "94",
    description:
    (<div>
      <p>Who do you think should create culture? How do we build a movement to encourage creativity and imagination? If these questions keep you up at night, the open internet movement — and Fuel The Movement Space — needs you!</p>
      <p>We're bringing people together to talk about how current copyright laws aren't equipped for the internet age. Right now, we have a unique opportunity in the European Union to update and reform copyright laws to enhance -- not hinder -- creativity and innovation. Fuel The Movement will help participants explore how these and other laws impact them -- and what YOU can do about it -- through interactive art installations, hands-on workshops, and lightning talks. Participants will walk away energized to support creativity on the open internet.</p>
    </div>),
    sessionsLink: "https://github.com/MozillaFoundation/mozfest-program-2016/issues?q=is%3Aopen+is%3Aissue+milestone%3A%22Session+accepted%22+label%3A%22%5BSpace%5D+Fuel+the+movement%22",
    contacts: [
      {
        name: "Melissa Romaine"
      },
      {
        name: "Stacy Martin"
      },
      {
        name: "Raegan MacDonald"
      },
      {
        name: "Georgia Bullen"
      }
    ]
  },
  {
    name: "Localisation",
    iconPath: "/assets/images/space-icons/localisation.svg",
    iconWidth: "56",
    description:
    (<div>
      <p>The Internet spans many cultures and languages — and so does the Localisation Space.</p>
      <p>This space reflects the diversity of the Web and the people who use it. It’s set up like a marketplace, rich with different colors, smells, and flavours. We’ll explore how people participate in the digital world in a way that reflects their unique culture and identity. Localisation experts and amateurs alike will share localisation tools, discuss their experiences on a multicultural Internet, and explore how we can create a Web that’s truly inclusive of all languages and societies.</p>
    </div>),
    sessionsLink: "https://github.com/MozillaFoundation/mozfest-program-2016/issues?q=is%3Aopen+is%3Aissue+milestone%3A%22Session+accepted%22+label%3A%22%5BSpace%5D+Localisation%22",
    contacts: [
      {
        name: "Heather Bailey"
      },
      {
        name: "Dwayne Bailey"
      },
      {
        name: "Manal Hassan"
      }
    ]
  },
  {
    name: "Youth Zone",
    iconPath: "/assets/images/space-icons/youthZone.svg",
    iconWidth: "86",
    description:
    (<div>
      <p>Too often, current-day education systems are prescriptive. Learning is based around certain objectives, like exams. And independent thinking isn’t encouraged.</p>
      <p>In the Youth Zone Space, we’re creating an environment where independent thinking isn’t just encouraged — it’s rewarded. Our space is a sandbox for kids, teens, and adults alike to tinker, hack, build, and play as a means of learning. Sessions will be hands-on and centered around Raspberry Pi and virtual reality, with a focus on the artistry of these tools. We’ll also reimagine what a 21st-century library should look like — a collection of open source tools and activities.</p>
    </div>),
    sessionsLink: "https://github.com/MozillaFoundation/mozfest-program-2016/issues?q=is%3Aopen+is%3Aissue+milestone%3A%22Session+accepted%22+label%3A%22%5BSpace%5D+Youth+zone%22",
    contacts: [
      {
        name: "Dorine Flies"
      },
      {
        name: "Andrew Mulholland"
      }
    ]
  },
  {
    name: "Demystify the Web",
    iconPath: "/assets/images/space-icons/demystify.svg",
    iconWidth: "62",
    sessionsLink: "https://github.com/MozillaFoundation/mozfest-program-2016/issues?q=is%3Aopen+is%3Aissue+milestone%3A%22Session+accepted%22+label%3A%22%5BSpace%5D+Demystify+the+web%22",
    description:
    (<div>
      <p>Grab your ticket to our carnival of learning! All who enter will gain the most important skills of our age: the ability to read, write and participate in our digital world.</p>
      <p>The Demystify the Web Space invites teachers and learners of all ages to join our funhouse of Web literacy. Embrace the unknown! Experience the thrills! Imagine and share the full potential of the Web with everyone!</p>
    </div>),
    contacts: [
      {
        name: "Robert Friedman"
      },
      {
        name: "Kim Wilkens"
      },
      {
        name: "Su Adams"
      },
      {
        name: "Simeon Oriko"
      }
    ]
  },
  {
    name: "A Tale of Two Cities: Dilemmas in Connected Spaces",
    iconPath: "/assets/images/space-icons/dilemmas.svg",
    iconWidth: "76",
    description:
    (<div>
      <p>As our lives and physical environments become even more connected online, we're faced with dilemmas. Should I allow my personal data to be used if it improves my daily life? Should my everyday objects be online?</p>
      <p>This space will allow makers and learners to explore these dilemmas through a series of interactive experiences and mischievous interventions. Participants might nap on a squishy chair that generates sleep data; cook a snack in a connected kitchen where appliances only sometimes do as you say; and hack on IoT hardware.</p>
    </div>),
    sessionsLink: "https://github.com/MozillaFoundation/mozfest-program-2016/issues?q=is%3Aopen+is%3Aissue+milestone%3A%22Session+accepted%22+label%3A%22%5BSpace%5D+A+tale+of+two+cities%3A+dilemmas+in+connected+spaces%22",
    contacts: [
      {
        name: "Michelle Thorne"
      },
      {
        name: "Jon Rogers"
      },
      {
        name: "Ian Forrester"
      },
      {
        name: "Dietrich Ayala"
      },
      {
        name: "George Roter"
      }
    ]
  },
  {
    name: "MozEx (Art Exhibit)",
    iconPath: "/assets/images/space-icons/mozEx.svg",
    iconWidth: "86",
    description:
    (<div>
      <p>MozEx is an art exhibition with a 21st-century twist. Curated by the digital learning teams at both the Tate and the V&A, this space showcases dynamic digital artwork that spans many disciplines and media.</p>
      <p>The MozEx exhibit explores links between art, society, and the digital world. Created by both individual practitioners and cross-disciplinary collaborations, the exhibit will explore the value of art to society through Web literacy, digital inclusion and accessibility, privacy, policy, and hacking.</p>
    </div>),
    sessionsLink: "https://github.com/MozillaFoundation/mozfest-program-2016/issues?q=is%3Aopen+is%3Aissue+milestone%3A%22Session+accepted%22+label%3A%22%5BExperience%5D+Moz+Ex%22",
    contacts: [
      {
        name: "Luca Damiani"
      },
      {
        name: "Irini Papadimitriou"
      }
    ]
  },
];

var SessionsPage = React.createClass({
  spaces: SpacesInfo,
  render: function() {
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

    return (
      <div className="sessions-page">
        <Header/>
        <HeroUnit image="/assets/images/hero/sessions.jpg"
                  image2x="/assets/images/hero/sessions.jpg">
          <h1>Spaces & Sessions</h1>
        </HeroUnit>
        <div className="white-background">
          <div className="content centered wide">
            <h1>Spaces and Sessions</h1>
            <p>Spaces are physical and thematic learning hubs based around a broad topic, like science, art, or journalism. A Space is made up of sessions, which are hands-on, educational gatherings based around a specific topic, like “Using Crafts to Teach Localization Processes.” Sessions generally run 30-90 minutes. The schedule for MozFest will be published in mid-October. Until then, <a href="https://github.com/MozillaFoundation/mozfest-program-2016/issues?q=is%3Aopen+is%3Aissue+milestone%3A%22Session+accepted%22" target="_blank">see all sessions here.</a></p>
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

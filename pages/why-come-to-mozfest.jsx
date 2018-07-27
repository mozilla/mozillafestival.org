var React = require('react');
var Jumbotron = require('../components/jumbotron.jsx');

import generateHelmet from '../lib/helmet.jsx';
import Timetable from '../components/timetable.jsx';

const HouseSchedule = () => {
  const SCHEDULE = [
    {
      date: `Mon Oct 22`,
      timeslots: [
        {
          time: `10:00`,
          description: `Programs begin`
        }
      ]
    },
    {
      date: `Tue Oct 23`,
      timeslots: [
        {
          time: `10:00`,
          description: `Programs begin`
        }
      ]
    },
    {
      date: `Wed Oct 24`,
      timeslots: [
        {
          time: `10:00`,
          description: `Programs begin`
        }
      ]
    },
    {
      date: `Thu Oct 25`,
      timeslots: [
        {
          time: `10:00`,
          description: `Programs begin`
        }
      ]
    },
    {
      date: `Fri Oct 26`,
      timeslots: [
        {
          time: `10:00`,
          description: `Programs begin`
        }
      ]
    }
  ];

  return <Timetable header="MozFest House @ Royal Society of Arts" schedule={SCHEDULE} />;
};


const WeekendSchedule = () => {
  const SCHEDULE = [
    {
      date: `Fri Oct 26`,
      timeslots: [
        {
          time: `18:00 to 21:00`,
          description: `Science Fair evening reception`
        }
      ]
    },
    {
      date: `Sat Oct 27`,
      timeslots: [
        {
          time: `8:00`,
          description: `Doors open`
        },
        {
          time: `9:00`,
          description: `Welcoming & Opening`
        },
        {
          time: `10:00`,
          description: `Morning sessions begin`
        },
        {
          time: `12:00`,
          description: `Lunch`
        },
        {
          time: `14:00`,
          description: `Afternoon sessions begin`
        },
        {
          time: `19:30`,
          description: `MozFest Party`
        }
      ]
    },
    {
      date: `Sun Oct 28`,
      timeslots: [
        {
          time: `9:00`,
          description: `Doors open`
        },
        {
          time: `9:30`,
          description: `MozFest Breakfast`
        },
        {
          time: `11:00`,
          description: `Morning sessions begin`
        },
        {
          time: `13:00`,
          description: `Lunch`
        },
        {
          time: `14:00`,
          description: `Afternoon sessions begin`
        },
        {
          time: `18:00`,
          description: `Closing Celebration`
        }
      ]
    }
  ];

  return <Timetable header="MozFest Weekend @ Ravensbourne" schedule={SCHEDULE} />;
};

class WhyComeToMozfest extends React.Component {
  constructor(props) {
    super(props);

    this.pageMetaDescription = "MozFest is an annual celebration of the world’s most valuable public resource: the open Web.";
  }

  render() {
    return (
      <div className="why-come-to-mozfest-page">
        {generateHelmet(this.pageMetaDescription)}
        <Jumbotron image="/assets/images/hero/why-come-to-mozfest.jpg"
          image2x="/assets/images/hero/why-come-to-mozfest.jpg">
          <h1 className="highlight">Why Come to MozFest</h1>
        </Jumbotron>
        <div className="white-background">
          <div className="content centered wide">
            <div>
              <h1>Top 7 Reasons to Come to MozFest 2018</h1>
              <ol className="circle-number-list text-left">
                <li>Discover the cool things everyone else will be talking about in six months</li>
                <li>Get your hands dirty! Hack with techies, create with artists, scheme with activists</li>
                <li>See former YouTube AI technologist Guillaume Chaslot, former FBI agent and author of “Messing with the Enemy” Clint Watts, executive director of Internet Without Borders Julie Owono and many others speak at our Dialogues & Debates</li>
                <li>Meet the people using the web to change the world, one brilliant idea at a time, like fighting poverty in Bangladesh or hacking to save the Brazilian rainforest</li>
                <li>Experience digital art in the wild</li>
                <li>Grab some of that MozFest magic for your own project — answer our call for proposals to lead a session and set other great minds loose on your idea. You can also collaborate with others at MozFest House, our pre-week programming</li>
                <li>Snag the bargain of the year — £45 for 3 festival days, 2 lunches, snacks, beer, an amazing party, and endless coffee</li>
              </ol>
            </div>
            <div className="full-content mt-5">
              <h1>What is MozFest like?</h1>
              <iframe src="https://player.vimeo.com/video/258268373?color=ffffff&title=0&byline=0&portrait=0" width="100%" frameBorder="0" allowFullScreen className="mozfest-vimeo"></iframe>
              <p>MozFest has something for everyone. You’ll find a diverse set of sessions and spaces where you will meet, make, and learn with others. Jump into an interactive session for some hands-on building. Join a small group discussion and debate the most pressing issues facing the Internet today. Or simply wander the festival and take in all the ongoing displays to explore at your own speed.
              </p>
            </div>
          </div>
        </div>
        <div className="content centered wide">
          <div className="outline">
            <h1>Weekend at a Glance</h1>
            <div className="mb-5">
              <HouseSchedule />
            </div>
            <div>
              <WeekendSchedule />
            </div>
          </div>
        </div>
        <div className="white-background">
          <div className="content wide">
            <h1 className="text-center">Participate at MozFest</h1>
            <div className="two-column">
              <p>MozFest brings together people who are open, friendly and eager to help. Participants of all ages and skill levels are welcome. We believe everyone has something to contribute. Youth especially are encouraged to come and lead sessions — we’re dedicated to mentoring and celebrating tomorrow’s leaders. For our youngest participants, on-site day care and activities are provided.</p>
              <p>We try to accommodate all interaction styles at MozFest. While many sessions are hands-on and fully interactive, we also have plenty of listening-based activities for those who participate best in that manner. We also feature individual maker stations where attendees can focus — individually or in a group — on a single activity.</p>
              <p>MozFest is proud to host attendees from all over the world. Because each of us arrives with different backgrounds, experiences and expectations, MozFest has <a href="https://www.mozilla.org/en-US/about/governance/policies/participation/" target="_blank">Participation Guidelines</a> outlining how we interact. Theses are ground rules guiding how participants, facilitators, space wranglers, staff, volunteers and vendors engage with one another during the festival. Our goal is to make sure everyone can enjoy a safe, respectful and rewarding weekend.</p>
            </div>
          </div>
          <div className="content wide">
            <h1 className="text-center">Accessibility</h1>
            <div className="two-column">
              <p>At MozFest, accessibility is a vital part building our inclusive community. From providing childcare to emphasizing language inclusion to ensuring people with disabilities are supported, we work hard to make sure every MozFest participant can engage with the people, spaces and themes of the festival.</p>
              <p>When you register to attend MozFest, please let us know if you have an accessibility need such as visual or auditory support, wheelchair access, or the need for a quiet space. We are committed to discussing attendees’ individual needs, and providing support to the best of our abilities.</p>
              <p>Making MozFest more accessible is an ongoing process. We welcome your suggestions and ideas and would like to learn from your experiences. Please <a href="mailto:festival@mozilla.org">contact us</a> for more information, or to ask for support.</p>
            </div>
          </div>
        </div>
      </div>
    );
  }
}

export default WhyComeToMozfest;

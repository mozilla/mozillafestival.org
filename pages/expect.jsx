var React = require('react');
var Header = require('../components/header.jsx');
var Footer = require('../components/footer.jsx');
var HeroUnit = require('../components/hero-unit.jsx');
var ImageTag = require('../components/imagetag.jsx');
var generateHelmet = require('../lib/helmet.jsx');

var Expect = React.createClass({
  pageMetaDescription: "MozFest is an annual celebration of the world’s most valuable public resource: the open Web.",
  render: function() {
    return (
      <div className="expect-page">
        {generateHelmet(this.pageMetaDescription)}
        <Header/>
        <HeroUnit image="/assets/images/expect.jpg"
                  image2x="/assets/images/expect.jpg">
          what to expect
        </HeroUnit>
        <div className="white-background">
          <div className="content centered wide">
            <h1>What is MozFest like?</h1>
            <div className="participating">
              <div className="half-content">
                <p>MozFest is a diverse, highly interactive event with something for everyone. The festival kicks off Friday evening with a science fair, where attendees can stroll around and check out presentations on inspiring ideas and projects, many of which were developed at previous MozFests. Saturday and Sunday are filled with participant-led sessions that generally run between one and three hours. These might be interactive labs where participants can learn something new or share their knowledge; small group breakout discussions where bright minds debate the most pressing issues facing the Internet today; or design sprints dedicated to hands-on making, hacking and producing a ‘thing’.</p>

                <p>Sessions are organized into spaces -- physical and thematic learning hubs based around a broad topic, like science, art, or journalism.</p>
              </div>
              <div className="half-content">
                <p>Ongoing, interactive experiences weave between spaces, connecting thematic threads and allowing participants to explore topics in a self-directed way.</p>
                <p>Some people choose to enjoy MozFest by completely immersing themselves in a single space, while others like to roam around the venue. Both options are equally welcome, and the festival features pathways -- curated sets of related sessions that explore a subject area like open data, dealing with online harassment, or mobilizing communities -- to guide you between spaces.</p>

                <p>The festival wraps on Sunday evening with a demo party, where we showcase and celebrate what we built together.</p>
              </div>
              <div>
                <iframe src="https://player.vimeo.com/video/154774646?color=ffffff&title=0&byline=0&portrait=0" width="100%" height="456" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
              </div>
            </div>
          </div>
        </div>
        <div className="content centered wide">
          <div className="outline">
            <h1>Weekend Outline</h1>
            <table className="schedule-table">
              <tr className="table-header">
                <td>
                  fri oct 28
                </td>
                <td>
                  sat oct 29
                </td>
                <td>
                  sun oct 30
                </td>
              </tr>
              <tr>
                <td><div className="td-container">&nbsp;<br/>&nbsp;</div></td>
                <td>
                  <div className="td-container">
                    <div className="time">8:00</div>
                    <div>Doors open</div>
                  </div>
                </td>
                <td>
                  <div className="td-container">
                    <div className="time">9:00</div>
                    <div>Doors open</div>
                  </div>
                </td>
              </tr>
              <tr>
                <td><div className="td-container">&nbsp;<br/>&nbsp;</div></td>
                <td>
                  <div className="td-container">
                    <div className="time">9:00</div>
                    <div>Welcome & Opening</div>
                  </div>
                </td>
                <td>
                  <div className="td-container">
                    <div className="time">10:00</div>
                    <div>Welcome & Opening</div>
                  </div>
                </td>
              </tr>
              <tr>
                <td><div className="td-container">&nbsp;<br/>&nbsp;</div></td>
                <td>
                  <div className="td-container">
                    <div className="time">10:00</div>
                    <div>Morning sessions begin</div>
                  </div>
                </td>
                <td>
                  <div className="td-container">
                    <div className="time">11:00</div>
                    <div>Morning sessions begin</div>
                  </div>
                </td>
              </tr>
              <tr>
                <td><div className="td-container">&nbsp;<br/>&nbsp;</div></td>
                <td>
                  <div className="td-container">
                    <div className="time">12:30</div>
                    <div>Lunch</div>
                  </div>
                </td>
                <td>
                  <div className="td-container">
                    <div className="time">13:00</div>
                    <div>Lunch</div>
                  </div>
                </td>
              </tr>
              <tr>
                <td><div className="td-container">&nbsp;<br/>&nbsp;</div></td>
                <td>
                  <div className="td-container">
                    <div className="time">14:00</div>
                    <div>Afternoon sessions begin</div>
                  </div>
                </td>
                <td>
                  <div className="td-container">
                    <div className="time">14:30</div>
                    <div>Afternoon sessions begin</div>
                  </div>
                </td>
              </tr>
              <tr>
                <td>
                  <div className="time">18:00 to 21:00</div>
                  <div>Science Fair evening reception</div>
                </td>
                <td>
                  <div className="time">18:00</div>
                  <div>Evening activities begin</div>
                </td>
                <td>
                  <div className="time">18:00</div>
                  <div>Demo party</div>
                </td>
              </tr>
            </table>
          </div>
        </div>
        <div className="white-background">
          <div className="content centered wide">
            <h1>Participating at MozFest</h1>
            <div className="participating">
              <div className="half-content">
                <p>MozFest is an annual festival where hundreds of passionate people gather to wield the Web for good. We create, teach and learn as a community in order to make the Internet, and by extension the world, a better place. Guiding the festival is Mozilla’s core learning vision: learning should be hands-on, immersive, and done collectively.</p>

                <p>MozFest is populated with people that are open, friendly and eager to help. Participants of all ages and skill levels are welcome. We believe that everyone has something to contribute. Youth especially are encouraged to come and lead sessions -- we’re dedicated to mentoring and celebrating tomorrow’s leaders. For our youngest participants, on-site day care and activities are provided.</p>
              </div>
              <div className="half-content">
                <p>We try to accommodate all interaction styles at MozFest. While many sessions are hands-on and fully interactive, we also have plenty of listening-based activities for those who participate best in that manner, as well as individual maker stations where attendees can focus -- individually or in a group -- on a single activity.</p>

                <p>MozFest is proud to host attendees from all over the world. Because each of us arrives with different backgrounds, experiences and expectations, MozFest has Participation Guidelines outlining how we interact. Theses are ground rules guiding how participants, facilitators, space wranglers, staff, volunteers and vendors engage with one another during the festival, so we can all enjoy a safe, respectful and rewarding weekend.</p>

              </div>
            </div>
          </div>
        </div>
        <Footer/>
      </div>
    );
  }
});

module.exports = Expect;

var React = require('react');

import Jumbotron from '../components/jumbotron.jsx';

const FESTIVAL_GUIDE_LINK = `https://issuu.com/mozfest/docs/mozfest_2017_festival_guide`;

var Expect = React.createClass({
  render: function() {
    return (
      <div className="expect-page">
        <Jumbotron image="/assets/images/hero/expect.jpg"
          image2x="/assets/images/hero/expect.jpg">
          <h1>what to expect</h1>
        </Jumbotron>
        <div className="white-background">
          <div className="content centered wide">
            <div>
              <h1>What is MozFest like?</h1>
              <div className="half-content text-left">
                <p>MozFest is a diverse, highly interactive event with something for everyone. The festival kicks off Friday evening with a science fair, where attendees can stroll around and check out presentations on inspiring ideas and projects, many of which were developed at previous MozFests. Saturday and Sunday are filled with participant-led sessions that generally run between 30 to 90 minutes. You’ll find interactive spaces where participants can learn something new or engage in some hands-on making or experimentation; small group breakout discussions where bright minds debate the most pressing issues facing the Internet today; and ongoing, informative displays where attendees can explore ideas at their own speed.</p>
              </div>
              <div className="half-content text-left">
                <p>Sessions are organized into spaces &mdash; physical and thematic learning hubs based around a topic of broad relevance to a healthy Internet, like open innovation, or decentralization. The festival features interactive experiences that weave between spaces, connecting thematic threads and allowing participants to explore topics in a self-directed way.</p>

                <p>The weekend wraps on Sunday evening with a celebratory party, and a chance to connect with new friends and take the work forward beyond the festival.</p>
              </div>
            </div>
            <div className="full-content">
              <p><a href={FESTIVAL_GUIDE_LINK}>Read the 2017 Festival Guide</a></p>
              <p><a href={FESTIVAL_GUIDE_LINK} className="guide-image-wrapper"><img src="/assets/images/festival-guide.png" alt="Festival guide" width="1612" height="1014"/></a></p>
            </div>
            <div>
              <iframe src="https://player.vimeo.com/video/258268373?color=ffffff&title=0&byline=0&portrait=0" width="100%" height="456" frameBorder="0" allowFullScreen></iframe>
            </div>
          </div>
        </div>
        <div className="content centered wide">
          <div className="outline">
            <h1>Weekend Outline</h1>
            <table className="schedule-table">
              <thead>
                <tr className="table-header">
                  <th>
                    fri oct 26
                  </th>
                  <th>
                    sat oct 27
                  </th>
                  <th>
                    sun oct 28
                  </th>
                </tr>
              </thead>
              <tbody>
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
                      <div className="time">9:30</div>
                      <div>MozFest Breakfast</div>
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
                      <div className="time">12:00</div>
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
                      <div className="time">14:00</div>
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
                    <div className="time">19:30</div>
                    <div>MozFest Party</div>
                  </td>
                  <td>
                    <div className="time">18:00</div>
                    <div>Closing Celebration</div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <div className="white-background">
          <div className="content centered wide">
            <div>
              <h1>Participating at MozFest</h1>
              <div className="half-content text-left">
                <p>MozFest is an annual festival where hundreds of passionate people gather to wield the Web for good. We create, teach and learn as a community in order to make the Internet, and by extension the world, a better place. Guiding the festival is Mozilla’s core learning vision: learning should be hands-on, immersive, and done collectively.</p>

                <p>MozFest is populated with people that are open, friendly and eager to help. Participants of all ages and skill levels are welcome. We believe that everyone has something to contribute. Youth especially are encouraged to come and lead sessions &mdash; we’re dedicated to mentoring and celebrating tomorrow’s leaders. For our youngest participants, on-site day care and activities are provided.</p>
              </div>
              <div className="half-content text-left">
                <p>We try to accommodate all interaction styles at MozFest. While many sessions are hands-on and fully interactive, we also have plenty of listening-based activities for those who participate best in that manner, as well as individual maker stations where attendees can focus &mdash; individually or in a group &mdash; on a single activity.</p>
                <p>MozFest is proud to host attendees from all over the world. Because each of us arrives with different backgrounds, experiences and expectations, MozFest has <a href="https://www.mozilla.org/en-US/about/governance/policies/participation/" target="_blank">Participation Guidelines</a> outlining how we interact. Theses are ground rules guiding how participants, facilitators, space wranglers, staff, volunteers and vendors engage with one another during the festival, so we can all enjoy a safe, respectful and rewarding weekend.</p>
              </div>
            </div>
            <div>
              <h1>Accessibility</h1>
              <div className="half-content text-left">
                <p>At MozFest, accessibility is a vital part of how our network creates an inclusive community. From providing childcare to emphasizing language inclusion to ensuring people with disabilities are supported, we are increasing our efforts to ensure every MozFest participant can engage with the people, spaces and themes of the festival.</p>
                <p>During the open call for proposals, if you would like to submit a session and have difficulty using the text-based form, please email us to discuss other options.</p>
              </div>
              <div className="half-content text-left">
                <p>When you register to attend MozFest, please let us know if you have an accessibility need such as visual or auditory support, wheelchair access, or the need for a quiet space. We are committed to discussing attendees’ individual needs, and providing support to the best of our abilities.</p>
                <p>Making MozFest more accessible is an ongoing process. We welcome your suggestions and ideas and would like to learn from your experiences. Please <a href="mailto:festival@mozilla.org">contact us</a> for more information, or to ask for support.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    );
  }
});

module.exports = Expect;

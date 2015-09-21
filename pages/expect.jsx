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
            <h1 className="expect-header">A Note from Mark Surman, Mozilla&#8217;s Executive Director</h1>
            <div className="letter">
              <div className="half-content">
                <p className="boldish">Dear Friends,</p>
                <p>MozFest is an annual celebration of the world’s most valuable public resource: the open Web. Participants are diverse -- there are engineers and artists, activists and educators. But everyone shares a common belief: the Web can make lives better. The Web unlocks opportunity, spurs creativity, teaches valuable skills and connects far-flung people and ideas.</p>
                <p>MozFest is about improving the open Web with new ideas and creations. It’s also about sharing the open philosophy. By 2025, five billion individuals will be online, many exclusively through their smartphones. And it’s so important these new users discover a Web that improves each and every person's life.</p>
              </div>
              <div className="half-content">
                <p>There are threats and challenges. Some companies and governments seek to wrestle control of the Web away from the public. There is also a lack of education around web literacy, the ability to meaningfully interact and create online. When users don't fully understand how the Web works, they can't harness its potential.</p>
                <p>We can change this. At MozFest, we unite for creative sessions, peer learning and brainstorms. Together, we devise ways to teach web literacy; to be Freedom Fighters who stand up for the Web; and to ensure the next billion people to come online find a fully-open platform.</p>
                <p>We're seeking leaders and mentors to join us in London this year; individuals eager to empower others and make a real impact online. If you're passionate about literacy, privacy and inclusion on the Web, we hope to see you in London.</p>
                <p>
                  Best,<br/>
                  <span className="signature-bold">—Mark</span>
                </p>
                <div className="signature-container">
                  <ImageTag src1x="/assets/images/signature.svg"
                    alt="Mark Surman signature"/>
                  <span className="surman-photo-crop">
                    <ImageTag className="surman-image" src1x="/assets/images/img-mark.jpg"
                      height="322" width="320"
                      alt="Mark Surman image"/>
                  </span>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div className="content centered wide">
          <h1>Participating at MozFest</h1>
          <div className="participating">
            <div className="half-content">
              <p>MozFest is an annual festival where hundreds of passionate people gather to wield the Web for good. We create, teach and learn as a community in order to make the world a better place. Guiding the festival is Mozilla’s core learning vision: learning should be hands-on, immersive, and done collectively. MozFest can feel chaotic, but everyone is open, friendly and eager to help.</p>
              <p>Participants of all ages and skill levels are welcome. We believe that everyone has something to contribute. Youth especially are encouraged to come</p>
            </div>
            <div className="half-content">
              <p>and lead sessions — we’re dedicated to mentoring and celebrating tomorrow’s leaders. For the very young, on-site daycare and activities are provided.</p>
              <p>MozFest runs for three days and kicks off Friday evening with the Science Fair, where participants demo new, exciting projects that improve the Web. Sessions run throughout Saturday and Sunday, and are accompanied by hacking and good coffee. There’s a party Saturday night, and Sunday evening features a closing demo where we showcase what we created together.</p>
            </div>
          </div>
        </div>
        <div className="white-background">
          <div className="content centered wide">
            <div className="outline">
              <h1>Weekend Outline</h1>
              <table className="schedule-table">
                <tr className="table-header">
                  <td>
                    fri nov 6
                  </td>
                  <td>
                    sat nov 7
                  </td>
                  <td>
                    sun nov 8
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
                      <div>Welcome & Opening Keynotes</div>
                    </div>
                  </td>
                  <td>
                    <div className="td-container">
                      <div className="time">10:00</div>
                      <div>Welcome & Opening Keynotes</div>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td><div className="td-container">&nbsp;<br/>&nbsp;</div></td>
                  <td>
                    <div className="td-container">
                      <div className="time">10:30</div>
                      <div>Morning sessions begin</div>
                    </div>
                  </td>
                  <td>
                    <div className="td-container">
                      <div className="time">11:30</div>
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
                      <div className="time">15:00</div>
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
                    <div className="time">17:30 to 18:30</div>
                    <div>Evening lightning talks</div>
                  </td>
                  <td>
                    <div className="time">18:00</div>
                    <div>Demo party</div>
                  </td>
                </tr>
              </table>
            </div>
          </div>
        </div>
        <Footer/>
      </div>
    );
  }
});

module.exports = Expect;


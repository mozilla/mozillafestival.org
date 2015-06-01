var React = require('react');
var Header = require('../components/header.jsx');
var Footer = require('../components/footer.jsx');
var HeroUnit = require('../components/hero-unit.jsx');
var ImageTag = require('../components/imagetag.jsx');

var Expect = React.createClass({
  render: function() {
    return (
      <div className="expect-page">
        <Header/>
        <HeroUnit image="/assets/images/expect.jpg"
                  image2x="/assets/images/expect.jpg">
          what to expect
        </HeroUnit>
        <div className="white-background">
          <div className="content centered wide">
            <h1>A Note from Mark Surman,</h1>
            <h1>Mozilla&#8217;s Executive Director</h1>
            <div className="letter">
              <div className="half-content">
                <p className="boldish">Dear Friends,</p>
                <p>MozFest is an opportunity for those of us passionate about the open Web to unite and make the world a better place. Each year, makers, inventors and educators travel from around the globe to come together and collectively learn and create. And each year, we make a difference.</p>
                <p>As advocates for the open Web, it’s our duty to improve, protect and share it. That’s what MozFest is all about. Come with an idea — no matter how big or small — and share it with the community. And come ready to lead, attend and interact in hundreds of meaningful, fun and creative sessions.</p>
              </div>
              <div className="half-content">
                <p>The open Web needs you now more than ever. Billions of new users are just now discovering the Web through their smartphones, and it’s so important they find the same platform we love so much: one where everyone is an equal citizen, where all people can participate and create, and where opportunity is limitless.</p>
                <p>
                  See you in London.<br/>
                  <span className="signature-bold">—Mark Surman</span>
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
                    <div className="td-container">
                      fri nov 6
                    </div>
                  </td>
                  <td>
                    <div className="td-container">
                      sat nov 7
                    </div>
                  </td>
                  <td>
                    <div className="td-container">
                      sun nov 8
                    </div>
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
                      <div className="time">10:00</div>
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
                    <div className="td-container">
                      <div className="time">18:00 to 21:00</div>
                      <div>Science Fair evening reception</div>
                    </div>
                  </td>
                  <td>
                    <div className="td-container">
                      <div className="time">17:30 to 18:30</div>
                      <div>Evening lightning talks</div>
                    </div>
                  </td>
                  <td>
                    <div className="td-container">
                      <div className="time">18:00</div>
                      <div>Demo party</div>
                    </div>
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


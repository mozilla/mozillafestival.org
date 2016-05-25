var React = require('react');
var Header = require('../components/header.jsx');
var Footer = require('../components/footer.jsx');
var HeroUnit = require('../components/hero-unit.jsx');
var ImageTag = require('../components/imagetag.jsx');
var generateHelmet = require('../lib/helmet.jsx');

var TimeLineItem = React.createClass({
  render: function() {
    var className = "timeline-item";
    if (this.props.flip) {
      className += " flip-it";
    }
    if (this.props.className) {
      className += " " + this.props.className;
    }
    var self = this;
    return (
      <div className={className}>
        <div className="timeline-time-circle">{this.props.time}</div>
        <div className="timeline-item-container">
          {function() {
            if (!self.props.flip) {
              return (
                <div className="timeline-image-container">
                  <ImageTag src1x={self.props.image}
                    height={self.props.imageHeight} width={self.props.imageWidth}
                    alt={self.props.alt}/>
                </div>
              );
            }
          }()}
          <div className="timeline-content-container">
            <div className="timeline-label">
              {function() {
                if (!self.props.flip) {
                  return (
                    <span className="label-line"></span>
                  );
                }
              }()}
              <span className="label">{this.props.label}</span>
              {function() {
                if (self.props.flip) {
                  return (
                    <span className="label-line"></span>
                  );
                }
              }()}
            </div>
            <div className="timeline-content">{this.props.children}</div>
          </div>
          {function() {
            if (self.props.flip) {
              return (
                <div className="timeline-image-container">
                  <ImageTag src1x={self.props.image}
                    height={self.props.imageHeight} width={self.props.imageWidth}
                    alt={self.props.alt}/>
                </div>
              );
            }
          }()}
        </div>
      </div>
    );
  }
});

var CircleNumber = React.createClass({
  render: function() {
    return (
      <div className="circle-container">
        <div className="circle">
          {this.props.number}
        </div>
        <div className="label">{this.props.children}</div>
      </div>
    );
  }
});

var About = React.createClass({
  pageMetaDescription: "Mozilla's annual, hands-on festival (affectionately known as MozFest) is dedicated to forging the future of the open Web.",
  render: function() {
    return (
      <div className="about-page">
        {generateHelmet(this.pageMetaDescription)}
        <Header/>
        <HeroUnit image="/assets/images/about.jpg"
                  image2x="/assets/images/about.jpg">
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
          <div className="circles-container">
            <CircleNumber number="1600+">
              over 1.6k<br/>attendees
            </CircleNumber>
            <CircleNumber number="50+">
              participants from more<br/>than 50 countries
            </CircleNumber>
            <CircleNumber number="325">
              workshops<br/>& sessions
            </CircleNumber>
          </div>
          <p>The seventh-annual MozFest will be held in London, from Friday, Oct. 28 - Sunday, Oct. 29. MozFest is an annual celebration of the open Internet movement. It's where passionate technologists, educators, and makers come together to explore the future of the open Web.</p>
        </div>
        <div className="timeline-container">
          <div className="starting-line"></div>
            <TimeLineItem time="2016" label="the present" flip="true"
              className="timeline-item-2016"
              image="/assets/images/img-2016.svg"
              alt="MozFest Flag"
              imageHeight={230} imageWidth={220}
            >
              This year, we'll highlight how the Internet movement’s boldest, most game-changing ideas can come from anyone, anywhere. We'll examine how issues like Web literacy, online privacy and encryption are relevant across the globe, and address challenges faced by people who don't yet feel they are welcome on the Web.
            </TimeLineItem>
          <TimeLineItem time="2015" label="2015"
            className="timeline-item-2015"
            image="/assets/images/about-1.png"
            alt="2010 about image"
            imageHeight={343} imageWidth={534}
          >
            In 2015, we focused on leadership, advocacy and impact. We placed a particular emphasis on training tomorrow's leaders, empowering participants to make a positive difference on the Web, and working toward universal web literacy.
          </TimeLineItem>
          <TimeLineItem time="2014" label="a free web" flip="true"
            className="timeline-item-2014"
            image="/assets/images/web.svg"
            alt="2014 about image"
            imageHeight={500} imageWidth={680}
          >
            At MozFest 2014, nearly 1,700 participants from
more than 50 countries came together to improve
art, science, journalism, music, education and more
on the open Web. We hosted hundreds of diverse
sessions with a single guiding principle: keeping the
Web wild and free.
          </TimeLineItem>
          <TimeLineItem time="2013" label="hands on learning"
            className="timeline-item-2013"
            image="/assets/images/hands-01.svg"
            alt="2013 about image"
            imageHeight={400} imageWidth={650}
          >
            Learning through building was a key theme at
MozFest 2013. We shared our passion for the open
Web by creating and teaching as a community. And
the venue sprang to life with DIY signage, sessions
and after-parties.
          </TimeLineItem>
          <TimeLineItem time="2012" label="building and making" flip="true"
            className="timeline-item-2012"
            image="/assets/images/about-3.jpg"
            alt="2012 about image"
            imageHeight={218} imageWidth={348}
          >
            In 2012, MozFest was all about making. The event’s
opening-day Science Fair highlighted participants’
innovative creations. And we made and hacked
awesome projects about gaming, mobile, privacy
and the Internet of Things.
          </TimeLineItem>
          <TimeLineItem time="2011" label="settling in london"
            className="timeline-item-2011"
            image="/assets/images/london-01.svg"
            alt="2011 about image"
            imageHeight={400} imageWidth={700}
          >
            In 2011, MozFest relocated to London with a
sharpened focus: media, freedom and the Web.
Participants lent their passion and creativity to
improve journalism and digital storytelling on the
open Web. We established a dedicated space for
youngsters to learn and make. And we built on the
infectious community spirit ﬁrst ignited in
Barcelona.
           </TimeLineItem>
          <TimeLineItem time="2010" label="the beginning" flip="true"
            className="timeline-item-2010"
            image="/assets/images/about-2.png"
            alt="2010 about image"
            imageHeight={280} imageWidth={420}
          >
            MozFest was born in Barcelona. Originally named
"Drumbeat," the festival convened a community of
people dedicated to learning, freedom and the
open Web. The inaugural event hosted 350
participants — and together, we wrote a book titled
“Learning Freedom and the Web.
          </TimeLineItem>
          <div className="content centered">
            <div className="final-circle">
              <div className="inner-circle">
                <div className="inner-circle-1"></div>
                <div className="inner-circle-2"></div>
              </div>
            </div>
          </div>
        </div>
        <Footer/>
      </div>
    );
  }
});

module.exports = About;

var React = require('react');
var Header = require('../components/header.jsx');
var Footer = require('../components/footer.jsx');
var Jumbotron = require('../components/jumbotron.jsx');
var ImageTag = require('../components/imagetag.jsx');
var generateHelmet = require('../lib/helmet.jsx');
var classnames = require('classnames');

var TimeLineItem = React.createClass({
  render: function() {
    var className = classnames(this.props.className, "timeline-item", {
      "flip-it": this.props.flip
    });

    var self = this;
    return (
      <div className={className}>
        <div className="timeline-time-circle">{this.props.time}</div>
        <div className="timeline-item-container w-100 d-flex align-items-center justify-content-center">
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
        <div className="circle rounded-circle d-flex align-items-center justify-content-center">
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
        <Jumbotron image="/assets/images/hero/about.jpg"
                  image2x="/assets/images/hero/about.jpg">
        </Jumbotron>
        <div className="white-background">
          <div className="content centered wide">
            <h1 className="expect-header">A Note from Mark Surman, Mozilla&#8217;s Executive Director</h1>
            <div className="letter">
              <div className="half-content">
                <p className="boldish">Dear Friends,</p>
                <p>I like to think of the Mozilla community as a reflection of the Internet: Creative. Eclectic. Speaking many languages, spanning many disciplines, always open to new ideas.</p>

                <p>This is most apparent at MozFest, the annual celebration of our community and the greater open Internet movement. Three days each year, we gather together and discuss, debate, create and hack to build a better Internet. We’re a diverse crew: Scientists from the UK. Educators from Kenya. Technologists from Shenzhen.</p>

                <p>When we come together this year, we want to celebrate our diversity. We want to show that the open Internet movement’s boldest, most game-changing ideas can come from anyone, anywhere. We have the proof: Over the past year, the next generation of open Internet leaders have campaigned for net neutrality in India; taught digital skills in Nairobi and Cape Town; and explored the future of the Internet of Things in Berlin.</p>
              </div>
              <div className="half-content">
                <p>This year at MozFest, we also want to do our duty as outlined in the Mozilla Manifesto: Ensure the Internet is a global public resource, open and accessible to all. That means teaching web literacy to more people in more places. It also means asking hard questions about what an ‘inclusive Internet’ really means. We need to actively address the challenges faced by people who don't yet feel they are welcome on the Web.</p>

                <p>We have our work cut out for us -- there’s no shortage of threats to the open Internet. Online privacy and important safeguards like encryption are in jeopardy. And walled gardens are making the Web more closed and less hackable.</p>

                <p>At MozFest this year, we’ll work on solutions to these issues and so many others. Our MozFest brainstorms, sessions and creativity always inspire me, and I can’t wait to see what we build together this year.</p>
                <p>
                  See you in London,<br/>
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
          <p>The eighth annual MozFest will be held in London, from Friday, Oct. 27 to Sunday, Oct. 29. MozFest is an annual celebration of the open Internet movement. It's where passionate technologists, educators, and makers come together to explore the future of the open Web.</p>
        </div>
        <div className="timeline-container">
          <div className="starting-line"></div>
            <TimeLineItem time="2016" label="inclusion" flip="true"
              className="timeline-item-2016"
              image="/assets/images/img-2016.svg"
              alt="MozFest Flag"
              imageHeight={230} imageWidth={220}
            >
              In 2016, we highlighted how the Internet movement’s boldest, most game-changing ideas can come from anyone, anywhere. We examined how issues like Web literacy, online privacy and encryption are relevant across the globe, and address challenges faced by people who don't yet feel they are welcome on the Web.
            </TimeLineItem>
          <TimeLineItem time="2015" label="training leaders"
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

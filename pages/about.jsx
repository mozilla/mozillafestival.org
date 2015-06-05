var React = require('react');
var Header = require('../components/header.jsx');
var Footer = require('../components/footer.jsx');
var HeroUnit = require('../components/hero-unit.jsx');
var ImageTag = require('../components/imagetag.jsx');
var Router = require('react-router');
var Link = Router.Link;

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
  render: function() {
    return (
      <div className="about-page">
        <Header/>
        <HeroUnit image="/assets/images/about.jpg"
                  image2x="/assets/images/about.jpg">
        </HeroUnit>
        <div className="white-background">
          <div className="content centered wide">
            <div className="circles-container">
              <CircleNumber number="1700">
                over 1.7k<br/>attendees
              </CircleNumber>
              <CircleNumber number="50+">
                participants from more<br/>than 50 countries
              </CircleNumber>
              <CircleNumber number="343">
                workshops<br/>& sessions
              </CircleNumber>
            </div>
            <p>Mozilla's anual, hands-on festival (affectionaltely known as MozFest) is dedicated to forging the future of the open web. It's where passionate technologists, educatios and creators unite to hack on innovative solutions for the web's most pressing issues.</p>
          </div>
        </div>
        <div className="timeline-container">
          <div className="starting-line"></div>
          <TimeLineItem time="2015" label="the present"
            className="timeline-item-2015"
            image="/assets/images/about-1.png"
            alt="2010 about image"
            imageHeight={343} imageWidth={534}
          >
            In 2015, we’re continuing the momentum from the 
past ﬁve years. Join us as we collectively build and 
teach the world’s most valuable public resource: 
the Web.
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
            <h1>Want to participate?</h1>
            <Link to="proposals" className="button"><span>Submit a Proposal</span></Link>
          </div>
        </div>
        <Footer/>
      </div>
    );
  }
});

module.exports = About;


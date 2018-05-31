var React = require('react');
var Jumbotron = require('../components/jumbotron.jsx');
var ImageTag = require('../components/imagetag.jsx');
var classnames = require('classnames');

import generateHelmet from '../lib/helmet.jsx';

var TimeLineItem = React.createClass({
  render: function() {
    var className = classnames(this.props.className, "timeline-item", {
      "flip-it": this.props.flip
    });

    var self = this;
    return (
      <div className={className}>
        <div className="timeline-time-circle">{this.props.time}</div>
        <div className="timeline-item-container w-100 d-flex justify-content-center">
          {(function() {
            if (!self.props.flip) {
              return (
                <div className="timeline-image-container d-flex align-items-center justify-content-end">
                  <ImageTag src1x={self.props.image}
                    height={self.props.imageHeight} width={self.props.imageWidth}
                    alt={self.props.alt}/>
                </div>
              );
            }
          })()}
          <div className="timeline-content-container d-flex align-items-center">
            <div>
              <div className="timeline-label">
                {(function() {
                  if (!self.props.flip) {
                    return (
                      <span className="label-line"></span>
                    );
                  }
                })()}
                <span className="label">{this.props.label}</span>
                {(function() {
                  if (self.props.flip) {
                    return (
                      <span className="label-line"></span>
                    );
                  }
                })()}
              </div>
              <div className="timeline-content">{this.props.children}</div>
            </div>
          </div>
          {(function() {
            if (self.props.flip) {
              return (
                <div className="timeline-image-container d-flex align-items-center">
                  <ImageTag src1x={self.props.image}
                    height={self.props.imageHeight} width={self.props.imageWidth}
                    alt={self.props.alt}/>
                </div>
              );
            }
          })()}
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
        <Jumbotron image="/assets/images/hero/about.jpg"
          image2x="/assets/images/hero/about.jpg">
        </Jumbotron>
        <div className="white-background">
          <div className="content centered wide">
            <div className="text-center">
              <h1>A note from Mark Surman, Mozilla’s Executive Director, about MozFest and Internet Health</h1>
            </div>
            <div className="letter">
              <div className="half-content">
                <p>Earlier this year, Mozilla released its first full-length <a href="https://internethealthreport.org/2018/">Internet Health Report</a> &mdash; a deep look at how the internet and human life intersect, in ways both good and bad.</p>
                <p>Writing the report was a chance to reflect on how remarkably the internet has changed. Today we have artificially-intelligent voice assistants and VR web browsing &mdash; innovations we couldn’t fathom in the days of 56k modems and static web pages.</p>
                <p>There have also been harmful developments. Like <a href="https://internethealthreport.org/2018/spotlight-understanding-fake-news/">sophisticated misinformation campaigns</a>. <a href="https://internethealthreport.org/2018/spotlight-securing-the-internet-of-things/">The erosion of privacy and user control</a>. And <a href="https://internethealthreport.org/2018/too-big-tech/">a handful of technology giants controlling what was once a more open, decentralized space</a>.</p>
                <p>This October, the people and ideas in the Internet Health Report will leap off the screen to discuss, debate and address these issues in person. Our ninth-annual Mozilla Festival is returning to London. And it feels more vital and necessary than ever.</p>
                <p>Like the web, the Mozilla Festival has evolved over the years. It started as a small gathering in Spain &mdash; a few hundred fiery thinkers in a Barcelona museum. Our goal was to connect people building a healthy internet.</p>
              </div>
              <div className="half-content">
                <p>As the internet became more entwined with everyday life, the festival’s goal remained the same &mdash; but the scope grew. We moved to a bigger space: the Ravensbourne campus in London. Participation swelled: In 2017, we hosted 2,500 activists, coders, journalists and educators from every continent except Antarctica. And the festival now unfolds across seven days and two venues.</p>
                <p>In October, we’ll have that same fiery spirit from 2010. We’ll also have the people and resources to make a big impact. A seven-day festival with hundreds of experts means we can build tools that better protect user data. We can advance thinking on topics like <a href="https://internethealthreport.org/2018/intelligent-machines-arent-always-right/">ethical AI</a> and <a href="https://internethealthreport.org/2018/germanys-hate-speech-law-makes-global-waves/">common-sense tech policy</a>. We can forge new partnerships, we can train tomorrow’s leaders and we can fuel the movement for a healthier internet.</p>
                <p><a href="https://foundation.mozilla.org/">MozFest is just the start</a>. Every year, we leave London inspired and committed. The ideas we bat around grow into influential and global campaigns. The code we craft becomes polished open-source products. All the while, we move toward a more open, inclusive and healthy internet.</p>
                <p>
                  Hope to see you there.<br/>
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
            <CircleNumber number="1700+">
              over 1.7k<br/>attendees
            </CircleNumber>
            <CircleNumber number="50+">
              participants from more<br/>than 50 countries
            </CircleNumber>
            <CircleNumber number="312">
              sessions<br/>& experiences
            </CircleNumber>
          </div>
          <p>The ninth annual MozFest will be held in London, from Friday, Oct. 26 to Sunday, Oct. 28, 2018. MozFest is an annual celebration of the open Internet movement. It's where passionate technologists, educators, and makers come together to explore the future of the open Web.</p>
        </div>
        <div className="timeline-container">
          <div className="starting-line"></div>
          <TimeLineItem time="2017" label="Internet Health"
            className="timeline-item-2017"
            image="/assets/images/about-page-2017.jpg"
            alt="2017 about image"
            imageHeight={167} imageWidth={240}
          >
            MozFest 2017 was a deep exploration of the bright spots and threats to Internet health. Conversations, prototyping and art-making were framed around the ideas of decentralization, digital inclusion, online privacy and security, open innovation and Web literacy. Young people shone at the festival this year, with an 11-year old speaker on the main stage and youth-led sessions in every space.
          </TimeLineItem>
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
            alt="2015 about image"
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
      </div>
    );
  }
});

module.exports = About;

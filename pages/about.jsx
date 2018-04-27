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
            <div className="confined-width-header text-center">
              <h1>A Note from Mark Surman, Mozilla&#8217;s Executive Director</h1>
            </div>
            <div className="letter">
              <div className="half-content">
                <p>October 2017 marks our eighth MozFest. It’s also our most ambitious one yet &mdash; more than ever, the movement for a healthy Internet needs a place to convene, organize and act.</p>
                <p>At its start, MozFest &mdash; then called Drumbeat and nestled in Barcelona’s Museum of Contemporary Art &mdash; featured a small band of hackers and makers.</p>
                <p>Since that 2010 gathering, MozFest has grown significantly. In size, yes &mdash; but more importantly, in scope. In 2011, the festival turned its attention to digital media, welcoming journalists and newsroom coders into the fold. In 2013, we focused on web literacy, inviting educators from around the world to craft tools and curricula for teaching the web. And in 2016, we talked about digital inclusion: who isn’t unlocking opportunity online, why that is, and what we can do to fix it.</p>
                <p>This is an evolution that mirrors the growth of the Internet health movement. Today, the concept of Internet health reaches far beyond the realm of open source code: it’s linked to civil liberties and public policy, free expression and inclusion. Discussions about the state of the web include engineers, but now also teachers, lawmakers, community organizers and artists.</p>
              </div>
              <div className="half-content">
                <p>This is a positive and heartening development. It’s also a necessary one. The Internet is layered into our lives like we never could have imagined. Access is no longer a luxury &mdash; it’s a fundamental part of 21st century life. A virus is no longer a nuisance consigned to a single terminal &mdash; it’s an existential threat that can disrupt hospitals, governments and entire cities.</p>
                <p>The movement for a healthy Internet is primed to address these problems. But we need a hub to trade ideas, find inspiration, swap code and build solutions.</p>
                <p>MozFest is that hub.</p>
                <p>Our sessions, speakers and workshops are built to foster collaboration across disciplines, borders and continents. We’re ready to face the biggest issues of the day &mdash; from fake news and online harassment to global cyberattacks &mdash; together, with an eye toward practical, open source solutions. </p>
                <p>The challenges we’re facing are sizable. But we’re prepared to roll up our sleeves and address them head on in London &mdash; then return to our communities, classrooms and computers, better equipped to defend the Internet as a global public resource.</p>
                <p>
                  See you there,<br/>
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

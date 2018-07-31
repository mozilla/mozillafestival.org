var React = require('react');
var ImageTag = require('../components/imagetag.jsx');

import { Link } from 'react-router-dom';
import Jumbotron from '../components/jumbotron.jsx';

const CircleNumber = (props) => {
  return (
    <div className="circle-container">
      <div className="circle rounded-circle d-flex align-items-center justify-content-center">
        {props.number}
      </div>
      <div className="label">{props.children}</div>
    </div>
  );
};

const SpaceIntroBlock = (props) => {
  return (
    <div className="space-intro-block-wrapper col-12 col-sm-6 col-md-4">
      <div className={classNames(props.className, `space-intro-block text-center h-100`)}>
        <h3>{props.name}</h3>
        <p className="my-0">{props.intro}</p>
      </div>
    </div>
  );
};

const TeamBlock = (props) => {
  return (
    <Link className="text-center my-4" to={props.linkTo}>
      <p>{props.name}</p>
      <ImageTag className="rounded-circle"
        src1x={props.imgSrc}
        height="200"
        width="200"
        alt={props.name}
      />
    </Link>
  );
};

var About = React.createClass({
  render: function() {
    return (
      <div className="about-page">
        <Jumbotron image="/assets/images/hero/about.jpg"
          image2x="/assets/images/hero/about.jpg">
          <div className="content-wrapper">
            <h1 className="highlight">About MozFest</h1>
            <p>Calling all hackers, performers, students, activists, scientists, designers and beyond<strong>. If you love the Internet, MozFest is for you!</strong></p>
            <p className="mb-0">On October 22-28, join us in London to share the newest tech, wildest ideas, most amazing stories, and your brilliant plans to <strong>make the Internet even more awesome</strong>.</p>
          </div>
        </Jumbotron>
        <div className="content centered wide">
          <div className="circles-container d-flex flex-column flex-sm-row justify-content-center">
            <CircleNumber number="2500+">
              participants
            </CircleNumber>
            <CircleNumber number="7">
              days of MozFest
            </CircleNumber>
            <CircleNumber number="320+">
              workshops, sessions, and events
            </CircleNumber>
            <CircleNumber number="50+">
              countries represented
            </CircleNumber>
            <CircleNumber number="£45">
              for a<br/>weekend ticket
            </CircleNumber>
            <CircleNumber number="1">
              amazing party
            </CircleNumber>
          </div>
          <p>Every year over 2500+ people flock to MozFest from around the world, to connect, share, learn, create... and party!</p>
        </div>
        <div className="white-background">
          <div className="content centered wide">
            <div className="text-center">
              <h1>A note from Mark Surman</h1>
            </div>
            <div className="letter two-column mt-4">
              <p>MozFest began as a small gathering in 2010 — a few hundred fiery thinkers dreaming and planning in Barcelona, Spain. Our goal was to connect people building a healthy Internet.
              </p>
              <p>A healthy Internet is one that’s accessible to all; decentralized; bursting with vibrant, creative communities, trustworthy content, and groundbreaking ideas. It’s an Internet where users control their data, and privacy and intellectual freedom thrive. An Internet not of passive consumers, but of active creators, building and shaping online tools and spaces.
              </p>
              <p>Today, the Internet is deeply entwined in our everyday lives, and MozFest, too, has grown. In 2017, we hosted 2,500 activists, coders, journalists and educators from 50 countries. The Festival unfolds in London, across seven days and two venues. It’s now co-designed with over 40 community volunteers. But our goal — to make our Internet, and our world, better — remains the same.
              </p>
              <p>This year, ideas from Mozilla’s first full-length <a href="https://internethealthreport.org/2018/">Internet Health Report</a> — a deep look at how the Internet and human life intersect — are at the heart of the festival. At MozFest 2018, we’ll strategize our next moves in global campaigns for net neutrality, data privacy, and online freedom. We’ll advance thinking on topics like <a href="https://internethealthreport.org/2018/intelligent-machines-arent-always-right/">ethical AI</a> and <a href="https://internethealthreport.org/2018/germanys-hate-speech-law-makes-global-waves/">common-sense tech policy</a>. We’ll collaborate on code, on art and practical ideas, creating seeds for the next great open-source products.
              </p>
              <p>
                Join us, and fuel the movement for a healthier Internet!
              </p>
              <div className="signature-container d-flex align-items-start justify-content-end">
                <ImageTag src1x="/assets/images/signature.svg"
                  alt="Mark Surman signature"/>
                <ImageTag className="rounded-circle" src1x="/assets/images/team/production/MarkSurman.jpg"
                  height="170" width="170"
                  alt="Mark Surman image"/>
              </div>
            </div>
          </div>
        </div>
        <div className="white-background">
          <div className="content centered wide">
            <div className="text-center">
              <h1>Spaces</h1>
              <p>
                Sessions at MozFest are organised under — physical and thematic learning hubs based around a topic of broad relevance to a healthy Internet.
              </p>
            </div>
            <div className="container-fluid">
              <div className="row">
                <SpaceIntroBlock name="Decentralisation"
                  intro="A space for unpacking concepts like mesh networking and blockchain — and conversely, internet shutdowns and monopolies"
                  className="decentralisation"
                />
                <SpaceIntroBlock name="Digital Inclusion"
                  intro="A space exploring equity, access and participation for everyone, all across the web"
                  className="digital-inclusion"
                />
                <SpaceIntroBlock name="Openness"
                  intro="A space for learning about open production, open projects, open code and all other things open"
                  className="openness"
                />
                <SpaceIntroBlock name="Privacy & Security"
                  intro="A space exploring encryption, VPNs, mass surveillance and safety online"
                  className="privacy-security"
                />
                <SpaceIntroBlock name="Web Literacy"
                  intro="A space devoted to the skills required to read, write and participate on the web"
                  className="web-literacy"
                />
                <SpaceIntroBlock name="Youth Zone"
                  intro="A space for youth leaders and their mentors who are creating art, technology and positive social change"
                  className="youth-zone"
                />
              </div>
            </div>
          </div>
        </div>
        <div className="white-background">
          <div className="content centered wide mb-5">
            <div>
              <h1>Meet the MozFest Team!</h1>
              <p>
                Over 50 people collaborate to bring you MozFest each year — we have a core production team, a global group of community memebers who curate sessions and wrangle our Spaces, and of course, our generous sponsors.
              </p>
            </div>
            <div className="container-fluid mb-5">
              <div className="row">
                <div className="col-md-4">
                  <TeamBlock name="Production Team"
                    imgSrc="assets/images/about/mozilla-festival_about-page_production-team@2x.jpg"
                    linkTo="/team/production"
                  />
                </div>
                <div className="col-md-4">
                  <TeamBlock name="Space Wranglers"
                    imgSrc="assets/images/about/mozilla-festival_about-page_space-wranglers@2x.jpg"
                    linkTo="/team/wranglers"
                  />
                </div>
                <div className="col-md-4">
                  <TeamBlock name="Sponsors"
                    imgSrc="assets/images/about/mozilla-festival_about-page_sponsors@2x.jpg"
                    linkTo="/team/sponsors"
                  />
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    );
  }
});

module.exports = About;

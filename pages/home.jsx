var React = require('react');
var Header = require('../components/header.jsx');
var Footer = require('../components/footer.jsx');
var Jumbotron = require('../components/jumbotron.jsx');
var Router = require('react-router');
var Link = Router.Link;
var ImageTag = require('../components/imagetag.jsx');
var SpeakersPromo = require('../components/speakers-promo.jsx');
var generateHelmet = require('../lib/helmet.jsx');


var speakersInfo = [
  {
    name: `Zeynep Tufekci`,
    twitter: `@zeynep`,
    pic: `/assets/images/speakers/Tufekci.jpg`,
    link: `https://www.youtube.com/watch?v=pO-brBVRyN8&index=12&list=PLYiaJo7rYNXLQSEAa2RdyyiS28Ke2Rl60`
  },
  {
    name: `Brian Behlendorf`,
    twitter: `@brianbehlendorf`,
    pic: `/assets/images/speakers/Behlendorf.jpg`,
    link: `https://www.youtube.com/watch?v=PvPGTu_GjVI&index=11&list=PLYiaJo7rYNXLQSEAa2RdyyiS28Ke2Rl60`
  },
  {
    name: `Maggie Vail`,
    twitter: `@magicbeans`,
    pic: `/assets/images/speakers/Vail.jpg`,
    link: `https://www.youtube.com/watch?v=RyAdgOoJm_E&list=PLYiaJo7rYNXLQSEAa2RdyyiS28Ke2Rl60&index=10`
  },
  {
    name: `Mark Surman`,
    twitter: `@msurman`,
    pic: `/assets/images/speakers/Surman.jpg`,
    link: `https://www.youtube.com/watch?v=AHb2Ki1XK88&list=PLYiaJo7rYNXLQSEAa2RdyyiS28Ke2Rl60&index=9`
  },
  {
    name: `Sahar Aziz`,
    twitter: `@saharazizlaw`,
    pic: `/assets/images/speakers/Aziz.jpg`,
    link: `https://www.youtube.com/watch?v=p4cftN0jtYg&list=PLYiaJo7rYNXLQSEAa2RdyyiS28Ke2Rl60&index=8`
  },
  {
    name: `Katherine Maher`,
    twitter: `@krmaher`,
    pic: `/assets/images/speakers/Maher.jpg`,
    link: `https://www.youtube.com/watch?v=n3whd0Wr-Bg&list=PLYiaJo7rYNXLQSEAa2RdyyiS28Ke2Rl60&index=7`
  },
  {
    name: `Chris Soghoian`,
    twitter: `@csoghoian`,
    pic: `/assets/images/speakers/Soghoian.jpg`,
    link: `https://www.youtube.com/watch?v=zTZbrkV3bs8&list=PLYiaJo7rYNXLQSEAa2RdyyiS28Ke2Rl60&index=6&t=16s`
  },
  {
    name: `Ashe Dryden`,
    twitter: `@ashedryden`,
    pic: `/assets/images/speakers/Dryden.jpg`,
    link: `https://www.youtube.com/watch?v=3k8WVijL124&list=PLYiaJo7rYNXLQSEAa2RdyyiS28Ke2Rl60&index=5`
  },
  {
    name: `Volker Birk`,
    pic: `/assets/images/speakers/Birk.jpg`,
    link: `https://www.youtube.com/watch?v=yd3cJjDW2dY&list=PLYiaJo7rYNXLQSEAa2RdyyiS28Ke2Rl60&index=4`
  },
  {
    name: `Katharina Borchert`,
    twitter: `@lyssaslounge`,
    pic: `/assets/images/speakers/Borchert.jpg`,
    link: `https://www.youtube.com/watch?v=4krgs4gHSbI&index=3&list=PLYiaJo7rYNXLQSEAa2RdyyiS28Ke2Rl60`
  },
  {
    name: `Simone Browne`,
    pic: `/assets/images/speakers/Browne.jpg`,
    link: `https://www.youtube.com/watch?v=lBwz-Jag1ZE&list=PLYiaJo7rYNXLQSEAa2RdyyiS28Ke2Rl60&index=1`
  },
  {
    name: `Eliot Higgins`,
    twitter: `@EliotHiggins`,
    pic: `/assets/images/speakers/Higgins.jpg`,
    link: `https://www.youtube.com/watch?v=iFx6eJF94Ew&list=PLYiaJo7rYNXLQSEAa2RdyyiS28Ke2Rl60&index=2`
  }
];

var Home = React.createClass({
  pageMetaDescription: "Three days of building a truly global web together. Come with an idea, leave with a community.",
  render: function () {
    var self = this;
    return (
      <div className="home-page">
        {generateHelmet(this.pageMetaDescription)}
        <Header logoImage="/assets/images/mozilla-festival_wordmark-interim_horizontal.svg"/>
        <Jumbotron className="home-jumbotron" image="/assets/images/hero/home/banner-home_5.jpg"
                  image2x="/assets/images/hero/home/banner-home_5.jpg"
                  hideBackgroundLines={true}>
          <div className="content-wrapper">
            <h1>Welcome to MozFest</h1>
            <p className="mb-0">The world's leading festival for the open Internet movement.</p>
            <p className="mb-0">October 27-29, 2017 Ravensbourne College, London</p>
            <Link to="/proposals" className="btn p-3 m-3">Propose a Session</Link>
          </div>
        </Jumbotron>
        <div className="white-background">
          <div className="content wide">
            <div className="row">
              <div className="col-sm-4 text-left">
                <h1>What is MozFest like?</h1>
                <p>MozFest is for advocates of a healthy Internet. Explore the intersection of the web with civil society, journalism, public policy, and art through interactive sessions.</p>
                <Link to="/expect" className="btn btn-primary-outline btn-block mt-3">What to Expect</Link>
              </div>
              <div className="col-sm-8">
                <div className="embed-responsive embed-responsive-16by9">
                  <iframe src="https://player.vimeo.com/video/205552025?color=ffffff&title=0&byline=0&portrait=0" className="embed-responsive-item" allowFullScreen></iframe>
                </div>
              </div>
            </div>
            <div className="horizontal-rule"></div>
          </div>
        </div>
        <div className="white-background">
          <div className="centered content wide">
            <h1>Watch now: 2016 Dialogues and Debates</h1>
            <SpeakersPromo speakersInfo={speakersInfo} />
          </div>
        </div>
        <div className="news content wide">
          <h1 className="text-center">Latest News</h1>
          <div className="row mt-5">
            <div className="col-12 col-md-6 text-left">
              <div className="news-widget mb-3">
                <img src="assets/images/BrianBehlendorf.jpeg" alt="Brian Behlendorf speaking on stage at MozFest" className="mb-2" style={{ "maxWidth": "100%" }} />
                <div className="source text-muted">Mozilla Festival</div>
                <a href="https://medium.com/mozilla-festival/bringing-back-the-decentralized-web-5598408037c7" className="title">Bringing Back the Decentralized Web</a>
                <div className="publish-date text-muted">Feb 23, 2017</div>
                <p className="mt-2">Brian Behlendorf is Executive Director at the Hyperledger Project. He spoke at MozFest 2016 about blockchain.</p>
              </div>
            </div>
            <div className="col-12 col-md-6">
              <div className="news-widget">
                <div className="source text-muted">Mozilla</div>
                <a href="https://internethealthreport.org/v01/" className="title">Internet Health Report</a>
              </div>
              <div className="news-widget my-4">
                <div className="source text-muted">El País</div>
                <a href="http://tecnologia.elpais.com/tecnologia/2016/10/31/actualidad/1477905484_625075.html" className="title">Mozilla planta cara a los ‘trolls’</a>
                <div className="author">by José Mendiola Zuriarrain</div>
                <div className="publish-date text-muted">Nov 3, 2016</div>
              </div>
              <div className="news-widget">
                <div className="source text-muted">CBC</div>
                <a href="http://www.cbc.ca/radio/spark/internet-health-as-a-social-issue-1.4053202" className="title">Internet health as a social issue</a>
                <div className="author">by Nora Young</div>
                <div className="publish-date text-muted">April 9, 2017</div>
              </div>
            </div>
          </div>
        </div>
        <div className="white-background">
          <div className="centered content wide">
          <h1>Join us at MozFest!</h1>
          <p>Sign up for MozFest updates and be first in line to get your tickets for 2017.</p>
          <a href="https://ti.to/Mozilla/mozfest-2017" className="btn btn-arrow"><span>Sign Up</span></a>
          </div>
        </div>
        <Footer />
      </div>
    );
  }
});

module.exports = Home;

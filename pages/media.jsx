var React = require('react');
var Header = require('../components/header.jsx');
var Footer = require('../components/footer.jsx');
var Jumbotron = require('../components/jumbotron.jsx');
var generateHelmet = require('../lib/helmet.jsx');
var Link = require("react-router").Link;

const PAST_COVERAGE = [
  { 
    media: `El País`,
    title: `Mozilla planta cara a los ‘trolls’`,
    link: `https://elpais.com/tecnologia/2016/10/31/actualidad/1477905484_625075.html`,
    date: `November 2016`
  },
  { 
    media: `t3n`,
    title: `Wie steht es um die Gesundheit des Internets? Auf Spurensuche beim Mozilla-Festival`,
    link: `http://t3n.de/news/mozilla-festival-internet-gesundheit-761360/3/`,
    date: `November 2016`
  },
  { 
    media: `3D Printing Industry`,
    title: `Exploring the Open Web at MozFest 2016`,
    link: `https://3dprintingindustry.com/news/exploring-open-web-mozfest-2016-97660/`,
    date: `October 2016`
  },
  { 
    media: `American Libraries Magazine`,
    title: `MozFest 2016: Dispatch from a Librarian`,
    link: `https://americanlibrariesmagazine.org/blogs/the-scoop/mozfest-2016-dispatch-from-a-librarian/`,
    date: `November 2016`
  },
  { 
    media: `The Independent`,
    title: `Baroness Kidron interview: ‘Children’s online safety is too vital to leave to Government’`,
    link: `http://www.independent.co.uk/news/people/profiles/baroness-kidron-interview-children-s-online-safety-is-too-vital-to-leave-to-government-9819862.html`,
    date: `October 2014`
  },
  { 
    media: `The Guardian`,
    title: `MozFest 2014: Creative Chaos - in the Best Way Possible`,
    link: `https://www.theguardian.com/info/developer-blog/2014/oct/31/mozfest-2014-creative-chaos-in-the-best-possible-way`,
    date: `October 2014`
  },
  { 
    media: `The Independent`,
    title: `Mozilla’s Lightbeam Tool will Expose who is Looking Over Your Shoulder on the Web `,
    link: `http://www.independent.co.uk/life-style/gadgets-and-tech/news/mozillas-lightbeam-tool-will-expose-who-is-looking-over-your-shoulder-on-the-web-8902269.html`,
    date: `October 2013`
  },
  { 
    media: `Nieman Lab`,
    title: `Lessons from #Mozfest: How the Knight and Mozilla Foundations are thinking about open source`,
    link: `http://www.niemanlab.org/2011/11/lessons-from-mozfest-how-the-knight-and-mozilla-foundations-are-thinking-about-open-source/`,
    date: `November 2011`
  },
];

var Media = React.createClass({
  renderPastCoverage: function() {
    let pastCoverage = PAST_COVERAGE.map(coverage => {
      return <li key={coverage.title} className="mb-4">
              <div className="media">{coverage.media}</div>
              <a href={coverage.link} target="_blank">{coverage.title}</a> <small>{coverage.date}</small>
            </li>
    });

    return <ul className="past-coverage text-left">{pastCoverage}</ul>
  },
  render: function() {
    return (
      <div className="media-page">
        {generateHelmet(this.pageMetaDescription)}
        <Header/>
        <Jumbotron image="/assets/images/hero/media.jpg"
                  image2x="/assets/images/hero/media.jpg">
          <h1>MozFest Media Center</h1>
        </Jumbotron>
        <div className="white-background">
          <div className="centered content wide">
            <h1>MozFest Media Center</h1>
            <p>Welcome to the MozFest Media Center, where you can find information and resources for your coverage of the annual festival.</p>
            <p>To join the MozFest press list; to request tickets, information and photos; or to arrange an interview with Mozilla executive director Mark Surman, contact Corey Nord at corey(at)pkpr.com. Please send the following information: first and last name, title, name of media outlet, contact phone number, and email address.</p>
            <p>You can also stay up-to-date on MozFest news via <a href="https://twitter.com/mozillafestival" target="_blank">Twitter</a>, <a href="https://www.flickr.com/photos/mozfest/" target="_blank">Flickr</a>, and <a href="https://medium.com/mozilla-festival" target="_blank">Medium</a>.</p>

            <h1>Past MozFest Coverage</h1>
            { this.renderPastCoverage() }
          </div>    
        </div>
        <Footer/>
      </div>
    );
  }
});

module.exports = Media;

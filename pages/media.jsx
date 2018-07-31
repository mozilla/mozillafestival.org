import React from 'react';
import Jumbotron from '../components/jumbotron.jsx';
import generateHelmet from '../lib/helmet.jsx';

const PAST_COVERAGE = [
  {
    media: `Wired UK`,
    title: `One unhappy year in Trump's America`,
    link: `http://www.wired.co.uk/article/upvote-17-one-unhappy-year-in-trumps-america`,
    date: `November 2017`
  },
  {
    media: `Campaign`,
    title: `Mozilla encourages people to 'reduce digital footprint' with pop-up`,
    link: `https://www.campaignlive.co.uk/article/mozilla-encourages-people-reduce-digital-footprint-pop-up/1448234`,
    date: `October 2017`
  },
  {
    media: `Alphr`,
    title: `The Glass Room: Uncover the secrets of your online life in Mozilla’s new exhibition`,
    link: `http://www.alphr.com/art/1007513/the-glass-room-uncover-the-secrets-of-your-online-life-in-mozilla-s-new-exhibition`,
    date: `October 2017`
  },
  {
    media: `The Culture Trip`,
    title: `Artists, Activists and Open Web Advocates Are Gathering at This London Festival for Internet Health `,
    link: `https://theculturetrip.com/europe/united-kingdom/england/london/articles/artists-activists-and-open-web-advocates-are-gathering-at-this-london-festival-for-internet-health/`,
    date: `October 2017`
  },
  {
    media: `Marketplace`,
    title: `Why is the internet so sick?`,
    link: `https://www.marketplace.org/2017/10/30/world/why-internet-so-sick`,
    date: `October 2017`
  },
  {
    media: `Heise`,
    title: `MozFest 2017: Festival für ein besseres Internet`,
    link: `https://www.heise.de/ix/meldung/MozFest-2017-Festival-fuer-ein-besseres-Internet-3863398.html`,
    date: `October 2017`
  },
  {
    media: `IFEX`,
    title: `Anything but neutral: A digital rights Q&A`,
    link: `https://www.ifex.org/international/2017/09/29/digital-rights-defenders/`,
    date: `September 2017`
  },
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
  }
];

var Media = React.createClass({
  renderPastCoverage: function() {
    let pastCoverage = PAST_COVERAGE.map(coverage => {
      return <li key={coverage.title} className="mb-4">
        <div className="media">{coverage.media}</div>
        <a href={coverage.link} target="_blank">{coverage.title}</a> <small>{coverage.date}</small>
      </li>;
    });

    return <ul className="past-coverage text-left mt-3 pl-0">{pastCoverage}</ul>;
  },
  render: function() {
    return (
      <div className="media-page">
        {generateHelmet(this.pageMetaDescription)}
        <Jumbotron image="/assets/images/hero/media.jpg"
          image2x="/assets/images/hero/media.jpg">
          <h1>MozFest Media Center</h1>
        </Jumbotron>
        <div className="white-background">
          <div className="centered content wide">
            <h1>MozFest Media Center</h1>
            <div className="text-left">
              <p>Welcome to the MozFest Media Center, where you can find information and resources for your coverage of the annual festival.</p>
              <p>To receive press credentials to attend and cover MozFest; to request information and photos; or to arrange an interview with Mozilla executive director Mark Surman, contact Corey Nord at corey(at)pkpr.com. Please send the following information:</p>
              <ul>
                <li>first and last name</li>
                <li>title</li>
                <li>name of media outlet</li>
                <li>contact phone number</li>
                <li>email address</li>
                <li>any specific coverage requests (camera crew, photographer, interviews, etc.)</li>
              </ul>
              <p>You can also stay up-to-date on MozFest news via <a href="https://twitter.com/mozillafestival" target="_blank">Twitter</a>, <a href="https://www.flickr.com/photos/mozfest/" target="_blank">Flickr</a>, and <a href="https://medium.com/mozilla-festival" target="_blank">Medium</a>.</p>
            </div>
          </div>
          <div className="centered content wide mt-5">
            <h1>Past MozFest Coverage</h1>
            { this.renderPastCoverage() }
          </div>
        </div>
      </div>
    );
  }
});

module.exports = Media;

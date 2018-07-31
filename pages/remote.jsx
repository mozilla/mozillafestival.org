var React = require('react');
var Header = require('../components/header.jsx');
var Footer = require('../components/footer.jsx');

import Jumbotron from '../components/jumbotron.jsx';
import generateHelmet from '../lib/helmet.jsx';

var FullWidth = require('../components/fullwidth.jsx');
var HalfColumn = require('../components/halfcolumn.jsx');
var ImageText = require('../components/imagetext.jsx');
var Page = require('../components/page.jsx');


var Expect = React.createClass({
  pageMetaDescription: "This year's MozFest Remote Challenge wants you to get involved and spread the MozFest spirit around the world, help others help you, and work locally, while sharing globally.",
  render: function() {
    return (
      <Page name="remote">
        {generateHelmet(this.pageMetaDescription)}

        <Header/>

        <Jumbotron image="/assets/images/remote.jpg"
          image2x="/assets/images/remote.jpg">
          <h1>Remote Participation</h1>
        </Jumbotron>

        <FullWidth white center>
          <div className="confined-width-header text-center">
            <h1>The MozFest Remote Challenge</h1>
          </div>
          <p>
            This year's MozFest Remote Challenge wants you to get involved and spread the MozFest spirit around the world, help others help you, and work locally, while sharing globally.
          </p>
        </FullWidth>

        <hr/>

        <FullWidth white>
          <h1>What's the MozFest spirit?</h1>
          <ImageText width={240} src1x="/assets/images/mozfest-flag.svg" alt="mozfest flag" right>
            <p>
              Part lab, part playground, MozFest is an event like no other. It's a convening of diverse leaders and learners dedicated to building and safeguarding the Open Web. It’s a conduit to collaboration near and far. Its spirit is one of boundless, generous cooperation in service to people, freedom, and a Web that helps protect them both. Be a part of this unique community experience wherever you are by tackling and sharing any of this year's remote challenges and by joining the MozFest conversation in-person or online. Help shape the future of the Web!
            </p>
          </ImageText>
        </FullWidth>

        <FullWidth center>
          <HalfColumn>
            <h1>Projects</h1>
            <ul>
              <li><a href="https://d157rqmxrxj6ey.cloudfront.net/chadsansing/9412/">Hello, MozFest Postcard</a></li>
              <li><a href="https://d157rqmxrxj6ey.cloudfront.net/chadsansing/8541/">Test @ the Fest</a></li>
              <li><a href="https://d157rqmxrxj6ey.cloudfront.net/chadsansing/7293/">Wild About MozFest</a></li>
              <li><a href="https://d157rqmxrxj6ey.cloudfront.net/chadsansing/7978/">Wish You Were Here Postcard</a></li>
              <li><a href="https://blog.webmaker.org/help-us-get-local-with-web-literacy">L10n Curriculum Localization Campaign</a></li>
            </ul>
          </HalfColumn>
          <HalfColumn>
            <h1>Conversations</h1>
            <ul>
              <li><a href="https://air.mozilla.org/">Air Mozilla</a></li>
              <li><a href="https://twitter.com/hashtag/mozfest">#MozFest Hashtag</a></li>
              <li><a href="https://twitter.com/mozfesttimeline">Digital memories on @mozfesttimeline</a></li>
              <li><a href="https://discourse.webmaker.org/c/events/mozfest">MozFest Discourse threads</a></li>
              <li><a href="https://twitter.com/hashtag/mozfestlive">Twitter chat with @MozTeach at #mozfestlive</a></li>
            </ul>
          </HalfColumn>
        </FullWidth>

        <FullWidth white>
          <h1>How can MozFest help me?</h1>
          <ImageText width={225} src1x="/assets/images/cycle-tech.svg" alt="technological cycle" left>
            <p>
              Work open. Share every step of the way. Ask others to test your work and make it better based on their reports and results. Use remote challenges like Test @ the Fest and channels like #mozfestlive and @mozfesttimeline to put your work out there in the Mozilla community. Whether you show up in London or online, you'll find Mozillians willing to help and lend a hand with whatever you're working on right now.
            </p>
          </ImageText>
        </FullWidth>

        <FullWidth center>
          <h1>Work Local. Share Global.</h1>
          <HalfColumn ragged>
            <p>
              MozFest is about making a difference wherever you're local (to borrow an idea from <a href="https://www.ted.com/talks/taiye_selasi_don_t_ask_where_i_m_from_ask_where_i_m_a_local">Taiye Selasi</a>). We come together to meet and work alongside others who want to make an impact on the Web and in their local communities. We want to know how your experience of local informs your work to build the Open Web and we want you to go home saying that you are "MozFest local," too.
            </p>
          </HalfColumn>
          <HalfColumn ragged>
            <p>
              There are as many ways to meet this year's Remote Challenge as there are champions like you. You might lead a group of friends at the kitchen table through a <a href="https://thimble.mozilla.org">Thimble remix</a>, join a project at MozFest from your part of the world, or remix or localize the projects below. You might follow along online or start your own online project. Check out the opportunities above and get local at MozFest from wherever you live!
            </p>
          </HalfColumn>
        </FullWidth>

        <Footer/>
      </Page>
    );
  }
});

module.exports = Expect;


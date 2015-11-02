var React = require('react');
var Header = require('../components/header.jsx');
var Footer = require('../components/footer.jsx');
var HeroUnit = require('../components/hero-unit.jsx');
var ImageTag = require('../components/imagetag.jsx');
var generateHelmet = require('../lib/helmet.jsx');

var FullWidth = React.createClass({
  render: function() {
    var className = "content wide";
    if (this.props.center) { className += " centered"; }
    var content = <div className={className}>{ this.props.children }</div>;
    if (this.props.white) {
      content = <div className="white-background">{ content }</div>;
    }
    return content;
  }
});

var HalfColumn = React.createClass({
  render: function() {
    var className = "half-content";
    if (this.props.ragged) { className += " ragged"; }
    return <div className={className}>{this.props.children}</div>;
  }
});

var ImageText = React.createClass({
  render: function() {
    var imagetag = <ImageTag src1x={this.props.src1x} width={this.props.width||null} height={this.props.height||null} alt={this.props.alt}/>;
    var img = <div className="illustration-image-container">{imagetag}</div>;
    var text = <div className="illustration-text">{ this.props.children }</div>;
    var className = "illustration" + (this.props.right ? " flip-it": "");
    return <div className={className}>{img}{text}</div>;
  }
});

var Page = React.createClass({
  render: function() {
    return <div className={this.props.name + "-page"}>{ this.props.children }</div>;
  }
});

var Expect = React.createClass({
  pageMetaDescription: "This year's MozFest Remote Challenge wants you to get involved and spread the MozFest spirit around the world, help others help you, and work locally, while sharing globally.",
  render: function() {
    return (
      <Page name="remote">
        {generateHelmet(this.pageMetaDescription)}

        <Header/>

        <HeroUnit image="/assets/images/remote.jpg"
                  image2x="/assets/images/remote.jpg">
          Remote Participation
        </HeroUnit>

        <FullWidth white center>
          <h1 className="remote-header">The MozFest Remote Challenge</h1>
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
              <li><a href="">L10n Curriculum Localization Campaign</a></li>
            </ul>
          </HalfColumn>
          <HalfColumn>
            <h1>Conversations</h1>
            <ul>
              <li><a href="https://air.mozilla.org/">Air Mozilla</a></li>
              <li><a href="https://twitter.com/hashtag/mozfest">#MozFest Hashtag </a></li>
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
              There are as many ways to meet this year's Remote Challenge as there are champions like you. You might lead a group of friends at the kitchen table through a Thimble remix, join a project at MozFest from your part of the world, or remix or localize the projects below. You might follow along online or start your own online project. Check out the opportunities above and get local at MozFest from wherever you live!
            </p>
          </HalfColumn>
        </FullWidth>

        <Footer/>
      </Page>
    );
  }
});

module.exports = Expect;


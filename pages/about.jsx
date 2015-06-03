var React = require('react');
var Header = require('../components/header.jsx');
var Footer = require('../components/footer.jsx');
var HeroUnit = require('../components/hero-unit.jsx');

var TimeLine = React.createClass({
  render: function() {
    return (
      <div>

      </div>
    );
  }
});

var TimeLineContent = React.createClass({
  render: function() {
    return (
      <div>

      </div>
    );
  }
});

var TimeLineImage = React.createClass({
  render: function() {
    return (
      <div>

      </div>
    );
  }
});

var TimeLineItem = React.createClass({
  render: function() {
    return (
      <div>

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
          <TimeLine>
            <TimeLineItem time="2015">
              <TimeLineImage></TimeLineImage>
              <TimeLineContent header="header">
                hm hm hm
              </TimeLineContent>
            </TimeLineItem>
            <TimeLineItem time="2014"></TimeLineItem>
            <TimeLineItem time="2013"></TimeLineItem>
            <TimeLineItem time="2012"></TimeLineItem>
            <TimeLineItem time="2011"></TimeLineItem>
            <TimeLineItem time="2010"></TimeLineItem>
          </TimeLine>
        </div>
        <Footer/>
      </div>
    );
  }
});

module.exports = About;


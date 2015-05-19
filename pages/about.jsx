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

var About = React.createClass({
  render: function() {
    return (
      <div className="about-page">
        <Header/>
        <HeroUnit/>
        <div className="content centered wide">
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


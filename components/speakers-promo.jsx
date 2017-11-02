var React = require('react');
var SpeakerTinyCard = require('../components/speaker-tiny-card.jsx');

var SpeakersPromo = React.createClass({
  propTypes: {
    speakersInfo: React.PropTypes.array.isRequired
  },
  render: function() {
    let groups = [];
    let tempGroup = [];

    this.props.speakersInfo.forEach((talk) => {
      talk.speakers.forEach((speaker) => {
        let theSpeaker = ( <SpeakerTinyCard {...speaker} link={talk.videoLink} key={speaker.name} /> );

        tempGroup.push({
          key: speaker.name,
          component: theSpeaker
        });
        if (tempGroup.length === 4) {
          groups.push({
            key: `the ${tempGroup[0].key} row`,
            component: tempGroup
          });
          tempGroup = [];
        }
      });
    });

    // don't forget to include leftover items, if any
    if (tempGroup.length !== 0) {
      groups.push({
        key: `the ${tempGroup[0].key} row`,
        component: tempGroup
      });
      tempGroup = [];
    }

    return (
      <div className="speakers-promo">
        {
          groups.map( (group) => {
            return (
              <div className="row" key={group.key}>
                {
                  group.component.map((speaker) => {
                    return <div className="col-xs-12 col-md-3" key={speaker.key}>{speaker.component}</div>;
                  })
                }
              </div>
            );
          })
        }
      </div>
    );
  }
});

module.exports = SpeakersPromo;

var React = require('react');
var TabSwitcher = require('../components/tab-switcher.jsx');
var Jumbotron = require('../components/jumbotron.jsx');
var SpeakerSeriesTalk = require('../components/speaker-series-talk.jsx');
var SPEAKERS_2016 = require('../talks/2016');
var SPEAKERS_2017 = require('../talks/2017');
var SPEAKERS_2018 = require('../talks/2018');

var SpeakersPage = React.createClass({
  renderTalk: function(talks) {
    return talks.map( talk => <SpeakerSeriesTalk {...talk} key={talk.name || talk.speakers[0].name} /> );
  },
  render: function() {
    return (
      <div className="team-page">
        <Jumbotron image="/assets/images/hero/team.jpg"
                  image2x="/assets/images/hero/team.jpg">
          <h1>Speakers</h1>
        </Jumbotron>
        <div className="content wide mt-0">
          <TabSwitcher baseURL={`/speakers/`} initialTab={this.props.match.params.tab} ref="tabSwitcher" className="pull-up">
            
            {/*<div name="2018" data-slug="2018">
              <h1>2018</h1>
              <div className="horizontal-rule"></div>
              { this.renderTalk(SPEAKERS_2018) }
            </div>*/}
            
            <div name="2017" data-slug="2017">
              <h1>2017</h1>
              <div className="horizontal-rule"></div>
              { this.renderTalk(SPEAKERS_2017) }
            </div>
            
            <div name="2016" data-slug="2016">
              <h1>2016</h1>
              <div className="horizontal-rule"></div>
              { this.renderTalk(SPEAKERS_2016) }
            </div>

          </TabSwitcher>
        </div>
      </div>
    );
  }
});

module.exports = SpeakersPage;


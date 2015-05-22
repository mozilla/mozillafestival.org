var React = require('react');
var Header = require('../components/header.jsx');
var Footer = require('../components/footer.jsx');
var ImageTag = require('../components/imagetag.jsx');

var accessToken = 'pk.eyJ1IjoiYWxpY29kaW5nIiwiYSI6Il90WlNFdE0ifQ.QGGdXGA_2QH-6ujyZE2oSg';

var Location = React.createClass({
  componentDidMount: function() {
    var self = this;
    /*var head = document.getElementsByTagName('head')[0];
    [
      'https://api.tiles.mapbox.com/mapbox.js/v2.1.5/mapbox.css'
    ].filter(function(url) {
      return !document.querySelector('link[href="' + url + '"]');
    }).forEach(function(url) {
      var link = document.createElement('link');
      link.setAttribute('href', url);
      link.setAttribute('rel', 'stylesheet');
      head.appendChild(link);
    });*/
    require([
      // These will automatically attach to the window object.
      'mapbox.js'
    ], function() {
      var pinLocation = [51.501, 0.00575];
      var popupLocation = [51.5025, 0.00575];
      var viewLocation = [51.506, 0.00575];
      L.mapbox.accessToken = accessToken;
      var map = L.mapbox.map(self.refs.map.getDOMNode(), 'mapbox.run-bike-hike');

      var popup = L.popup({
        closeButton: false,
        closeOnClick: false,
        minWidth: 226
      })
      .setLatLng(popupLocation)
      .setContent(
        '<div class="mapbox-popup">' +
          '<p>Ravensbourne</p>' +
          '<img height="154" width="207" src="/assets/images/482_Ravensbourne_400.jpg"/>' +
          '<p>61 Penrose Way</p>' +
          '<p>Greenwich Peninsula</p>' +
          '<p>London SE10 0EW</p>' +
          '<a href="http://www.ravensbourne.ac.uk/">ravensbourne.ac.uk</a>' +
          '<p>+44 20 3040 3500</p>' +
        '</div>'
      )
      .openOn(map);

      map.setView(viewLocation, 15);

      var marker = L.marker(pinLocation, {
        icon: L.mapbox.marker.icon({
            'marker-size': 'large',
            'marker-color': '#BD4A5F'
        })
      }).addTo(map);
      marker.bindPopup(popup);
    });
  },
  render: function() {
    return (
      <div className="location-page">
        <Header/>
        <div className="map-box-container" ref="map"></div>
        <div className="white-background">
          <div className="centered content wide">
            <h1>Main Festival Venue</h1>
            <p>This year, Mozfest returns to <a href="http://www.ravensbourne.ac.uk/">Ravensbourne</a>, a media campus in the heart of London. Located right next to the O2 arena and North Greenwich tube station, Ravensbourne's nine floors of open work spaces, breakout rooms and cozy corners are ideal for collaboration and creative working.</p>
            <div className="horizontal-rule"></div>
            <h1>How to get there</h1>
            <div className="illustration">
              <div className="illustration-text small">
                <h2>London Underground</h2>
                <p>Take the Jubilee Line to North Greenwich (Zone 2).</p>
                <p>
                  5 minutes from Canary Wharf<br/>
                  10 minutes from London Bridge<br/>
                  15 minutes from Waterloo<br/>
                  20 minutes from Bond Street<br/>
                </p>
                <p>The Jubilee line is the only Underground route that connects with all others – so not only you can reach us easily, but National Rail passengers from all over the UK can, too. </p>
                <p>It is a two minute walk from North Greenwich underground station to Ravensbourne. Upon exiting the station, please follow signs to The O2 and follow the covered walkway to The O2's main entrance. Before you reach the main entrance to The O2, turn right into Penrose Way and Ravensbourne’s entrance is on your right. </p>
              </div>
              <div className="illustration-image-container">
                <ImageTag src1x="/assets/images/img-train.svg"
                  height="219" width="214"
                  alt="train icon"/>
              </div>
            </div>
          </div>
        </div>
        <div className="centered content wide">
          <div className="illustration">
            <div className="illustration-image-container">
              <ImageTag src1x="/assets/images/img-bus.svg"
                height="219" width="274"
                alt="train icon"/>
            </div>
            <div className="illustration-text small">
              <h2>Bus</h2>
              <p>Eight TfL bus routes operate to and from North Greenwich including three 24 hour bus services. Key destinations include Stratford, Charlton, Greenwich, Lewisham, Woolwich, Eltham, North Kent and Central London. Please visit <a href="http://www.tfl.gov.uk/">www.tfl.gov.uk</a> for timetable information.</p>
              <h2>Docklands Light Railway (DLR)</h2>
              <p>Just one stop via the Jubilee line from Canary Wharf or Canning Town.</p>
              <h2>Thames Clipper</h2>
              <p>We are a two minute walk from the North Greenwich Pier stop on the Thames Clipper route. Please visit the <a href="http://www.thamesclippers.com/">Thames Clipper website</a> for routes, fares and journey times.</p>
            </div>
          </div>
        </div>
        <div className="white-background">
          <div className="centered content wide">
            <div className="illustration">
              <div className="illustration-text small">
                <h2>Eurostar</h2>
                <p>The current network takes the Eurostar into King’s Cross St. Pancras Station, from where you only need to jump on the Northern Line down to London Bridge where you can then change for the Jubilee Line. </p>
                <h2>National Rail</h2>
                <p>Charlton mainline station is just a short ride on either a 486, 472 or 161 bus from North Greenwich underground station. All these buses start their route from North Greenwich underground so there is no confusion as to which way to go.</p>
                <p>Southeastern runs services to Charlton train station. Turn left out of the station and catch 486, 472 or N472 buses to North Greenwich.</p>
              </div>
              <div className="illustration-image-container">
                <ImageTag src1x="/assets/images/img-ship.svg"
                  height="218" width="286"
                  alt="train icon"/>
              </div>
            </div>
            <div className="horizontal-rule"></div>
            <div className="illustration">
              <div className="illustration-image-container">
                <ImageTag src1x="/assets/images/img-lightbulb.svg"
                  height="252" width="240"
                  alt="train icon"/>
              </div>
              <div className="illustration-text">
                <h1>Travel Tips & Tricks</h1>
                <p>Passport, local currency, device charger and socks...there are some items you should never leave home without! Check out these <a href="https://wiki.mozilla.org/Travel_Guide">comprehensive travel tips</a> from seasoned Mozilla travelers.</p>
              </div>
            </div>
          </div>
        </div>
        <Footer/>
      </div>
    );
  }
});

module.exports = Location;

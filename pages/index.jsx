var React = require('react');

var Index = React.createClass({
  render: function() {
    return (
      <html lang="en">
      <head>
        <meta charSet="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="description" content="Three days of building a truly global web together. Come with an idea, leave with a community." />
        <meta property="og:type" content="website" />
        <meta property="og:url" content="https://2015.mozillafestival.org" />
        <meta property="og:title" content="Mozilla Festival 2015" />
        <meta property="og:description" content="Three days of building a truly global web together. Come with an idea, leave with a community." />
        <meta property="og:image" content="https://2015.mozillafestival.org/assets/images/site-thumbnail.png" />
        <title>MozFest 2015</title>
        <link rel="icon" type="image/png" sizes="32x32" href="assets/images/favicon/favicon.png" />
        <link rel="icon" type="image/png" sizes="180x180" href="assets/images/favicon/apple-touch-icon-180x180.png" />
        <link rel="apple-touch-icon" type="image/png" sizes="180x180" href="assets/images/favicon/apple-touch-icon-180x180.png" />
        <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Open+Sans:800,600,400,300" />
        <link rel="stylesheet" type="text/css" href="index.css" />
        <link rel="stylesheet" type="text/css" href='https://api.tiles.mapbox.com/mapbox.js/v2.1.5/mapbox.css' />
        <link rel="stylesheet" type="text/css" href="build/bundle.css" />
      </head>
      <body>
        <div id="my-app" dangerouslySetInnerHTML={{__html: this.props.markup}}></div>
        <script src="/build/main.js"></script>
      </body>
      </html>
    );
  }
});

module.exports = Index;

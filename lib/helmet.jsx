var React = require('react');
var Helmet = require('react-helmet');

module.exports = function (description) {
  return  <Helmet  
            meta={[
              {name: "description", content: description},
              {property: "og:description", content: description}
            ]}
          />;
}


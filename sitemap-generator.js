var sm = require("sitemap"),
    paths = require('./routes.js').paths;

var PATH_TO_SKIP = [
  "/proposals-2015"
];

paths = paths.filter(function(path) {
  return (PATH_TO_SKIP.indexOf(path) == -1);
});

var Sitemap = sm.createSitemap({
  hostname: 'https://2015.mozillafestival.org',
  cacheTime: 60*1000,
  urls: paths.map(function(path) {
    return {
      url: path,
      changefreq: 'weekly', // default for all pages
      priority: '0.5' // default for all pages
    }
  })
});

Sitemap.toXML( function(xml){ console.log(xml) });

module.exports = Sitemap;

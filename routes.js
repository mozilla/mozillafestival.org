var routesMeta = [
  { name: 'home', path: '/', handler: require('./pages/home.jsx')},
  { name: 'proposals', path: '/proposals', handler: require('./pages/proposals.jsx')},
  { name: 'proposals-extended', path: '/proposals-2015', handler: require('./pages/proposals-extended.jsx')},
  { name: 'tickets', path: '/tickets', handler: require('./pages/tickets.jsx')},
  { name: 'location', path: '/location', handler: require('./pages/location.jsx')},
  { name: 'about', path: '/about', handler: require('./pages/about.jsx')},
  { name: 'contact', path: '/contact', handler: require('./pages/contact.jsx')},
  { name: 'expect', path: '/expect', handler: require('./pages/expect.jsx')},
  { name: 'guidelines', path: '/guidelines', handler: require('./pages/guidelines.jsx')},
  { name: 'fringe-events', path: '/fringe-events', handler: require('./pages/fringe-events.jsx')},
  { name: 'volunteer', path: '/volunteer', handler: require('./pages/volunteer.jsx')},
  { name: 'session-add-success', path: '/session-add-success', handler: require('./pages/session-add-success.jsx')}
];

var paths = routesMeta.map(function(routeMeta) {
  return routeMeta.path;
});

module.exports = {
  routesMeta: routesMeta,
  paths: paths
};

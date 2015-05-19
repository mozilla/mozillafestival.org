var express = require('express'),
    Habitat = require('habitat'),
    path = require('path');

Habitat.load();

var app = express(),
  env = new Habitat();

app.configure(function() {
  app.use(express.static(__dirname + '/public'));
});

app.get('*', function (request, response) {
  response.sendfile(path.join(__dirname, '/public/index.html'));
});

app.listen(env.get('PORT'), function () {
  console.log('Server listening ( http://localhost:%d )', env.get('PORT'));
});

var express = require('express'),
    Habitat = require('habitat'),
    path = require('path'),
    request = require('request'),
    bodyParser = require('body-parser');

Habitat.load();

var app = express(),
  env = new Habitat();

app.configure(function() {
  app.use(express.static(__dirname + '/public'));
  app.use(bodyParser.json());
  app.use(function(err, req, res, next) {
    res.send(err);
  });
});

app.post('/add-session', function (req, res) {
  var sessionName = req.body.name;
  // Request from API
  request({
    method: 'POST',
    url: "http://scotttest2015.sched.org/api/session/add" +
      "?session_key=" + sessionName +
      "&name=" + sessionName +
      "&api_key=" + env.get("SCHED_KEY")
      "&session_type=2015-mozfest-session" +
      "&session_start=15-01-01" +
      "&session_end=15-01-01",
    headers: {
      'User-Agent': '2015-mozfest'
    }
  }, function(err, other) {
    if (err) {
      res.status(500).send({ error: err });
    } else {
      request({
        method: 'POST',
        url: "http://scotttest2015.sched.org/api/session/mod" +
              "?session_key=" + sessionName +
              "&active=N" +
              "&api_key=eaa8736ab6aeba0c90f5c84b3f4b8dc5",
        headers: {
          'User-Agent': '2015-mozfest'
        }
      }, function(err, other) {
        if (err) {
          res.status(500).send({ error: err });
        } else {
          res.send('Ok');
        }
      });
    }
  });
});

app.get('*', function (request, response) {
  response.sendfile(path.join(__dirname, '/public/index.html'));
});

app.listen(env.get('PORT'), function () {
  console.log('Server listening ( http://localhost:%d )', env.get('PORT'));
});

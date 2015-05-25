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
  var sessionName = req.body.sessionName;
  var firstName = req.body.firstName;
  var surname = req.body.surname;
  var email = req.body.email;
  var organization = req.body.organization;
  var twitter = req.body.twitter;
  var otherFacilitators = req.body.otherFacilitators;
  var description = req.body.description;
  var agenda = req.body.agenda;
  var participants = req.body.participants;
  var outcome = req.body.outcome;
  request({
    method: 'POST',
    url: "http://scotttest2015.sched.org/api/session/add" +
      "?session_key=" + sessionName +
      "&name=" + sessionName +
      "&api_key=" + env.get("SCHED_KEY") +
      "&session_type=2015-mozfest-session" +
      "&session_start=15-01-01" +
      "&session_end=15-01-01" +
      "&description=" + description +
      "&firstName=" + firstName +
      "&surname=" + surname +
      "&email=" + email +
      "&organization=" + organization +
      "&twitter=" + twitter +
      "&otherFacilitators=" + otherFacilitators +
      "&agenda=" + agenda +
      "&participants=" + participants +
      "&outcome=" + outcome,
    headers: {
      'User-Agent': '2015-mozfest'
    }
  }, function(err, other) {
      res.status(500).send({ error: err || other.body });
    if (err || other.body !== "Ok") {
      res.status(500).send({ error: err || other.body });
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
        if (err || other.body !== "Ok") {
          res.status(500).send({ error: err || other.body });
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

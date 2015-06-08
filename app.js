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
  var theme = req.body.theme;
  var mode = req.body.mode;
  var audience = req.body.audience;
  var schedEndpoint = env.get("SCHED_ENDPOINT");
  request({
    method: 'POST',
    url: schedEndpoint + "/api/session/add",
    form: {
      session_key: sessionName,
      name: sessionName,
      api_key: env.get("SCHED_KEY"),
      venue: "2015-mozfest-session",
      session_type: theme,
      tags: theme + "," + mode + "," + audience,
      session_start: "15-01-01",
      session_end: "15-01-01",
      description: description,
      active: "N",
      // These are all custom fields.
      firstName: firstName,
      surname: surname,
      email: email,
      organization: organization,
      twitter: twitter,
      otherFacilitators: otherFacilitators,
      agenda: agenda,
      participants: participants,
      outcome: outcome
    },
    headers: {
      'User-Agent': '2015-mozfest'
    }
  }, function(err, other) {
    if (err || other.body !== "Ok") {
      res.send(err || other.body);
    } else {
      request({
        method: 'POST',
        url: "https://docs.google.com/forms/d/1MdPWZ6GsMpDiZnq3qwCfQSJ-7icdCQYGWuHIXrPlO3g/formResponse",
        form: {
          "entry.1997444383": sessionName,
          "entry.1998897375": firstName,
          "entry.2103035832": surname,
          "entry.867181236": email,
          "entry.2119147272": organization,
          "entry.19580374": twitter,
          "entry.1737828681": otherFacilitators,
          "entry.2044069696": description,
          "entry.415053139": agenda,
          "entry.1536930973": participants,
          "entry.70607986": outcome,
          "entry.1933249344": theme,
          "entry.1397401732": mode,
          "entry.91255530": audience
        }
      });
      res.send("Ok");
    }
  });
});

app.get('*', function (request, response) {
  response.sendfile(path.join(__dirname, '/public/index.html'));
});

app.listen(env.get('PORT'), function () {
  console.log('Server listening ( http://localhost:%d )', env.get('PORT'));
});

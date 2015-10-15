var express = require('express'),
    Habitat = require('habitat'),
    path = require('path'),
    request = require('request'),
    bodyParser = require('body-parser'),
    compression = require('compression'),
    RateLimit = require('express-rate-limit');

Habitat.load();

var app = express(),
  env = new Habitat();

app.enable('trust proxy');

var limiter = RateLimit({
  windowMs: 60 * 1000,
  delayMs: 1000,
  max: 5,
  global: false
});

app.configure(function() {
  app.use(compression());
  app.use(express.static(__dirname + '/public', {maxAge: 3600000}));
  app.use(bodyParser.json());
  app.use(function(err, req, res, next) {
    res.send(err);
  });
});

app.post('/add-fringe-event', limiter, function (req, res) {
  console.log("/////////////")
  console.log(req.body);
  request({
    method: 'POST',
    // my temp form
    url: "https://docs.google.com/a/mozillafoundation.org/forms/d/1PPD9q83CYhSdmlWTDWfo1JSHb3dE4Jy0JZ7ZB8yJ8BA/formResponse",
    form: req.body
  }, function(err) {
    if (err) {
      res.status(500).send({error: err});
    } else {
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

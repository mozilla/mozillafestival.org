var express = require('express'),
    Habitat = require('habitat'),
    path = require('path'),
    request = require('request'),
    bodyParser = require('body-parser'),
    compression = require('compression'),
    RateLimit = require('express-rate-limit'),
    GoogleSpreadsheet = require("google-spreadsheet");

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
  var userInputs = {};
  var fringeFormFields = env.get("FRINGE_FORM_FIELD");
  var realFieldNames = {
    name: fringeFormFields.name,
    time: fringeFormFields.time,
    location: fringeFormFields.location,
    description: fringeFormFields.description,
    link: fringeFormFields.link,
    privacy: fringeFormFields.privacy
  };
  Object.keys(req.body).forEach(function(maskedFieldName){
    var realFieldName = realFieldNames[maskedFieldName];
    userInputs[realFieldName] = req.body[maskedFieldName];
  });
  request({
    method: 'POST',
    url: env.get('FRINGE_EVENT_FORM_ACTION_URL'),
    form: userInputs
  }, function(err) {
    if (err) {
      console.log("[Error] ", err);
      res.status(500).send({error: err});
    } else {
      res.send("Ok");
    }
  });
});

app.get('*', function (request, response) {
  if (request.path === "/get-fringe-events") {
    // this fetches data stored in the Fringe Events Google Spreadsheet
    var sheet = new GoogleSpreadsheet(env.get('FRINGE_EVENT_SPREADSHEET_ID'));
    sheet.getRows(1, function(err, rows){
      if (err) {
        console.log("[Error] ", err);
        response.status(500).json(err);
      } else {
        response.send( rows.filter(function(row) {
                        return row.approved;
                      }));
      }
    });
  } else {
    response.sendfile(path.join(__dirname, '/public/index.html'));
  }
});

app.listen(env.get('PORT'), function () {
  console.log('Server listening ( http://localhost:%d )', env.get('PORT'));
});

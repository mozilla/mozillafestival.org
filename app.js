var express = require('express'),
    Habitat = require('habitat'),
    path = require('path'),
    request = require('request'),
    bodyParser = require('body-parser'),
    compression = require('compression'),
    hatchet = require('hatchet'),
    RateLimit = require('express-rate-limit'),
    GoogleSpreadsheet = require("google-spreadsheet"),
    uuid = require('uuid');

var proposalHandler = require('./lib/proposal-handler');

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

app.post('/add-session', limiter, function (req, res) {
  var formUrl = env.get("PROPOSAL_FORM_ACTION_URL");
  var proposalUUID = uuid.v4();
  var userInputs = {
    firstName: req.body.firstName,
    surname: req.body.surname,
    email: req.body.email,
    affiliationOrganization: req.body.affiliationOrganization,
    otherFacilitators: req.body.otherFacilitators,
    twitter: req.body.twitter,
    space: req.body.space,

    exhibitTitle: req.body.exhibitTitle,
    exhibitMethod: req.body.exhibitMethod,
    exhibitLink: req.body.exhibitLink,
    exhibitDescription: req.body.exhibitDescription,
    exhibitLearnReflect: req.body.exhibitLearnReflect,
    exhibitWhyGoodMozfest: req.body.exhibitWhyGoodMozfest,
    exhibitAnotherSpace: req.body.exhibitAnotherSpace,

    sessionTitle: req.body.sessionTitle,
    descWorkBest: req.body.descWorkBest,
    descMakeLearn: req.body.descMakeLearn,
    descHowWorking: req.body.descHowWorking,
    descParticipants: req.body.descParticipants,
    descOutcome: req.body.descOutcome,
    descAnotherLang: req.body.descAnotherLang,
    descTravel: req.body.descTravel,
    descOtherSpace: req.body.descOtherSpace,
  };

  proposalHandler.submitProposalToGoogleSheet(formUrl, userInputs, proposalUUID, function(err) {
    if (err) {
      res.status(500).send({error: err});
    } else {
      proposalHandler.postToGithub(env, userInputs, proposalUUID, function(githubErr) {
        if (githubErr) {
          res.status(500).send({error: githubErr});
        } else {
          res.send("OK");
        }
      });
    }
  });
});

/* ********************
* temporarily disable posting to /add-fringe-event 
* leaving code here so we can quickly turn these pages back on in June and September
******************** */

// app.post('/add-fringe-event', limiter, function (req, res) {
//   var userInputs = {};
//   var fringeFormFields = env.get("FRINGE_FORM_FIELD");
//   var realFieldNames = {
//     name: fringeFormFields.name,
//     time: fringeFormFields.time,
//     location: fringeFormFields.location,
//     description: fringeFormFields.description,
//     link: fringeFormFields.link,
//     privacy: fringeFormFields.privacy
//   };
//   Object.keys(req.body).forEach(function(maskedFieldName){
//     var realFieldName = realFieldNames[maskedFieldName];
//     userInputs[realFieldName] = req.body[maskedFieldName];
//   });
//   request({
//     method: 'POST',
//     url: env.get('FRINGE_EVENT_FORM_ACTION_URL'),
//     form: userInputs
//   }, function(err) {
//     if (err) {
//       console.log("[Error] ", err);
//       res.status(500).send({error: err});
//     } else {
//       res.send("Ok");
//     }
//   });
// });

app.get('*', function (request, response) {
  response.sendfile(path.join(__dirname, '/public/index.html'));

  /* ********************
  * temporarily disable the following
  * leaving code here so we can quickly turn these pages back on in June and September
  ******************** */

  // if (request.path === "/get-fringe-events") {
  //   // this fetches data stored in the Fringe Events Google Spreadsheet
  //   var sheet = new GoogleSpreadsheet(env.get('FRINGE_EVENT_SPREADSHEET_ID'));
  //   sheet.getRows(1, function(err, rows){
  //     if (err) {
  //       console.log("[Error] ", err);
  //       response.status(500).json(err);
  //     } else {
  //       response.send( rows.filter(function(row) {
  //                       return row.approved;
  //                     }));
  //     }
  //   });
  // } else {
  //   response.sendfile(path.join(__dirname, '/public/index.html'));
  // }
});

app.listen(env.get('PORT'), function () {
  console.log('Server listening ( http://localhost:%d )', env.get('PORT'));
});

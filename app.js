var express = require('express'),
    Habitat = require('habitat'),
    path = require('path'),
    request = require('request'),
    bodyParser = require('body-parser'),
    compression = require('compression'),
    hatchet = require('hatchet'),
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

app.post('/add-session', limiter, function (req, res) {
  var firstName = req.body.firstName;
  var surname = req.body.surname;
  var email = req.body.email;
  var affiliationOrganization = req.body.affiliationOrganization;
  var otherFacilitators = req.body.otherFacilitators;
  var twitter = req.body.twitter;
  var space = req.body.space;

  var exhibitTitle = req.body.exhibitTitle;
  var exhibitMethod = req.body.exhibitMethod;
  var exhibitLink = req.body.exhibitLink;
  var exhibitDescription = req.body.exhibitDescription;
  var exhibitLearnReflect = req.body.exhibitLearnReflect;
  var exhibitWhyGoodMozfest = req.body.exhibitWhyGoodMozfest;
  var exhibitAnotherSpace = req.body.exhibitAnotherSpace;

  var descWorkBest = req.body.descWorkBest;
  var descMakeLearn = req.body.descMakeLearn;
  var descHowWorking = req.body.descHowWorking;
  var descParticipants = req.body.descParticipants;
  var descOutcome = req.body.descOutcome;
  var descAnotherLang = req.body.descAnotherLang;
  var descTravel = req.body.descTravel;
  var descOtherSpace = req.body.descOtherSpace;

  request({
    method: 'POST',
    url: "https://docs.google.com/forms/d/1E0-DnkmwsXLJNwe5l5S56-cGuKxjkpgYJhI5iXvuEZg/formResponse",
    form: {
      "entry.1690539558": firstName,
      "entry.261675776": surname,
      "entry.218044069": email,
      "entry.1795974313": affiliationOrganization,
      "entry.23778947": otherFacilitators,
      "entry.744026452": twitter,
      "entry.1083667921": space,

      "entry.2071720042": exhibitTitle,
      "entry.112655350": exhibitMethod,
      "entry.2017683266": exhibitLink,
      "entry.1475043467": exhibitDescription,
      "entry.221338538": exhibitLearnReflect,
      "entry.1161931220": exhibitWhyGoodMozfest,
      "entry.881836083": exhibitAnotherSpace,

      "entry.2012896072": descWorkBest,
      "entry.742790242": descMakeLearn,
      "entry.1229027759": descHowWorking,
      "entry.644649977": descParticipants,
      "entry.1907976042": descOutcome,
      "entry.1376483821": descAnotherLang,
      "entry.928325971": descTravel,
      "entry.2085186584": descOtherSpace
    }
  }, function(err) {
    if (err) {
      res.status(500).send({error: err});
    } else {
      /*hatchet.send("mozfest_session_proposal", {
        email: email
      }, function(err, data) {
        if (err) {
          console.error("Error sending email: " + err);
        } else {
          console.log("we sent a message!");
        }
      });*/
      res.send("Ok");
    }
  });
  res.send("Ok");
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

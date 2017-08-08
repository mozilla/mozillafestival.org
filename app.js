var express = require('express'),
    Habitat = require('habitat'),
    path = require('path'),
    request = require('request'),
    bodyParser = require('body-parser'),
    compression = require('compression'),
    RateLimit = require('express-rate-limit'),
    GoogleSpreadsheet = require("google-spreadsheet"),
    proposalHandler = require('./lib/proposal-handler');

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
  app.use(function(req, res, next) {
    // redirect path with trailing slash, e.g., /path/ to  "/path"
    // since there's no easy way for React Router v0.13 to get both cases working
    // once we upgrade React Router to v1+ we won't need this temp fix anymore
    // see https://github.com/ReactTraining/react-router/issues/820 for more info
    if ( req.path[req.path.length-1] === '/' ) {
      res.redirect(req.path.substr(0,req.path.length-1));
    } else {
      next();
    }
  });
});

app.post(`/add-proposal`, limiter, function(req, res) {
  // line breaks are essential for the private key.
  // if reading this private key from env var this extra replace step is a MUST
  var GOOGLE_API_CRED = {
    email: env.get(`GOOGLE_API_CLIENT_EMAIL_2017`),
    key: env.get(`GOOGLE_API_PRIVATE_KEY_2017`).replace(/\\n/g, `\n`)
  };
  var SPREADSHEET_ID = env.get(`PROPOSAL_SPREADSHEET_ID_2017`);

  proposalHandler.postToSpreadsheet(req.body, SPREADSHEET_ID, GOOGLE_API_CRED, (err, proposal) => {
    if (err) res.status(500).json(err);

    proposalHandler.postToGithub({
      token: env.get(`GITHUB_BOT_TOKEN_2017`),
      owner: env.get(`GITHUB_REPO_OWNER_2017`),
      repo: env.get(`GITHUB_REPO_NAME_2017`)
    }, proposal, (githubErr, issueNum) => {
      if (githubErr) res.status(500).json(githubErr);

      var rowData = { uuid: proposal.uuid, githubissuenumber: issueNum};
      proposalHandler.updateSpreadsheetRow(rowData, SPREADSHEET_ID, GOOGLE_API_CRED, (updateError) => {
        // if (updateErr) res.status(500).json(updateErr);


        res.send(`Success!`);
      });
    });
  });
});

app.post('/add-fringe-event', limiter, function (req, res) {
  var SPREADSHEET_ID = env.get(`FRINGE_EVENT_SPREADSHEET_ID_2017`);
  var sheet = new GoogleSpreadsheet(SPREADSHEET_ID);
  var fringeEvent = req.body;

  // line breaks are essential for the private key.
  // if reading this private key from env var this extra replace step is a MUST
  sheet.useServiceAccountAuth({
    "client_email": env.get(`GOOGLE_API_CLIENT_EMAIL_2017`),
    "private_key": env.get(`GOOGLE_API_PRIVATE_KEY_2017`).replace(/\\n/g, `\n`)
  }, (err) => {
    if (err) {
      console.log(`[Error] ${err}`);
      res.status(500).json(err);
    }

    sheet.addRow(1, fringeEvent, (addRowErr) => {
      if (addRowErr) {
        console.log(`[addRowErr]`, addRowErr);
        res.status(500).json(addRowErr);
      }

      res.status(200).send({ result: `Fringe event submitted!`});
    });
  });
});


function getFringeEvents(response) {
  // fetches data stored in the Fringe Events Google Spreadsheet
  var sheet = new GoogleSpreadsheet(env.get(`FRINGE_EVENT_SPREADSHEET_ID_2017`));

  // line breaks are essential for the private key.
  // if reading this private key from env var this extra replace step is a MUST
  sheet.useServiceAccountAuth({
    "client_email": env.get(`GOOGLE_API_CLIENT_EMAIL_2017`),
    "private_key": env.get(`GOOGLE_API_PRIVATE_KEY_2017`).replace(/\\n/g, `\n`)
  }, function(err) {
    if (err) {
      console.log(`[Error] ${err}`);
      response.status(500).json(err);
    }
    // GoogleSpreadsheet.getRows(worksheet_id, callback)
    sheet.getRows(1, function(sheetErr, rows) {
      if (sheetErr) {
        console.log("[Error] ", sheetErr);
        response.status(500).json(sheetErr);
      } else {
        response.send(rows.filter(function(row) {
          return row.approved;
        }));
      }
    });
  });
}

app.get('*', function (request, response) {
  if (request.path === "/get-fringe-events") {
    getFringeEvents(response);
  } else {
    response.sendfile(path.join(__dirname, '/public/index.html'));
  }
});

app.listen(env.get(`PORT`), () => {
  console.log(`\n*******************************************`);
  console.log(`*                                         *`);
  console.log(`*  MozFest listening on port ${env.get(`PORT`)}         *`);
  console.log(`*                                         *`);
  console.log(`*******************************************\n`);
});

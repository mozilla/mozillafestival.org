import express from "express";
import Habitat from "habitat";
import path from "path";
import bodyParser from "body-parser";
import compression from "compression";
import RateLimit from "express-rate-limit";
import { Helmet as ReactHelmet } from "react-helmet";
import GoogleSpreadsheet from "google-spreadsheet";
import proposalHandler from "./lib/proposal-handler";

import React from 'react';
import { renderToString } from 'react-dom/server';
import { StaticRouter } from 'react-router';
import Main from './main.jsx';

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

app.use(compression());
app.use(express.static(path.resolve(__dirname, `public`)));
app.use(bodyParser.json());

app.post(`/add-proposal`, limiter, (req, res) => {
  // line breaks are essential for the private key.
  // if reading this private key from env var this extra replace step is a MUST
  var GOOGLE_API_CRED = {
    email: env.get(`GOOGLE_API_CLIENT_EMAIL_2018`),
    key: env.get(`GOOGLE_API_PRIVATE_KEY_2018`).replace(/\\n/g, `\n`)
  };
  var SPREADSHEET_ID = env.get(`PROPOSAL_SPREADSHEET_ID_2018`);

  proposalHandler.postToSpreadsheet(req.body, SPREADSHEET_ID, GOOGLE_API_CRED, (err, proposal) => {
    if (err) res.status(500).json(err);

    proposalHandler.postToGithub({
      token: env.get(`GITHUB_BOT_TOKEN_2018`),
      owner: env.get(`GITHUB_REPO_OWNER_2018`),
      repo: env.get(`GITHUB_REPO_NAME_2018`)
    }, proposal, (githubErr, issueNum) => {
      if (githubErr) {
        res.status(500).json(githubErr);
      } else {
        var rowData = { uuid: proposal.uuid, githubissuenumber: issueNum};
        proposalHandler.updateSpreadsheetRow(rowData, SPREADSHEET_ID, GOOGLE_API_CRED, (updateError) => {
          if (updateError) res.status(500).json(updateError);

          res.send(`Success!`);
        });
      }
    });
  });
});

app.post('/add-fringe-event', limiter, (req, res) => {
  var SPREADSHEET_ID = env.get(`FRINGE_EVENT_SPREADSHEET_ID_2018`);
  var sheet = new GoogleSpreadsheet(SPREADSHEET_ID);
  var fringeEvent = req.body;

  // line breaks are essential for the private key.
  // if reading this private key from env var this extra replace step is a MUST
  sheet.useServiceAccountAuth({
    "client_email": env.get(`GOOGLE_API_CLIENT_EMAIL_2018`),
    "private_key": env.get(`GOOGLE_API_PRIVATE_KEY_2018`).replace(/\\n/g, `\n`)
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
  var sheet = new GoogleSpreadsheet(env.get(`FRINGE_EVENT_SPREADSHEET_ID_2018`));

  // line breaks are essential for the private key.
  // if reading this private key from env var this extra replace step is a MUST
  sheet.useServiceAccountAuth({
    "client_email": env.get(`GOOGLE_API_CLIENT_EMAIL_2018`),
    "private_key": env.get(`GOOGLE_API_PRIVATE_KEY_2018`).replace(/\\n/g, `\n`)
  }, (err) => {
    if (err) {
      console.log(`[Error] ${err}`);
      response.status(500).json(err);
    }
    // GoogleSpreadsheet.getRows(worksheet_id, callback)
    sheet.getRows(1, (sheetErr, rows) => {
      if (sheetErr) {
        console.log("[Error] ", sheetErr);
        response.status(500).json(sheetErr);
      } else {
        let approvedRows = rows.filter(row => {
          let approved = row.approved.toLowerCase().trim();
          return approved === `y` || approved === `yes`;
        }).map(row => {
          // don't expose contact email
          delete row.contactemail;
          return row;
        });

        response.send(approvedRows);
      }
    });
  });
}

function getHouseEvents(response) {
  // fetches data stored in the Google Spreadsheet
  var sheet = new GoogleSpreadsheet(env.get(`HOUSE_EVENT_SPREADSHEET_ID_2018`));

  // line breaks are essential for the private key.
  // if reading this private key from env var this extra replace step is a MUST
  sheet.useServiceAccountAuth({
    "client_email": env.get(`GOOGLE_API_CLIENT_EMAIL_2018`),
    "private_key": env.get(`GOOGLE_API_PRIVATE_KEY_2018`).replace(/\\n/g, `\n`)
  }, (err) => {
    if (err) {
      console.log(`[Error] ${err}`);
      response.status(500).json(err);
    }
    // GoogleSpreadsheet.getRows(worksheet_id, callback)
    sheet.getRows(1, (sheetErr, rows) => {
      if (sheetErr) {
        console.log("[Error] ", sheetErr);
        response.status(500).json(sheetErr);
      } else {
        let approvedRows = rows.filter(row => {
          let showOnSite = row.addtowebsite.toLowerCase().trim();
          return showOnSite === `y` || showOnSite === `yes`;
        });
        response.send(approvedRows);
      }
    });
  });
}

app.get(`*`, (req, res) => {
  const reactHelmet = ReactHelmet.renderStatic();
  const requestPath = req.path;

  const context = {}; // context object contains the results of the render
  const appHtml = renderToString(
    <StaticRouter location={req.url} context={context}>
      <Main />
    </StaticRouter>
  );

  if (context.url) {
    // Somewhere a `<Redirect>` was rendered
    res.redirect(301, context.url);
  } else if (requestPath === "/get-fringe-events") {
    getFringeEvents(res);
  } else if (requestPath === "/get-house-events") {
    getHouseEvents(res);
  } else {
    res.status(context.pageNotFound ? 404 : 200).send(renderPage(appHtml, reactHelmet));
  }
});

function renderPage(appHtml, reactHelmet) {
  if (!appHtml) {
    appHtml = `<!-- When user's browser does not allow any scripts to run, we show the following instead of a blank page. -->
                  <div class="please-allow-javascript-notice px-4 py-2 text-center" style="background: #e4f832;">
                    <p class="m-0">This website relies on JavaScript, please make sure to allow mozillafestival.org in your script blocker.</p>
                  </div>
                  <div class="home-page">
                    <div class="page-header">
                      <div class="header-content">
                        <div class="nav-home">
                          <img src="/assets/images/Mozilla-Festival-2018.svg" alt="mozfest logo">
                        </div>
                      </div>
                    </div>
                    <div class="jumbotron-container pb-0" style="height: 100%;">
                      <div class="jumbotron m-0" style="background-image: url(&quot;/assets/images/hero/home/banner-home_5.jpg&quot;); background-size: cover; padding-top: 150px; padding-bottom: 180px; height: 100%;">
                        <div class="jumbotron-content">
                          <h1>MozFest</h1>
                          <h2>The world's leading festival for the open Internet movement.</h2>
                          <div class="horizontal-rule"></div>
                          <p>October 27-29, 2017 Ravensbourne College, London</p>
                          <a href="https://vimeo.com/205552025/37560e3619" class="btn p-3 m-3">Watch Video</a>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- "no JS is allowed" scenario handling ends. -->`;
  }

  return `<!DOCTYPE html>
          <html lang="en">
            <head>
              <meta charset="UTF-8">
              <meta name="viewport" content="width=device-width, initial-scale=1">
              ${reactHelmet.title.toString()}
              ${reactHelmet.meta.toString()}
              <link rel="icon" type="image/png" sizes="32x32" href="/assets/images/favicon/favicon.png">
              <link rel="icon" type="image/png" sizes="152x152" href="/assets/images/favicon/apple-touch-icon-152x152@2x.png" />
              <link rel="apple-touch-icon" type="image/png" sizes="76x76" href="/asset/images/apple-touch-icon-76x76@2x.png" />
              <link rel="apple-touch-icon" type="image/png" sizes="120x120" href="/assets/images/favicon/apple-touch-icon-120x120@2x.png" />
              <link rel="apple-touch-icon" type="image/png" sizes="152x152" href="/assets/images/favicon/apple-touch-icon-152x152@2x.png" />
              <link rel="stylesheet" href="https://code.cdn.mozilla.net/fonts/fira.css">
              <link href="https://fonts.googleapis.com/css?family=Zilla+Slab|Zilla+Slab+Highlight" rel="stylesheet">
              <link rel="stylesheet" type="text/css" href="/vendor/css/font-awesome.min.css">
              <link rel="stylesheet" type="text/css" href="/vendor/css/mofo-bootstrap.css"/>
              <link rel="stylesheet" type="text/css" href="/build/style.css">
              <link rel="stylesheet" type="text/css" href='https://api.tiles.mapbox.com/mapbox.js/v2.1.5/mapbox.css'>
              <script>
                (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
                })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

                ga('create', 'UA-35433268-1', 'auto');
                ga('send', 'pageview');
              </script>
            </head>
            <body>
              <div id="app">${appHtml}</div>
              <div class="widgets" style="display: none;">
                <!-- <div class="tito-volunteer-tickets">
                  <tito-button event="Mozilla/mozfest-volunteers" releases="rixmuxc3owq">Volunteer</tito-button>
                </div> -->
              </div>
              <script src="/build/bundle.js"></script>
            </body>
          </html>`;
}

app.listen(env.get(`PORT`), () => {
  console.log(`\n*******************************************`);
  console.log(`*                                         *`);
  console.log(`*  MozFest listening on port ${env.get(`PORT`)}         *`);
  console.log(`*                                         *`);
  console.log(`*******************************************\n`);
});

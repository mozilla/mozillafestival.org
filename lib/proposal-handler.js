var GoogleSpreadsheet = require(`google-spreadsheet`);
var moment = require(`moment-timezone`);
var uuid = require(`uuid`);
var request = require(`request`);
var _ = require(`underscore`);
var path = require(`path`);
var fs = require(`fs`);
var hatchet = require(`hatchet`);
var EnglishStrings = require('../pages/proposals/language/english.json');

var postToSpreadsheet = function(proposal, spreadsheetId, googleApiCred, callback) {
  // make sure keys are all lowercase and contain no symbols or spaces
  // reference: https://www.npmjs.com/package/google-spreadsheet#row-based-api-limitations
  proposal = Object.assign(proposal, {
    uuid: uuid.v4(),
    githubissuenumber: ``,
    timestamp: moment().tz(`Europe/London`).format(`MMM DD, YYYY, h:mm:ssa zz`) // (e.g., May 29, 2017, 8:51:14pm BST)
  });

  var l10nlanguage = proposal.l10nlanguage;

  if (Array.isArray(l10nlanguage)) {
    proposal.l10nlanguage = l10nlanguage.join(`, `);
  }

  var sheet = new GoogleSpreadsheet(spreadsheetId);

  sheet.useServiceAccountAuth({
    "client_email": googleApiCred.email,
    "private_key": googleApiCred.key
  }, (err) => {
    if (err) {
      console.log(`[Error] ${err}`);
      callback(err);
    }

    sheet.addRow(1, proposal, (addRowErr) => {
      if (addRowErr) {
        console.log(`[addRowErr]`, addRowErr);
        callback(err);
      }

      callback(null, proposal);
    });
  });
};

var updateSpreadsheetRow = function(data, spreadsheetId, googleApiCred, callback) {
  var sheet = new GoogleSpreadsheet(spreadsheetId);

  sheet.useServiceAccountAuth({
    "client_email": googleApiCred.email,
    "private_key": googleApiCred.key
  }, (err) => {
    if (err) {
      console.log(`[Error] ${err}`);
      callback(err);
    }

    // GoogleSpreadsheet.getRows(worksheet_id, callback)
    sheet.getRows(1, (getRowError, rows) => {
      if (getRowError) {
        console.log(`[getRowError]`, getRowError);
        // we don't need to report this getRowError to user
        // i.e., this shouldn't result in a status 500 to user
        // therefore no need to call callback(getRowError) here
      }

      for (var i = 0; i<rows.length; i++) {
        if (rows[i].uuid === data.uuid) {
          rows[i].githubissuenumber = data.githubissuenumber;
          rows[i].save();
          break;
        }
      }

      callback();
    });
  });
};

function getGithubMilestoneId(spaceName, githubInfo, callback) {
  // IMPORTANT: make sure milestones are created on the repo
  //            otherwise GitHub won't be able to retrieve milestone id of it

  var milestoneId = ``;
  var options = {
    method: `GET`,
    url: `https://api.github.com/repos/${githubInfo.owner}/${githubInfo.repo}/milestones`,
    headers: {
      Accept: `application/vnd.github.v3+json`,
      "User-Agent": `MozFest 2018 proposal`
    },
    json: true
  };

  request(
    options,
    (err, response, body) => {
      if (err) {
        console.log(err);
        callback(err);
      } else if (response.statusCode !== 200 && response.statusCode !== 201) {
        console.log(`[Error getting Github milestones] Response status HTTP ${response.statusCode}, Github error message: ${response.body.message}`);
        callback(`Error`);
      } else {
        for (var i = 0; i<body.length; i++) {
          if (body[i].title === spaceName) {
            milestoneId = body[i].number;
            break;
          }
        }
        callback(null, milestoneId);
      }
    }
  );
}

// format GitHub issue so only the fields of our choices are included
function formatGithubIssue(proposal, githubInfo, callback) {
  const L10NLANGUAGE = EnglishStrings.form_field_options.l10nlanguage;

  var pathToTemplate = path.normalize(__dirname + `/../proposal-ticket-template.md`);
  var template = _.template(fs.readFileSync(pathToTemplate,`utf-8`));
  var milestoneId = ``;
  var labels = [];

  proposal.proposallanguage = ``;

  if (proposal.secondaryspace) {
    labels.push(`[Secondary Space] ${proposal.secondaryspace}`);
  }

  if (proposal.proposallanguage !== `English` ) {
    // we don't need a label for "English" if the proposal was written in English
    labels.push(proposal.proposallanguage);
  }

  if (proposal.format) {
    labels.push(`[Format] ${proposal.format}`);
  }

  if (proposal.travelstipend) {
    labels.push(`Stipend requested`);
  }

  // Simplifying language label
  // so it's either "[LANG] one_of_predefined_lang" OR "[LANG] Other"
  if (proposal.l10nlanguage) {
    let l10lang = `Other`;

    for (let langKey in L10NLANGUAGE) {
      if (!L10NLANGUAGE.hasOwnProperty(langKey)) continue;

      if (proposal.l10nlanguage === L10NLANGUAGE[langKey]) {
        l10lang = proposal.l10nlanguage;

        break;
      }
    }

    labels.push(`[LANG] ${l10lang}`);
  }

  if (!! proposal.l10nsupport && proposal.l10nsupport !== EnglishStrings.form_field_options.l10nsupport.no) {
    labels.push(`Localisation support requested`);
  }

  getGithubMilestoneId(proposal.space, githubInfo, (err, id) => {
    if (!err) {
      milestoneId = id;
    }

    let createFacilitatorObj = (fullProposal, index) =>{
      var facilitator = {};

      if (fullProposal[`otherfacilitator${index}firstname`] || fullProposal[`otherfacilitator${index}surname`]) {
        facilitator.name = (fullProposal[`otherfacilitator${index}firstname`] || ``) + ` ` + (fullProposal[`otherfacilitator${index}surname`] || ``);
        facilitator.name = facilitator.name.trim();
      }

      if (fullProposal[`otherfacilitator${index}githubhandle`]) {
        facilitator.githubhandle = fullProposal[`otherfacilitator${index}githubhandle`];
      }

      return facilitator;
    };

    var metaInBody = {
      id: proposal.uuid,
      sessionName: proposal.sessionname,
      primarySpace: proposal.space,
      submitterName: `${proposal.firstname} ${proposal.surname}`,
      submitterOrg: proposal.organisation,
      submitterGithub: proposal.githubhandle,
      // additionalFacilitators: proposal.otherfacilitators,
      otherFacilitator1: createFacilitatorObj(proposal, 1),
      otherFacilitator2: createFacilitatorObj(proposal, 2),
      otherFacilitator3: createFacilitatorObj(proposal, 3),
      l10nlanguage: proposal.l10nlanguage,
      l10nsupport: proposal.l10nsupport,
      description: proposal.description,
      outcome: proposal.outcome,
      needs: proposal.needs,
      timeNeeded: proposal.timeneeded
    };

    if (proposal.secondaryspace) {
      metaInBody.secondarySpace = proposal.secondaryspace;
    }

    var issue = {
      title: proposal.sessionname,
      labels: labels.filter(label => !!label),
      body: template(metaInBody)
    };

    if (milestoneId) {
      issue.milestone = milestoneId;
    }

    callback(issue);
  });
}

function sendConfirmationEmail(proposal, githubIssue) {

  var localeMap = {
    "English": "en_US",
    "German": "de",
    "French": "fr",
    "Spanish": "es"
  };
  var issueTitle = githubIssue.title;

  hatchet.send(`mozfest_session_proposal_2017`, {
    "locale": localeMap[proposal.proposallanguage] || localeMap.English,
    "email": proposal.email,
    "first_name": proposal.firstname,
    "github_issue_url": githubIssue.html_url,
    "github_issue_title": issueTitle
  }, (err) => {
    if (err) {
      console.log(`Failed to send proposal confirmation email for ${issueTitle}! Error: ${err}`);
    } else {
      console.log(`Proposal confirmation email successfully sent for ${issueTitle}!`);
    }
  });
}

var postToGithub = function(githubInfo, proposal, callback) {
  formatGithubIssue(proposal, githubInfo, (formattedIssue) => {
    var options = {
      method: `POST`,
      url: `https://api.github.com/repos/${githubInfo.owner}/${githubInfo.repo}/issues`,
      body: formattedIssue,
      headers: {
        Accept: `application/vnd.github.v3+json`,
        "User-Agent": `MozFest 2018 proposal`,
        Authorization: `token ${githubInfo.token}`
      },
      json: true
    };

    // Post issue to GitHub
    request(
      options,
      (err, response, body) => {
        if (err) {
          console.log(`[Error posting to Github] `, err);
          callback(err);
        } else if (response.statusCode !== 200 && response.statusCode !== 201) {
          console.log(`[Error posting to Github] Response status HTTP ${response.statusCode}, Github error message: ${response.body.message}`, response.body.errors);
          callback({});
        } else {
          console.log(`Successfully posted to GitHub: Issue #${body.number}. '${body.title}'`);
          // send email confirmation with link to GitHub issue
          sendConfirmationEmail(proposal, body);
          callback(null, body.number);
        }
      }
    );
  });
};

module.exports = {
  postToSpreadsheet: postToSpreadsheet,
  postToGithub: postToGithub,
  updateSpreadsheetRow: updateSpreadsheetRow
};

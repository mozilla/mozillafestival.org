var GoogleSpreadsheet = require(`google-spreadsheet`);
var moment = require(`moment-timezone`);
var uuid = require(`uuid`);
var request = require(`request`);
var _ = require(`underscore`);
var path = require(`path`);
var fs = require(`fs`);
var hatchet = require(`hatchet`);

var postToSpreadsheet = function(proposal, spreadsheetId, googleApiCred, callback) {
  // make sure keys are all lowercase and contain no symbols or spaces
  // reference: https://www.npmjs.com/package/google-spreadsheet#row-based-api-limitations
  proposal = Object.assign(proposal, {
    uuid: uuid.v4(),
    githubissuenumber: ``,
    timestamp: moment().tz(`Europe/London`).format(`MMM DD, YYYY, h:mm:ssa zz`) // (e.g., May 29, 2017, 8:51:14pm BST)
  });

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
  var milestoneId = ``;
  var options = {
    method: `GET`,
    url: `https://api.github.com/repos/${githubInfo.owner}/${githubInfo.repo}/milestones`,
    headers: {
      Accept: `application/vnd.github.v3+json`,
      "User-Agent": `MozFest 2017 proposal`
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
  var pathToTemplate = path.normalize(__dirname + `/../issue-template.md`);
  var template = _.template(fs.readFileSync(pathToTemplate,`utf-8`));
  var milestoneId = ``;
  var labels = [];

  if (proposal.secondaryspace) {
    labels.push(proposal.secondaryspace);
  }

  if (proposal.additionallanguage) {
    labels.push(proposal.additionallanguage);
  }

  if (proposal.travelstipend) {
    labels.push(`Stipend requested`);
  }

  getGithubMilestoneId(proposal.space, githubInfo, (err, id) => {
    if (!err) {
      milestoneId = id;
    }

    var issue = {
      title: proposal.name,
      milestone: milestoneId,
      labels: labels,
      body: template({
        id: proposal.uuid,
        submitterName: `${proposal.firstname} ${proposal.surname}`,
        submitterOrg: proposal.organisation,
        submitterGithub: proposal.githubhandle,
        additionalFacilitators: proposal.otherfacilitators,
        description: proposal.description,
        outcome: proposal.outcome,
        needs: proposal.needs,
        timeNeeded: proposal.timeneeded
      })
    };

    callback(issue);

  });
}

function sendConfirmationEmail(proposal, githubIssue) {
  hatchet.send(`mozfest_session_proposal_2017`, {
    "email": proposal.email,
    "first_name": proposal.firstname,
    "github_issue_url": githubIssue.html_url,
    "github_issue_title": githubIssue.title
  }, (err, response) => {
    if (err) {
      console.log(`Failed to send proposal confirmation email for ${proposal.title}!`);
    } else {
      console.log(`Proposal confirmation email successfully sent for ${proposal.title}!`);
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
        "User-Agent": `MozFest 2017 proposal`,
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
          console.log(`[Error posting to Github] Response status HTTP ${response.statusCode}, Github error message: ${response.body.message}`);
          callback({});
        } else {
          console.log(`Successfully posted to GitHub: Issue #" ${body.number}. '${body.title}'`);
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

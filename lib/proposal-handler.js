var GoogleSpreadsheet = require(`google-spreadsheet`);
var moment = require(`moment-timezone`);
var uuid = require(`uuid`);
var request = require(`request`);
var _ = require(`underscore`);
var path = require(`path`);
var fs = require(`fs`);

let postToSpreadsheet = function(proposal, spreadsheetId, googleApiCred, callback) {
  // make sure keys are all lowercase and contain no symbols or spaces
  // reference: https://www.npmjs.com/package/google-spreadsheet#row-based-api-limitations
  proposal = Object.assign(proposal, {
    uuid: uuid.v4(),
    githubissuenumber: ``,
    timestamp: moment().tz(`Europe/London`).format(`MMM DD, YYYY, h:mm:ssa zz`) // (e.g., May 29, 2017, 8:51:14pm BST)
  });

  let sheet = new GoogleSpreadsheet(spreadsheetId);

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

let updateSpreadsheetRow = function(data, spreadsheetId, googleApiCred, callback) {
  let sheet = new GoogleSpreadsheet(spreadsheetId);

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

      for (let i = 0; i<rows.length; i++) {
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

// format GitHub issue so only the fields of our choices are included
function formatGithubIssue(proposal) {
  var pathToTemplate = path.normalize(__dirname + `/../issue-template.md`);
  var template = _.template(fs.readFileSync(pathToTemplate,`utf-8`));
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

  // create the issue object to be posted to GitHub
  var issue = {
    title: proposal.name,
    labels: labels,
    body: template({
      id: proposal.uuid,
      submitterName: `${proposal.firstname} ${proposal.surname}`,
      submitterOrg: proposal.organisation,
      submitterTwitter: proposal.twitterhandle,
      submitterGithub: proposal.githubhandle,
      additionalFacilitators: proposal.otherfacilitators,
      description: proposal.description,
      outcome: proposal.outcome,
      needs: proposal.needs,
      timeNeeded: proposal.timeneeded
    })
  };

  return issue;
}

let postToGithub = function(githubInfo, proposal, callback) {
  let githubEndpoint = `https://api.github.com/repos/${githubInfo.owner}/${githubInfo.repo}/issues`;
  let issue = formatGithubIssue(proposal);
  let options = {
    method: `POST`,
    url: githubEndpoint,
    body: issue,
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
        console.log("[Error posting to Github] ", err);
        callback(err);
      } else if (response.statusCode != 200 && response.statusCode != 201) {
        console.log("[Error posting to Github] Response status HTTP " + response.statusCode + ", Github error message: " + response.body.message);
        callback({});
      } else {
        console.log("Successfully posted to GitHub: Issue #" + body.number + ", " + body.title);
        // send email confirmation with link to GitHub issue
        // sendConfirmationEmail({
        //   toEmail: userInputs.email,
        //   firstName: userInputs.firstName,
        //   issueUrl: body.html_url,
        //   issueTitle: body.title
        // });
        callback(null, body.number);
      }
    }
  );
};

module.exports = {
  postToSpreadsheet: postToSpreadsheet,
  postToGithub: postToGithub,
  updateSpreadsheetRow: updateSpreadsheetRow
};

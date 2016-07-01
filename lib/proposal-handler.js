var request = require('request'),
    path = require('path'),
    fs = require('fs'),
    _ = require('underscore'),
    hatchet= require('hatchet');


// post proposal to Google Sheet (a Google Form hack)
var submitProposalToGoogleSheet = function (formUrl, userInputs, proposalUUID, callback) {
  var formData = {
    "entry.1303159015": proposalUUID,

    "entry.1690539558": userInputs.firstName,
    "entry.261675776": userInputs.surname,
    "entry.218044069": userInputs.email,
    "entry.1795974313": userInputs.affiliationOrganization,
    "entry.23778947": userInputs.otherFacilitators,
    "entry.744026452": userInputs.twitter,
    "entry.1083667921": userInputs.space,

    "entry.2071720042": userInputs.exhibitTitle,
    "entry.112655350": userInputs.exhibitMethod,
    "entry.2017683266": userInputs.exhibitLink,
    "entry.1475043467": userInputs.exhibitDescription,
    "entry.221338538": userInputs.exhibitLearnReflect,
    "entry.1161931220": userInputs.exhibitWhyGoodMozfest,
    "entry.881836083": userInputs.exhibitAnotherSpace,

    "entry.1193243393": userInputs.sessionTitle,
    "entry.2012896072": userInputs.descWorkBest,
    "entry.742790242": userInputs.descMakeLearn,
    "entry.1229027759": userInputs.descHowWorking,
    "entry.644649977": userInputs.descParticipants,
    "entry.1907976042": userInputs.descOutcome,
    "entry.1376483821": userInputs.descAnotherLang,
    "entry.928325971": userInputs.descTravel,
    "entry.2085186584": userInputs.descOtherSpace
  };

  request({
    method: 'POST',
    url: formUrl,
    form: formData
  }, function(err) {
    if (err) {
      callback(err);
    } else {
      callback(null);
    }
  });
}

// create GitHub issue
var postToGithub = function(env, userInputs, proposalUUID, callback) {
  var githubEndpoint = "https://api.github.com/repos/" + env.get("GITHUB_REPO") + "/issues";
  var issue = formatGithubIssue(userInputs, proposalUUID);
  var options = {
    method: "POST",
    url: githubEndpoint,
    body: issue,
    headers: {
      Accept: "application/vnd.github.v3+json",
      "User-Agent": "MozFest 2016 proposal"
    },
    auth: {
      user: env.get("GITHUB_BOT_USERNAME"),
      pass: env.get("GITHUB_BOT_PASSWORD")
    },
    json: true
  };

  // Post issue to GitHub
  request(
    options, 
    function(err,response,body) {
      if (err) {
        console.log("[Error posting to Github] ", err);
        callback(err);
      } else if (response.statusCode != 200 && response.statusCode != 201) {
        console.log("[Error posting to Github] Response status HTTP " + response.statusCode + ", Github error message: " + response.body.message);
        callback({});
      } else {
        console.log("Successfully posted to GitHub: Issue #" + body.number + ", " + body.title);
        // send email confirmation with link to GitHub issue
        sendConfirmationEmail({
          toEmail: userInputs.email,
          firstName: userInputs.firstName,
          issueUrl: body.html_url,
          issueTitle: body.title
        })
        callback(null);
      }
    }
  );
}

// format GitHub issue so only the fields of our choices are included
function formatGithubIssue(userInputs, proposalUUID) {
  var pathToTemplate = path.normalize(__dirname + '/../issue-template.md');
  var template = _.template(fs.readFileSync(pathToTemplate,'utf-8'));
  var spaceToLabelMap = {
    digital: "Digital arts and culture",
    localisation: "Localisation",
    science: "Open science",
    journalism: "Journalism",
    exhibit: "Moz Ex",
    cities: "A tale of two cities: a dilemma of connected spaces",
    demystify: "Demystify the web",
    badges: "Open badges",
    youth: "Youth zone",
    movement: "Fuel the movement"
  };

  // process secondary space info
  var secondarySpace = null;
  if ( userInputs.descOtherSpace ) { // for Space session proposals
    secondarySpace = userInputs.descOtherSpace.replace("-backup","");
  } else if ( userInputs.exhibitAnotherSpace ) { // for proposals labelled as Moz Ex
    secondarySpace = userInputs.exhibitAnotherSpace.replace("another-","");
  }

  // format GitHub labels
  var labels = [ userInputs.space ];
  if ( secondarySpace && secondarySpace !== "none" ) {
    labels.push(secondarySpace);
  }
  labels = labels.map(function(key) {
    if ( key == 'exhibit' ) {
      return "[Experience] " + spaceToLabelMap[key];
    } else {
      return "[Space] " + spaceToLabelMap[key];
    }
  });

  if (  userInputs.descTravel === 'travel-only' ) {
    labels.push("Stipend requested");
  }

  // create the issue object to be posted to GitHub
  var issue = {
    title: userInputs.exhibitTitle || userInputs.sessionTitle,
    labels: labels,
    body: template({
      id: proposalUUID,
      submitterName: userInputs.firstName + " " + userInputs.surname,
      submitterOrg: userInputs.affiliationOrganization,
      submitterTwitter: userInputs.twitter,

      space: userInputs.space,
      secondarySpace: secondarySpace,

      exhibitMethod: userInputs.exhibitMethod,
      exhibitLink: userInputs.exhibitLink,

      description: userInputs.exhibitDescription || userInputs.descMakeLearn,
      format: userInputs.descWorkBest,
      agenda: userInputs.descHowWorking,

      participants: userInputs.descParticipants,
      outcome: userInputs.descOutcome
    })
  };

  return issue;
};

// TODO:FIXME: clean this up; test this
function sendConfirmationEmail(info) {
  hatchet.send("mozfest_session_proposal_2016", {
    email: info.toEmail,
    first_name: info.firstName,
    github_issue_url: info.issueUrl,
    github_issue_title: info.issueTitle
  }, function(err, response) {
    if (err) {
      console.log("Failed to send proposal confirmation email.")
    } else {
      console.log("Proposal confirmation email successfully sent!")
    }
  });
}

module.exports = {
  submitProposalToGoogleSheet: submitProposalToGoogleSheet,
  postToGithub: postToGithub
}

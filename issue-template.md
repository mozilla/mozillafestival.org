**[ ID ]** <%= id %>

**[ Submitter's Name ]** <%= submitterName %>
<% if (typeof(submitterOrg) !== "undefined") { %>**[ Submitter's Affiliated Organisation ]** <%= submitterOrg %><% } %>
<% if (typeof(submitterTwitter) !== "undefined") { %>**[ Submitter's Twitter ]** <%= submitterTwitter %><% } %>

**[ Space ]** <%= space %>
<% if (secondarySpace !== "none") { %>**[ Secondary Space ]** <%= secondarySpace %><% } %>

<% if (typeof(exhibitMethod) !== "undefined") { %>**[ Exhibit Method ]** <%= exhibitMethod %><% } %>
<% if (typeof(exhibitLink) !== "undefined") { %>**[ Exhibit Link ]** <%= exhibitLink %><% } %>

<% if (typeof(format) !== "undefined") { %>**[ Format ]** <%= format %><% } %>

### Description
<%= description %>

### Agenda
<%= agenda %>

<% if (typeof(participants) !== "undefined") { %>
### Participants
<%= participants %>
<% } %>

<% if (typeof(outcome) !== "undefined") { %>
### Outcome
<%= outcome %>
<% } %>

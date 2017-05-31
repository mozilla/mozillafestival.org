**[ UUID ]** <%= id %>

**[ Submitter's Name ]** <%= submitterName %>
<% if (typeof(submitterOrg) !== "undefined") { %>**[ Submitter's Affiliated Organisation ]** <%= submitterOrg %><% } %>
<% if (typeof(submitterGithub) !== "undefined") { %>**[ Submitter's Github ]** <%= submitterGithub %><% } %>

<% if (typeof(additionalFacilitators) !== "undefined") { %>**[ Additional facilitators ]** <%= additionalFacilitators %><% } %>


### What will happen in your session?
<%= description %>

### What is the goal or outcome of your session?
<%= outcome %>

<% if (typeof(needs) !== "undefined") { %>
### If your session requires additional materials or electronic equipment, please outline your needs.
<%= needs %>
<% } %>

### Time needed
<%= timeNeeded %>

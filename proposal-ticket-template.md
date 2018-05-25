**[ UUID ]** <%= id %>

**[ Session Name ]** <%= sessionName %>
**[ Primary Space ]** <%= primarySpace %>
<% if (typeof(secondarySpace) !== "undefined") { %>**[ Secondary Space ]** <%= secondarySpace %><% } %>

**[ Submitter's Name ]** <%= submitterName %>
<% if (typeof(submitterOrg) !== "undefined") { %>**[ Submitter's Affiliated Organisation ]** <%= submitterOrg %><% } %>
<% if (typeof(submitterGithub) !== "undefined") { %>**[ Submitter's GitHub ]** <%= submitterGithub %><% } %>

<% if (typeof(otherFacilitator1.name) !== "undefined") { %>**[ Other Facilitator 1's Name ]** <%= otherFacilitator1.name %><% } %>
<% if (typeof(otherFacilitator1.githubhandle) !== "undefined") { %>**[ Other Facilitator 1's GitHub ]** <%= otherFacilitator1.githubhandle %><% } %>

<% if (typeof(otherFacilitator2.name) !== "undefined") { %>**[ Other Facilitator 2's Name ]** <%= otherFacilitator2.name %><% } %>
<% if (typeof(otherFacilitator2.githubhandle) !== "undefined") { %>**[ Other Facilitator 2's GitHub ]** <%= otherFacilitator2.githubhandle %><% } %>

<% if (typeof(otherFacilitator3.name) !== "undefined") { %>**[ Other Facilitator 3's Name ]** <%= otherFacilitator3.name %><% } %>
<% if (typeof(otherFacilitator3.githubhandle) !== "undefined") { %>**[ Other Facilitator 3's GitHub ]** <%= otherFacilitator3.githubhandle %><% } %>

<% if (typeof(l10nlanguage) !== "undefined" || typeof(l10nsupport) !== "undefined") { %>
---

<% if (typeof(l10nlanguage) !== "undefined") { %>**[ Language ]** <%= l10nlanguage %><% } %>
<% if (typeof(l10nsupport) !== "undefined") { %>**[ Localisation Support Requested ]** <%= l10nsupport %><% } %>

---
<% } %>

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

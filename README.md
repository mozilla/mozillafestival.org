# mozillafestival.org

The Mozilla Festival website.

## Setup

```
$> npm install
$> cp sample.env .env
```

## To run

```
$> npm start
```

By default this will run the website on [http://localhost:9090](http://localhost:9090)

## Development

Deployment to `staging` and `production` servers is automated via [Heroku](https://heroku.com).

master – Deploys to [http://mozillafestival-org-staging.herokuapp.com/](http://mozillafestival-org-staging.herokuapp.com/)

Production pushes are handled manually. Email [Mozilla Foundation DevOps](mailto:devops@mozillafoundation.org) or an active contributor to this repo to ask about a production push.


## Environment Variables

- `PROPOSAL_SPREADSHEET_ID_2017`
- `GOOGLE_API_CLIENT_EMAIL_2017`
- `GOOGLE_API_PRIVATE_KEY_2017`
- `GITHUB_TOKEN`
- `GITHUB_OWNER`
- `GITHUB_REPO`
- ``

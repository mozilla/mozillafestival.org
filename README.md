[![Build Status](https://travis-ci.org/mozilla/mozillafestival.org.svg?branch=master)](https://travis-ci.org/mozilla/mozillafestival.org)

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

Deployment to `staging` and `production` servers is automated via [Travis-CI](https://travis-ci.org).

develop – Deploys to [http://mozillafestival-org-staging.herokuapp.com/](http://mozillafestival-org-staging.herokuapp.com/)

master – Deploys to [http://mozillafestival-org-production.herokuapp.com/](http://mozillafestival-org-production.herokuapp.com/)

import React from 'react';
import Helmet from 'react-helmet';

export default (description) => {
  const TITLE = `Mozilla Festival (MozFest)`;

  if (!description) {
    description = `Mozilla Festival (MozFest) brings the world together to leave the internet better than we found it. Mozilla, the non-profit behind the Firefox browser, invites you to our annual interactive festival in London, England. Come shape our best future online.`;
  }

  return <Helmet defaultTitle={TITLE}>
    <title>{TITLE}</title>
    <meta name="description" content={description} />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="https://mozillafestival.org" />
    <meta property="og:title" content={TITLE} />
    <meta property="og:description" content={description} />
    <meta property="og:image" content="https://mozillafestival.org/assets/images/site-thumbnail.jpg" />
  </Helmet>;
};


import React from 'react';
import Helmet from 'react-helmet';

const TITLE = `Mozilla Festival (MozFest)`;
const DESCRIPTION = `Mozilla Festival (MozFest) brings the world together to leave the internet better than we found it. Mozilla, the non-profit behind the Firefox browser, invites you to our annual interactive festival in London, England. Come shape our best future online.`;

export default (description = DESCRIPTION, jsonLd) => {
  return <Helmet defaultTitle={TITLE}>
    <title>{TITLE}</title>
    <meta name="description" content={description} />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="https://mozillafestival.org" />
    <meta property="og:title" content={TITLE} />
    <meta property="og:description" content={description} />
    <meta property="og:image" content="https://mozillafestival.org/assets/images/site-thumbnail.jpg" />
    { jsonLd && <script type="application/ld+json">{ JSON.stringify(jsonLd) }</script> }
  </Helmet>;
};


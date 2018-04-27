import React from 'react';
import Helmet from 'react-helmet';

export default (description) => {
  const title = `Mozilla Festival`;

  if (!description) {
    description = `Three days of building a truly global web together. Come with an idea, leave with a community.`;
  }

  return <Helmet defaultTitle={title}>
    <title>{title}</title>
    <meta name="description" content={description} />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="https://mozillafestival.org" />
    <meta property="og:title" content={title} />
    <meta property="og:description" content={description} />
    <meta property="og:image" content="https://mozillafestival.org/assets/images/site-thumbnail.jpg" />
  </Helmet>;
};


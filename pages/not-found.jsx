import React from 'react';
import Header from '../components/header.jsx';
import Footer from '../components/footer.jsx';
import Jumbotron from '../components/jumbotron.jsx';

const NotFound = () => {
  return <div>
    <Header logoImage="/assets/images/mozilla-festival_wordmark-interim_horizontal.svg"/>
    <Jumbotron className="home-jumbotron" image="/assets/images/hero/home/banner-home_5.jpg"
              image2x="/assets/images/hero/home/banner-home_5.jpg">
      <div className="content-wrapper">
      </div>
    </Jumbotron>
    <div className="white-background">
      <div className="centered content wide">
        <p>Page not found!</p>
      </div>
    </div>
    <Footer />
  </div>;
}

export default NotFound;

import React from 'react';
import Jumbotron from '../components/jumbotron.jsx';

class NotFound extends React.Component {
  componentWillMount() {
    const { staticContext } = this.props;
    if (staticContext) {
      staticContext.pageNotFound = true;
    }
  }

  render() {
    return <div>
      <Jumbotron className="home-jumbotron" image="/assets/images/hero/home/banner-home_5.jpg"
        image2x="/assets/images/hero/home/banner-home_5.jpg">
      </Jumbotron>
      <div className="white-background">
        <div className="centered content wide">
          <p>Page not found!</p>
        </div>
      </div>
    </div>;
  }
}

export default NotFound;

import React from 'react';
import Jumbotron from '../components/jumbotron.jsx';
import generateHelmet from '../lib/helmet.jsx';

class SchedulePage extends React.Component {
  constructor(props) {
    super(props);
  }

  render() {
    return (
      <div className="schedule-page">
        {generateHelmet(this.pageMetaDescription)}
        <Jumbotron image="/assets/images/hero/contact.jpg"
          image2x="/assets/images/hero/contact.jpg"
          className="short"
        >
          <h1 className="highlight">Schedule</h1>
        </Jumbotron>
        <div className="white-background">
          <div className="content wide">
            <div className="container-fluid">
              <div className="row text-center">
                <div className="col-12">
                  <h1>MozFest 2018 Schedule</h1>
                </div>
              </div>
              <div className="row text-center">
                <div className="col-12 my-5">
                  <a href="https://guidebook.com/guide/147793/" className="btn btn-arrow"><span>View Online Schedule</span></a>
                </div>
                <div className="col-12 mb-5">
                  <h6>Get the Guidebook App</h6>
                  <a className="mx-1" href="https://itunes.apple.com/us/app/id428713847">
                    <img src="https://s3.amazonaws.com/coverpage.guidebook.com/production/img_app_store_badge.png" srcSet="https://s3.amazonaws.com/coverpage.guidebook.com/production/img_app_store_badge.png 1x, https://s3.amazonaws.com/coverpage.guidebook.com/production/img_app_store_badge@2x.png 2x" alt="Download on the app store" height="40" />
                  </a>
                  <a className="mx-1" href="https://play.google.com/store/apps/details?id=com.guidebook.android">
                    <img src="https://s3.amazonaws.com/coverpage.guidebook.com/production/img_google_play_badge.png" srcSet="https://s3.amazonaws.com/coverpage.guidebook.com/production/img_google_play_badge.png 1x, https://s3.amazonaws.com/coverpage.guidebook.com/production/img_google_play_badge@2x.png 2x" alt="Download on the play store" height="40" />
                  </a>
                </div>
              </div>
              <div className="row">
                <div className="col-12">
                  <p>Celebrate what you love about the web, and discover new ways to make the internet a better, healthier place.</p>

                  <p>This year we have over 300 sessions taking place over MozFest weekend, not to mention the 18 events you can attend at MozFest House.</p>
                  <p> We are using Guidebook as our scheduling tool to share the sessions and bios of facilitators, and to help you decide how to spend your time over the weekend at Ravensbourne.</p>

                  <p>Download the Guidebook app on your phone to view the schedule. Once you download it, search for <strong>Mozilla Festival 2018</strong> in the app and add it to get the schedule. You can also view the <a href="https://guidebook.com/guide/147793/schedule/" target="_blank">schedule online</a>. If you don't wish to use Guidebook, please note that we will be displaying the schedule at info booths on each floor over the festival weekend.</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    );
  }
}

export default SchedulePage;

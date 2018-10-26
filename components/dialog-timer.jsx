import React from 'react';
import PropTypes from 'prop-types';
import classNames from 'classnames';

const DNDTime = new Date('27 Oct 2018 08:00:00 UTC');

class DialogTimer extends React.Component {
  render() {
    let buttonClasses = classNames(`btn`, {
      "btn-link": !this.props.buttonAsCta,
      "btn-video-play": !this.props.buttonAsCta,
      "btn-cta-video-play": this.props.buttonAsCta
    });

    let current = new Date();

    if (current < DNDTime) {
      return (
        <div>
          <p></p>
          <p>Watch the LIVE Stream on October 27, 2018 at 8:00 am UTC.</p>
        </div>
      );
    }

    return (
      <div>
        <button className={buttonClasses}
          onClick={() => this.props.toggleVideoTakeover()}
        >
          { this.props.buttonAsCta &&
            <div>
              Watch the Dialogues and Debates&nbsp;<strong>LIVE</strong><i className="ml-2 fa fa-play-circle-o" aria-hidden="true"></i>
             </div>
          }
        </button>
      </div>
    );

    return dialogsButton;
  }
}

DialogTimer.propTypes = {
  buttonAsCta: PropTypes.bool,
  toggleVideoTakeover: PropTypes.func
};

export default DialogTimer;

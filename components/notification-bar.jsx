import React from 'react';
import classNames from 'classnames';
import PropTypes from 'prop-types';
import { Link } from 'react-router-dom';

class NotificationBar extends React.Component {
  constructor(props) {
    super(props);
  }

  render() {
    let classnames = classNames(`notification-bar p-2 text-center`, this.props.className, { "hyperlinked": this.props.to });
    let barInner = this.props.children;

    if (this.props.to) {
      barInner = <Link to={this.props.to}>
        { this.props.children }
      </Link>;
    }

    return <div className={classnames}>
      { barInner }
    </div>;
  }
}

NotificationBar.propTypes = {
  children: PropTypes.node.isRequired,
  to: PropTypes.string
};

export default NotificationBar;

var React = require('react');
var Router = require('react-router');
var slugify = require('slugify');
var ImageTag = require('./imagetag.jsx');

import { Link } from 'react-router-dom';

var SpacePathwayProfile = React.createClass({
  getDefaultProps: function() {
    return {
      showWranglerLink: true
    };
  },
  render: function() {
    var id = slugify(this.props.name);
    var showWranglerLink = this.props.showWranglerLink;

    return (
      <div className="space-profile" id={id}>
        <div className="detail">
          <div className="header">
            { this.props.iconPath ? <div className="image-container"><ImageTag alt={`${this.props.name} icon`} src1x={this.props.iconPath} width={this.props.iconWidth} /></div>
              : null}
            <h1><a href={"#"+id}>{this.props.name}</a></h1>
          </div>
          { this.props.type ? <div className="type">{this.props.type}</div> : null }
          <div className="description">{this.props.description}</div>
        </div>
        <div className="contacts">
          { this.props.contacts ? <h2 className="mt-4 mt-sm-0">{(this.props.contacts.length > 1) ? this.props.contactTitle + "s" : this.props.contactTitle}</h2>
            : null}
          <ul>
            {
              this.props.contacts ? this.props.contacts.map((contact) => {
                return (
                  <li key={contact.name}>
                    { showWranglerLink ? <Link to={"/team/wranglers#"+slugify(contact.name)}>{contact.name}</Link>
                      : contact.name}
                  </li>
                );
              }) : null
            }
          </ul>
        </div>
      </div>
    );
  }
});

module.exports = SpacePathwayProfile;

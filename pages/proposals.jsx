var React = require('react');
var Header = require('../components/header.jsx');
var Footer = require('../components/footer.jsx');
var HeroUnit = require('../components/hero-unit.jsx');

var Proposals = React.createClass({
  render: function() {
    return (
      <div className="proposals-page">
        <Header/>
        <HeroUnit image="/assets/images/placeholder-image.png"
                  image2x="/assets/images/placeholder-image.png">
          call for proposals
        </HeroUnit>
        <div className="content">
          <form className="proposals-form">
            <h1>Share your idea</h1>
            <p>The Mozilla Festival is designed around three days of peer-led conversations, hands on workshops and skillshares.</p>
            <label>Full Name</label>
            <input type="text"/>
            <label>What's your Twitter handle?</label>
            <input type="text"/>
            <label>What's your session proposal idea?</label>
            <textarea></textarea>
            <label>What kind of community are you looking for?</label>
            <select></select>
            <label>Another question?</label>
            <textarea></textarea>
            <label>Tags - Click up to 7 that apply</label>
            <input type="text" placeholder="Other tags"/>
            <input type="checkbox"/>
            <label className="checkbox-input">By clicking &ldquo;Submit&rdquo; you agree to do lot's of things including let us share your application with the world.</label>
            <button>Submit</button>
          </form>
        </div>
        <Footer/>
      </div>
    );
  }
});

module.exports = Proposals;


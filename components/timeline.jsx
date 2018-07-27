import React from 'react';
import classnames from 'classnames';

var ImageTag = require('../components/imagetag.jsx');

const TimeLineItem = (props) => {
  let className = classnames(props.className, "timeline-item", {
    "flip-it": props.flip
  });

  return (
    <div className={className}>
      <div className="timeline-time-circle">{props.time}</div>
      <div className="timeline-item-container w-100 d-flex justify-content-center">
        {(function() {
          if (!props.flip) {
            return (
              <div className="timeline-image-container d-flex align-items-center justify-content-end">
                <ImageTag src1x={props.image}
                  height={props.imageHeight} width={props.imageWidth}
                  alt={props.alt}/>
              </div>
            );
          }
        })()}
        <div className="timeline-content-container d-flex align-items-center">
          <div>
            <div className="timeline-label">
              {(function() {
                if (!props.flip) {
                  return (
                    <span className="label-line"></span>
                  );
                }
              })()}
              <span className="label">{props.label}</span>
              {(function() {
                if (props.flip) {
                  return (
                    <span className="label-line"></span>
                  );
                }
              })()}
            </div>
            <div className="timeline-content">{props.children}</div>
          </div>
        </div>
        {(function() {
          if (props.flip) {
            return (
              <div className="timeline-image-container d-flex align-items-center">
                <ImageTag src1x={props.image}
                  height={props.imageHeight} width={props.imageWidth}
                  alt={props.alt}/>
              </div>
            );
          }
        })()}
      </div>
    </div>
  );
};

class Timeline extends React.Component {
  render() {
    return (
      <div className="timeline-container">
        <div className="starting-line"></div>
        <TimeLineItem time="2017" label="Internet Health"
          className="timeline-item-2017"
          image="/assets/images/about-page-2017.jpg"
          alt="2017 about image"
          imageHeight={167} imageWidth={240}
        >
          MozFest 2017 was a deep exploration of the bright spots and threats to Internet health. Conversations, prototyping and art-making were framed around the ideas of decentralization, digital inclusion, online privacy and security, open innovation and Web literacy. Young people shone at the festival this year, with an 11-year old speaker on the main stage and youth-led sessions in every space.
        </TimeLineItem>
        <TimeLineItem time="2016" label="inclusion" flip="true"
          className="timeline-item-2016"
          image="/assets/images/img-2016.svg"
          alt="MozFest Flag"
          imageHeight={230} imageWidth={220}
        >
          In 2016, we highlighted how the Internet movement’s boldest, most game-changing ideas can come from anyone, anywhere. We examined how issues like Web literacy, online privacy and encryption are relevant across the globe, and address challenges faced by people who don't yet feel they are welcome on the Web.
        </TimeLineItem>
        <TimeLineItem time="2015" label="training leaders"
          className="timeline-item-2015"
          image="/assets/images/about-1.png"
          alt="2015 about image"
          imageHeight={343} imageWidth={534}
        >
          In 2015, we focused on leadership, advocacy and impact. We placed a particular emphasis on training tomorrow's leaders, empowering participants to make a positive difference on the Web, and working toward universal web literacy.
        </TimeLineItem>
        <TimeLineItem time="2014" label="a free web" flip="true"
          className="timeline-item-2014"
          image="/assets/images/web.svg"
          alt="2014 about image"
          imageHeight={500} imageWidth={680}
        >
          At MozFest 2014, nearly 1,700 participants from
more than 50 countries came together to improve
art, science, journalism, music, education and more
on the open Web. We hosted hundreds of diverse
sessions with a single guiding principle: keeping the
Web wild and free.
        </TimeLineItem>
        <TimeLineItem time="2013" label="hands on learning"
          className="timeline-item-2013"
          image="/assets/images/hands-01.svg"
          alt="2013 about image"
          imageHeight={400} imageWidth={650}
        >
          Learning through building was a key theme at
MozFest 2013. We shared our passion for the open
Web by creating and teaching as a community. And
the venue sprang to life with DIY signage, sessions
and after-parties.
        </TimeLineItem>
        <TimeLineItem time="2012" label="building and making" flip="true"
          className="timeline-item-2012"
          image="/assets/images/about-3.jpg"
          alt="2012 about image"
          imageHeight={218} imageWidth={348}
        >
          In 2012, MozFest was all about making. The event’s
opening-day Science Fair highlighted participants’
innovative creations. And we made and hacked
awesome projects about gaming, mobile, privacy
and the Internet of Things.
        </TimeLineItem>
        <TimeLineItem time="2011" label="settling in london"
          className="timeline-item-2011"
          image="/assets/images/london-01.svg"
          alt="2011 about image"
          imageHeight={400} imageWidth={700}
        >
          In 2011, MozFest relocated to London with a
sharpened focus: media, freedom and the Web.
Participants lent their passion and creativity to
improve journalism and digital storytelling on the
open Web. We established a dedicated space for
youngsters to learn and make. And we built on the
infectious community spirit ﬁrst ignited in
Barcelona.
        </TimeLineItem>
        <TimeLineItem time="2010" label="the beginning" flip="true"
          className="timeline-item-2010"
          image="/assets/images/about-2.png"
          alt="2010 about image"
          imageHeight={280} imageWidth={420}
        >
          MozFest was born in Barcelona. Originally named
"Drumbeat," the festival convened a community of
people dedicated to learning, freedom and the
open Web. The inaugural event hosted 350
participants — and together, we wrote a book titled
“Learning Freedom and the Web.
        </TimeLineItem>
        <div className="content centered">
          <div className="final-circle">
            <div className="inner-circle">
              <div className="inner-circle-1"></div>
              <div className="inner-circle-2"></div>
            </div>
          </div>
        </div>
      </div>
    );
  }
}

export default Timeline;

var React = require('react');
var TabSwitcher = require('../components/tab-switcher.jsx');
var Header = require('../components/header.jsx');
var Footer = require('../components/footer.jsx');
var Jumbotron = require('../components/jumbotron.jsx');
var MemberProfile = require('../components/member-profile.jsx');

var productionMembers = [
  {
    name: `Mark Surman`,
    title: `Festival Director`,
    pic: `/assets/images/team/production/MarkSurman.jpg`,
    bio: <p>Mark is Executive Director of the Mozilla Foundation, a global community that does everything from making Firefox to taking stands on issues like privacy and net neutrality. Mark’s main job is to build the movement side of Mozilla, rallying the citizens of the web, building alliances with like-minded organizations and leaders, and growing the open internet movement. </p>
  },
  {
    name: `Sarah Allen`,
    title: `Executive Festival Director`,
    pic: `/assets/images/team/production/SarahAllen.jpg`,
    bio: <p>Sarah Allen is MozFest's producer. When she is not focused on MozFest, she helps with other Mozilla convenings in London and beyond. Sarah draws her experience from a diverse events background having worked with creative and inspiring companies, such as Movember Europe, Secret Cinema and CSM iLUKA. MozFest 2017 will be Sarah’s fifth festival.</p>
  },
  {
    name: `Erika Drushka`,
    title: `Program Designer`,
    pic: `/assets/images/team/production/Erika.jpg`,
    bio: <p>Erika Drushka is the Program Designer for MozFest 2017. With a background in documentary film making, advocacy and fundraising, Erika's focus at Mozilla is building network strength in the open internet movement through convenings. This will be Erika's fifth MozFest.</p>
  },
  {
    name: `Allen “Gunner” Gunn`,
    title: `Co-designer and Emcee`,
    pic: `/assets/images/team/production/AllenGunn.jpg`,
    bio: <p><a href="https://aspirationtech.org/about/people/gunner">Gunner</a> is Executive Director of <a href="https://aspirationtech.org/">Aspiration</a> in San Francisco, USA, and works to help NGOs, activists, foundations and software developers make more effective use of technology for social change. He is an experienced facilitator with a passion for designing collaborative open learning processes.</p>
  },
  {
    name: `Kevin Zawacki`,
    title: `Comms Manager & Speaker Series Designer`,
    pic: `/assets/images/team/production/Kevin.jpg`,
    bio: <p>Kevin Zawacki is Communications Manager for the Mozilla Foundation, and has a background in journalism and public relations. Kevin brings his magic to copy writing, media relations and is the mastermind behind the Dialogues and Debates speakers series. 2017 will be Kevin's fourth MozFest.</p>
  }
];

var wranglers = [
  // Digital Arts and Culture wranglers
  {
    name: `Julie Neville`,
    twitter: `@ArtsAward`,
    pic: `/assets/images/team/wrangler/JulieNeville.jpg`,
    bio: <p>Championing Arts Award and youth voice. Julie Neville is a project manager, facilitator and photographer.
I care about empowering young people to take the lead.  I think arts, culture and creative activities help to make the world inclusive, that’s why we’re placing the arts at the heart of digital learning.</p>
  },
  // Journalism wranglers
  {
    name: `Erika Owens`,
    twitter: `@erikao`,
    pic: `/assets/images/team/wrangler/ErikaOwens.jpg`,
    bio: <p>Erika Owens convenes people and projects in the journalism-code community for Knight-Mozilla OpenNews. As Program Manager, Erika runs the Knight-Mozilla Fellowships and builds enduring connections by creating inclusive community spaces. Erika also co-organizes Hacks/Hackers Philadelphia. She loves nonprofit journalism, people watching, and laughing heartily.</p>
  },
  {
    name: `Ryan Pitts`,
    twitter: `@ryanpitts`,
    pic: `/assets/images/team/wrangler/RyanPitts.jpg`,
    bio: <p>Ryan is a former reporter and editor who found his way into writing code thanks to a baseball stats habit. He's been working in digital and data journalism for more than a decade, at newspapers and on projects like Census Reporter. Now he helps the Knight-Mozilla OpenNews team support the developers, designers, and data analysts covering the news and building the next generation of storytelling tools</p>
  },
  {
    name: `Eva Constantaras`,
    twitter: ``,
    pic: `/assets/images/team/wrangler/EvaConstantaras.jpg`,
    bio: <p>Eva spends a lot of time coaxing her little data journalism seedlings in countries with messy data, overworked journalists, hostile governments and little internet projects into bloom. Some of these data teams, like in Kenya in Afghanistan, are thriving, while others, such as in Sudan and Myanmar are still taking root.</p>
  },
  // Open Science wranglers
  {
    name: `Arliss Collins`,
    twitter: `@arlissc`,
    // pic: `/assets/images/team/wrangler/`,
    bio: <p></p>
  },
  {
    name: `Joey Lee`,
    twitter: `@joeyklee`,
    pic: `/assets/images/team/wrangler/JoeyLee.jpg`,
    bio: <p>A geographer and computational media artist from San Francisco, California passionate about technological literacy and the engagement of art and science through computation and collaboration. He is co-author of "The Big Atlas of LA Pools" and co-creator of the "Aerial Bold" Kickstarter project (video here). He is currently based in Vancouver, Canada, balancing his time between his MSc research and teaching at the University of British Columbia, building workshops around opensource tools (e.g. Maptime Vancouver), and exploring projects around geography and technology.</p>
  },
  {
    name: `Richard Smith-Unna`,
    twitter: `@blahah`,
    pic: `/assets/images/team/wrangler/RichardSmith-Unna.jpg`,
    bio: <p>A computational biology PhD student at the University of Cambridge. He is currently focused on understanding a particularly efficient kind of photosynthesis called C4. He develops and contributes to a wide array of open source software and teaching materials for bioinformatics, including the Content Mine, Solvers.io, and BioJulia.</p>
  },
  {
    name: `Kirstie Whitaker`,
    twitter: `@KirstieJane`,
    pic: `/assets/images/team/wrangler/KirstieWhitaker.jpg`,
    bio: <p>Kirstie Whitaker is a scientist at the University of Cambridge and a Mozilla fellow for science. She puts teenagers in MRI scanners and looks at their brains. Then, she sits at her laptop and writes code to analyse those pretty pictures. She is excited to build diverse connections at MozFest.</p>
  },
  // Open Badges wranglers
  {
    name: `Matt Rogers`,
    twitter: `@mattdigitalme`,
    pic: `/assets/images/team/wrangler/MattRogers.jpg`,
    bio: <p>Matt is passionate about working with young people, particularly when it comes to using and experimenting with existing and emerging technologies. Before working for DigitalMe, Matt was a KS1-KS4 teacher for eight years most recently at a Primary School in South East London, where he also held the roles of CAS Lead Educatorand Mathletics Lead Educator. This experience directly feeds into Matt’s work on Safe, Naace CPD badges and Scout badges at DigitalMe.</p>
  },
  {
    name: `Tim Riches`,
    twitter: ``,
    pic: `/assets/images/team/wrangler/TimRiches.jpg`,
    bio: <p>Tim has worked in education for the past 12 years. First working with arts groups, helping to develop web based participation projects and then co-founding award winning open learning platforms including Radiowaves & NUMU.  His main focus is working with partners to develop a new skills currency using Mozilla Open Badges. Tim also works with the Open Badges developing international partnerships and projects.</p>
  },
  {
    name: `Lucy Lewis`,
    twitter: ``,
    pic: `/assets/images/team/wrangler/LucyLewis.jpg`,
    bio: <p>Lucy Lewis is Projects Director at DigitalMe. She has extensive experience in developing and managing award winning digital learning programmes, reaching over 10,000 learners, with partners including The Imperial War Museum, The Children’s Society and The London 2012 Olympics.Since 2011, Lucy has worked in the field of Open Badges and digital credentials, working with employers, learning providers and charities to implement Open Badge programmes and developing tools such as the Badge Design canvas and The Open Badge Academy platform.</p>
  },
  {
    name: `Grainne Hamilton`,
    twitter: ``,
    pic: `/assets/images/team/wrangler/GrainneHamilton.jpg`,
    bio: <p>Grainne’s varied background includes leading the development of the Jisc Open Badges Toolkit, contributing to the Mozilla Discover project, which involved developing badge-based pathways to employment, as well as founding and facilitating the Open Badges in Scottish Education Group. Most recently, she was Senior Consultant at Blackboard, which involved helping a global range of clients make best use of their teaching and learning systems. At DigitalMe, Grainne contributes her expertise to a range of projects and further develops the Open Badges ecosystem in Scotland</p>
  },
  // Fuel The Movement wranglers
  {
    name: `Melissa Romaine`,
    twitter: `@Melechuga`,
    pic: `/assets/images/team/wrangler/MelissaRomaine.jpg`,
    bio: (<div>
            <p>Melissa works to build and support a network of open internet activists. She is passionate about bringing different voices to the table for collaboration. Come to the Fuel the Movement space with curiosity, an eagerness to share your perspectives, and a commitment to take what you learn beyond these walls.</p>
            <p>Catch her in Fuel the Movement, and ping her on Twitter at <a href="https://twitter.com/@melechuga">@melechuga</a>.</p>
         </div>)
  },
  {
    name: `Stacy Martin`,
    twitter: ``,
    pic: `/assets/images/team/wrangler/StacyMartin.jpg`,
    bio: <p>Stacy Martin is part of the Policy Team at Mozilla. As a Senior Manager of Privacy and Engagement, Stacy focuses on building privacy community and education/awareness materials. She has created several teaching kits on topics such as privacy, passwords, and DRM.  Stacy is a co-founder of Privacy Lab, which she and Noah Swartz started in San Francisco and are now expanding to include Europe. She has been with Mozilla for 5 years. Her passion is online safety and privacy for vulnerable populations. She has worked with domestic violence organizations, city councils and other nonprofits. She also works with Mozilla volunteers on various privacy-related projects.</p>
  },
  {
    name: `Raegan MacDonald`,
    twitter: ``,
    // pic: `/assets/images/team/wrangler/`,
    bio:  (<div>
            <p>Raegan MacDonald, Senior EU Policy Manager, Mozilla</p>
            <p>Raegan leads Mozilla’s public policy work in the EU. She specialises in copyright reform, net neutrality, privacy, and data protection.</p>
            <p>Originally from Canada, Raegan is a policy wonk with experience in campaigning and advocacy. She’s been working on EU legislation for over 5 years, both as manager of Access Now’s Brussels Office and with the European Digital Rights (EDRi) network.</p>
            <p>As an honorary Belgian, she is a fan of L’Union St. Gilloise football team, Belgian beer, and PikniK Électronic, though she is forever loyal to her native land’s signature delicacy: the poutine.</p>
          </div>)
  },
  {
    name: `Georgia Bullen`,
    twitter: `@georgiamoon`,
    pic: `/assets/images/team/wrangler/GeorgiaBullen.jpg`,
    bio: <p>Georgia Bullen is the Director of Technology Projects for New America's Open Technology Institute. She manages the Measurement Lab and the Public Interest Technology Baseline projects, and supports Broadband Adoption, Data Visualization and technology work generally. She has previously worked on data visualization projects in the areas of social media, transportation logistics, economic geography, and urban issues</p>
  },
  // Localisation wranglers
  {
    name: `Heather Bailey & Dwayne Bailey`,
    twitter: `@hjbailey`,
    pic: `/assets/images/team/wrangler/HeatherBaileyDwayneBailey.jpg`,
    bio: <p>Heather & Dwayne Bailey are driven by a passion to see people embracing creativity, expression, learning and the web in their own language. They're quite open to one on one sparing if somehow you think your special area of the world is not impacted by language. You will lose.</p>
  },
  {
    name: `Manal Hassan`,
    twitter: ``,
    // pic: `/assets/images/team/wrangler/`,
    bio: <p></p>
  },
  // Youth Zone wranglers
  {
    name: `Dorine Flies`,
    twitter: `@dorineFlies`,
    pic: `/assets/images/team/wrangler/DorineFlies.jpg`,
    bio:  (<div>
            <p>Heading up the "Youth led" programme at MozFest, her interest in Sociology and Anthropology led her to explore how digital culture can build resilience and inclusion across communities.</p>
            <p>A keen RPG gamer, Dorine’s years spent assembling raid teams of young people in World of Warcraft lead her to a second career in HR supporting engineers (naturally)!</p>
            <p><a href="https://medium.com/@DorineFlies">https://medium.com/@DorineFlies</a></p>
          </div>)
  },
  {
    name: `Andrew Mulholland`,
    twitter: `@gbaman`,
    pic: `/assets/images/team/wrangler/AndrewMulholland.jpg`,
    bio: <p>Andrew Mulholland, a Computer Science student currently studying at Queens University in Belfast is passionate about getting kids excited and involved in Computer Science and Digital making. In his free time, he can be found working with schools and running events including the Northern Ireland Raspberry Jam.</p>
  },
  // Demystify the Web wranglers
  {
    name: `Robert Friedman`,
    twitter: `@omnignorant`,
    pic: `/assets/images/team/wrangler/RobertFriedman.jpg`,
    bio: <p>Robert Friedman believes that all people should have equal and open access to the networks, opportunities and resources they need to make their dreams come true; that the internet is a basic human right fundamental to making this vision a reality; and that universal web literacy is where to start.</p>
  },
  {
    name: `Kim Wilkens`,
    twitter: `@kimxtom`,
    pic: `/assets/images/team/wrangler/KimWilkens.jpg`,
    bio: (<div>
            <p>Kim Wilkens is the Computer Science Initiative Coordinator for K-8 at St. Anne’s-Belfield School and the founder of Tech-Girls. She is passionate about transforming technology users into technology creators, collaborators and activists. Kim looks forward to embracing the possibilities and challenges of the future with you at MozFest!</p>
            <p><a href="http://techkim.wikispaces.com">http://techkim.wikispaces.com</a></p>
          </div>)
  },
  {
    name: `Su Adams`,
    twitter: `@SuAdams`,
    pic: `/assets/images/team/wrangler/SuAdams.jpg`,
    bio:  (<div>
            <p>I teach teachers to teach tech, deliver workshops and write curriculum.</p>
            <p>Oozing enthusiasm and always keen to share, I'm a Mozilla Regional Clubs Co-ordinator, CoderDojo London Mentor, a member of A11yhacks: coding with vision impaired and am a UK Code Week Ambassador.  In my spare time, I sleep!</p>
            <p>What I love most is seeing faces light up!</p>
          </div>)
  },
  {
    name: `Simeon Oriko`,
    twitter: `@simeonoriko`,
    pic: `/assets/images/team/wrangler/SimeonOriko.jpg`,
    bio: <p>Simeon Oriko, an Open Innovation strategist & consultant based in Nairobi, is passionate about helping people translate their ideas into reality. This passion shapes his vision and work at Jamlab, a co-creation community he established and serves as its Executive Director.</p>
  },
  // Dilemmas in Connected Spaces wranglers
  {
    name: `Michelle Thorne`,
    twitter: `@thornet`,
    pic: `/assets/images/team/wrangler/MichelleThorne.jpg`,
    bio: <p>Michelle Thorne (<a href="https://twitter.com/@thornet">@thornet</a>) leads Mozilla’s Open Internet of Things Studio, a professional learning network committed to making IoT more open. She’s published Understanding the Connected Home and regularly facilitates programs that advocate for equality through digital empowerment and innovation through open, collaborative practices. She’s based in Berlin, Germany.</p>
  },
  {
    name: `Jon Rogers`,
    twitter: ``,
    pic: `/assets/images/team/wrangler/JonRogers.jpg`,
    bio: <p>Jon Rogers is a Senior Research Fellow with the Open IoT Studio at the Mozilla Foundation. He balances his academic life as a professor of Creative Technology at the University of Dundee with exploring how we can embed internet stewardship into the making of meaningful connected devices. He is based between Berlin and Dundee.</p>
  },
  {
    name: `Ian Forrester`,
    twitter: `@cubicgarden`,
    pic: `/assets/images/team/wrangler/IanForrester.jpg`,
    bio: (<div>
            <p>Ian Forrester is a well known and likeable character on the digital scene in the UK. Living in Manchester, UK where he works for the BBC's R&D north lab. He specialises in open innovation and new disruptive opportunities; by creating value via open engagement and collaborations with startups, universities, early adopters and hackers.</p>
            <p><a href="http://www.cubicgarden.com">http://www.cubicgarden.com</a></p>
          </div>)
  },
  {
    name: `Dietrich Ayala`,
    twitter: `@autonome`,
    pic: `/assets/images/team/wrangler/DietrichAyala.jpg`,
    bio: <p>Dietrich Ayala reincarnated into this earthly vehicle for a purpose: to valiantly fly the flag of curiosity and to lead the insurgency against boredom.</p>
  },
  {
    name: `Michael Saunby`,
    twitter: `@msaunby`,
    pic: `/assets/images/team/wrangler/MichaelSaunby.jpg`,
    bio: <p>Michael Saunby, a *****</p>
  },
  {
    name: `George Roter`,
    twitter: `@geroter`,
    // pic: `/assets/images/team/wrangler/`,
    bio: <p></p>
  },
  // MozEx (Art Exhibit) wranglers
  {
    name: `Luca Damiani`,
    twitter: `@mozexhibit`,
    pic: `/assets/images/team/wrangler/LucaDamiani.jpg`,
    bio: <p>Visual Artist, Luca M Damiani works and practices internationally in the fields of Arts&Design, Technology, Visual Culture. He is also Digital Studio Producer at Tate and Lecturer in Graphic and Media Design at University Arts London. Luca is always looking at creating ongoing conversations about the role of the arts in society.</p>
  },
  {
    name: `Irini Papadimitriou`,
    twitter: ``,
    pic: `/assets/images/team/wrangler/IriniPapadimitriou.jpg`,
    bio:  (<div>
            <p>Irini Papadimitriou is Digital Programmes Manager at the V&A and responsible for programmes such as the annual Digital Design Weekend, a big-scale event presenting intersections of art, design and technology; and the monthly Digital Futures, an open platform & networking event for displaying and discussing work by researchers, artists, designers, companies and other professionals working with art, technology, design, science and beyond.</p>
            <p>Irini is also Head of New Media Arts Development at Watermans, an arts organisation presenting innovative work and supporting artists working with technology, where she is curating the exhibition programme and an annual Digital Performance festival, exploring intersections in digital art, dance, sound, performance, science and more.</p>
            <p>She is also one of the organisers for London's Mini Maker Faire, and one of the co-founders of Maker Assembly, a critical gathering about maker cultures.</p>
            <p>Links</p>
            <ul>
              <li><a href="http://www.vam.ac.uk/blog/author/irini-papadimitriou">V&A blog</a></li>
              <li><a href="https://www.watermans.org.uk/new-media-arts-archive/">Watermans New Media Arts</a></li>
              <li><a href="http://makerassembly.org ">Maker Assembly</a></li>
            </ul>
          </div>)
  }
];

var Partners = React.createClass({
  partnersInfo: [
    {
      name: `Youth Led EU`,
      logo: `/assets/images/team/partner/YouthLedEU.jpg`,
      link: `https://www.facebook.com/YouthLedEU/`
    },
    {
      name: `The Tate`,
      logo: `/assets/images/team/partner/Tate.jpg`,
      link: `http://www.tate.org.uk/`
    },
    {
      name: `The V&A`,
      logo: `/assets/images/team/partner/VA.jpg`,
      link: `https://www.vam.ac.uk/`
    },
    {
      name: `Arts Award`,
      logo: `/assets/images/team/partner/ArtsAward.jpg`,
      link: `http://www.artsaward.org.uk/`
    },
    {
      name: `BBC`,
      logo: `/assets/images/team/partner/BBC.jpg`,
      link: `http://www.bbc.co.uk/rd`
    },
    {
      name: `Ravensbourne`,
      logo: `/assets/images/team/partner/Ravensbourne.jpg`,
      link: `http://www.ravensbourne.ac.uk/`
    },
    {
      name: `Digital Me`,
      logo: `/assets/images/team/partner/DigitalMe.jpg`,
      link: `http://www.digitalme.co.uk/`
    },
    {
      name: `Translate`,
      logo: `/assets/images/team/partner/Translate.jpg`,
      link: `http://www.translate.org.za/`
    },
  ],
  renderPartnerLogos: function(partner) {
    return this.partnersInfo.map((partner) => {
      console.log(partner);
      return <div className="col-12 col-sm-4 partner" key={partner.key}>
                <a href={partner.link}>
                  { partner.logo ? <img src={partner.logo} alt={partner.name} className="img-fluid mb-4" /> : partner.name }
                </a>
              </div>
    });
  },
  render: function() {
    return <div className="row">{this.renderPartnerLogos()}</div>
  }
});


var TeamPage = React.createClass({
  render: function() {
    return (
      <div className="team-page">
        <Header/>
        <Jumbotron image="/assets/images/hero/team.jpg"
                  image2x="/assets/images/hero/team.jpg">
          <h1>Team</h1>
        </Jumbotron>
        <div className="content wide mt-0">
          <TabSwitcher baseURL={`/team/`} initialTab={this.props.params.tab} ref="tabSwitcher" className="pull-up">
            <div name="Production" slug="production">
              <h1>Our 2017 Production Team</h1>
              <div className="horizontal-rule"></div>
              {
                productionMembers.map( member => {
                  return ( <MemberProfile name={member.name} title={member.title} pic={member.pic} bio={member.bio} key={member.name} /> );
                })
              }
            </div>

            <div name="Sponsors" slug="sponsors" className="sponsors">
              <h1>Our 2017 Sponsors</h1>
              <div className="horizontal-rule mb-5"></div>
              <div>
                <p>MozFest is the world’s leading event for and by the open Internet movement, and one of Mozilla’s largest annual networking opportunities. This three-day festival of interactive sessions, hands-on activities, and engaging talks brings together 1,800 passionate advocates of the open web from around the world for the flagship event in Mozilla’s leadership network.</p>
                <p>
                  For 2017, Mozilla is offering new sponsorship opportunities for supporters to help us deliver MozFest in London. The festival is growing at a phenomenal rate, as are the opportunities to collaborate, build, and convene with this dynamic network.
                </p>
                <p>If you would like to sponsor MozFest 2017, please reach out to us at <a href="mailto:festival@mozilla.org">festival@mozilla.org</a>. A festival team member will share with you the various options for support and next steps. We look forward to hearing from you.</p>
              </div>
            </div>

            {/* hiding('hidden') this tab for now as requested in https://github.com/mozilla/mozillafestival.org/issues/554 */}
            <div name="Wranglers" slug="wranglers" hidden>
              <h1>Our 2017 Space Wranglers</h1>
              <div className="horizontal-rule mb-5"></div>
              <div>To be announced</div>
            </div>

            {/* hiding this tab for now as requested in https://github.com/mozilla/mozillafestival.org/issues/554 */}
            <div name="Partners" slug="partners">
              <h1>Our 2017 Partners</h1>
              <div className="horizontal-rule mb-5"></div>
              <Partners />
            </div>
          </TabSwitcher>
        </div>
        <Footer/>
      </div>
    );
  }
});

module.exports = TeamPage;


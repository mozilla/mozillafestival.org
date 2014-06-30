<?php
/*
 * Template Name: "What to Expect" Page
 * Description:
 */
get_header();
?>
<div id="content">
  <div class="constrained">
    <h2>What to Expect Page</h2>

    <!-- Location ===================== -->
    <section class="panel">
      <div class="header">
        <h3>Location</h3>
        <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d2483.625720762954!2d0.0055388!3d51.501735499999995!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47d8a81c7eafaba3%3A0xc9dc7cd5a491fd50!2s6+Penrose+Way!5e0!3m2!1sen!2sca!4v1403672002980" width="600" height="450" frameborder="0" style="border:0"></iframe>
      </div>
      <p>
        This year, Mozfest returns to <a href='http://www.ravensbourne.ac.uk/'>Ravensbourne</a>, a media campus in the heart of London. Located right next to the O2 arena and North Greenwich tube station, Ravensbourne's nine floors of open work spaces, breakout rooms and cozy corners are ideal for collaboration and creative working.
      </p>
    </section>

    <!-- How to get there ===================== -->
    <section class="tiles-container">
      <div class="header">
        <h3>How to get there:</h3>
      </div>
      <ul class="item-list">
        <li>
          <h4>
            <img src="<?php echo get_template_directory_uri(); ?>/media/img/underground.png" alt="underground" title="underground" width="277" height="190" class="size-full" />
             London Underground
           </h4>
          <p>Take the Jubilee Line to North Greenwich (Zone 2).</p>
          <ul>
            <li>5 minutes from Canary Wharf</li>
            <li>10 minutes from London Bridge</li>
            <li>15 minutes from Waterloo</li>
            <li>20 minutes from Bond Street</li>
          </ul>
          <p>
            The Jubilee line is the only Underground route that connects with all others – so not only you can reach us easily, National Rail passengers from all over the UK can too.
          </p>
          <p>
            It is a 2 minute walk from North Greenwich underground station to Ravensbourne. On exiting the station, please follow signs to The O2 and follow the covered walkway to The O2's main entrance. Before you reach the main entrance to The O2, turn right into Penrose Way and Ravensbourne’s entrance is on your right.
          </p>
        </li>
        <li>
          <h4>
            <img src="<?php echo get_template_directory_uri(); ?>/media/img/bus.png" alt="bus" title="bus" width="277" height="190" class="size-full" />
             Bus
          </h4>
          <p>Eight TfL bus routes operate to and from North Greenwich including three 24 hour bus services. Key destinations include Stratford, Charlton, Greenwich, Lewisham, Woolwich, Eltham, North Kent and Central London. Please visit <a href="http://www.tfl.gov.uk/">www.tfl.gov.uk</a> for timetable information.</p>
        </li>
        <li>
          <h4>
            <img src="<?php echo get_template_directory_uri(); ?>/media/img/DLR.png" alt="DLR" title="DLR" width="277" height="190" class="size-full" />
            Docklands Light Railway (DLR)
          </h4>
          <p>Just one stop via the Jubilee line from Canary Wharf or Canning Town.</p>
        </li>
        <li>
          <h4>
            <img src="<?php echo get_template_directory_uri(); ?>/media/img/clipper.png" alt="clipper" title="clipper" width="277" height="190" class="size-full" />
            Thames Clipper
          </h4>
          <p>
            We are a two minute walk from the North Greenwich Pier stop on the Thames Clipper route. Please visit the <a href="http://www.thamesclippers.com/">Thames Clipper website</a> for routes, fares and journey times.
          </p>
        </li>
        <li>
          <h4>
            <img src="<?php echo get_template_directory_uri(); ?>/media/img/national_rail.png" alt="national rail" title="national rail" width="277" height="190" class="size-full" />
             National Rail
          </h4>
          <p>
            Charlton mainline station is just a short ride on either a 486, 472 or 161 bus from North Greenwich underground station. All these buses start their route from North Greenwich underground so there is no confusion as to which way to go.
          </p>
          <p>
            Southeastern runs services to Charlton train station. Turn left out of the station and catch 486, 472 or N472 buses to North Greenwich.
          </p>
        </li>
        <li>
          <h4>
            <img src="<?php echo get_template_directory_uri(); ?>/media/img/eurostar.png" alt="eurostar" title="eurostar" width="277" height="190" class="size-full" />
            Eurostar
          </h4>
          <p>
            The current network takes the Eurostar into King’s Cross St. Pancras Station, from where you only need to jump on the Northern Line down to London Bridge where you can then change for the Jubilee Line.
          </p>
        </li>
      </ul>
    </section>


    <!-- Hotels ===================== -->
    <section class="panel">
      <header>
      <h3>Hotels</h3>
      <p></p>
      <img src="<?php echo get_template_directory_uri(); ?>/media/img/park_plaza_westminister.jpg" alt="Park Plaza Westminster Bridge" class="size-full">
      </header>
      <section>
        <h4><a href="http://www.parkplaza.com/london-hotel-gb-se1-7ut/gbwestmi">Park Plaza Westminster Bridge</a></h4>
        200 Westminster Bridge Road <br />
        London SE1 7UT <br />
      </section>

      <section>
        <h4><a href="http://www.parkplaza.com/london-hotel-gb-se1-7ry/gbcounty">Park Plaza County Hall</a></h4>
        1 Addington Street<br />
        London SE1 7RY<br />
        </section>
        <section>
        <h4><a href="http://www.premierinn.com/en/hotel/SOUANC/london-southwark-borough-market">Premier Inn Southwark </a></h4>
        Bankside, 34 Park Street <br />
        London SE1 9EF <br />
      </section>
    </section>


    <!-- Travel Tips & Tricks ===================== -->
    <section class="panel">
      <div class="header">
        <h3>Travel Tips & Tricks</h3>
      </div>
      <p>
        Passport, local currency, device charger and socks...there are some items you should never leave home without! Check out these <a href="https://wiki.mozilla.org/Travel_Guide">comprehensive travel tips</a> from seasoned Mozilla travelers.
      </p>
    </section>


  </div>
</div>

<?php

get_footer();

?>

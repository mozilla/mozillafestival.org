<?php

get_header();

?>

<div id="carousel">
  <div class="constrained">
    <h2>MozFest 2014</h2>
    <a class="button" href="https://blog.webmaker.org/mozfest-2014-we-made-this-together">See What We Made Together</a>
  </div>
</div>

<div id="content" class="splash-2014">
  <div class="constrained">
    <div id="home-sidebar" class="section">
      <div class="section">
        <h3>Missed out on MozFest 2014?</h3>
        <p>It's not too late to participate! Remix our <a href="https://jess.makes.org/thimble/LTgzMjI4MTY=/mozfest-remotee-challenge">Remotee Challenge</a>.</p>
      </div>
      <?php if ( is_active_sidebar( "home-primary" ) ) : ?>
      <div id="blogs-list" class="section">
        <?php dynamic_sidebar( "home-primary" ); ?>
      </div>
      <?php endif; ?>
    </div>
    <div id="scribbleLive-wrapper">
      <div class="scrbbl-embed" data-src="/event/867043"></div>
      <script>(function(d, s, id) {var js,ijs=d.getElementsByTagName(s)[0];if(d.getElementById(id))return;js=d.createElement(s);js.id=id;js.src="//embed.scribblelive.com/widgets/embed.js";ijs.parentNode.insertBefore(js, ijs);}(document, 'script', 'scrbbl-js'));</script>
    </div>
  </div>
</div>
<?php

get_footer();

<?php

get_header();

?>

<div id="carousel">
  <div class="constrained">
    <h2>Register for MozFest!</h2>
    <a class="button" href="https://sendto.mozilla.org/page/contribute/mozilla-festival-2014-registration">Register now</a>
  </div>
</div>

<div id="content" class="splash-2014">
  <div class="constrained">
    <div id="scribbleLive-wrapper">
      <!-- FIXME, temp, using MozFest 2013's ScribbleLive as placeholder -->
      <div class="scrbbl-embed" data-src="/event/237586"></div>
      <script>(function(d, s, id) {var js,ijs=d.getElementsByTagName(s)[0];if(d.getElementById(id))return;js=d.createElement(s);js.id=id;js.src="//embed.scribblelive.com/widgets/embed.js";ijs.parentNode.insertBefore(js, ijs);}(document, 'script', 'scrbbl-js'));</script>
    </div>
    <div id="home-sidebar">
      <?php if ( is_active_sidebar( "home-primary" ) ) : ?>
        <div id="blogs-list">
          <?php dynamic_sidebar( "home-primary" ); ?>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>
<?php

get_footer();

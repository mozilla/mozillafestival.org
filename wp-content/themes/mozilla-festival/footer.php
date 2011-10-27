<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content after
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */
?>

	</div><!-- #main -->
    </div><!-- .wrap -->
</div><!-- #page -->
<footer id="colophon" role="contentinfo">
    <div class="cf wrap">
        <div class="vevent">
            <p class="summary">
            <em class="hide"><?php bloginfo( 'name' ); ?></em>
            Media, Freedom &amp; the web
            </p>
            <p class="hcard"><abbr class="dtstart" title="2011-11-4">Nov 4-6, 2011</abbr> <span class="hide"><span class="location">at Ravensborne College, London, UK</span></span></p>
        </div>
        <div class="nav">
            <?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu' => 'primary', 'depth' => 1  )); ?>
        </div>
        <div class="nav moz">
            <ul class="menu">
                <li class="fb_frame">
                    <div class="a_faux">
                    <div class="fb-like" data-href="mozilladrumbeat" data-send="false" data-layout="button_count" data-show-faces="false" data-colorscheme="dark"></div>
                    </div>
                </li>
                <li class="twitter_frame">
                    <div class="a_faux"> 
                        <a href="https://twitter.com/mozilla" class="twitter-follow-button" data-button="grey" data-text-color="#FFFFFF" data-link-color="#FFFFFF" data-show-count="false">Follow @mozilla</a>
                        <script src="//platform.twitter.com/widgets.js" type="text/javascript"></script>
                    </div>
                </li>
                <li><a href="http://join.mozilla.org">Join Mozilla</a></li>
                <li><a href="http://www.mozilla.org/about/mission.html">Mozilla's mission</a></li>
                <li><a href="https://donate.mozilla.org/Sign-Up">Get updates from Mozilla</a></li>
            </ul>
        </div>
    </div>
</footer><!-- #colophon -->
                    <!-- Only execute on the homepage - with added shiv for Webkit -->
                    <script src="<?php bloginfo( 'stylesheet_directory' ); ?>/video.js"></script>
                    <script>
                        if (document.querySelectorAll('body.home').length) { 
                        window.setTimeout(function() {
                            var max = 351, 
                                els = document.querySelectorAll('li.col'),
                                l = els.length,
                                i;
                            for (i = l; i--;) {
                                var h = els[i].offsetHeight;
                                if (h > max) {
                                    max = h;
                                }
                            }
                            if (max !== 351) {
                                for (i = l; i--;) {
                                    els[i].style.minHeight = max + "px";
                                }
                            }
                        }, 1000);
                        }
                        VideoJS.setupAllWhenReady();
                    </script>
<?php wp_footer(); ?>

</body>
</html>

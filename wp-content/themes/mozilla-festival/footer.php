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
            <?php bloginfo( 'description' ); ?>
            </p>
            <p class="hcard"><abbr class="dtstart" title="2011-11-4">Nov 4-6, 2011</abbr> <span class="hide"><span class="location">at Ravensborne College, London, UK</span></span></p>
            </div>
<div class="nav">
       <?php wp_nav_menu( array( 'theme_location' => 'primary', 'depth' => 1  )); ?>
	    <a class="register" href="https://donate.mozilla.org/page/contribute/festival-register">Register</a>
    </div>
    </div>
    </footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>

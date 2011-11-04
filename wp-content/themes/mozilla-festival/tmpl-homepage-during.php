<?php
/**
 * Template Name: Homepage - during the festival
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */

get_header(); ?>

		<div id="primary" class="one_col">
            <img id="info_graphic" src="<?php bloginfo('stylesheet_directory'); ?>/images/fest_info.png" alt="open web developers, journalists, media educators - we're getting together for 3 days to build real projects that will change media, the web and the world" />
			<div id="content" role="main">
                <section class="buzz">
                    <h2 class="entry-title">What's happening</h2>
                    <div>
                    <iframe src="http://embed.scribblelive.com/Embed/v5.aspx?Id=32231&amp;ThemeId=3498" frameborder="0" height="300"></iframe>
                </div>
                <h3>Ravensborne Festival blogs</h3>
                <p>The students at Ravensborne are providing festival coverage on the following topics:</p>
                <ul>
                 <li>
                <h3>Audio and Video</h3>
                <a href="http://mozilla1.ravewebmedia.co.uk/">Ravezilla - Audio and Video Innovation</a>
                </li>
                <li>
                <h3>Education</h3>
                <a href="http://mozilla2.ravewebmedia.co.uk/">Mozilla Festival - Children and Education</a>
                </li>
                <li>
                <h3>Gaming</h3>
                <a href="http://mozilla3.ravewebmedia.co.uk/">Mozilla Gaming</a>
                </li>
               <li>
                <h3>Journalism</h3>
                <a href="http://mozilla4.ravewebmedia.co.uk/">Jour<em>new</em>ism</a>
                </li>
                </ul>
                </section>
                <section>
                    <h2 class="entry-title">More coverage</h2>
                    <ol class="grid">
                    <?php
                        query_posts('posts_per_page=6&category_name=uncategorized,mozfest,festival-blog');
                        while (have_posts()) : the_post() 
                    ?>
                    <li class="col">
                        <a href="<?php the_permalink(); ?>">
                        <?php the_post_thumbnail(); ?>
                        <h3><?php the_title(); ?></h3>
                        </a>
                        <?php global $more; $more = 0; ?>
                        <?php the_content('Read more'); ?>
                    </li>
                    <?php endwhile; ?>
                    </ol>
                    <?php 
                        $c = get_category_by_slug('mozfest'); 
                        if ($c -> count > 3) :
                            $blog_url = get_category_link($c -> cat_ID);
                    ?>
                        <footer>
                        <ul class="social">
                        <li><a href="http://twitter.com/mozilla"><img alt="@mozillafestival on twitter" src="<?php bloginfo('stylesheet_directory'); ?>/images/twitter.png" /></a></li>
                        <li><a href="https://mozillafestival.org/category/mozfest/feed/"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/rss.png" alt="Follow our RSS feed for updates" /></a></li>
                        </ul>
                        <a href="<?php echo $blog_url; ?>" class="register">See all blog posts</a>
                        </footer>
                    <?php endif; ?>
                </section>
                <section>
                    <h2 class="entry-title">Who's here?</h2>
                    <p>Take a look at our  <a href="http://lanyrd.com/2011/mozilla-festival/">our Lanyrd page</a> or <a href="http://lanyrd.com/2011/mozilla-festival/attendees/">participant directory</a> . And please add yourself if you haven't already.</p>
                    <footer>
                        <?php
                            $category_ID = get_cat_ID('people');
                            $category_url = get_category_link($category_ID);
                        ?>
                        <a class="register" href="/whos-coming/">See speakers and facilitators</a>
                    </footer>
                </section>
                <section class="partnerships">
                    <h2 class="entry-title">In partnership with</h2>
                     <h3 class="partner-head">Challenge partners</h3>
                    <ul class="partners challenge">
                    <?php
                        $sticky = get_option('sticky_posts');
                        $args = array(
                            'category_name' => 'Partners',
                            'orderby' => 'rand',
                            'ignore_sticky_posts' => 1,
                            'tag' => 'challenge-partners',
                            'post__in' => $sticky,
                            'posts_per_page' => -1
                        );
                        query_posts($args);
                        while (have_posts()) : the_post()
                    ?>
                     <li>
                    <a href="<?php echo get_the_content(); ?>"><?php the_post_thumbnail(); ?></a>
                    </li>
                    <?php endwhile; ?>
                    <?php
                        $args = array(
                            'category_name' => 'Partners',
                            'orderby' => 'rand',
                            'tag' => 'challenge-partners',
                            'posts_per_page' => -1,
                            'post__not_in' => $sticky
                        );
                        #query_posts('posts_per_page=-1&category_name=Partners&orderby=rand&tag=challenge-partners');
                        query_posts($args);
                        while (have_posts()) : the_post()
                    ?>
                    <li>
                    <a href="<?php echo get_the_content(); ?>"><?php the_post_thumbnail(); ?></a>
                    </li>
                    <?php endwhile; ?>
                    </ul> 
                  
               </section>
			</div><!-- #content -->
		</div><!-- #primary -->

<?php get_footer(); ?>

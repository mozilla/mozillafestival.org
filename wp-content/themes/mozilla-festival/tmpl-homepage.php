<?php
/**
 * Template Name: Homepage
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
            <img src="<?php bloginfo('stylesheet_directory'); ?>/images/fest_info.png" alt="open web developers, journalists, media educators - we're getting together for 3 days to build real projects that will change media, the web and the world" />
			<div id="content" role="main">
                <section>
                    <h2 class="entry-title">What's new?</h2>
                    <ol class="grid">
                    <?php
                        query_posts('posts_per_page=3&category_name=uncategorized,mozfest,festival-blog');
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

                <?php 
                    query_posts('posts_per_page=6&category_name=people&orderby=rand&tag=vip');
                    if (have_posts()): 
                ?>
                <section>
                    <h2 class="entry-title">Who's coming?</h2>
                    <ul class="peeps">
                    <?php  
                        while (have_posts()): the_post()
                    ?>
                    <li>
                        <h2><?php the_title(); ?></h2>
                        <?php the_content(); ?>
                    </li>
                    <?php endwhile; ?>
                    </ul>
                    <footer>
                        <?php
                            $category_ID = get_cat_ID('people');
                            $category_url = get_category_link($category_ID);
                        ?>
                        <a class="register" href="/whos-coming/">See who else is coming</a>
                    </footer>
                </section>
                <?php endif; ?>
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
                     <h3 class="partner-head">Human API partners</h3>
                    <ul class="partners human">
                    <?php
                        query_posts('posts_per_page=-1&category_name=Partners&orderby=rand&tag=human-api');
                        while (have_posts()) : the_post()
                    ?>
                    <li>
                    <a href="<?php echo get_the_content(); ?>"><?php the_post_thumbnail(); ?></a>
                    </li>
                    <?php endwhile; ?>
                    </ul>
                     <h3 class="partner-head">Content partners</h3>
                    <ul class="partners content">
                    <?php
                        query_posts('posts_per_page=-1&category_name=Partners&orderby=rand&tag=content-partners');
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

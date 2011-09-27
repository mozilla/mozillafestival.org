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
			<div id="content" role="main">

				<?php the_post(); ?>

				<?php get_template_part( 'content', 'page' ); ?>
                 <section>
                    <h2 class="entry-title">What's new?</h2>
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
                        <p class="meta"><?php twentyeleven_posted_on(); ?></p>
                        <?php the_content('Read more'); ?>
                    </li>
                    <?php endwhile; ?>
                    </ol>
                    <?php 
                        $c = get_category_by_slug('mozfest'); 
                        if ($c -> count > 6) :
                            $blog_url = get_category_link($c -> cat_ID);
                    ?>
                        <footer>
                        <a href="<?php echo $blog_url; ?>" class="register">See all blog posts</a>
                        </footer>
                    <?php endif; ?>
                </section>

                <?php 
                    query_posts('posts_per_page=4&category_name=people&orderby=rand');
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
                <section>
                    <h2 class="entry-title">In partnership with</h2>
                    <ul class="partners">
                    <?php
                        query_posts('posts_per_page=-1&category_name=Partners&orderby=rand');
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

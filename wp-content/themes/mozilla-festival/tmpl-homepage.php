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

				<?php comments_template( '', true ); ?>
                <section>
                    <h2 class="entry-title">Recent Blog Posts</h2>
                    <ol class="grid">
                    <?php
                        query_posts('posts_per_page=6&category_name=uncategorized,mozfest,festival-blog');
                        while (have_posts()) : the_post() 
                    ?>
                    <li class="col">
                        <a href="<?php the_permalink(); ?>">
                            <?php the_post_thumbnail(); ?>
                            <?php the_title(); ?>
                        </a>
                        <?php twentyeleven_posted_on(); ?>
                        <?php the_content(); ?>
                    </li>
                    <?php endwhile; ?>
                    </ol>
                </section>
			</div><!-- #content -->
		</div><!-- #primary -->

<?php get_footer(); ?>

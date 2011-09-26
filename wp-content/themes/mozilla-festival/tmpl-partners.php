<?php
/**
 * Template Name: Festival Partners
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

		<div id="primary" class="with_side">
			<div id="content" role="main">

				<?php the_post(); ?>

				<?php get_template_part( 'content', 'page' ); ?>
                <ul class="partners">
                <?php
                    query_posts('category_name=Partners&posts_per_page=-1');
                    while (have_posts()) : the_post()
                ?>
                <li>
                    <a href="<?php echo get_the_content(); ?>">
                    <?php the_post_thumbnail(); ?>
                    </a>
                </li>
                <?php endwhile; ?>
                </ul>
				<?php comments_template( '', true ); ?>

			</div><!-- #content -->
		</div><!-- #primary -->
<?php get_sidebar(); ?>
<?php get_footer(); ?>

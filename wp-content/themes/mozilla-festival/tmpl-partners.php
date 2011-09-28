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
                    <h2 class="partner-head">Challenge partners</h2>
                    <ul class="partners challenge">
                    <?php
                        query_posts('posts_per_page=-1&category_name=Partners&orderby=rand&tag=challenge-partners');
                        while (have_posts()) : the_post()
                    ?>
                    <li>
                    <a href="<?php echo get_the_content(); ?>"><?php the_post_thumbnail(); ?></a>
                    </li>
                    <?php endwhile; ?>
                    </ul>
                     <h2 class="partner-head">Human API partners</h2>
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
                     <h2 class="partner-head">Content partners</h2>
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
				<?php comments_template( '', true ); ?>

			</div><!-- #content -->
		</div><!-- #primary -->
<?php get_sidebar(); ?>
<?php get_footer(); ?>

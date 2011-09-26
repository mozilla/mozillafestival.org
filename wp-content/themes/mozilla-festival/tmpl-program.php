<?php
/**
 * Template Name: Program page
 *
 * This is the template that displays all posts in the 'programming'
 * category that also also tagged with the page it is embedded on slug
 * eg. fail.com/foo will grab all posts tagged foo
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
                <ul class="peeps">
                <?php
                    $page_slug = $post->post_name;
                    query_posts('category_name=programming&posts_per_page=-1&tag=' . $page_slug);
                    while (have_posts()) : the_post()
                ?>
                <li>
                    <h2><a href="<?php echo get_post_meta($post->ID, 'URL', true); ?>"><?php the_title(); ?></a></h2>
                    <div class="bio">
                    <?php the_content(); ?>
                    </div>
                </li>
                <?php endwhile; ?>
                </ul>
				<?php comments_template( '', true ); ?>

			</div><!-- #content -->
		</div><!-- #primary -->
<?php get_sidebar(); ?>
<?php get_footer(); ?>

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

		<div id="primary" class="one_col">
			<div id="content" role="main">

				<?php the_post(); ?>

				<?php get_template_part( 'content', 'page' ); ?>
                <?php
                    $page_slug = $post->post_name;
                ?>
                <ul class="peeps <?php echo $page_slug; ?>">
                <?php 
                    query_posts('category_name=programming&orderby=title&order=ASC&posts_per_page=-1&tag=' . $page_slug);
                    while (have_posts()) : the_post()
                ?>
                <li>
                    <h2><a href="<?php echo get_post_meta($post->ID, 'URL', true); ?>"><?php the_title(); ?></a></h2>
                    <div class="bio">
                    <?php the_content(); ?>
                    <?php 
                        $list = get_the_tags();
                        $list_items = array();
                        $list_html = '<p class="tags">Who should come: ';
                        foreach($list as $tag) {
                            if ($tag->name != $page_slug) {
                                $list_items[] = "<a href='/tag/{$tag->slug}/'>{$tag->name}</a>";
                            }
                        }
                        $list_html .= implode(', ', $list_items);
                        $list_html .= '</p>';
                        if (count($list_items) > 4) {
                            echo $list_html;
                        }
                    ?>
                    </div>
                </li>
                <?php endwhile; ?>
                </ul>
				<?php comments_template( '', true ); ?>

			</div><!-- #content -->
		</div><!-- #primary -->
<?php get_footer(); ?>

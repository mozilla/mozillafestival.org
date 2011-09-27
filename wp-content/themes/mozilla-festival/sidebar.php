<?php
/**
 * The Sidebar containing the main widget area.
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */

$options = twentyeleven_get_theme_options();
$current_layout = $options['theme_layout'];

if ( 'content' != $current_layout ) :
?>
		<div id="secondary" class="widget-area" role="complementary">
			<?php if ( ! dynamic_sidebar( 'sidebar-1' ) ) : ?>

				<aside id="archives" class="widget">
					<h3 class="widget-title"><?php _e( 'Archives', 'twentyeleven' ); ?></h3>
					<ul>
						<?php wp_get_archives( array( 'type' => 'monthly' ) ); ?>
					</ul>
				</aside>

				<aside id="meta" class="widget">
					<h3 class="widget-title"><?php _e( 'Meta', 'twentyeleven' ); ?></h3>
					<ul>
						<?php wp_register(); ?>
						<li><?php wp_loginout(); ?></li>
						<?php wp_meta(); ?>
					</ul>
				</aside>

			<?php endif; // end sidebar widget area ?>
		    <aside id="recent-posts" class="widget widget_recent_entries">
                <h3 class="widget-title">Recent Blog Posts</h3>
                <ul>
                <?php
                    $recent_posts = wp_get_recent_posts(array(
                        'numberposts' => 5,
                        'category_name' => 'uncategorized,mozfest,festival-blog',
                        'post_status' => 'publish'
                    ));
                    foreach( $recent_posts as $recent) {
                        echo '<li><a href="' . get_permalink($recent["ID"])  . '">' . $recent['post_title']  . '</a></li>';
                    }
                ?>
                </ul>
            </aside>
        </div><!-- #secondary .widget-area -->
<?php endif; ?>

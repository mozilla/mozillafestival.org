<?php
/**
 * The default template for displaying content
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */
?>

	<li>
        <h2><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'twentyeleven' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
        <div class="bio">
        <?php the_content(); ?>
        <?php
            $list = get_the_tags();
            $list_items = array();
            $list_html = '<p class="tags">Who should come: ';
            foreach($list as $tag) {
                if ($tag->name != 'design-challenges') {
                    $list_items[] = "<a href='/tag/{$tag->slug}/'>{$tag->name}</a>";
                }
            }
            $list_html .= implode(', ', $list_items);
            $list_html .= '</p>';
            echo $list_html;
        ?>
        </div>
    </li><!-- #post-<?php the_ID(); ?> -->

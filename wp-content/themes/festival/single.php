<?php global $trackbacks; $comments = $trackbacks; ?>
<?php get_header(); ?>

<section id="content-main" class="hfeed single">

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

  <article class="hentry" id="post-<?php the_ID(); ?>">
    <h1 class="entry-title page-title"><?php the_title(); ?></h1>
    <div class="entry-meta">
      <address class="vcard">Posted by <cite class="author fn"><a class="url" href="<?php echo get_author_posts_url( $authordata->ID, $authordata->user_nicename ) ?>" title="See all <?php the_author_posts() ?> posts by <?php the_author() ?>"><?php the_author() ?> <?php echo get_avatar(get_the_author_meta('user_email'), 36) ?></a></cite></address>
      <p>
        <time class="published" pubdate="pubdate" datetime="<?php the_time('Y-m-d\TH:i:sP') ?>" title="<?php the_time('Y-m-d\TH:i:sP') ?>"><?php the_time('F jS, Y') ?></time> &middot; <?php the_category(', ') ?> 
        <?php if ( current_user_can( 'edit_page', $post->ID ) ) : ?><span class="edit"><?php edit_post_link('Edit', '&middot; ', ''); ?></span><?php endif; ?>
      </p>
    </div>
    
    <div class="entry-content">
      <?php the_content(); ?>
    </div>
    
    <?php wp_link_pages(array('before' => '<p class="pages"><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number', 'link_before' => '<b>', 'link_after' => '</b>')); ?>
    
  <?php if (fc_show_spread() == true) : ?>
    <ul class="spread">
    <?php if (function_exists('sharethis_button')) : ?><li><?php sharethis_button(); ?></li><?php endif; ?> 
    <?php if ( (fc_get_comment_type_count('ping') > 0 ) || (pings_open()) ) : ?><li><a href="<?php trackback_url(display); ?>" title="See other sites linking to this post">Trackbacks (<?php fc_comment_type_count('ping') ?>)</a></li><?php endif; ?>
    <?php if ( (fc_get_comment_type_count('comment') > 0) || (comments_open()) ) : ?><li><a href="<?php comments_link(); ?>" title="Read comments on this post">Comments (<?php fc_comment_type_count('comment'); ?>)</a></li><?php endif; ?>
    </ul>
  <?php endif; ?>
  </article>

<?php comments_template(); ?>

<?php endwhile; else: ?>

  <h1 class="page-title">Not Found</h1>
  <p>Sorry, there is no post here.</p>

<?php endif; ?>

</section><!-- end #content-main -->
  
<?php get_sidebar(); ?>
<?php get_footer(); ?>

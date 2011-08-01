<?php
$search_count = 0;

$search = new WP_Query("s=$s & showposts=-1");
if($search->have_posts()) : while($search->have_posts()) : $search->the_post();
$search_count++;
endwhile; endif;
?>

<?php get_header(); ?>

  <section id="content-main" class="hfeed">

  <?php if (have_posts()) : ?>

    <h1 class="page-title">We found <?php echo $wp_query->found_posts; ?> results for &#8220;<?php the_search_query(); ?>&#8221;</h1>

    <?php while (have_posts()) : the_post(); ?>

      <div class="hentry search" id="post-<?php the_ID(); ?>">
        <h3 class="entry-title"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3>
        <p class="postmeta">Posted by <?php the_author() ?> on <time class="published" datetime="<?php the_time('Y-m-d\TH:i:sP') ?>" title="<?php the_time('Y-m-d\TH:i:sP') ?>"><?php the_time('F jS, Y') ?></time></p>
        <div class="entry-summary">
          <?php the_excerpt(); ?>
        </div>
      </div>

    <?php endwhile; ?>

    <?php if (fc_show_posts_nav()) : ?>
      <ul class="nav-paging">
        <?php if ( $paged < $wp_query->max_num_pages ) : ?><li class="prev"><?php next_posts_link(__('Older posts','mozblog')); ?></li><?php endif; ?>
        <?php if ( $paged > 1 ) : ?><li class="next"><?php previous_posts_link(__('Newer posts','mozblog')); ?></li><?php endif; ?>
      </ul>
    <?php endif; ?>

  <?php else : ?>

    <h1 class="page-title">No results for &#8220;<?php the_search_query(); ?>&#8221;</h1>
    <p>Try a different search?</p>
    <p><img src="<?php bloginfo('template_url'); ?>/img/notfound.png" alt="" width="400" height="300" class="image-center"></p>

  <?php endif; ?>

  </section>

<?php get_sidebar(); ?>
<?php get_footer(); ?>

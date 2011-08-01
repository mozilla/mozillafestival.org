<?php get_header(); ?>

<section id="content-main">
  <h1 class="page-title"><?php echo get_page_by_path('archives')->post_title; ?></h1>
  
  <?php if ( function_exists('fc_archives') ) : ?>
  <?php fc_archives(); ?>
  <?php else : ?>
  <p>The archives aren&#8217;t available right now, but we&#8217;re on it. You can still browse normally.</p>
  <?php endif; ?>

</section>
  
<?php get_sidebar(); ?>
<?php get_footer(); ?>

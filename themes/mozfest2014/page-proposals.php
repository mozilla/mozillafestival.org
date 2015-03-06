<?php
/*
 * Template Name: "Proposals" Page
 * Description:
 */
get_header();
?>

<div id="carousel">
  <div class="constrained">
    <?php while ( have_posts() ) : the_post(); ?>
        <h2><?php the_title(); ?></h2>
        <?php the_content(); ?>
      <?php endwhile; // end of the loop. ?>
  </div>
</div>

<div id="content" class="splash-2014 proposal-listings loading">
  <div class="constrained" id="proposal-listing">
    <div id="proposal-filter-container">
      <select name="proposal-filter" id="proposal-filter">
        <option value="all">All Proposals</option>
      </select>
    </div>
    <div id="proposals"></div>
  </div>
</div>

<script src="<?php echo get_template_directory_uri(); ?>/media/js/nunjucks.min.js"></script>
<script>nunjucks.configure( '<?php echo str_replace(home_url(), '', get_template_directory_uri()); ?>/media/partials' );</script>
<script src="<?php echo get_template_directory_uri(); ?>/media/js/selectize.min.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/media/js/proposal-listings.js"></script>

<?php

get_footer();

?>

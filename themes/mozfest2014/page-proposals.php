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

<div id="content" class="splash-2014 proposal-listings">
  <div class="constrained">

    <section class="session">
      <header>
        <h3>Session Title</h3>
        <p class="excerpt">This is a small preview of the summary</p>
      </header>
      <article>
        <h4>Subheading</h4>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Autem, illo, nemo impedit delectus cum tempora fugit repellendus illum fugiat dolorum. Rem molestias at voluptas laboriosam expedita totam accusantium ipsa temporibus.</p>
        <h4>Another Subheading</h4>
        <p>Consequuntur, esse, harum, fugit cupiditate ut dicta quam magnam possimus dolor expedita aliquid soluta eaque quis tempore repudiandae placeat voluptas quisquam culpa dignissimos ipsam optio deserunt ipsa! Blanditiis, incidunt, laborum.</p>
        <h4>And Another...</h4>
        <p>Atque temporibus adipisci inventore necessitatibus in sunt quod itaque deleniti! Sit, at, maiores architecto molestias ea perspiciatis eum cumque facere modi laudantium aliquid dolores aperiam illo animi magnam libero sed.</p>
        <h4>Yet Another Subheading</h4>
        <p>Sint, modi, cum sunt dicta illum dolorem quisquam. Ipsum, odit, beatae, dolorum, mollitia velit aliquid voluptatibus ea fuga quidem molestiae itaque similique error voluptate dolorem cum enim animi tenetur et.</p>
      </article>
    </section>

  </div>
</div>

<script src="<?php echo get_template_directory_uri(); ?>/media/js/proposals.js"></script>

<?php

get_footer();

?>

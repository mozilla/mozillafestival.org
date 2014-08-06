<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <?php if (is_single()): ?>
        <div class="panel">
            <div class="header"><h2><?php the_title(); ?></h2></div>
    <?php elseif (!is_page()): ?>
        <div class="panel">
            <div class="header"><h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3></div>
    <?php endif; ?>
    <?php the_content(); ?>
    <?php if (is_single()): ?>
        <?php if (has_tag()): ?>
            <div class="footer">
                <?php the_tags('<p><strong>Tagged:</strong> ', ', ', '</p>'); ?>
            </div>
        <?php endif; ?>
        </div>
    <?php elseif (!is_page()): ?>
        </div>
    <?php endif; ?>
</article>

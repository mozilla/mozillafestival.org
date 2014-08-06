<?php

get_header();

?>
<div id="content">
	<div class="constrained">
		<div id="content-inner">
<?php

if (have_posts()) {
	while (have_posts()) {
		the_post();
		$type = get_post_format();
		if (empty($type)) $type = get_post_type();
		get_template_part('content', $type);
	}

?>
<div class="page-nav">
    <div class="previous"><?php next_posts_link('&laquo; Older posts'); ?></div>
    <div class="next"><?php previous_posts_link('Newer posts &raquo;'); ?></div>
</div>
<?php

} else {
	get_template_part('content', 'missing');
}

?>
		</div>
<?php

get_sidebar();

?>
	</div>
</div>
<?php

get_footer();

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
} else {
	get_template_part('content', 'missing');
}

wp_reset_query();

?>
		</div>
<?php

get_sidebar();

?>
	</div>
</div>
<?php

get_footer();

?>

<?php

get_header();

?>
<div id="content">
	<div class="constrained">
		<div id="content-inner">
<?php

if (have_posts()) {
	global $wp_query;
	$term = $wp_query->get_queried_object();
	$taxonomy = get_taxonomy($term->taxonomy);

	echo '<h2><a href="'.site_url($taxonomy->rewrite['slug']).'/">'.$taxonomy->label.'</a> &raquo; '.$term->name.'</h2>';

	if ($term->description) {
		if ($term->taxonomy == 'theme') {
			echo '<div class="panel">';
			$content = explode('<p>', apply_filters('the_content', $term->description), 2);
			if (empty($content[0])) {
				$content = explode('</p>', $content[1], 2);
				echo '<div class="header"><h3>'.$content[0].'</h3></div>';
				$content = trim($content[1]);
			} else {
				$content = implode('<p>', $content);
			}
			echo '<div class="intro">';
			echo $content;
			echo '</div>';
			// echo '<p class="intro">'.nl2br($term->description).'</p>';
			echo '</div>';
		} else {
			echo '<div class="intro" style="margin-bottom: 2em;">';
			echo apply_filters('the_content', $term->description);
			echo '</div>';
		}
	}

	echo '<div class="panel">';
	echo '<div class="header"><h2>Sessions</h2></div>';
	while (have_posts()) {
		the_post();
		$type = get_post_format();
		if (empty($type)) $type = get_post_type();
		get_template_part('content', $type);
	}
	echo '</div>';
} else if (defined('TAXONOMY_ARCHIVE')) {
	get_template_part('taxonomy-list', TAXONOMY_ARCHIVE);
}

	get_template_part('taxonomy-more');

?>
		</div>
<?php

get_sidebar();

?>
	</div>
</div>
<?php

get_footer();

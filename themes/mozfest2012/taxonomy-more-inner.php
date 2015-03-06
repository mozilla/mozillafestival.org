<?php

foreach (array('theme'=>'Session Themes', 'format'=>'Session Formats') as $type => $label) {
	echo '<section><h3>'.$label.'</h3>';
	$terms = get_terms($type, array('hide_empty'=>0));
	if (!empty($terms)) {
		echo '<ul class="taxonomy-list '.$type.'">';
		foreach ((array) $terms as $term) {
			$link = get_term_link($term, $type);
			if (is_tax($type, $term->name)) {
				$selected = ' class="selected"';
			} else {
				$selected = '';
			}
			if (!is_wp_error($link)) {
				echo '<li'.$selected.'><a href="'.$link.'">'.$term->name.'</a></li>';
			}
		}
		echo '</ul>';
	}
	echo '</section>';
}

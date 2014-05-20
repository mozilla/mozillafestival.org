<?php

global $wp_registered_sidebars;

$sidebar = get_post_meta(get_the_ID(), 'page-sidebar', true);

if (empty($sidebar))
	$sidebar = 'default';

echo '<div id="sidebar">';
foreach ($wp_registered_sidebars as $name => $config) {
	if (strpos($name, $sidebar) === 0) {
		if (is_active_sidebar($name)) {
			$class = substr($name, strlen($sidebar) + 1);
			if (empty($class)) $class = 'primary';
			echo '<aside class="' . $class . '">';
			dynamic_sidebar($name);
			echo "</aside>\n";
		}
	}
}
echo '</div>';

?>
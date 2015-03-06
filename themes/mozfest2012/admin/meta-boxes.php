<?php

function __mf2012_page_sidebar_options() {
	global $wp_registered_sidebars;
	$sidebars = array();

	foreach ($wp_registered_sidebars as $name => $items) {
		$sidebar = array_shift(explode('-', $name));
		$sidebars[$sidebar] = ucwords(str_replace('_', ' ', $sidebar));
	}

	return $sidebars;
}

function mf2012_page_sidebar_meta_box ($post) {
	$sidebars = __mf2012_page_sidebar_options();
	$selected = get_post_meta($post->ID, 'page-sidebar', true);

	echo '<p><select name="page-sidebar">';
	foreach ($sidebars as $sidebar => $label) {
		printf('<option value="%s"%s>%s</option>', $sidebar, selected($selected, $sidebar), $label);
	}
	echo '</select></p>';

	wp_nonce_field('mf2012_page_sidebar_meta_box', 'mf2012_page_sidebar_meta_box_nonce');
	// echo '<pre>'.print_r($sidebar_list,1).'</pre>';
}

function mf2012_page_sidebar_meta_box_save ($post_id) {
    // Bail if we're doing an auto save
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

    // if our nonce isn't there, or we can't verify it, bail
    if (!isset($_POST['mf2012_page_sidebar_meta_box_nonce'])
			|| !wp_verify_nonce($_POST['mf2012_page_sidebar_meta_box_nonce'], 'mf2012_page_sidebar_meta_box'))
		return; 

    // if our current user can't edit this post, bail
    if (!current_user_can('edit_post')) return;

	$sidebars = array_keys(__mf2012_page_sidebar_options());
	$sidebar = $_POST['page-sidebar'];

	if ($sidebar == 'default' || !in_array($sidebar, $sidebars)) {
		delete_post_meta($post_id, 'page-sidebar');
	} else {
		update_post_meta($post_id, 'page-sidebar', $sidebar);
	}
}

add_action('save_post', 'mf2012_page_sidebar_meta_box_save');

function mf2012_add_meta_boxes () {
	add_meta_box('page-sidebar-div', __('Sidebar'), 'mf2012_page_sidebar_meta_box', 'page', 'side', 'core');
}

add_action('add_meta_boxes', 'mf2012_add_meta_boxes');
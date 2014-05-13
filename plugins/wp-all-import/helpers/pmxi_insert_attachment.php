<?php
function pmxi_insert_attachment($object, $file = false, $parent = 0) {
	global $wpdb;

	$user_id = get_current_user_id();

	$defaults = array('post_status' => 'inherit', 'post_type' => 'post', 'post_author' => $user_id,
		'ping_status' => get_option('default_ping_status'), 'post_parent' => 0, 'post_title' => '',
		'menu_order' => 0, 'to_ping' =>  '', 'pinged' => '', 'post_password' => '', 'post_content' => '',
		'guid' => '', 'post_content_filtered' => '', 'post_excerpt' => '', 'import_id' => 0, 'context' => '');

	$object = wp_parse_args($object, $defaults);
	if ( !empty($parent) )
		$object['post_parent'] = $parent;

	unset( $object[ 'filter' ] );

	$object = sanitize_post($object, 'db');

	// export array as variables
	extract($object, EXTR_SKIP);

	if ( empty($post_author) )
		$post_author = $user_id;

	$post_type = 'attachment';

	if ( ! in_array( $post_status, array( 'inherit', 'private' ) ) )
		$post_status = 'inherit';

	if ( !empty($post_category) )
		$post_category = array_filter($post_category); // Filter out empty terms

	// Make sure we set a valid category.
	if ( empty($post_category) || 0 == count($post_category) || !is_array($post_category) ) {
		$post_category = array();
	}

	// Are we updating or creating?
	if ( !empty($ID) ) {
		$update = true;
		$post_ID = (int) $ID;
	} else {
		$update = false;
		$post_ID = 0;
	}

	// Create a valid post name.
	if ( empty($post_name) )
		$post_name = sanitize_title($post_title);
	else
		$post_name = sanitize_title($post_name);

	// expected_slashed ($post_name)
	$post_name = wp_unique_post_slug($post_name, $post_ID, $post_status, $post_type, $post_parent);

	if ( empty($post_date) )
		$post_date = current_time('mysql');
	if ( empty($post_date_gmt) )
		$post_date_gmt = current_time('mysql', 1);

	if ( empty($post_modified) )
		$post_modified = $post_date;
	if ( empty($post_modified_gmt) )
		$post_modified_gmt = $post_date_gmt;

	if ( empty($comment_status) ) {
		if ( $update )
			$comment_status = 'closed';
		else
			$comment_status = get_option('default_comment_status');
	}
	if ( empty($ping_status) )
		$ping_status = get_option('default_ping_status');

	if ( isset($to_ping) )
		$to_ping = preg_replace('|\s+|', "\n", $to_ping);
	else
		$to_ping = '';

	if ( isset($post_parent) )
		$post_parent = (int) $post_parent;
	else
		$post_parent = 0;

	if ( isset($menu_order) )
		$menu_order = (int) $menu_order;
	else
		$menu_order = 0;

	if ( !isset($post_password) )
		$post_password = '';

	if ( ! isset($pinged) )
		$pinged = '';

	// expected_slashed (everything!)
	$data = compact( array( 'post_author', 'post_date', 'post_date_gmt', 'post_content', 'post_content_filtered', 'post_title', 'post_excerpt', 'post_status', 'post_type', 'comment_status', 'ping_status', 'post_password', 'post_name', 'to_ping', 'pinged', 'post_modified', 'post_modified_gmt', 'post_parent', 'menu_order', 'post_mime_type', 'guid' ) );
	$data = wp_unslash( $data );

	if ( $update ) {
		$wpdb->update( $wpdb->posts, $data, array( 'ID' => $post_ID ) );
	} else {
		// If there is a suggested ID, use it if not already present
		if ( !empty($import_id) ) {
			$import_id = (int) $import_id;
			if ( ! $wpdb->get_var( $wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE ID = %d", $import_id) ) ) {
				$data['ID'] = $import_id;
			}
		}

		$wpdb->insert( $wpdb->posts, $data );
		$post_ID = (int) $wpdb->insert_id;
	}

	if ( empty($post_name) ) {
		$post_name = sanitize_title($post_title, $post_ID);
		$wpdb->update( $wpdb->posts, compact("post_name"), array( 'ID' => $post_ID ) );
	}

	if ( $file )
		update_attached_file( $post_ID, $file );

	clean_post_cache( $post_ID );

	if ( ! empty( $context ) )
		add_post_meta( $post_ID, '_wp_attachment_context', $context, true );	

	return $post_ID;
}
?>
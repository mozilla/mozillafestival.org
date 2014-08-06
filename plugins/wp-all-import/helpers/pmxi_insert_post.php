<?php

function pmxi_insert_post($postarr, $wp_error = false){
	global $wpdb;

	$user_id = get_current_user_id();

	$defaults = array('post_status' => 'draft', 'post_type' => 'post', 'post_author' => $user_id,
		'ping_status' => get_option('default_ping_status'), 'post_parent' => 0,
		'menu_order' => 0, 'to_ping' =>  '', 'pinged' => '', 'post_password' => '',
		'guid' => '', 'post_content_filtered' => '', 'post_excerpt' => '', 'import_id' => 0,
		'post_content' => '', 'post_title' => '');

	$postarr = wp_parse_args($postarr, $defaults);	

	$postarr = sanitize_post($postarr, 'db');

	// export array as variables
	extract($postarr, EXTR_SKIP);

	// Are we updating or creating?
	$post_ID = 0;
	$update = false;
	if ( ! empty( $ID ) ) {
		$update = true;

		// Get the post ID and GUID
		$post_ID = $ID;
		$post_before = get_post( $post_ID );
		if ( is_null( $post_before ) ) {
			if ( $wp_error )
				return new WP_Error( 'invalid_post', __( 'Invalid post ID.' ) );
			return 0;
		}

		$guid = get_post_field( 'guid', $post_ID );
		$previous_status = get_post_field('post_status', $ID);
	} else {
		$previous_status = 'new';
	}	

	if ( empty($post_type) )
		$post_type = 'post';

	if ( empty($post_status) )
		$post_status = 'draft';	

	// Make sure we set a valid category.
	if ( empty($post_category) || 0 == count($post_category) || !is_array($post_category) ) {
		// 'post' requires at least one category.
		if ( 'post' == $post_type && 'auto-draft' != $post_status )
			$post_category = array( get_option('default_category') );
		else
			$post_category = array();
	}

	if ( empty($post_author) )
		$post_author = $user_id;	

	// Create a valid post name. Drafts and pending posts are allowed to have an empty
	// post name.
	if ( empty($post_name) ) {
		if ( !in_array( $post_status, array( 'draft', 'pending', 'auto-draft' ) ) )
			$post_name = sanitize_title($post_title);
		else
			$post_name = '';
	} else {
		// On updates, we need to check to see if it's using the old, fixed sanitization context.
		$check_name = sanitize_title( $post_name, '', 'old-save' );
		if ( $update && strtolower( urlencode( $post_name ) ) == $check_name && get_post_field( 'post_name', $ID ) == $check_name )
			$post_name = $check_name;
		else // new post, or slug has changed.
			$post_name = sanitize_title($post_name);
	}

	// If the post date is empty (due to having been new or a draft) and status is not 'draft' or 'pending', set date to now
	if ( empty($post_date) || '0000-00-00 00:00:00' == $post_date )
		$post_date = current_time('mysql');

		// validate the date
		$mm = substr( $post_date, 5, 2 );
		$jj = substr( $post_date, 8, 2 );
		$aa = substr( $post_date, 0, 4 );
		$valid_date = wp_checkdate( $mm, $jj, $aa, $post_date );
		if ( !$valid_date ) {
			if ( $wp_error )
				return new WP_Error( 'invalid_date', __( 'Whoops, the provided date is invalid.' ) );
			else
				return 0;
		}

	if ( empty($post_date_gmt) || '0000-00-00 00:00:00' == $post_date_gmt ) {
		if ( !in_array( $post_status, array( 'draft', 'pending', 'auto-draft' ) ) )
			$post_date_gmt = get_gmt_from_date($post_date);
		else
			$post_date_gmt = '0000-00-00 00:00:00';
	}

	if ( $update || '0000-00-00 00:00:00' == $post_date ) {
		$post_modified     = current_time( 'mysql' );
		$post_modified_gmt = current_time( 'mysql', 1 );
	} else {
		$post_modified     = $post_date;
		$post_modified_gmt = $post_date_gmt;
	}

	if ( 'publish' == $post_status ) {
		$now = gmdate('Y-m-d H:i:59');
		if ( mysql2date('U', $post_date_gmt, false) > mysql2date('U', $now, false) )
			$post_status = 'future';
	} elseif( 'future' == $post_status ) {
		$now = gmdate('Y-m-d H:i:59');
		if ( mysql2date('U', $post_date_gmt, false) <= mysql2date('U', $now, false) )
			$post_status = 'publish';
	}

	if ( empty($comment_status) ) {
		if ( $update )
			$comment_status = 'closed';
		else
			$comment_status = get_option('default_comment_status');
	}
	if ( empty($ping_status) )
		$ping_status = get_option('default_ping_status');

	if ( isset($to_ping) )
		$to_ping = sanitize_trackback_urls( $to_ping );
	else
		$to_ping = '';

	if ( ! isset($pinged) )
		$pinged = '';

	if ( isset($post_parent) )
		$post_parent = (int) $post_parent;
	else
		$post_parent = 0;

	// Check the post_parent to see if it will cause a hierarchy loop
	$post_parent = apply_filters( 'wp_insert_post_parent', $post_parent, $post_ID, compact( array_keys( $postarr ) ), $postarr );

	if ( isset($menu_order) )
		$menu_order = (int) $menu_order;
	else
		$menu_order = 0;	

	$post_name = wp_unique_post_slug($post_name, $post_ID, $post_status, $post_type, $post_parent);

	// expected_slashed (everything!)
	$data = compact( array( 'post_author', 'post_date', 'post_date_gmt', 'post_content', 'post_content_filtered', 'post_title', 'post_excerpt', 'post_status', 'post_type', 'comment_status', 'ping_status', 'post_password', 'post_name', 'to_ping', 'pinged', 'post_modified', 'post_modified_gmt', 'post_parent', 'menu_order', 'guid' ) );	
	$data = wp_unslash( $data );
	$where = array( 'ID' => $post_ID );

	if ( $update ) {		
		if ( false === $wpdb->update( $wpdb->posts, $data, $where ) ) {
			if ( $wp_error )
				return new WP_Error('db_update_error', __('Could not update post in the database'), $wpdb->last_error);
			else
				return 0;
		}
	} else {
		if ( isset($post_mime_type) )
			$data['post_mime_type'] = wp_unslash( $post_mime_type ); // This isn't in the update				
		if ( false === $wpdb->insert( $wpdb->posts, $data ) ) {
			if ( $wp_error )
				return new WP_Error('db_insert_error', __('Could not insert post into the database'), $wpdb->last_error);
			else
				return 0;
		}
		$post_ID = (int) $wpdb->insert_id;

		// use the newly generated $post_ID
		$where = array( 'ID' => $post_ID );
	}
	
	if ( isset( $tags_input ) && is_object_in_taxonomy($post_type, 'post_tag') )
		wp_set_post_tags( $post_ID, $tags_input );
	
	$current_guid = get_post_field( 'guid', $post_ID );

	// Set GUID
	if ( !$update && '' == $current_guid )
		$wpdb->update( $wpdb->posts, array( 'guid' => get_permalink( $post_ID ) ), $where );	

	return $post_ID;
}

?>
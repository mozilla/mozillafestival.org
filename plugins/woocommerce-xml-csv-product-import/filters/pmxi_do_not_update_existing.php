<?php
function pmwi_pmxi_do_not_update_existing($current_post_ids, $post_to_update_id){
	$children = get_posts( array(
		'post_parent' 	=> $post_to_update_id,
		'posts_per_page'=> -1,
		'post_type' 	=> 'product_variation',
		'fields' 		=> 'ids',
		'post_status'	=> 'publish'
	) );

	if ( $children ) {
		foreach ( $children as $child ) {																		
			if ( ! in_array($child, $current_post_ids) ) $current_post_ids[] = $child;													
		}
	}
	return $current_post_ids;
}
?>
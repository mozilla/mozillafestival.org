<?php 

function pmxi_delete_post($post_id) {
	$post = new PMXI_Post_Record();
	$post->get_by_post_id($post_id)->isEmpty() or $post->delete();
}
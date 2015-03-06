<?php
/**
 * Delete attachments linked to a specified post
 * @param int $parent_id Parent id of post to delete attachments for
 */
function wp_delete_attachments($parent_id, $unlink = true, $type = '') {	
	foreach (get_posts(array('post_parent' => $parent_id, 'post_type' => 'attachment', 'numberposts' => -1, 'post_status' => null)) as $attach) {
		if ($type == 'files' and ! wp_attachment_is_image( $attach->ID ) ){
			wp_delete_attachment($attach->ID, true);							
		}	
		elseif ( ($type == 'images' and wp_attachment_is_image( $attach->ID )) or "" == $type){

			if ($unlink or ! wp_attachment_is_image( $attach->ID )){ 
				wp_delete_attachment($attach->ID, true);
			}
			else{
				global $wpdb;
				$sql = "delete a,b,c
					FROM ".$wpdb->posts." a
					LEFT JOIN ".$wpdb->term_relationships." b ON ( a.ID = b.object_id )
					LEFT JOIN ".$wpdb->postmeta." c ON ( a.ID = c.post_id )
					LEFT JOIN ".$wpdb->posts." d ON ( a.ID = d.post_parent )
					WHERE a.ID = ".$attach->ID.";";

					$wpdb->query( 
						$wpdb->prepare($sql, '')
					);
			}
		}		
	}
}
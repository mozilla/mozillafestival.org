<?php

/**
 * Find duplicates according to settings
 */
function pmxi_findDuplicates($articleData, $custom_duplicate_name = '', $custom_duplicate_value = '', $duplicate_indicator = 'title')
{		
	global $wpdb;

	if ('custom field' == $duplicate_indicator){
		$duplicate_ids = array();
		$args = array(
			'post_type' => $articleData['post_type'],
			'meta_query' => array(
				array(
					'key' => $custom_duplicate_name,
					'value' => $custom_duplicate_value,						
				)
			)
		);			
		$query = new WP_Query( $args );
		
		if ( $query->have_posts() ) $duplicate_ids[] = $query->post->ID;

		wp_reset_postdata();

		return $duplicate_ids;
	}
	elseif('parent' == $duplicate_indicator){

		$field = 'post_title'; // post_title or post_content			
		return $wpdb->get_col($wpdb->prepare("
			SELECT ID FROM " . $wpdb->posts . "
			WHERE
				post_type = %s
				AND ID != %s
				AND post_parent = %s
				AND REPLACE(REPLACE(REPLACE($field, ' ', ''), '\\t', ''), '\\n', '') = %s
			",
			$articleData['post_type'],
			isset($articleData['ID']) ? $articleData['ID'] : 0,
			(!empty($articleData['post_parent'])) ? $articleData['post_parent'] : 0,
			preg_replace('%[ \\t\\n]%', '', $articleData[$field])
		));
	}
	else{
		$field = 'post_' . $duplicate_indicator; // post_title or post_content
		return $wpdb->get_col($wpdb->prepare("
			SELECT ID FROM " . $wpdb->posts . "
			WHERE
				post_type = %s
				AND ID != %s
				AND REPLACE(REPLACE(REPLACE($field, ' ', ''), '\\t', ''), '\\n', '') = %s
			",
			$articleData['post_type'],
			isset($articleData['ID']) ? $articleData['ID'] : 0,
			preg_replace('%[ \\t\\n]%', '', $articleData[$field])
		));
	}
}

?>
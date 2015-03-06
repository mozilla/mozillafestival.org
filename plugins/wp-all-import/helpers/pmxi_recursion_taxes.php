<?php
function pmxi_recursion_taxes($parent, $tx_name, $txes, $key){

	if (is_array($parent)){
		$parent['name'] = sanitize_text_field($parent['name']);
		if (empty($parent['parent'])){				

			$term = is_exists_term($tx_name, $parent['name']);				

			if ( empty($term) and !is_wp_error($term) ){
				$term = wp_insert_term(
					$parent['name'], // the term 
				  	$tx_name // the taxonomy			  	
				);
			}
			return ( ! is_wp_error($term)) ? $term['term_id'] : 0;
		}
		else{
			$parent_id = pmxi_recursion_taxes($parent['parent'], $tx_name, $txes, $key);
			
			$term = is_exists_term($tx_name, $parent['name'], (int)$parent_id);				

			if ( empty($term) and  !is_wp_error($term) ){
				$term = wp_insert_term(
					$parent, // the term 
				  	$tx_name, // the taxonomy			  	
				  	array('parent'=> (!empty($parent_id)) ? $parent_id : 0)
				);
			}
			return ( ! is_wp_error($term)) ? $term['term_id'] : 0;
		}			
	}
	else{	

		if ( !empty($txes[$key - 1]) and !empty($txes[$key - 1]['parent']) and $parent != $txes[$key - 1]['parent']) {				
			$parent_id = pmxi_recursion_taxes($txes[$key - 1]['parent'], $tx_name, $txes, $key - 1);
			
			$term = is_exists_term($tx_name, $parent, (int)$parent_id);

			if ( empty($term) and !is_wp_error($term) ){
				$term = wp_insert_term(
					$parent, // the term 
				  	$tx_name, // the taxonomy			  	
				  	array('parent'=> (!empty($parent_id)) ? $parent_id : 0)
				);
			}
			return ( ! is_wp_error($term)) ? $term['term_id'] : 0;
		}
		else{
			
			$term = is_exists_term($tx_name, $parent);
			if ( empty($term) and !is_wp_error($term) ){					
				$term = wp_insert_term(
					$parent, // the term 
				  	$tx_name // the taxonomy			  	
				);
			}				
			return ( ! is_wp_error($term)) ? $term['term_id'] : 0;
		}
	}

}
?>
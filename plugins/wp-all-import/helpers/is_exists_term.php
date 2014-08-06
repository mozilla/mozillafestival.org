<?php
if (!function_exists('is_exists_term')){
	
	function is_exists_term($tx_name, $name, $parent_id = ''){		

		return term_exists( $name, $tx_name, $parent_id );	
		
	}
}
?>
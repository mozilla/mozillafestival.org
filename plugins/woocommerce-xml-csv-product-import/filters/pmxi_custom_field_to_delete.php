<?php
function pmwi_pmxi_custom_field_to_delete($field_to_delete, $pid, $post_type, $options, $cur_meta_key){

	if ($field_to_delete === false || $post_type != "product") return $field_to_delete;

	// Do not update attributes
	if ($options['update_all_data'] == 'no' and ! $options['is_update_attributes'] and (in_array($cur_meta_key, array('_default_attributes', '_product_attributes')) or strpos($cur_meta_key, "attribute_") === 0)) return false;
	
	// Update only these Attributes, leave the rest alone
	if ($options['update_all_data'] == 'no' and $options['is_update_attributes'] and $options['update_attributes_logic'] == 'only'){
		
		if ($cur_meta_key == '_product_attributes'){
			$current_product_attributes = get_post_meta($pid, '_product_attributes', true);
			if ( ! empty($current_product_attributes) and ! empty($options['attributes_list']) and is_array($options['attributes_list'])) 
				foreach ($current_product_attributes as $attr_name => $attr_value) {
					if ( in_array($attr_name, array_filter($options['attributes_list'], 'trim'))) unset($current_product_attributes[$attr_name]);
				}

			update_post_meta($pid, '_product_attributes', $current_product_attributes);
			return false;
		}

		if ( strpos($cur_meta_key, "attribute_") === 0 and ! empty($options['attributes_list']) and is_array($options['attributes_list']) and ! in_array(str_replace("attribute_", "", $cur_meta_key), array_filter($options['attributes_list'], 'trim'))) return false;

		if (in_array($cur_meta_key, array('_default_attributes'))) return false;

	}

	// Leave these attributes alone, update all other Attributes
	if ($options['update_all_data'] == 'no' and $options['is_update_attributes'] and $options['update_attributes_logic'] == 'all_except'){
		
		if ($cur_meta_key == '_product_attributes'){
			
			if (empty($options['attributes_list'])) { delete_post_meta($pid, $cur_meta_key); return false; }

			$current_product_attributes = get_post_meta($pid, '_product_attributes', true);
			if ( ! empty($current_product_attributes) and ! empty($options['attributes_list']) and is_array($options['attributes_list'])) 
				foreach ($current_product_attributes as $attr_name => $attr_value) {
					if ( ! in_array($attr_name, array_filter($options['attributes_list'], 'trim'))) unset($current_product_attributes[$attr_name]);
				}
				
			update_post_meta($pid, '_product_attributes', $current_product_attributes);
			return false;
		}

		if ( strpos($cur_meta_key, "attribute_") === 0 and ! empty($options['attributes_list']) and is_array($options['attributes_list']) and in_array(str_replace("attribute_", "", $cur_meta_key), array_filter($options['attributes_list'], 'trim'))) return false;

		if (in_array($cur_meta_key, array('_default_attributes'))) return false;
	}
	
	return true;		
}
?>
<?php
function pmwi_pmxi_custom_field_to_update( $field_to_update, $post_type, $options, $m_key ){

	if ($field_to_update === false || $post_type != 'product') return $field_to_update;	

	if ($options['update_attributes_logic'] == 'full_update') return true;
	if ($options['is_update_attributes'] and $options['update_attributes_logic'] == "only" and ! empty($options['attributes_list']) and is_array($options['attributes_list']) and in_array(str_replace("attribute_", "", $m_key), $options['attributes_list']) ) return true;
	if ($options['is_update_attributes'] and $options['update_attributes_logic'] == "all_except" and ( empty($options['attributes_list']) or ! in_array(str_replace("attribute_", "", $m_key), $options['attributes_list']) )) return true;

	return false;
}
?>
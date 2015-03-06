<?php
function pmwi_pmxi_reimport($entry, $post){

	if ( $entry != "product" ) return;

	$all_existing_attributes = array();
	$hide_taxonomies = array('product_type');
	$post_taxonomies = array_diff_key(get_taxonomies_by_object_type(array($entry), 'object'), array_flip($hide_taxonomies));
	if (!empty($post_taxonomies)): 
		foreach ($post_taxonomies as $ctx):  if ("" == $ctx->labels->name or strpos($ctx->name, "pa_") === false) continue;
			$all_existing_attributes[] = $ctx->name;												
		endforeach;
	endif;
	if (!empty($existing_attributes)):
		foreach ($existing_attributes as $key => $attr) {
			$all_existing_attributes[] = $attr;												
		}
	endif;

	?>
	<div class="input">
		<input type="hidden" name="attributes_list" value="0" />			
		<input type="hidden" name="is_update_attributes" value="0" />
		<input type="checkbox" id="is_update_attributes_<?php echo $entry; ?>" name="is_update_attributes" value="1" <?php echo $post['is_update_attributes'] ? 'checked="checked"': '' ?>  class="switcher"/>
		<label for="is_update_attributes_<?php echo $entry; ?>"><?php _e('Attributes', 'pmxi_plugin') ?></label>
		<!--a href="#help" class="help" title="<?php _e('If Keep Custom Fields box is checked, it will keep all Custom Fields, and add any new Custom Fields specified in Custom Fields section, as long as they do not overwrite existing fields. If \'Only keep this Custom Fields\' is specified, it will only keep the specified fields.', 'pmxi_plugin') ?>">?</a-->
		<div class="switcher-target-is_update_attributes_<?php echo $entry; ?>" style="padding-left:17px;">
			<div class="input">
				<input type="radio" id="update_attributes_logic_full_update_<?php echo $entry; ?>" name="update_attributes_logic" value="full_update" <?php echo ( "full_update" == $post['update_attributes_logic'] ) ? 'checked="checked"': '' ?> class="switcher"/>
				<label for="update_attributes_logic_full_update_<?php echo $entry; ?>"><?php _e('Update all Attributes', 'pmxi_plugin') ?></label>								
			</div>
			<div class="input">
				<input type="radio" id="update_attributes_logic_only_<?php echo $entry; ?>" name="update_attributes_logic" value="only" <?php echo ( "only" == $post['update_attributes_logic'] ) ? 'checked="checked"': '' ?> class="switcher"/>
				<label for="update_attributes_logic_only_<?php echo $entry; ?>"><?php _e('Update only these Attributes, leave the rest alone', 'pmxi_plugin') ?></label>								
				<div class="switcher-target-update_attributes_logic_only_<?php echo $entry; ?> pmxi_choosen" style="padding-left:17px;">										
					
					<span class="hidden choosen_values"><?php if (!empty($all_existing_attributes)) echo implode(',', $all_existing_attributes);?></span>
					<input class="choosen_input" value="<?php if (!empty($post['attributes_list']) and "only" == $post['update_attributes_logic']) echo implode(',', $post['attributes_list']); ?>" type="hidden" name="attributes_only_list"/>																				
				</div>
			</div>
			<div class="input">
				<input type="radio" id="update_attributes_logic_all_except_<?php echo $entry; ?>" name="update_attributes_logic" value="all_except" <?php echo ( "all_except" == $post['update_attributes_logic'] ) ? 'checked="checked"': '' ?> class="switcher"/>
				<label for="update_attributes_logic_all_except_<?php echo $entry; ?>"><?php _e('Leave these attributes alone, update all other Attributes', 'pmxi_plugin') ?></label>								
				<div class="switcher-target-update_attributes_logic_all_except_<?php echo $entry; ?> pmxi_choosen" style="padding-left:17px;">
					
					<span class="hidden choosen_values"><?php if (!empty($all_existing_attributes)) echo implode(',', $all_existing_attributes);?></span>
					<input class="choosen_input" value="<?php if (!empty($post['attributes_list']) and "all_except" == $post['update_attributes_logic']) echo implode(',', $post['attributes_list']); ?>" type="hidden" name="attributes_except_list"/>																														
				</div>
			</div>
		</div>
	</div>	
	<?php
}
?>
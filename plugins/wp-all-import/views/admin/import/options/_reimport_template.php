<tr>
	<td colspan="3" style="padding-top:20px;">
		<fieldset class="optionsset">
			<legend><?php _e('Record Matching','pmxi_plugin');?></legend>		
			<div class="input" style="margin-bottom:15px; position:relative;">
				<input type="button" value="<?php _e('Click to see hints', 'pmxi_plugin');?>" class="button-primary pmxi_tips_pointer">				
				<input type="radio" id="auto_matching_<?php echo $entry; ?>" class="switcher" name="duplicate_matching" value="auto" <?php echo 'manual' != $post['duplicate_matching'] ? 'checked="checked"': '' ?>/>
				<label for="auto_matching_<?php echo $entry; ?>"><?php _e('Automatic Record Matching', 'pmxi_plugin' )?></label><br>
				<div class="switcher-target-auto_matching_<?php echo $entry; ?>"  style="padding-left:17px;">
					<div class="input">
						<label><?php _e("Unique key"); ?></label>
						
						<input type="text" class="smaller-text" name="unique_key" style="width:300px;" value="<?php echo esc_attr($post['unique_key']) ?>" <?php echo  ( ! $isWizard ) ? 'disabled="disabled"' : '' ?>/>
						<a href="#help" class="help" title="<?php _e('If posts are being updated and not just created during a brand new import, the problem is that the value of the unique key is not unique for each record.', 'pmxi_plugin') ?>">?</a>
					</div>
				</div>
				<input type="radio" id="manual_matching_<?php echo $entry; ?>" class="switcher" name="duplicate_matching" value="manual" <?php echo 'manual' == $post['duplicate_matching'] ? 'checked="checked"': '' ?>/>
				<label for="manual_matching_<?php echo $entry; ?>"><?php _e('Manual Record Matching', 'pmxi_plugin' )?></label>
				<div class="switcher-target-manual_matching_<?php echo $entry; ?>" style="padding-left:17px;">
					<div class="input">						
						<input type="radio" id="duplicate_indicator_title_<?php echo $entry; ?>" class="switcher" name="duplicate_indicator" value="title" <?php echo 'title' == $post['duplicate_indicator'] ? 'checked="checked"': '' ?>/>
						<label for="duplicate_indicator_title_<?php echo $entry; ?>"><?php _e('match by Title', 'pmxi_plugin' )?></label><br>
						<input type="radio" id="duplicate_indicator_content_<?php echo $entry; ?>" class="switcher" name="duplicate_indicator" value="content" <?php echo 'content' == $post['duplicate_indicator'] ? 'checked="checked"': '' ?>/>
						<label for="duplicate_indicator_content_<?php echo $entry; ?>"><?php _e('match by Content', 'pmxi_plugin' )?></label><br>
						<input type="radio" id="duplicate_indicator_custom_field_<?php echo $entry; ?>" class="switcher" name="duplicate_indicator" value="custom field" <?php echo 'custom field' == $post['duplicate_indicator'] ? 'checked="checked"': '' ?>/>
						<label for="duplicate_indicator_custom_field_<?php echo $entry; ?>"><?php _e('match by Custom field', 'pmxi_plugin' )?></label><br>
						<span class="switcher-target-duplicate_indicator_custom_field_<?php echo $entry; ?>" style="vertical-align:middle; padding-left:17px;">
							<?php _e('Name', 'pmxi_plugin') ?>
							<input type="text" name="custom_duplicate_name" value="<?php echo esc_attr($post['custom_duplicate_name']) ?>" />
							<?php _e('Value', 'pmxi_plugin') ?>
							<input type="text" name="custom_duplicate_value" value="<?php echo esc_attr($post['custom_duplicate_value']) ?>" />
						</span>
					</div>
				</div>
			</div>
			<hr>
			<div class="input">
				<input type="hidden" name="create_new_records" value="0" />
				<input type="checkbox" id="create_new_records_<?php echo $entry; ?>" name="create_new_records" value="1" <?php echo $post['create_new_records'] ? 'checked="checked"' : '' ?> />
				<label for="create_new_records_<?php echo $entry; ?>"><?php _e('Create new posts from records newly present in your file', 'pmxi_plugin') ?></label>
			</div>
			<div class="input">
				<input type="hidden" name="is_delete_missing" value="0" />
				<input type="checkbox" id="is_delete_missing_<?php echo $entry; ?>" name="is_delete_missing" value="1" <?php echo $post['is_delete_missing'] ? 'checked="checked"': '' ?> class="switcher"/>
				<label for="is_delete_missing_<?php echo $entry; ?>"><?php _e('Delete posts that are no longer present in your file', 'pmxi_plugin') ?></label>
				<a href="#help" class="help" title="<?php _e('Check this option if you want to delete posts from the previous import operation which are not found among newly imported set.', 'pmxi_plugin') ?>">?</a>
			</div>
			<div class="switcher-target-is_delete_missing_<?php echo $entry; ?>" style="padding-left:17px;">
				<div class="input">
					<input type="hidden" name="is_keep_attachments" value="0" />
					<input type="checkbox" id="is_keep_attachments_<?php echo $entry; ?>" name="is_keep_attachments" value="1" <?php echo $post['is_keep_attachments'] ? 'checked="checked"': '' ?> />
					<label for="is_keep_attachments_<?php echo $entry; ?>"><?php _e('Do not remove attachments', 'pmxi_plugin') ?></label>
					<a href="#help" class="help" title="<?php _e('Check this option if you want attachments like *.pdf or *.doc files to be kept in media library after parent post or page is removed or replaced during reimport operation.', 'pmxi_plugin') ?>">?</a>
				</div>
				<div class="input">
					<input type="hidden" name="is_keep_imgs" value="0" />
					<input type="checkbox" id="is_keep_imgs_<?php echo $entry; ?>" name="is_keep_imgs" value="1" <?php echo $post['is_keep_imgs'] ? 'checked="checked"': '' ?> />
					<label for="is_keep_imgs_<?php echo $entry; ?>"><?php _e('Do not remove images', 'pmxi_plugin') ?></label>
					<a href="#help" class="help" title="<?php _e('Check this option if you want images like featured image to be kept in media library after parent post or page is removed or replaced during reimport operation.', 'pmxi_plugin') ?>">?</a>
				</div>
				<div class="input">
					<input type="hidden" name="is_update_missing_cf" value="0" />
					<input type="checkbox" id="is_update_missing_cf_<?php echo $entry; ?>" name="is_update_missing_cf" value="1" <?php echo $post['is_update_missing_cf'] ? 'checked="checked"': '' ?> class="switcher"/>
					<label for="is_update_missing_cf_<?php echo $entry; ?>"><?php _e('Instead of deletion, set Custom Field', 'pmxi_plugin') ?></label>
					<a href="#help" class="help" title="<?php _e('Check this option if you want to update posts custom fields from the previous import operation which are not found among newly imported set.', 'pmxi_plugin') ?>">?</a>			
					<div class="switcher-target-is_update_missing_cf_<?php echo $entry; ?>" style="padding-left:17px;">
						<div class="input">
							<?php _e('Name', 'pmxi_plugin') ?>
							<input type="text" name="update_missing_cf_name" value="<?php echo esc_attr($post['update_missing_cf_name']) ?>" />
							<?php _e('Value', 'pmxi_plugin') ?>
							<input type="text" name="update_missing_cf_value" value="<?php echo esc_attr($post['update_missing_cf_value']) ?>" />									
						</div>
					</div>
				</div>
				<div class="input">
					<input type="hidden" name="set_missing_to_draft" value="0" />
					<input type="checkbox" id="set_missing_to_draft_<?php echo $entry; ?>" name="set_missing_to_draft" value="1" <?php echo $post['set_missing_to_draft'] ? 'checked="checked"': '' ?> />
					<label for="set_missing_to_draft_<?php echo $entry; ?>"><?php _e('Instead of deletion, change post status to Draft', 'pmxi_plugin') ?></label>					
				</div>
			</div>			
			<div class="input">
				<input type="hidden" id="is_keep_former_posts_<?php echo $entry; ?>" name="is_keep_former_posts" value="yes" />				
				<input type="checkbox" id="is_not_keep_former_posts_<?php echo $entry; ?>" name="is_keep_former_posts" value="no" <?php echo "yes" != $post['is_keep_former_posts'] ? 'checked="checked"': '' ?> class="switcher" />
				<label for="is_not_keep_former_posts_<?php echo $entry; ?>"><?php _e('Update existing posts with changed data in your file', 'pmxi_plugin') ?></label>

				<div class="switcher-target-is_not_keep_former_posts_<?php echo $entry; ?>" style="padding-left:17px;">
					<input type="radio" id="update_all_data_<?php echo $entry; ?>" class="switcher" name="update_all_data" value="yes" <?php echo 'no' != $post['update_all_data'] ? 'checked="checked"': '' ?>/>
					<label for="update_all_data_<?php echo $entry; ?>"><?php _e('Update all data', 'pmxi_plugin' )?></label><br>
					
					<input type="radio" id="update_choosen_data_<?php echo $entry; ?>" class="switcher" name="update_all_data" value="no" <?php echo 'no' == $post['update_all_data'] ? 'checked="checked"': '' ?>/>
					<label for="update_choosen_data_<?php echo $entry; ?>"><?php _e('Choose which data to update', 'pmxi_plugin' )?></label><br>
					<div class="switcher-target-update_choosen_data_<?php echo $entry; ?>"  style="padding-left:17px;">
						<div class="input">
							<input type="hidden" name="is_update_status" value="0" />
							<input type="checkbox" id="is_update_status_<?php echo $entry; ?>" name="is_update_status" value="1" <?php echo $post['is_update_status'] ? 'checked="checked"': '' ?> />
							<label for="is_update_status_<?php echo $entry; ?>"><?php _e('Post status', 'pmxi_plugin') ?></label>
							<a href="#help" class="help" title="<?php _e('Check this option if you want previously imported posts to change their publish status or being restored from Trash.', 'pmxi_plugin') ?>">?</a>
						</div>
						<div class="input">
							<input type="hidden" name="is_update_title" value="0" />
							<input type="checkbox" id="is_update_title_<?php echo $entry; ?>" name="is_update_title" value="1" <?php echo $post['is_update_title'] ? 'checked="checked"': '' ?> />
							<label for="is_update_title_<?php echo $entry; ?>"><?php _e('Title', 'pmxi_plugin') ?></label>
						</div>
						<div class="input">
							<input type="hidden" name="is_update_slug" value="0" />
							<input type="checkbox" id="is_update_slug_<?php echo $entry; ?>" name="is_update_slug" value="1" <?php echo $post['is_update_slug'] ? 'checked="checked"': '' ?> />
							<label for="is_update_slug_<?php echo $entry; ?>"><?php _e('Slug', 'pmxi_plugin') ?></label>
						</div>
						<div class="input">
							<input type="hidden" name="is_update_content" value="0" />
							<input type="checkbox" id="is_update_content_<?php echo $entry; ?>" name="is_update_content" value="1" <?php echo $post['is_update_content'] ? 'checked="checked"': '' ?> />
							<label for="is_update_content_<?php echo $entry; ?>"><?php _e('Content', 'pmxi_plugin') ?></label>
						</div>
						<div class="input">
							<input type="hidden" name="is_update_excerpt" value="0" />
							<input type="checkbox" id="is_update_excerpt_<?php echo $entry; ?>" name="is_update_excerpt" value="1" <?php echo $post['is_update_excerpt'] ? 'checked="checked"': '' ?> />
							<label for="is_update_excerpt_<?php echo $entry; ?>"><?php _e('Excerpt/Short Description', 'pmxi_plugin') ?></label>
						</div>
						<div class="input">
							<input type="hidden" name="is_update_dates" value="0" />
							<input type="checkbox" id="is_update_dates_<?php echo $entry; ?>" name="is_update_dates" value="1" <?php echo $post['is_update_dates'] ? 'checked="checked"': '' ?> />
							<label for="is_update_dates_<?php echo $entry; ?>"><?php _e('Dates', 'pmxi_plugin') ?></label>
						</div>
						<div class="input">
							<input type="hidden" name="is_update_menu_order" value="0" />
							<input type="checkbox" id="is_update_menu_order_<?php echo $entry; ?>" name="is_update_menu_order" value="1" <?php echo $post['is_update_menu_order'] ? 'checked="checked"': '' ?> />
							<label for="is_update_menu_order_<?php echo $entry; ?>"><?php _e('Menu order', 'pmxi_plugin') ?></label>
						</div>
						<div class="input">
							<input type="hidden" name="is_update_parent" value="0" />
							<input type="checkbox" id="is_update_parent_<?php echo $entry; ?>" name="is_update_parent" value="1" <?php echo $post['is_update_parent'] ? 'checked="checked"': '' ?> />
							<label for="is_update_parent_<?php echo $entry; ?>"><?php _e('Parent post', 'pmxi_plugin') ?></label>
						</div>	
						<div class="input">
							<input type="hidden" name="is_update_attachments" value="0" />
							<input type="checkbox" id="is_update_attachments_<?php echo $entry; ?>" name="is_update_attachments" value="1" <?php echo $post['is_update_attachments'] ? 'checked="checked"': '' ?> />
							<label for="is_update_attachments_<?php echo $entry; ?>"><?php _e('Attachments', 'pmxi_plugin') ?></label>
						</div>	
						
						<?php 

							// add-ons re-import options
							do_action('pmxi_reimport', $entry, $post);

						?>						

						<div class="input">
							<input type="hidden" name="is_update_images" value="0" />
							<input type="checkbox" id="is_update_images_<?php echo $entry; ?>" name="is_update_images" value="1" <?php echo $post['is_update_images'] ? 'checked="checked"': '' ?> class="switcher" />
							<label for="is_update_images_<?php echo $entry; ?>"><?php _e('Images', 'pmxi_plugin') ?></label>
							<!--a href="#help" class="help" title="<?php _e('This will keep the featured image if it exists, so you could modify the post image manually, and then do a reimport, and it would not overwrite the manually modified post image.', 'pmxi_plugin') ?>">?</a-->
							<div class="switcher-target-is_update_images_<?php echo $entry; ?>" style="padding-left:17px;">
								<div class="input" style="margin-bottom:3px;">								
									<input type="radio" id="update_images_logic_full_update_<?php echo $entry; ?>" name="update_images_logic" value="full_update" <?php echo ( "full_update" == $post['update_images_logic'] ) ? 'checked="checked"': '' ?> />
									<label for="update_images_logic_full_update_<?php echo $entry; ?>" style="position:relative; top:1px;"><?php _e('Update all images', 'pmxi_plugin') ?></label>
								</div>
								<div class="input" style="margin-bottom:3px;">								
									<input type="radio" id="update_images_logic_add_new_<?php echo $entry; ?>" name="update_images_logic" value="add_new" <?php echo ( "add_new" == $post['update_images_logic'] ) ? 'checked="checked"': '' ?> />
									<label for="update_images_logic_add_new_<?php echo $entry; ?>" style="position:relative; top:1px;"><?php _e('Don\'t touch existing images, append new images', 'pmxi_plugin') ?></label>
								</div>
							</div>
						</div>			
						<div class="input">			
							<input type="hidden" name="custom_fields_list" value="0" />			
							<input type="hidden" name="is_update_custom_fields" value="0" />
							<input type="checkbox" id="is_update_custom_fields_<?php echo $entry; ?>" name="is_update_custom_fields" value="1" <?php echo $post['is_update_custom_fields'] ? 'checked="checked"': '' ?>  class="switcher"/>
							<label for="is_update_custom_fields_<?php echo $entry; ?>"><?php _e('Custom Fields', 'pmxi_plugin') ?></label>
							<!--a href="#help" class="help" title="<?php _e('If Keep Custom Fields box is checked, it will keep all Custom Fields, and add any new Custom Fields specified in Custom Fields section, as long as they do not overwrite existing fields. If \'Only keep this Custom Fields\' is specified, it will only keep the specified fields.', 'pmxi_plugin') ?>">?</a-->
							<div class="switcher-target-is_update_custom_fields_<?php echo $entry; ?>" style="padding-left:17px;">
								<div class="input">
									<input type="radio" id="update_custom_fields_logic_full_update_<?php echo $entry; ?>" name="update_custom_fields_logic" value="full_update" <?php echo ( "full_update" == $post['update_custom_fields_logic'] ) ? 'checked="checked"': '' ?> class="switcher"/>
									<label for="update_custom_fields_logic_full_update_<?php echo $entry; ?>"><?php _e('Update all Custom Fields', 'pmxi_plugin') ?></label>								
								</div>
								<?php
								$existing_meta_keys = array();
								$hide_fields = array('_wp_page_template', '_edit_lock', '_edit_last', '_wp_trash_meta_status', '_wp_trash_meta_time');
								if (!empty($meta_keys) and $meta_keys->count()):
									foreach ($meta_keys as $meta_key) { if (in_array($meta_key['meta_key'], $hide_fields) or strpos($meta_key['meta_key'], '_wp') === 0) continue;
										$existing_meta_keys[] = $meta_key['meta_key'];												
									}
								endif;
								?>	
								<div class="input">
									<input type="radio" id="update_custom_fields_logic_only_<?php echo $entry; ?>" name="update_custom_fields_logic" value="only" <?php echo ( "only" == $post['update_custom_fields_logic'] ) ? 'checked="checked"': '' ?> class="switcher"/>
									<label for="update_custom_fields_logic_only_<?php echo $entry; ?>"><?php _e('Update only these Custom Fields, leave the rest alone', 'pmxi_plugin') ?></label>								
									<div class="switcher-target-update_custom_fields_logic_only_<?php echo $entry; ?> pmxi_choosen" style="padding-left:17px;">
											
										<span class="hidden choosen_values"><?php if (!empty($existing_meta_keys)) echo implode(',', $existing_meta_keys);?></span>
										<input class="choosen_input" value="<?php if (!empty($post['custom_fields_list']) and "only" == $post['update_custom_fields_logic']) echo implode(',', $post['custom_fields_list']); ?>" type="hidden" name="custom_fields_only_list"/>										
									</div>
								</div>
								<div class="input">
									<input type="radio" id="update_custom_fields_logic_all_except_<?php echo $entry; ?>" name="update_custom_fields_logic" value="all_except" <?php echo ( "all_except" == $post['update_custom_fields_logic'] ) ? 'checked="checked"': '' ?> class="switcher"/>
									<label for="update_custom_fields_logic_all_except_<?php echo $entry; ?>"><?php _e('Leave these fields alone, update all other Custom Fields', 'pmxi_plugin') ?></label>								
									<div class="switcher-target-update_custom_fields_logic_all_except_<?php echo $entry; ?> pmxi_choosen" style="padding-left:17px;">
										
										<span class="hidden choosen_values"><?php if (!empty($existing_meta_keys)) echo implode(',', $existing_meta_keys);?></span>
										<input class="choosen_input" value="<?php if (!empty($post['custom_fields_list']) and "all_except" == $post['update_custom_fields_logic']) echo implode(',', $post['custom_fields_list']); ?>" type="hidden" name="custom_fields_except_list"/>																				
									</div>
								</div>
							</div>
						</div>	
						<div class="input">
							<input type="hidden" name="taxonomies_list" value="0" />
							<input type="hidden" name="is_update_categories" value="0" />
							<input type="checkbox" id="is_update_categories_<?php echo $entry; ?>" name="is_update_categories" value="1" class="switcher" <?php echo $post['is_update_categories'] ? 'checked="checked"': '' ?> />
							<label for="is_update_categories_<?php echo $entry; ?>"><?php _e('Taxonomies (incl. Categories and Tags)', 'pmxi_plugin') ?></label>
							<div class="switcher-target-is_update_categories_<?php echo $entry; ?>" style="padding-left:17px;">
								<?php
								$existing_taxonomies = array();
								$hide_taxonomies = (class_exists('PMWI_Plugin')) ? array('product_type') : array();
								$post_taxonomies = array_diff_key(get_taxonomies_by_object_type(array($post_type), 'object'), array_flip($hide_taxonomies));
								if (!empty($post_taxonomies)): 
									foreach ($post_taxonomies as $ctx):  if ("" == $ctx->labels->name or (class_exists('PMWI_Plugin') and $post_type == "product" and strpos($ctx->name, "pa_") === 0)) continue;
										$existing_taxonomies[] = $ctx->name;												
									endforeach;
								endif;
								?>
								<div class="input" style="margin-bottom:3px;">								
									<input type="radio" id="update_categories_logic_all_except_<?php echo $entry; ?>" name="update_categories_logic" value="all_except" <?php echo ( "all_except" == $post['update_categories_logic'] ) ? 'checked="checked"': '' ?> class="switcher"/>
									<label for="update_categories_logic_all_except_<?php echo $entry; ?>" style="position:relative; top:1px;"><?php _e('Leave these taxonomies alone, update all others', 'pmxi_plugin') ?></label>
									<div class="switcher-target-update_categories_logic_all_except_<?php echo $entry; ?> pmxi_choosen" style="padding-left:17px;">
										
										<span class="hidden choosen_values"><?php if (!empty($existing_taxonomies)) echo implode(',', $existing_taxonomies);?></span>
										<input class="choosen_input" value="<?php if (!empty($post['taxonomies_list']) and "all_except" == $post['update_categories_logic']) echo implode(',', $post['taxonomies_list']); ?>" type="hidden" name="taxonomies_except_list"/>																				
									</div>
								</div>
								<div class="input" style="margin-bottom:3px;">								
									<input type="radio" id="update_categories_logic_only_<?php echo $entry; ?>" name="update_categories_logic" value="only" <?php echo ( "only" == $post['update_categories_logic'] ) ? 'checked="checked"': '' ?> class="switcher"/>
									<label for="update_categories_logic_only_<?php echo $entry; ?>" style="position:relative; top:1px;"><?php _e('Update only these taxonomies, leave the rest alone', 'pmxi_plugin') ?></label>
									<div class="switcher-target-update_categories_logic_only_<?php echo $entry; ?> pmxi_choosen" style="padding-left:17px;">
										
										<span class="hidden choosen_values"><?php if (!empty($existing_taxonomies)) echo implode(',', $existing_taxonomies);?></span>
										<input class="choosen_input" value="<?php if (!empty($post['taxonomies_list']) and "only" == $post['update_categories_logic']) echo implode(',', $post['taxonomies_list']); ?>" type="hidden" name="taxonomies_only_list"/>										
									</div>
								</div>
								<div class="input" style="margin-bottom:3px;">								
									<input type="radio" id="update_categories_logic_full_update_<?php echo $entry; ?>" name="update_categories_logic" value="full_update" <?php echo ( "full_update" == $post['update_categories_logic'] ) ? 'checked="checked"': '' ?> class="switcher"/>
									<label for="update_categories_logic_full_update_<?php echo $entry; ?>" style="position:relative; top:1px;"><?php _e('Remove existing taxonomies, add new taxonomies', 'pmxi_plugin') ?></label>
								</div>
								<div class="input" style="margin-bottom:3px;">								
									<input type="radio" id="update_categories_logic_add_new_<?php echo $entry; ?>" name="update_categories_logic" value="add_new" <?php echo ( "add_new" == $post['update_categories_logic'] ) ? 'checked="checked"': '' ?> class="switcher"/>
									<label for="update_categories_logic_add_new_<?php echo $entry; ?>" style="position:relative; top:1px;"><?php _e('Only add new', 'pmxi_plugin') ?></label>
								</div>
							</div>
						</div>	
					</div>
				</div>
			</div>				
		</fieldset>
	</td>
</tr>
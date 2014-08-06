<tr>
	<td colspan="3" style="padding-top:20px;">
		<fieldset class="optionsset">
			<legend><?php _e('Featured Image & Media Gallery', 'pmxi_plugin') ?></legend>		

			<?php if ($post_type != "product" or ! class_exists('PMWI_Plugin')):?>
			
			<center>

				<h3>Please upgrade to the professional edition of WP All Import to download and import images to the post media gallery.</h3>

				<p style='font-size: 1.3em; font-weight: bold;'><a href="http://www.wpallimport.com/upgrade-to-pro?utm_source=wordpress.org&utm_medium=custom-fields&utm_campaign=free+plugin" target="_blank" class="upgrade_link">Upgrade Now</a></p>

				<hr />

			</center>

			<?php endif; ?>

			<div class="input">
				<p style="margin-bottom:5px;"><?php _e('<b>Image URLs</b> (one per line)', 'pmxi_plugin');?></p>
				<textarea name="featured_image" class="newline" style="width:100%;margin-bottom:5px;" <?php if ($post_type != "product" or ! class_exists('PMWI_Plugin')):?>disabled="disabled"<?php endif; ?>><?php echo esc_attr($post['featured_image']) ?></textarea>			
				<label for="featured_delim_<?php echo $entry;?>"><?php _e('Place one image URL per line, or separate URLs with a ', 'pmxi_plugin');?></label>
				<input type="text" class="small" id="featured_delim_<?php echo $entry;?>" name="featured_delim" value="<?php echo esc_attr($post['featured_delim']) ?>" style="width:5%; text-align:center;" <?php if ($post_type != "product" or ! class_exists('PMWI_Plugin')):?>disabled="disabled"<?php endif; ?>/>
				<span style="float:right;">
					<input type="hidden" name="create_draft" value="no" />
					<input type="checkbox" id="create_draft_<?php echo $entry; ?>" name="create_draft" value="yes" <?php echo 'yes' == $post['create_draft'] ? 'checked="checked"' : '' ?> class="fix_checkbox" <?php if ($post_type != "product" or ! class_exists('PMWI_Plugin')):?>disabled="disabled"<?php endif; ?>/>
					<label for="create_draft_<?php echo $entry; ?>"><?php _e('If no images are downloaded successfully, create entry as Draft.', 'pmxi_plugin') ?></label>
				</span>
			</div>
			<div class="input" style="margin:3px 0px;">
				<input type="hidden" name="download_images" value="0" />
				<input type="checkbox" id="download_images_<?php echo $entry; ?>" name="download_images" value="1" <?php echo $post['download_images'] ? 'checked="checked"' : '' ?> class="fix_checkbox" <?php if ($post_type != "product" or ! class_exists('PMWI_Plugin')):?>disabled="disabled"<?php endif; ?>/>
				<label for="download_images_<?php echo $entry;?>"><?php _e('Download images','pmxi_plugin');?> </label>
				<a href="#help" class="help" title="<?php _e('If this option enabled, then plugin will download images into the Uploads folder. If this option disabled, then plugin will search files in Uploads <strong>/wp-content/uploads/'.date("Y/m").'</strong> folder.', 'pmxi_plugin') ?>">?</a>
			</div>
			<div class="input" style="margin:3px 0px;">
				<input type="hidden" name="auto_rename_images" value="0" />
				<input type="checkbox" id="auto_rename_images_<?php echo $entry; ?>" name="auto_rename_images" value="1" <?php echo $post['auto_rename_images'] ? 'checked="checked"' : '' ?> class="switcher fix_checkbox" <?php if ($post_type != "product" or ! class_exists('PMWI_Plugin')):?>disabled="disabled"<?php endif; ?>/>
				<label for="auto_rename_images_<?php echo $entry;?>"><?php _e('Instead of using original image file name, set file name(s) to','pmxi_plugin');?> </label>
				<input type="text" id="auto_rename_images_suffix_<?php echo $entry;?>" class="switcher-target-auto_rename_images_<?php echo $entry; ?>" name="auto_rename_images_suffix" value="<?php echo esc_attr($post['auto_rename_images_suffix']) ?>" /> <a href="#help" class="help" title="<?php _e('Instead of using original image file name, set file name(s) suffix', 'pmxi_plugin') ?>">?</a>
			</div>			
			<div class="input">
				<input type="hidden" name="set_image_meta_data" value="0" />
				<input type="checkbox" id="set_image_meta_data_<?php echo $entry; ?>" name="set_image_meta_data" value="1" <?php echo $post['set_image_meta_data'] ? 'checked="checked"' : '' ?> class="switcher fix_checkbox"/>
				<label for="set_image_meta_data_<?php echo $entry;?>"><?php _e('Set Image Meta Data (alt text, caption, description, title)','pmxi_plugin');?></label>
			</div>
			<div class="switcher-target-set_image_meta_data_<?php echo $entry; ?>" style="padding-left:17px;">
				<div class="input">
					<p style="margin-bottom:5px;"><?php _e('Title', 'pmxi_plugin');?> <a href="#help" class="help" title="<?php _e('Image Title', 'pmxi_plugin') ?>">?</a></p>
					<textarea name="image_meta_title" class="newline" style="width:100%; margin-bottom:5px;" placeholder="<?php _e('Default will be image filename. The first title will be associated with the first image URL, the second title will be associated with second image URL, etc.','pmxi_plugin');?>" <?php if ($post_type != "product" or ! class_exists('PMWI_Plugin')):?>disabled="disabled"<?php endif; ?>><?php echo esc_attr($post['image_meta_title']) ?></textarea>			
					<label for="image_meta_title_delim_<?php echo $entry;?>"><?php _e('Separated by', 'pmxi_plugin');?></label>
					<input type="text" class="small" id="image_meta_title_delim_<?php echo $entry;?>" name="image_meta_title_delim" value="<?php echo esc_attr($post['image_meta_title_delim']) ?>" style="width:5%; text-align:center;" <?php if ($post_type != "product" or ! class_exists('PMWI_Plugin')):?>disabled="disabled"<?php endif; ?>/> <span>(<?php _e('or newline','pmxi_plugin');?>)</span>						
				</div>
				<div class="input">
					<p style="margin-bottom:5px;"><?php _e('Caption', 'pmxi_plugin');?> <a href="#help" class="help" title="<?php _e('Image Capltion', 'pmxi_plugin') ?>">?</a></p>
					<textarea name="image_meta_caption" class="newline" style="width:100%; margin-bottom:5px;" placeholder="The first caption will be associated with the first image URL, the second caption will be associated with the second image URL, etc." <?php if ($post_type != "product" or ! class_exists('PMWI_Plugin')):?>disabled="disabled"<?php endif; ?>><?php echo esc_attr($post['image_meta_caption']) ?></textarea>			
					<label for="image_meta_caption_delim_<?php echo $entry;?>"><?php _e('Separated by', 'pmxi_plugin');?></label>
					<input type="text" class="small" id="image_meta_caption_delim_<?php echo $entry;?>" name="image_meta_caption_delim" value="<?php echo esc_attr($post['image_meta_caption_delim']) ?>" style="width:5%; text-align:center;" <?php if ($post_type != "product" or ! class_exists('PMWI_Plugin')):?>disabled="disabled"<?php endif; ?>/> <span>(<?php _e('or newline','pmxi_plugin');?>)</span>						
				</div>
				<div class="input">
					<p style="margin-bottom:5px;"><?php _e('Alt text', 'pmxi_plugin');?> <a href="#help" class="help" title="<?php _e('Image Alt Text', 'pmxi_plugin') ?>">?</a></p>
					<textarea name="image_meta_alt" class="newline" style="width:100%; margin-bottom:5px;" placeholder="The first alt text will be associated with the first image URL, the second alt text will be associted with the second image URL, etc." <?php if ($post_type != "product" or ! class_exists('PMWI_Plugin')):?>disabled="disabled"<?php endif; ?>><?php echo esc_attr($post['image_meta_alt']) ?></textarea>			
					<label for="image_meta_alt_delim_<?php echo $entry;?>"><?php _e('Separated by', 'pmxi_plugin');?></label>
					<input type="text" class="small" id="image_meta_alt_delim_<?php echo $entry;?>" name="image_meta_alt_delim" value="<?php echo esc_attr($post['image_meta_alt_delim']) ?>" style="width:5%; text-align:center;" <?php if ($post_type != "product" or ! class_exists('PMWI_Plugin')):?>disabled="disabled"<?php endif; ?>/> <span>(<?php _e('or newline','pmxi_plugin');?>)</span>						
				</div>
				<div class="input">
					<p style="margin-bottom:5px;"><?php _e('Description', 'pmxi_plugin');?> <a href="#help" class="help" title="<?php _e('Image Description', 'pmxi_plugin') ?>">?</a></p>
					<textarea name="image_meta_description" class="newline" style="width:100%; margin-bottom:5px;" placeholder="The first description will be associated with the first URL, the second descrition will be associated with the second URL, etc." <?php if ($post_type != "product" or ! class_exists('PMWI_Plugin')):?>disabled="disabled"<?php endif; ?>><?php echo esc_attr($post['image_meta_description']) ?></textarea>			
					<label for="image_meta_description_delim_<?php echo $entry;?>"><?php _e('Separated by', 'pmxi_plugin');?></label>
					<input type="text" class="small" id="image_meta_description_delim_<?php echo $entry;?>" name="image_meta_description_delim" value="<?php echo esc_attr($post['image_meta_description_delim']) ?>" style="width:5%; text-align:center;" <?php if ($post_type != "product" or ! class_exists('PMWI_Plugin')):?>disabled="disabled"<?php endif; ?>/> <span>(<?php _e('or newline','pmxi_plugin');?>)</span>						
				</div>
			</div>		

		</fieldset>								
	</td>
</tr>
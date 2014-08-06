<tr>
	<td colspan="3" style="padding-top:20px;">
		<fieldset class="optionsset">
			<legend><?php _e('Import Processing','pmxi_plugin');?></legend>					
			<p>
				<div class="input">															
					<div class="input" style="margin-bottom:5px;">
						<input type="radio" id="import_default_processing_<?php echo $entry; ?>" class="switcher" name="import_processing" value="default" <?php echo ('ajax' != $post['import_processing']) ? 'checked="checked"': '' ?>/>
						<label for="import_default_processing_<?php echo $entry; ?>"><?php _e('High Speed Small File Processing', 'pmxi_plugin' )?> <a href="#help" class="help" title="<?php printf( __('Your server\'s max_execution_time setting is %s seconds. If the import takes longer than this, it will fail.', 'pmxi_plugin'), ini_get('max_execution_time'));?>">?</a></label>					
					</div>
					
					<input type="radio" id="import_ajax_processing_<?php echo $entry; ?>" class="switcher" name="import_processing" value="ajax" <?php echo 'ajax' == $post['import_processing'] ? 'checked="checked"': '' ?>/>
					<label for="import_ajax_processing_<?php echo $entry; ?>"><?php _e('Iterative, Piece-by-Piece Processing', 'pmxi_plugin' )?></label>					
					
					<span class="switcher-target-import_ajax_processing_<?php echo $entry; ?> pl17">
						<div class="input pl17" style="margin:5px 0px;">							
							<label for="records_per_request"><?php _e('In each iteration, process', 'pmxi_plugin');?></label> <input type="text" name="records_per_request" style="vertical-align:middle; font-size:11px; background:#fff !important; width: 40px; text-align:center;" value="<?php echo esc_attr($post['records_per_request']) ?>" /> <?php _e('records', 'pmxi_plugin'); ?>
							<a href="#help" class="help" title="<?php printf(__('WP All Import must be able to process this many records in less than your server\'s max_execution_time, which is %s seconds. If your import fails before finishing (you will see \'Import XML - Error\' in the progress bar), to troubleshoot you should lower this number. If you are importing images, especially high resolution images, high numbers here are probably a bad idea, since downloading the images can take lots of time. 20 posts with 5 images each = 100 images, at 500Kb per image that\'s 50Mb. Can your server download that in less than your max_execution_time? Maybe, maybe not.', 'pmxi_plugin'), ini_get('max_execution_time')); ?>">?</a>							
						</div>
						<div class="input pl17" style="margin:5px 0px;">
							<input type="hidden" name="chuncking" value="0" />
							<input type="checkbox" id="chuncking_<?php echo $entry; ?>" name="chuncking" value="1" class="fix_checkbox" <?php echo $post['chuncking'] ? 'checked="checked"': '' ?>/>
							<label for="chuncking_<?php echo $entry; ?>"><?php _e('Split file up into <strong>' . PMXI_Plugin::getInstance()->getOption('large_feed_limit') . '</strong> record chunks.', 'pmxi_plugin');?></label> 
							<a href="#help" class="help" title="<?php _e('This option will decrease the amount of slowdown experienced at the end of large imports. The slowdown is partially caused by the need for WP All Import to read deeper and deeper into the file on each successive iteration. Splitting the file into pieces means that, for example, instead of having to read 19000 records into a 20000 record file when importing the last 1000 records, WP All Import will just split it into 20 chunks, and then read the last chunk from the beginning.','pmxi_plugin'); ?>">?</a>							
						</div>							
					</span>									
				</div>				
			</p>
			<p>
				<div class="input">
					<input type="hidden" name="is_fast_mode" value="0" />
					<input type="checkbox" id="is_fast_mode_<?php echo $entry; ?>" name="is_fast_mode" value="1" class="fix_checkbox" <?php echo $post['is_fast_mode'] ? 'checked="checked"': '' ?>/>
					<label for="is_fast_mode_<?php echo $entry; ?>"><?php _e('Increase speed by disabling do_action calls in wp_insert_post during import.', 'pmxi_plugin') ?> <a href="#help" class="help" title="<?php _e('This option is for advanced users with knowledge of WordPress development. Your theme or plugins may require these calls when posts are created. Verify your created posts work properly if you check this box.', 'pmxi_plugin') ?>">?</a></label>
				</div>
			</p>		
			<p>
				<div class="input">
					<input type="hidden" name="is_import_specified" value="0" />
					<input type="checkbox" id="is_import_specified_<?php echo $entry; ?>" class="switcher fix_checkbox" name="is_import_specified" value="1" <?php echo $post['is_import_specified'] ? 'checked="checked"': '' ?>/>
					<label for="is_import_specified_<?php echo $entry; ?>"><?php _e('Import only specified records', 'pmxi_plugin') ?> <a href="#help" class="help" title="<?php _e('Enter records or record ranges separated by commas, e.g. <b>1,5,7-10</b> would import the first, the fifth, and the seventh to tenth.', 'pmxi_plugin') ?>">?</a></label>
					<span class="switcher-target-is_import_specified_<?php echo $entry; ?>" style="vertical-align:middle">
						<div class="input" style="display:inline;">
							<input type="text" name="import_specified" value="<?php echo esc_attr($post['import_specified']) ?>" style="width:50%;"/>
						</div>
					</span>
				</div>
			</p>	
			<?php if (in_array($source_type, array('ftp', 'file'))): ?>
				<p>
					<div class="input">
						<input type="hidden" name="is_delete_source" value="0" />
						<input type="checkbox" id="is_delete_source_<?php echo $entry; ?>" class="fix_checkbox" name="is_delete_source" value="1" <?php echo $post['is_delete_source'] ? 'checked="checked"': '' ?>/>
						<label for="is_delete_source_<?php echo $entry; ?>"><?php _e('Delete source XML file after importing', 'pmxi_plugin') ?> <a href="#help" class="help" title="<?php _e('This setting takes effect only when script has access rights to perform the action, e.g. file is not deleted when pulled via HTTP or delete permission is not granted to the user that script is executed under.', 'pmxi_plugin') ?>">?</a></label>
					</div>
				</p>
			<?php endif; ?>
			<?php if (class_exists('PMLC_Plugin')): // option is only valid when `WP Wizard Cloak` pluign is enabled ?>
				<p>
					<div class="input">
						<input type="hidden" name="is_cloak" value="0" />
						<input type="checkbox" id="is_cloak_<?php echo $entry; ?>" class="fix_checkbox" name="is_cloak" value="1" <?php echo $post['is_cloak'] ? 'checked="checked"': '' ?>/>
						<label for="is_cloak_<?php echo $entry; ?>"><?php _e('Auto-Cloak Links', 'pmxi_plugin') ?> <a href="#help" class="help" title="<?php printf(__('Automatically process all links present in body of created post or page with <b>%s</b> plugin', 'pmxi_plugin'), PMLC_Plugin::getInstance()->getName()) ?>">?</a></label>
					</div> 
				</p>
			<?php endif; ?>	
			<hr>
			<p>
				<div class="input pl17">
					<label for="save_import_as" style="margin-right:17px; width: 103px;"><?php _e('Friendly Name:','pmxi_plugin');?></label> <input type="text" name="friendly_name" title="<?php _e('Save friendly name...', 'pmxi_plugin') ?>" style="vertical-align:middle; font-size:11px; background:#fff !important;" value="<?php echo esc_attr($post['friendly_name']) ?>" />
				</div>
			</p>
			<p>
				<div class="input">
					<input type="checkbox" id="save_template_as_<?php echo $entry; ?>" name="save_template_as" class="fix_checkbox" value="1" <?php echo ( ! empty($post['save_template_as'])) ? 'checked="checked"' : '' ?>/> <label for="save_template_as_<?php echo $entry; ?>"><?php _e('Save template as:','pmxi_plugin');?></label> &nbsp;<input type="text" name="name" title="<?php _e('Save Template As...', 'pmxi_plugin') ?>" style="vertical-align:middle; font-size:11px;" value="<?php echo (!empty($post['name'])) ? esc_attr($post['name']) : ''; ?>" />
				</div>
			</p>								
		</fieldset>
	</td>
</tr>
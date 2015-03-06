<tr>
	<td colspan="3" style="padding-top:20px;">
		<fieldset class="optionsset" style="text-align:center;">
			<legend><?php _e('Custom Fields','pmxi_plugin');?></legend>

			<center>

				<h3>Please upgrade to the professional edition of WP All Import to import data to Custom Fields.</h3>

				<p style='font-size: 1.3em; font-weight: bold;'><a href="http://www.wpallimport.com/upgrade-to-pro?utm_source=wordpress.org&utm_medium=custom-fields&utm_campaign=free+plugin" target="_blank" class="upgrade_link">Upgrade Now</a></p>

				<hr />

			</center>


			<table class="form-table custom-params" style="max-width:none; border:none;">
			<thead>
				<tr>
					<td><?php _e('Name', 'pmxi_plugin') ?></td>
					<td><?php _e('Value', 'pmxi_plugin') ?></td>					
				</tr>
			</thead>
			<tbody>								
				<?php if (!empty($post['custom_name'])):?>
					<?php foreach ($post['custom_name'] as $i => $name): ?>
						<tr class="form-field">
							<td><input type="text" name="custom_name[]"  value="<?php echo esc_attr($name) ?>" disabled="disabled"/></td>
							<td class="action remove">
								<textarea name="custom_value[]" disabled="disabled"><?php echo esc_html($post['custom_value'][$i]) ?></textarea>
								<a href="#remove"></a>
							</td>														
						</tr>
					<?php endforeach ?>
				<?php else: ?>
					<tr class="form-field">
						<td><input type="text" name="custom_name[]"  value="" disabled="disabled"/></td>
						<td class="action remove">
							<textarea name="custom_value[]" disabled="disabled"></textarea>
							<a href="#remove"></a>
						</td>													
					</tr>
				<?php endif;?>
				<tr class="form-field template">
					<td><input type="text" name="custom_name[]" value="" disabled="disabled"/></td>
					<td class="action remove">
						<textarea name="custom_value[]" disabled="disabled"></textarea>
						<a href="#remove"></a>
					</td>												
				</tr>			
				<tr>
					<td colspan="2"><a href="#add" title="<?php _e('add', 'pmxi_plugin')?>" class="action add-new-custom"><?php _e('Add more', 'pmxi_plugin') ?></a></td>												
				</tr>
			</tbody>
			</table>
			<select class="existing_meta_keys">
				<option value=""><?php _e('Existing Custom Fields...','pmxi_plugin');?></option>
				<?php				
				$hide_fields = array('_wp_page_template', '_edit_lock', '_edit_last', '_wp_trash_meta_status', '_wp_trash_meta_time');
				if (!empty($meta_keys) and $meta_keys->count()):
					foreach ($meta_keys as $meta_key) { if (in_array($meta_key['meta_key'], $hide_fields) or strpos($meta_key['meta_key'], '_wp') === 0) continue;
						?>
						<option value="<?php echo $meta_key['meta_key'];?>"><?php echo $meta_key['meta_key'];?></option>
						<?php
					}
				endif;
				?>
			</select>
			<br/>			
		</fieldset>		
	</td>
</tr>
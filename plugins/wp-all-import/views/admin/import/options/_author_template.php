<tr>
	<td colspan="3">
		<h3 style="float:left;"><?php _e('Download & Import Attachments', 'pmxi_plugin') ?></h3>
		<span class="separated_by" style="position:relative; top:15px; margin-right:0px;"><?php _e('Separated by','pmxi_plugin');?></span>
		<div>
			<input type="text" name="attachments" style="width:93%;" value="<?php echo esc_attr($post['attachments']) ?>" />
			<input type="text" class="small" name="atch_delim" value="<?php echo esc_attr($post['atch_delim']) ?>" style="width:5%; text-align:center; float:right;"/>
		</div>																	
	</td>								
</tr>	
<tr>
	<td colspan="3">
		<h3><?php _e('Post Author', 'pmxi_plugin') ?></h3>
		<div>
			<input type="text" name="author" value="<?php echo esc_attr($post['author']) ?>"/> <a href="#help" class="help" title="<?php _e('Value that contains user ID, login, slug or email.', 'pmxi_plugin') ?>">?</a>			
		</div>																	
	</td>								
</tr>		
<tr>
	<td colspan="3">
		<?php if ($entry != 'page'):?>
		<h3><?php (class_exists('PMWI_Plugin') and $entry == 'product') ? _e('WooCommerce Short Description', 'pmxi_plugin') : _e('Post Excerpt', 'pmxi_plugin'); ?></h3>
		<div>
			<input type="text" name="post_excerpt" style="width:100%;" value="<?php echo esc_attr($post['post_excerpt']) ?>" />
		</div>
		<?php endif; ?>
		<h3><?php _e('Post Slug', 'pmxi_plugin') ?></h3>
		<div>
			<input type="text" name="post_slug" style="width:100%;" value="<?php echo esc_attr($post['post_slug']) ?>" />
		</div> 
	</td>
</tr>
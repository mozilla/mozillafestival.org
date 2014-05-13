<tr>
	<td colspan="3">
		<div class="col2">
			<fieldset style="margin-left:0px;">
				<legend><?php _e('Categories', 'pmxi_plugin') ?>  <a href="#help" class="help" title="<?php _e('Uncheck a box and the category will still be created, but the post will not be assigned to it.', 'pmxi_plugin') ?>">?</a></legend>
				<ol class="sortable no-margin">
					<?php if (!empty($post['categories'])):?>
						<?php
							$categories = json_decode($post['categories']);

							if (empty($categories)) $categories = explode(',', $post['categories']);

							if (!empty($categories) and is_array($categories)): $i = 0; foreach ($categories as $cat) { $i++;
								if (is_null($cat->parent_id) or empty($cat->parent_id))
								{
									?>
									<li id="item_<?php echo $i; ?>">
										<div class="drag-element">
											<input type="checkbox" class="assign_post" <?php if ($cat->assign): ?>checked="checked"<?php endif; ?> title="<?php _e('Assign post to the taxonomy.','pmxi_plugin');?>"/>											
											<input type="text" class="widefat" value="<?php echo (is_object($cat)) ? esc_attr($cat->xpath) : esc_attr($cat); ?>"/>
										</div>
										<?php if ($i>1):?><a href="javascript:void(0);" class="icon-item remove-ico"></a><?php endif;?>
										<?php if (is_object($cat)) echo reverse_taxonomies_html($categories, $cat->item_id, $i); ?>
									</li>
									<?php
								}
							}; else: ?>
							<li id="item_1">
								<div class="drag-element">
									<input type="checkbox" class="assign_post" checked="checked" title="<?php _e('Assign post to the taxonomy.','pmxi_plugin');?>"/>									
									<input type="text" class="widefat" value=""/>
								</div>
							</li>
							<?php endif;?>
					<?php else: ?>
				    <li id="item_1">
				    	<div class="drag-element">
				    		<input type="checkbox" class="assign_post" checked="checked" title="<?php _e('Assign post to the taxonomy.','pmxi_plugin');?>"/>				    		
				    		<input type="text" class="widefat" value=""/>
				    	</div>
				    </li>
					<?php endif; ?>
				</ol>
				<a href="javascript:void(0);" class="icon-item add-new-ico"><?php _e('Add more', 'pmxi_plugin');?></a> <br><br>
				<input type="hidden" class="hierarhy-output" name="categories" value="<?php echo esc_attr($post['categories']) ?>"/>
				<div class="hidden" id="dialog-confirm-category-removing" title="Delete categories?"><?php _e('Remove only current category or current category with subcategories?', 'pmxi_plugin');?></div>
				<div class="delim">
					<label><?php _e('Separated by', 'pmxi_plugin'); ?></label>
					<input type="text" class="small" name="categories_delim" value="<?php echo ( ! empty($post['categories_delim']) ) ? str_replace("&amp;","&", htmlentities(htmlentities($post['categories_delim']))) : ',' ; ?>" />
					<label for="categories_auto_nested"><?php _e('Enable Auto Nest', 'pmxi_plugin');?></label>
					<input type="hidden" name="categories_auto_nested" value="0"/>
					<input type="checkbox" id="categories_auto_nested" name="categories_auto_nested" <?php if ($post['categories_auto_nested']):?>checked="checked"<?php endif; ?>/>
					<a href="#help" class="help" title="<?php _e('If this box is checked, a category hierarchy will be created. For example, if your <code>{category}</code> value is <code>Mens > Shoes > Diesel</code>, enter <code>&gt;</code> as the separator and enable <code>Auto Nest</code> to create <code>Diesel</code> as a child category of <code>Shoes</code> and <code>Shoes</code> as a child category of <code>Mens.</code>', 'pmxi_plugin') ?>">?</a>
				</div>
			</fieldset>
		</div>
		<div class="col2">
			<fieldset style="padding:5px; margin-right:0px;">
				<legend><?php _e('Tags', 'pmxi_plugin') ?> </legend>				
				<input type="text" name="tags" class="widefat" value="<?php echo esc_attr($post['tags']) ?>" /> <br> <br>
				<div class="delim">
					<label><?php _e('Separated by', 'pmxi_plugin'); ?></label>
					<input type="text" class="small" name="tags_delim" value="<?php echo esc_attr($post['tags_delim']) ?>" />					
				</div>
			</fieldset>
		</div>
	</td>
</tr>
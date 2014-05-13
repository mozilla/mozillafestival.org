<tr>
	<td style="border-bottom:1px solid #ccc;" colspan="3">

		<?php if ($post_type != "post"):?>					

			<?php if ($post_type == "product" and class_exists('PMWI_Plugin')):?>								

				<input type="hidden" class="is_disabled" value="0"/>

			<?php else: ?>
				<center>
					<h3>Please upgrade to the professional version of WP All Import to import to Custom Post Types.</h3>
					<input type="hidden" class="is_disabled" value="1"/>
					<p style='font-size: 1.3em; font-weight: bold;'><a href="http://www.wpallimport.com/upgrade-to-pro?utm_source=wordpress.org&utm_medium=custom-fields&utm_campaign=free+plugin" target="_blank" class="upgrade_link">Upgrade Now</a></p>
					<hr />
				</center>
			<?php endif; ?>								

		<?php else: ?>

		<input type="hidden" class="is_disabled" value="0"/>

		<?php endif; ?>
		
		<input type="hidden" name="encoding" value="<?php echo ($isWizard) ? PMXI_Plugin::$session->data['pmxi_import']['encoding'] : $post['encoding']; ?>"/>
		<input type="hidden" name="delimiter" value="<?php echo ($isWizard) ? PMXI_Plugin::$session->data['pmxi_import']['is_csv'] : $post['delimiter']; ?>"/>

		<?php $is_support_post_format = ( current_theme_supports( 'post-formats' ) && post_type_supports( $post_type, 'post-formats' ) ) ? true : false; ?>

		<div class="<?php echo ($is_support_post_format) ? 'col3' : 'col2';?>" style="margin-bottom:20px;">
			<h3><?php _e('Post Status', 'pmxi_plugin') ?></h3>				
			<div>
				<div class="input">
					<input type="radio" id="status_publish_<?php echo $entry; ?>" name="status" value="publish" <?php echo 'publish' == $post['status'] ? 'checked="checked"' : '' ?> class="switcher"/>
					<label for="status_publish_<?php echo $entry; ?>"><?php _e('Published', 'pmxi_plugin') ?></label>
				</div>
				<div class="input">
					<input type="radio" id="status_draft_<?php echo $entry; ?>" name="status" value="draft" <?php echo 'draft' == $post['status'] ? 'checked="checked"' : '' ?> class="switcher"/>
					<label for="status_draft_<?php echo $entry; ?>"><?php _e('Draft', 'pmxi_plugin') ?></label>
				</div>
				<div class="input fleft" style="position:relative;width:220px; margin-bottom:15px;">
					<input type="radio" id="status_xpath_<?php echo $entry; ?>" class="switcher" name="status" value="xpath" <?php echo 'xpath' == $post['status'] ? 'checked="checked"': '' ?>/>
					<label for="status_xpath_<?php echo $entry; ?>"><?php _e('Set with XPath', 'pmxi_plugin' )?></label> <br>
					<div class="switcher-target-status_xpath_<?php echo $entry; ?> set_xpath">
						<div class="input">
							&nbsp;<input type="text" class="smaller-text" name="status_xpath" style="width:150px; float:left;" value="<?php echo esc_attr($post['status_xpath']) ?>"/>
							<a href="#help" class="help" title="<?php _e('The value of presented XPath should be one of the following: (\'publish\', \'draft\', \'trash\').', 'pmxi_plugin') ?>" style="position:relative; top:2px;">?</a>
						</div>
					</div>
				</div>								
				<div class="clear"></div>
				<div class="input">
					<input type="hidden" name="comment_status" value="closed" />
					<input type="checkbox" id="comment_status_<?php echo $entry; ?>" name="comment_status" value="open" <?php echo 'open' == $post['comment_status'] ? 'checked="checked"' : '' ?> />
					<label for="comment_status_<?php echo $entry; ?>"><?php _e('Allow Comments', 'pmxi_plugin') ?></label>
				</div>
				<div class="input">
					<input type="hidden" name="ping_status" value="closed" />
					<input type="checkbox" id="ping_status_<?php echo $entry; ?>" name="ping_status" value="open" <?php echo 'open' == $post['ping_status'] ? 'checked="checked"' : '' ?> />
					<label for="ping_status_<?php echo $entry; ?>"><?php _e('Allow Trackbacks and Pingbacks', 'pmxi_plugin') ?></label>
				</div>
			</div>														
		</div>
		<?php if ($is_support_post_format):?>
		<div class="col3">
			<h3><?php _e('Post Format', 'pmxi_plugin') ?></h3>											
			<div>
				<?php $post_formats = get_terms( 'post_format' , array('hide_empty' => false)); ?>

				<div class="input">
					<input type="radio" id="post_format_<?php echo "standart_" . $entry; ?>" name="post_format" value="0" <?php echo (empty($post['post_format']) or ( empty($post_formats) )) ? 'checked="checked"' : '' ?> />
					<label for="post_format_<?php echo "standart_" . $entry; ?>"><?php _e( "Standard", 'pmxi_plugin') ?></label>
				</div>

				<?php								
					if ( ! empty($post_formats) ){
						foreach ($post_formats as $post_format) {
							?>
							<div class="input">
								<input type="radio" id="post_format_<?php echo $post_format->slug . "_" . $entry; ?>" name="post_format" value="<?php echo $post_format->name; ?>" <?php echo $post_format->name == $post['post_format'] ? 'checked="checked"' : '' ?> />
								<label for="post_format_<?php echo $post_format->slug . "_" . $entry; ?>"><?php _e( $post_format->name, 'pmxi_plugin') ?></label>
							</div>
							<?php
						}
					}			
				?>
			</div>				
		</div>				
		<?php endif; ?>
		<div class="<?php echo ($is_support_post_format) ? 'col3' : 'col2';?>" <?php if ($is_support_post_format):?>style="border-right:none; padding-right:0px;"<?php endif; ?>>
			<h3><?php _e('Post Dates', 'pmxi_plugin') ?><a href="#help" class="help" title="<?php _e('Use any format supported by the PHP <b>strtotime</b> function. That means pretty much any human-readable date will work.', 'pmxi_plugin') ?>">?</a></h3>							
			<div class="input">
				<input type="radio" id="date_type_specific_<?php echo $entry; ?>" class="switcher" name="date_type" value="specific" <?php echo 'random' != $post['date_type'] ? 'checked="checked"' : '' ?> />
				<label for="date_type_specific_<?php echo $entry; ?>">
					<?php _e('As specified', 'pmxi_plugin') ?>
				</label>
				<span class="switcher-target-date_type_specific_<?php echo $entry; ?>" style="vertical-align:middle">
					<input type="text" class="datepicker" name="date" value="<?php echo esc_attr($post['date']) ?>" style="width:40%;"/>
				</span>
			</div>
			<div class="input">
				<input type="radio" id="date_type_random_<?php echo $entry; ?>" class="switcher" name="date_type" value="random" <?php echo 'random' == $post['date_type'] ? 'checked="checked"' : '' ?> />
				<label for="date_type_random_<?php echo $entry; ?>">
					<?php _e('Random dates', 'pmxi_plugin') ?><a href="#help" class="help" title="<?php _e('Posts will be randomly assigned dates in this range. WordPress ensures posts with dates in the future will not appear until their date has been reached.', 'pmxi_plugin') ?>">?</a>
				</label> <br>
				<span class="switcher-target-date_type_random_<?php echo $entry; ?>" style="vertical-align:middle">
					<input type="text" class="datepicker" name="date_start" value="<?php echo esc_attr($post['date_start']) ?>" />
					<?php _e('and', 'pmxi_plugin') ?>
					<input type="text" class="datepicker" name="date_end" value="<?php echo esc_attr($post['date_end']) ?>" />
				</span>
			</div>							
		</div>	
	</td>
</tr>
<tr>
	<td colspan="3">
		<h3 style="text-align:center; color:#999;"><?php _e('Drag elements from the XML tree on the right to any textbox below.','pmxi_plugin');?></h3>
	</td>
</tr>
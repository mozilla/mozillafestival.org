<div class="tag">		
	<div id="set_encoding">
		<a href="javascript:void(0);" class="set_encoding"><?php _e('Set Character Encoding', 'pmxi_plugin');?></a>
		<div id="select_encoding">
			<label for="import_encoding"><?php _e('Current Character Encoding', 'pmxi_plugin');?></label>		
			<select id="import_encoding" name="import_encoding">				
				<?php $encoding_detected = false; foreach (PMXI_Plugin::$encodings as $key => $enc) :
					?>
					<option value="<?php echo $enc; ?>" <?php if (PMXI_Plugin::$session->data['pmxi_import']['encoding'] == $enc): $encoding_detected = true; ?>selected="selected"<?php endif;?>><?php echo $enc; ?></option>
					<?php
				endforeach; 
				?>
				<?php if ( ! $encoding_detected ): ?>
					<option value="<?php echo PMXI_Plugin::$session->data['pmxi_import']['encoding'];?>"><?php echo PMXI_Plugin::$session->data['pmxi_import']['encoding'];?></option>			
				<?php endif;?>
				<option value="new"><?php _e('Enter new...', 'pmxi_plugin');?></option>
			</select>		
		</div>
		<div id="add_encoding">
			<input id="new_encoding" value=""/>
			<a href="javascript:void(0);" id="add_new_encoding"><?php _e('Add', 'pmxi_plugin');?></a>
			<a href="javascript:void(0);" id="cancel_new_encoding"><?php _e('Cancel', 'pmxi_plugin');?></a>
		</div>
	</div>
	<?php if (!empty($elements->length)):?>
		<!--input type="hidden" name="tagno" value="<?php echo $tagno ?>" /-->
		<div class="title">
			<?php printf(__('Record #<strong><input type="text" value="%s" name="tagno" class="tagno"/></strong> out of <strong class="pmxi_count">%s</strong>', 'pmxi_plugin'), $tagno, PMXI_Plugin::$session->data['pmxi_import']['count']); ?>
			<div class="navigation">
				<?php if ($tagno > 1): ?><a href="#prev">&laquo;</a><?php else: ?><span>&laquo;</span><?php endif ?>
				<?php if ($tagno < PMXI_Plugin::$session->data['pmxi_import']['count']): ?><a href="#next">&raquo;</a><?php else: ?><span>&raquo;</span><?php endif ?>
			</div>
		</div>
		<div class="clear"></div>
		<div class="xml resetable"> <?php if (!empty($elements->length)) $this->render_xml_element($elements->item( 0 ), true);  ?></div>
		<p class="xpath_help">
			<?php _e('Operate on elements using your own PHP functions, use FOREACH loops, and more.<br />Read the <a href="http://www.wpallimport.com/portal/" target="_blank">documentation</a> to learn how.', 'pmxi_plugin') ?>
		</p>	
	<?php else: ?>
		<div class="error inline below-h2" style="padding:10px; margin-top:45px;">
			<?php printf(__('History file not found. Probably you are using wrong encoding.', 'pmxi_plugin')); ?>			
		</div>
	<?php endif; ?>
</div>
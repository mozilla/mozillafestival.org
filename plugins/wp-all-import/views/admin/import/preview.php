<div id="post-preview">

	<div class="title">
		<?php printf(__('Record #<strong><input type="text" value="%s" name="tagno" class="tagno"/></strong> out of <strong class="pmxi_count">%s</strong>', 'pmxi_plugin'), $tagno, PMXI_Plugin::$session->data['pmxi_import']['count']); ?>
		<div class="navigation" style="float:right; margin-left:10px; margin-top:2px; font-size:16px;">			
			<?php if ($tagno > 1): ?><a href="#prev">&laquo;</a><?php else: ?><span>&laquo;</span><?php endif ?>
			<?php if ($tagno < PMXI_Plugin::$session->data['pmxi_import']['count']): ?><a href="#next">&raquo;</a><?php else: ?><span>&raquo;</span><?php endif ?>
		</div>
	</div>

	<?php if ($this->errors->get_error_codes()): ?>
		<?php $this->error() ?>
	<?php endif ?>
		
	<?php if (isset($title)): ?>
		<h2 class="title"><?php echo $title ?></h2>
	<?php endif ?>
	<?php if (isset($content)): ?>
		<div class="content"><?php echo apply_filters('the_content', $content) ?></div>
	<?php endif ?>
</div>
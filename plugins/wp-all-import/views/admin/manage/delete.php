<h2><?php _e('Delete Import', 'pmxi_plugin') ?></h2>

<form method="post">
	<p><?php printf(__('Are you sure you want to delete <strong>%s</strong> import?', 'pmxi_plugin'), $item->name) ?></p>
	<div class="input">
		<input type="checkbox" id="is_delete_posts" name="is_delete_posts" /> <label for="is_delete_posts">Delete associated posts as well</label>
	</div>
	<p class="submit">
		<?php wp_nonce_field('delete-import', '_wpnonce_delete-import') ?>
		<input type="hidden" name="is_confirmed" value="1" />
		<input type="submit" class="button-primary" value="Delete" />
	</p>
	
</form>
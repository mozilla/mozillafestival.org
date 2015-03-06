<h2>Bulk Delete Imports</h2>

<form method="post">
	<input type="hidden" name="action" value="bulk" />
	<input type="hidden" name="bulk-action" value="<?php echo esc_attr($action) ?>" />
	<?php foreach ($ids as $id): ?>
		<input type="hidden" name="items[]" value="<?php echo esc_attr($id) ?>" />
	<?php endforeach ?>
	
	<p><?php printf(__('Are you sure you want to delete <strong>%s</strong> selected %s?', 'pmxi_plugin'), $items->count(), _n('import', 'imports', $items->count(), 'pmxi_plugin')) ?></p>
	<p><input type="checkbox" id="is_delete_posts" name="is_delete_posts" /> <label for="is_delete_posts">Delete associated posts as well</label></p>
	
	<p class="submit">
		<?php wp_nonce_field('bulk-imports', '_wpnonce_bulk-imports') ?>
		<input type="hidden" name="is_confirmed" value="1" />
		<input type="submit" class="button-primary" value="Delete" />
	</p>
</form>
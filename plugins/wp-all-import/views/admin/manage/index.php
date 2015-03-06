<h2>
	<?php _e('Manage Imports', 'pmxi_plugin') ?>
	&nbsp;
	<a href="<?php echo esc_url(add_query_arg(array('page' => 'pmxi-admin-import'), admin_url('admin.php'))) ?>" class="add-new-h2"><?php echo esc_html_x('New Import', 'pmxi_plugin'); ?></a>
</h2>

<?php
	// notify user
	if (!PMXI_Plugin::getInstance()->getOption('dismiss_manage_top')) {
		?>
		<div class="updated"><p>
			<?php printf(
					__('Need help with WP All Import? Please visit <a href="http://www.wpallimport.com/support">http://www.wpallimport.com/support</a> or send an e-mail to <a href="mailto:support@soflyy.com">support@soflyy.com</a>. We give priority to professional edition customers, but we respond to most inquiries from free version users within 24 hours during business days.<br/><br/>Please note that we are unable to handle technical support requests in the WordPress.org community forums. <br/><br/><a href="javascript:void(0);" id="dismiss_manage_top"><u>dismiss</u></a>', 'pmxi_plugin')
			) ?>
		</p></div>
		<?php
	}
?>

<?php if ($this->errors->get_error_codes()): ?>
	<?php $this->error() ?>
<?php endif ?>

<form method="get">
	<input type="hidden" name="page" value="<?php echo esc_attr($this->input->get('page')) ?>" />
	<p class="search-box">
		<label for="search-input" class="screen-reader-text"><?php _e('Search Imports', 'pmxi_plugin') ?>:</label>
		<input id="search-input" type="text" name="s" value="<?php echo esc_attr($s) ?>" />
		<input type="submit" class="button" value="<?php _e('Search Imports', 'pmxi_plugin') ?>">
	</p>
</form>

<?php
// define the columns to display, the syntax is 'internal name' => 'display name'
$columns = array(
	'id'			=> __('ID', 'pmxi_plugin'),
	'name'			=> __('File', 'pmxi_plugin'),
	'xpath'			=> __('XPath', 'pmxi_plugin'),
	'post_count'	=> __('Records', 'pmxi_plugin'),
	'first_import'	=> __('First Import', 'pmxi_plugin'),
	'registered_on'	=> __('Last Import', 'pmxi_plugin'),
	/*'scheduled'		=> __('Reimport Schedule', 'pmxi_plugin'),	
	'next_import'	=> __('Next Import', 'pmxi_plugin'),*/
);

$columns = apply_filters('pmxi_manage_imports_columns', $columns);

?>
<form method="post" id="import-list" action="<?php echo remove_query_arg('pmxi_nt') ?>">
	<input type="hidden" name="action" value="bulk" />
	<?php wp_nonce_field('bulk-imports', '_wpnonce_bulk-imports') ?>

	<div class="tablenav">
		<div class="alignleft actions">
			<select name="bulk-action">
				<option value="" selected="selected"><?php _e('Bulk Actions', 'pmxi_plugin') ?></option>
				<option value="delete"><?php _e('Delete', 'pmxi_plugin') ?></option>
			</select>
			<input type="submit" value="<?php esc_attr_e('Apply', 'pmxi_plugin') ?>" name="doaction" id="doaction" class="button-secondary action" />
		</div>

		<?php if ($page_links): ?>
			<div class="tablenav-pages">
				<?php echo $page_links_html = sprintf(
					'<span class="displaying-num">' . __('Displaying %s&#8211;%s of %s', 'pmxi_plugin') . '</span>%s',
					number_format_i18n(($pagenum - 1) * $perPage + 1),
					number_format_i18n(min($pagenum * $perPage, $list->total())),
					number_format_i18n($list->total()),
					$page_links
				) ?>
			</div>
		<?php endif ?>
	</div>
	<div class="clear"></div>

	<table class="widefat pmxi-admin-imports">
		<thead>
		<tr>
			<th class="manage-column column-cb check-column" scope="col">
				<input type="checkbox" />
			</th>
			<?php
			$col_html = '';
			foreach ($columns as $column_id => $column_display_name) {
				$column_link = "<a href='";
				$order2 = 'ASC';
				if ($order_by == $column_id)
					$order2 = ($order == 'DESC') ? 'ASC' : 'DESC';

				$column_link .= esc_url(add_query_arg(array('order' => $order2, 'order_by' => $column_id), $this->baseUrl));
				$column_link .= "'>{$column_display_name}</a>";
				$col_html .= '<th scope="col" class="column-' . $column_id . ' ' . ($order_by == $column_id ? $order : '') . '">' . $column_link . '</th>';
			}
			echo $col_html;
			?>
		</tr>
		</thead>
		<tfoot>
		<tr>
			<th class="manage-column column-cb check-column" scope="col">
				<input type="checkbox" />
			</th>
			<?php echo $col_html; ?>
		</tr>
		</tfoot>
		<tbody id="the-pmxi-admin-import-list" class="list:pmxi-admin-imports">
		<?php if ($list->isEmpty()): ?>
			<tr>
				<td colspan="<?php echo count($columns) + 1 ?>"><?php _e('No previous imports found.', 'pmxi_plugin') ?></td>
			</tr>
		<?php else: ?>
			<?php
			$periods = array( // scheduling periods
				'*/5 * * * *' => __('every 5 min'),
				'*/10 * * * *' => __('every 10 min'),
				'*/30 * * * *' => __('half-hourly'),
				'0 * * * *' => __('hourly'),
				'0 */4 * * *' => __('every 4 hours'),
				'0 */12 * * *' => __('half-daily'),
				'0 0 * * *' => __('daily'),
				'0 0 * * 1' => __('weekly'),
				'0 0 1 * 1' => __('monthly'),
			);
			$class = '';
			?>
			<?php foreach ($list as $item): ?>
				<?php $class = ('alternate' == $class) ? '' : 'alternate'; ?>
				<tr class="<?php echo $class; ?>" valign="middle">
					<th scope="row" class="check-column">
						<input type="checkbox" id="item_<?php echo $item['id'] ?>" name="items[]" value="<?php echo esc_attr($item['id']) ?>" />
					</th>
					<?php foreach ($columns as $column_id => $column_display_name): ?>
						<?php
						switch ($column_id):
							case 'id':
								?>
								<th valign="top" scope="row">
									<?php echo $item['id'] ?>
								</th>
								<?php
								break;
							case 'scheduled':
								?>
								<td>
									<?php echo $item['scheduled'] ? $periods[$item['scheduled']] : '' ?>
								</td>
								<?php
								break;
							case 'first_import':
								?>
								<td>
									<?php if ('0000-00-00 00:00:00' == $item['first_import']): ?>
										<em>never</em>
									<?php else: ?>
										<?php echo mysql2date(__('Y/m/d g:i a', 'pmxi_plugin'), $item['first_import']) ?>
									<?php endif ?>
								</td>
								<?php
								break;
							case 'registered_on':
								?>
								<td>
									<?php if ('0000-00-00 00:00:00' == $item['registered_on']): ?>
										<em>never</em>
									<?php else: ?>
										<?php echo mysql2date(__('Y/m/d g:i a', 'pmxi_plugin'), $item['registered_on']) ?>
									<?php endif ?>
								</td>
								<?php
								break;
							case 'next_import':
								?>
								<td>
									<?php if ('0000-00-00 00:00:00' == $item['registered_on'] or empty($item['scheduled'])): ?>
										<em>never</em>
									<?php
									else:
										$task = new _PMXI_Import_Record_Cron_Parser($item['scheduled']);
										$task_date = $task->getNextRunDate();
										echo mysql2date(__('Y/m/d g:i a', 'pmxi_plugin'), $task_date->format('Y-m-d H:i:s'));
									endif;
									?>
								</td>
								<?php
								break;
							case 'name':
								?>
								<td>
									<strong><?php echo apply_filters("pmxi_import_name", (!empty($item['friendly_name'])) ? $item['friendly_name'] : $item['name'], $item['id']); ?></strong> <?php if ( (int) $item['triggered']) _e("<i> -> Import triggered...</i>"); if ( (int) $item['processing']) _e("<i> -> Import currently in progress....</i>");  ?><br>
									<?php if ($item['path']): ?>
										<em><?php echo str_replace("\\", '/', preg_replace('%^(\w+://[^:]+:)[^@]+@%', '$1*****@', $item['path'])); ?></em>
									<?php endif ?>
									<div class="row-actions">

										<?php do_action('pmxi_import_menu', $item['id'], $this->baseUrl); ?>

										<span class="edit"><a class="edit" href="<?php echo esc_url(add_query_arg(array('id' => $item['id'], 'action' => 'edit'), $this->baseUrl)) ?>"><?php _e('Edit Template', 'pmxi_plugin') ?></a></span> |
										<span class="edit"><a class="edit" href="<?php echo esc_url(add_query_arg(array('id' => $item['id'], 'action' => 'options'), $this->baseUrl)) ?>"><?php _e('Edit Options', 'pmxi_plugin') ?></a></span> |
										<span class="update"><a class="update" href="<?php echo esc_url(add_query_arg(array('id' => $item['id'], 'action' => 'update'), $this->baseUrl)) ?>"><?php _e('Re-Run Import', 'pmxi_plugin') ?></a></span> |

										<?php if ( in_array($item['type'], array('url', 'ftp', 'file'))): ?>
											<!--span class="edit get_cron_url"><a class="edit" href="javascript:void(0);" rel='<?php echo "wget -q -O /dev/null \"".home_url()."?import_key=".PMXI_Plugin::getInstance()->getOption('cron_job_key')."&import_id=".$item['id']."&action=processing\"\n" . "wget -q -O /dev/null "."\"".home_url()."?import_key=".PMXI_Plugin::getInstance()->getOption('cron_job_key')."&import_id=".$item['id']."&action=trigger"."\"";?>'><?php _e('Get Cron URL', 'pmxi_plugin') ?></a></span> |-->
											<span class="edit"><a class="edit" href="<?php echo esc_url(add_query_arg(array('id' => $item['id'], 'action' => 'scheduling'), $this->baseUrl)) ?>"><?php _e('Cron Scheduling', 'pmxi_plugin') ?></a></span> |
										<?php endif; ?>
										<span class="update"><a class="update" href="<?php echo esc_url(add_query_arg(array('page' => 'pmxi-admin-import', 'id' => $item['id']), admin_url('admin.php'))) ?>"><?php _e('Re-Run With New File', 'pmxi_plugin') ?></a></span> |
										<span class="update"><a class="update" href="<?php echo esc_url(add_query_arg(array('id' => $item['id'], 'action' => 'log'), $this->baseUrl)) ?>"><?php _e('Download Log', 'pmxi_plugin') ?></a></span> |
										<span class="delete"><a class="delete" href="<?php echo esc_url(add_query_arg(array('id' => $item['id'], 'action' => 'delete'), $this->baseUrl)) ?>"><?php _e('Delete', 'pmxi_plugin') ?></a></span>
										<?php if ( ($item['imported'] + $item['skipped']) < $item['count'] and ! $item['options']['is_import_specified'] and ! (int) $item['triggered'] ):?>
										| <span class="update"><a class="update" href="<?php echo esc_url(add_query_arg(array('id' => $item['id'], 'action' => 'update', 'type' => 'continue'), $this->baseUrl)) ?>"><?php _e('Continue import', 'pmxi_plugin') ?></a></span>
										<?php endif; ?>
									</div>
								</td>
								<?php
								break;
							case 'xpath':
								?>
								<td>
									<?php echo $item['xpath'];?>
								</td>
								<?php
								break;
							case 'post_count':
								?>
								<td>
									<strong><?php echo $item['post_count'] ?></strong>
								</td>
								<?php
								break;
							default:
								?>
								<td>
									<?php do_action('pmxi_manage_imports_column', $column_id, $item); ?>
								</td>
								<?php
								break;
						endswitch;
						?>
					<?php endforeach; ?>
				</tr>
				<?php do_action('pmxi_manage_imports', $item, $class); ?>
			<?php endforeach; ?>
		<?php endif ?>
		</tbody>
	</table>

	<div class="tablenav">
		<?php if ($page_links): ?><div class="tablenav-pages"><?php echo $page_links_html ?></div><?php endif ?>

		<div class="alignleft actions">
			<select name="bulk-action2">
				<option value="" selected="selected"><?php _e('Bulk Actions', 'pmxi_plugin') ?></option>
				<?php if ( empty($type) or 'trash' != $type): ?>
					<option value="delete"><?php _e('Delete', 'pmxi_plugin') ?></option>
				<?php else: ?>
					<option value="restore"><?php _e('Restore', 'pmxi_plugin')?></option>
					<option value="delete"><?php _e('Delete Permanently', 'pmxi_plugin')?></option>
				<?php endif ?>
			</select>
			<input type="submit" value="<?php esc_attr_e('Apply', 'pmxi_plugin') ?>" name="doaction2" id="doaction2" class="button-secondary action" />
		</div>
	</div>
	<div class="clear"></div>
	<?php
	// notify user
	if (!PMXI_Plugin::getInstance()->getOption('dismiss_manage_bottom')) {
		?>
		<div class="updated_bottom"><p>
			<?php printf(
					__('<a href="http://wordpress.org/extend/plugins/wp-all-import" target="_blank">If you like WPAllImport, please rate us five stars on WordPress.org!</a> <a href="http://wordpress.org/extend/plugins/wp-all-import" class="pmxi_stars" target="_blank"></a> <br/><br/><a href="javascript:void(0);" id="dismiss_manage_bottom">dismiss</a>', 'pmxi_plugin')
			) ?>
		</p></div>
		<?php
	}
	?>

	<p style='font-size: 1.3em; font-weight: bold;'><a href="http://www.wpallimport.com/upgrade-to-pro?utm_source=wordpress.org&utm_medium=manage&utm_campaign=free+plugin" target="_blank" class="upgrade_link">Find out more about the professional edition of WP All Import.</a></p>
	
</form>
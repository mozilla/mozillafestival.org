<?php if (in_array($source_type, array('url', 'ftp'))): ?>
	<tr>
		<td colspan="3" style="padding-top:20px;">
			<fieldset class="optionsset">
				<legend><?php _e('Scheduling','pmxi_plugin');?></legend>			
				<div class="input">
					<input type="hidden" name="is_scheduled" value="0" />
					<input type="checkbox" id="is_scheduled_<?php echo $entry; ?>" class="switcher fix_checkbox" name="is_scheduled" value="1" <?php echo $scheduled['is_scheduled'] ? 'checked="checked"': '' ?>/>
					<label for="is_scheduled_<?php echo $entry; ?>"><?php _e('Schedule import using WordPress Scheduling Logic', 'pmxi_plugin') ?> <a href="#help" class="help" title="<?php _e('Using this is not recommended. Unless you are importing a very small file, use cron jobs instead.', 'pmxi_plugin') ?>">?</a></label>
					<span class="switcher-target-is_scheduled_<?php echo $entry; ?>" style="vertical-align:middle">
						<select name="scheduled_period">
							<?php foreach (array(
									'*/5 * * * *' => __('every 5 min'),
									'*/10 * * * *' => __('every 10 min'),
									'*/30 * * * *' => __('half-hourly'),
									'0 * * * *' => __('hourly'),
									'0 */4 * * *' => __('every 4 hours'),
									'0 */12 * * *' => __('half-daily'),
									'0 0 * * *' => __('daily'),
									'0 0 * * 1' => __('weekly'),
									'0 0 1 * 1' => __('monthly'),
								) as $key => $title): ?>
								<option value="<?php echo $key ?>" <?php echo $key == $scheduled['scheduled_period'] ? 'selected="selected"' : '' ?>><?php echo esc_html($title) ?></option>
							<?php endforeach ?>
						</select>
						<a href="#help" class="help" title="<?php _e('<b>Warning</b>: Execution periods are not guaranteed due to internal WordPress scheduling logic. Scheduled tasks are only triggered upon a user request/visit to the site. On sites with low user activity, when time span between requests exceeds scheduled periods, those scheduling periods cannot be kept. In most cases though, such behavior achives the goal since delayed tasks are run right when a request is registered and user sees the content after the task has been executed. ', 'pmxi_plugin') ?>">?</a>
					</span>

					<br /><br />

					<p>
						<?php _e('<b>This import can be also be scheduled using cron jobs.</b> Save the import, visit the <i>Manage Imports</i> page, and click the <i>Cron Scheduling</i> link to set up cron scheduling. Using WordPress scheduling logic is not recommended for large files.','pmxi_plugin');?>
					</p>

				</div>					
			</fieldset>
		</td>
	</tr>
<?php endif;?>
<h2>
	<?php _e('Cron Scheduling', 'pmxi_plugin') ?>
</h2>

<p>
	To schedule an import, you must create two cron jobs in your web hosting control panel. One cron job will be used to run the Trigger script, the other to run the Execution script.
</p>

<p>
	Trigger Script URL<br /><small>Run the trigger script when you want to update your import. Once per 24 hours is recommended.</small><br /><input style='width: 700px;' type='text' value='<?php echo home_url() . '/wp-cron.php?import_key=' . $cron_job_key . '&import_id=' . $id . '&action=trigger'; ?>' />
	<br /><br />

	Execution Script URL<br /><small>Run the execution script frequently. Once per two minutes is recommended.</small><br /><input style='width: 700px;' type='text' value='<?php echo home_url() . '/wp-cron.php?import_key=' . $cron_job_key . '&import_id=' . $id . '&action=processing'; ?>' /><br /><br />
</p>


<p><strong>Trigger Script</strong></p>

<p>Every time you want to schedule the import, run the trigger script.</p>

<p>To schedule the import to run once every 24 hours, run the trigger script every 24 hours. Most hosts require you to use “wget” to access a URL. Ask your host for details.</p>

<p><i>Example:</i></p>

<p>wget -q -O /dev/null "<?php echo home_url() . '/wp-cron.php?import_key=' . $cron_job_key . '&import_id=' . $id . '&action=trigger'; ?>"</p>
 
<p><strong>Execution Script</strong></p>

<p>The Execution script actually executes the import, once it has been triggered with the Trigger script.</p>

<p>It processes in iteration (only importing a few records each time it runs) to optimize server load. It is recommended you run the execution script every 2 minutes.</p>

<p>It also operates this way in case of unexpected crashes by your web host. If it crashes before the import is finished, the next run of the cron job two minutes later will continue it where it left off, ensuring reliability.</p>

<p><i>Example:</i></p>

<p>wget -q -O /dev/null "<?php echo home_url() . '/wp-cron.php?import_key=' . $cron_job_key . '&import_id=' . $id . '&action=processing'; ?>"</p>

<p><strong>Notes</strong></p>
 
<p>
	Your web host may require you to use a command other than wget, although wget is most common. In this case, you must asking your web hosting provider for help.
</p>

<p>
	See the <a href='http://www.wpallimport.com/documentation/common-use-cases/setting-up-a-recurring-import-using-cron-jobs/'>documentation</a> for more details.
</p>


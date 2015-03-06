<div class="inner-content">
	<h2><?php _e('Import XML - <span id="status">Processing...</span>', 'pmxi_plugin') ?></h2>
	<hr />	
	<p id="process_notice"><?php _e('Importing may take some time. Please do not close your browser or refresh the page until the process is complete.', 'pmxi_plugin') ?></p>
	<div id="processbar">
		<div></div>
		<span id="import_progress">
			<span id="left_progress"><?php _e('Time Elapsed', 'pmxi_plugin');?> <span id="then">00:00:00</span></span><span id="center_progress"> <?php _e('Created','pmxi_plugin');?> <span id="created_count"><?php echo $update_previous->created; ?></span> / <?php _e('Updated','pmxi_plugin');?> <span id="updated_count"><?php echo $update_previous->updated; ?></span> <?php _e('of', 'pmxi_plugin');?> <span id="of"><?php echo $update_previous->count; ?></span> <?php _e('records', 'pmxi_plugin'); ?> </span><span id="right_progress"><span id="percents_count">0</span>%</span>
		</span>
	</div>
	<div id="logbar">
		<a href="javascript:void(0);" id="view_log"><?php _e('View Log','pmxi_plugin');?></a><span id="download_log_separator"> | </span> <a href="<?php echo add_query_arg(array('id' => $update_previous->id, 'action' => 'log', 'page' => 'pmxi-admin-manage'), $this->baseUrl); ?>" id="download_log"><?php _e('Download Log','pmxi_plugin');?></a>
		<p><?php _e('Warnings','pmxi_plugin');?> (<span id="warnings">0</span>), <?php _e('Errors','pmxi_plugin');?> (<span id="errors">0</span>)</p>
	</div>
	<fieldset id="logwrapper" <?php if ( "default" == $update_previous->options['import_processing'] ): ?>style="display:block;"<?php else: ?>style="display:none;"<?php endif; ?>>
		<legend><?php _e('Log','pmxi_plugin');?></legend>
		<div id="loglist">
			
		</div>
	</fieldset>
	<a href="<?php echo add_query_arg(array('page' => 'pmxi-admin-manage'), remove_query_arg(array('id','page'), $this->baseUrl)); ?>" style="float:right; display:none;" id="import_finished"><?php _e('Manage Imports', 'pmxi_plugin') ?></a>									
</div>

<script type="text/javascript">
//<![CDATA[
(function($){

	var odd = false;

	$('#status').each(function () {

		var then = $('#then');
		start_date = moment().sod();		
		update = function(){
			var duration = moment.duration({'seconds' : 1});
			start_date.add(duration); 

			if ( ! $('#download_log').is(':visible')) then.html(start_date.format('HH:mm:ss'));
		};
		update();
		setInterval(update, 1000);

		var $this = $(this);
		if ($this.html().match(/\.{3}$/)) {
			var dots = 0;
			var status = $this.html().replace(/\.{3}$/, '');
			var interval ;						
			interval = setInterval(function () {				
				if ($this.html().match(new RegExp(status + '\\.{1,3}$', ''))) {									
					$this.html(status + '...'.substr(0, dots++ % 3 + 1));
				} else {						
					$('#download_log').show();
					$('#download_log_separator').show();	
					$('#process_notice').hide();				
					clearInterval(update);
					clearInterval(interval);					
				}								
				// fill log bar									
				
				write_log();	

				var percents = $('#percents_count').html();
				$('#processbar div').css({'width': ((parseInt(percents) > 100 || percents == undefined) ? 100 : percents) + '%'});		
				

			}, 1000);
			$('#processbar').css({'visibility':'visible'});
		}
	});

	function write_log(){			

		$('.progress-msg').each(function(i){ 
												
			if ($('#loglist').find('p').length > 100) $('#loglist').html('');										

			<?php if ( "default" == $update_previous->options['import_processing'] ): ?>
				if ($(this).find('.processing_info').length) {
					$('#created_count').html($(this).find('.created_count').html());
					$('#updated_count').html($(this).find('.updated_count').html());
					$('#percents_count').html($(this).find('.percents_count').html());					
				}
			<?php endif; ?>

			if ( ! $(this).find('.processing_info').length ){ 
				$('#loglist').append('<p ' + ((odd) ? 'class="odd"' : 'class="even"') + '>' + $(this).html() + '</p>');
				odd = !odd;
			}
			$('#loglist').animate({ scrollTop: $('#loglist').get(0).scrollHeight }, 0);			
			$(this).remove();			
		});	
	}

	window.onbeforeunload = function () {
		return 'WARNING:\nImport process in under way, leaving the page will interrupt\nthe operation and most likely to cause leftovers in posts.';
	};		
})(jQuery);

//]]>
</script>

<?php if ( "ajax" == $update_previous->options['import_processing'] ): ?>
<script type="text/javascript">
	//<![CDATA[
	(function($){
		function parse_element(){
			$.post('admin.php?page=pmxi-admin-import&action=process', {}, function (data) {
				
				// responce with error
				if (data === null){
					$('#process_notice').hide();
					$('#import_finished').show();
					$('#download_log').show();
					$('#download_log_separator').show();
					$('#status').html('Error');
					window.onbeforeunload = false;
					return;
				}

				$('#loglist').append(data.log);

				$('#created_count').html(data.created);
				$('#updated_count').html(data.updated);
				$('#warnings').html(data.warnings);
				$('#errors').html(data.errors);
				$('#percents_count').html(data.percentage);
			    $('#processbar div').css({'width': data.percentage + '%'});

				if ( data.done ){
					$('#process_notice').hide();					
					$('#download_log').attr('href', data.log_link).show();
					$('#download_log_separator').show();					
				} 
				else parse_element();

			}, 'json').fail(function() { 					
				
				$('#process_notice').hide();				
				$('#download_log').show();
				$('#download_log_separator').show();
				$('#status').html('Error');
				window.onbeforeunload = false;

			});			
		}
		
		parse_element();

	})(jQuery);
	//]]>
</script>
<?php endif; ?>
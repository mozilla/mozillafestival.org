(function(window, $, undefined) {
	
var _parent = window.dialogArguments || opener || parent || top;

$.fn.wplupload  = function($options) {
	var $up, $defaults = { 
		runtimes : 'gears,browserplus,html5,flash,silverlight,html4',
		browse_button_hover: 'hover',
		browse_button_active: 'active'
	};
	
	$options = $.extend({}, $defaults, $options);
	
	return this.each(function() {
		var $this = $(this);
				
		$up = new plupload.Uploader($options);
		
		
		/*$up.bind('Init', function(up) {
			var dropElm = $('#' + up.settings.drop_element);
			if (dropElm.length && up.features.dragdrop) {
				dropElm.bind('dragenter', function() {
					$(this).css('border', '3px dashed #cccccc');
				});
				dropElm.bind('dragout drop', function() {
					$(this).css('border', 'none');
				});
			}			
		});*/
		
		$up.bind('FilesAdded', function(up, files) {
			$.each(files, function(i, file) {
				// Create a progress bar containing the filename
				$('#basic').find('.submit-buttons').hide();				
				$('#progress').css({'visibility':'visible'});
			})
		});
		
		$up.init();				
		
		$up.bind('Error', function(up, err) {									
			$('#upload_process').html(err.message);
		});
		
		$up.bind('FilesAdded', function(up, files) {
			// Disable submit and enable cancel
			
			$('#cancel-upload').removeAttr('disabled');
			
			$up.start();
		});
		
		$up.bind('UploadFile', function(up, file, r) {			
				
		});
		
		$up.bind('UploadProgress', function(up, file) {
			// Lengthen the progress bar
			$('#progressbar').html('Uploading data feed ... ' + file.percent + '%');
			$('#upload_process').progressbar({value:file.percent});

		});
		
		
		$up.bind('FileUploaded', function(up, file, r) {
			var fetch = typeof(shortform) == 'undefined' ? 1 : 2;			
			
			r = _parseJSON(r.response);		
			
			$('#filepath').val(r.name);

			$('#progressbar').html('Upload Complete - ' + file.name + ' (' + ( (file.size / (1024*1024) >= 1) ? (file.size / (1024*1024)).toFixed(2) + 'mb' : (file.size / (1024)).toFixed(2) + 'kb') + ')');					

			$('#basic').find('.submit-buttons').show();

			if (r.OK) {					

			} else if (r.error != undefined && '' != r.error.message) {
				$('#progressbar').html(r.error.message);
			}
			
		});
		
		$up.bind('UploadComplete', function(up) {
			$('#cancel-upload').attr('disabled', 'disabled');			
		});
		
		$('#cancel-upload').click(function() {
			var i, file;
			
			$up.stop();		
						
			i = $up.files.length;
			for (i = $up.files.length - 1; i >= 0; i--) {
				file = $up.files[i];
				if ($.inArray(file.status, [plupload.QUEUED, plupload.UPLOADING]) !== -1) {
					$up.removeFile($up.getFile(file.id));					
				}
			}
			
			$('#cancel-upload').attr('disabled', 'disabled');
			
		});
		
	});	
};

function _parseJSON(r) {
	var obj;
	try {
		var matches = r.match(/{.*}/);		
		obj = $.parseJSON(matches[0]);
	} catch (e) {		
		obj = { OK : 0 };	
	}	
	return obj;
}

}(window, jQuery));
/**
 * plugin javascript
 */
(function($){$(function () {
		
	$('#dismiss').click(function(){

		$(this).parents('div.updated:first').slideUp();
		$.post('admin.php?page=pmxi-admin-settings&action=dismiss', {dismiss: true}, function (data) {
			
		}, 'html');
	});
	
	$('#dismiss_manage_top').click(function(){

		$(this).parents('div.updated:first').slideUp();
		$.post('admin.php?page=pmxi-admin-settings&action=dismiss_manage_top', {dismiss: true}, function (data) {
			
		}, 'html');
		
	});

	$('#dismiss_manage_bottom').click(function(){

		$(this).parents('div.updated_bottom:first').slideUp();
		$.post('admin.php?page=pmxi-admin-settings&action=dismiss_manage_bottom', {dismiss: true}, function (data) {
			
		}, 'html');
		
	});
	
});})(jQuery);
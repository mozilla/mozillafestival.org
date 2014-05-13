<div class="updated">
	<p><?php printf(__('Current selection matches <span class="matches_count">%s</span> %s.', 'pmxi_plugin'), $node_list_count, _n('element', 'elements', $node_list_count, 'pmxi_plugin')) ?></p>
	<?php if (PMXI_Plugin::getInstance()->getOption('highlight_limit') and $elements->length > PMXI_Plugin::getInstance()->getOption('highlight_limit')): ?>
		<p><?php _e('<strong>Note</strong>: Highlighting is turned off since can be very slow on large sets of elements.', 'pmxi_plugin') ?></p>
	<?php endif ?>
</div>
<div id="current_xml">
	<?php $this->render_xml_element($elements->item(0), false, '//'); ?>
</div>
<script type="text/javascript">
(function($){	
	var paths = <?php echo json_encode($paths) ?>;
	var $xml = $('.xml');
	
	$xml.html($('#current_xml').html()).css({'visibility':'visible'});
	for (var i = 0; i < paths.length; i++) {
		$xml.find('.xml-element[title="' + paths[i] + '"]').addClass('selected').parents('.xml-element').find('> .xml-content.collapsed').removeClass('collapsed').parent().find('> .xml-expander').html('-');
	}	
})(jQuery);
</script>
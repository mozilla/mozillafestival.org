/**
 * plugin admin area javascript
 */
(function($){$(function () {
	if ( ! $('body.pmxi_plugin').length) return; // do not execute any code if we are not on plugin page

	$('.product_data_tabs').find('a').click(function(){
		$('.product_data_tabs').find('li').removeClass('active');
		$(this).parent('li').addClass('active');
		$('.panel').hide();
		$('#' + $(this).attr('rel')).show();
	});

	var change_depencies = function (){

		var is_variable = ($('#product-type').val() == 'variable');
		var is_grouped = ($('#product-type').val() == 'grouped');
		var is_simple = ($('#product-type').val() == 'simple');
		var is_external = ($('#product-type').val() == 'external');
		var is_downloadable = ($('#_downloadable').is(':checked'));
		var is_virtual = ($('#_virtual').is(':checked'));			
		var is_multiple_product_type = ($('input[name=is_multiple_product_type]:checked').val() == 'yes');		

		if (!is_multiple_product_type) $('.product_data_tabs li, .options_group').show();

		$('.product_data_tabs li, .options_group').each(function(){

			if (($(this).hasClass('hide_if_grouped') || 				
				$(this).hasClass('hide_if_external')) && is_multiple_product_type)				
			{
	 			if ($(this).hasClass('hide_if_grouped') && is_grouped) { $(this).hide(); return true; } else if ( $(this).hasClass('hide_if_grouped') && !is_grouped )  $(this).show(); 	 			
	 			if ($(this).hasClass('hide_if_external') && is_external) { $(this).hide(); return true; } else if ( $(this).hasClass('hide_if_external') && !is_external )  $(this).show();	 			
	 		}

	 		if (($(this).hasClass('show_if_simple') || $(this).hasClass('show_if_variable') || $(this).hasClass('show_if_grouped') || $(this).hasClass('show_if_external')) && is_multiple_product_type){
	 			if ($(this).hasClass('show_if_simple') && is_simple) $(this).show(); else if ( ! is_simple ){  
	 				$(this).hide();
	 				if ($(this).hasClass('show_if_variable') && is_variable) $(this).show(); else if ( ! is_variable ){  
	 					$(this).hide();
	 					if ($(this).hasClass('show_if_grouped') && is_grouped) $(this).show(); else if ( ! is_grouped ) { 
	 						$(this).hide();
	 						if ($(this).hasClass('show_if_external') && is_external) $(this).show(); else if ( ! is_external ) $(this).hide();
	 					}
	 				}
	 			}
	 			else if( !$(this).hasClass('show_if_simple') ){	 				
	 				if ($(this).hasClass('show_if_variable') && is_variable) $(this).show(); else if ( ! is_variable ){  
	 					$(this).hide();
	 					if ($(this).hasClass('show_if_grouped') && is_grouped) $(this).show(); else if ( ! is_grouped ) { 
	 						$(this).hide();
	 						if ($(this).hasClass('show_if_external') && is_external) $(this).show(); else if ( ! is_external ) $(this).hide();
	 					}
	 				}
	 				else if ( !$(this).hasClass('show_if_variable') ){	 					
	 					if ($(this).hasClass('show_if_grouped') && is_grouped) $(this).show(); else if ( ! is_grouped ) { 
	 						$(this).hide();
	 						if ($(this).hasClass('show_if_external') && is_external) $(this).show(); else if ( ! is_external ) $(this).hide();
	 					}
	 					else if ( !$(this).hasClass('show_if_grouped') ){
	 						if ($(this).hasClass('show_if_external') && is_external) $(this).show(); else if ( ! is_external ) $(this).hide();
	 					}
	 				}
	 			}
	 		}

	 		if ($(this).hasClass('hide_if_virtual') || 
				$(this).hasClass('show_if_virtual') || 
				$(this).hasClass('show_if_downloadable'))
	 		{
	 			if ($(this).hasClass('hide_if_virtual') && is_virtual) $(this).hide(); else if ( $(this).hasClass('hide_if_virtual') && !is_virtual )  $(this).show();
	 			if ($(this).hasClass('show_if_virtual') && is_virtual) $(this).show(); else if ( $(this).hasClass('show_if_virtual') && !is_virtual )  $(this).hide();
	 			if ($(this).hasClass('show_if_downloadable') && is_downloadable) $(this).show(); else if ( $(this).hasClass('show_if_downloadable') && !is_downloadable )  $(this).hide();
	 		}
		});

		if ($('input[name=is_product_manage_stock]:checked').val() == 'no') $('.stock_fields').hide(); else $('.stock_fields').show(); 
		
		if ($('#link_all_variations').is(':checked')) $('.variations_tab').hide(); else if (is_variable) $('.variations_tab').show();			
		
		if ($('#xml_matching_parent').is(':checked') && is_variable) $('#variations_tag').show(); else $('#variations_tag').hide();

		if ( ! is_simple ) {
			$('.woocommerce_options_panel').find('input, select').attr('disabled','disabled'); 
			$('.upgrade_template').show();
		} 
		else { 
			$('.woocommerce_options_panel').find('input, select').removeAttr('disabled');
			$('.upgrade_template').hide();
		}
	}

	$('input[name=matching_parent]').click(function(){

		if ($(this).val() == "xml") $('#variations_tag').show(); else $('#variations_tag').hide();

	});

	change_depencies();

	$('#product-type').change(function(){
		change_depencies();
		$('.wc-tabs').find('li:visible:first').find('a').click();
	});
	$('#_virtual, #_downloadable, input[name=is_product_manage_stock]').click(change_depencies);
	$('input[name=is_multiple_product_type]').click(function(){
		change_depencies();
		$('.wc-tabs').find('li:visible:first').find('a').click();
	});
	$('#link_all_variations').change(function(){
		if ($(this).is(':checked'))
			$('.variations_tab').hide();		
		else
			$('.variations_tab').show();
	});
	$('#regular_price_shedule').click(function(){
		$('#sale_price_range').show();
		$('input[name=is_regular_price_shedule]').val('1');
		$(this).hide();
	});

	$('#cancel_regular_price_shedule').click(function(){
		$('#sale_price_range').hide();
		$('input[name=is_regular_price_shedule]').val('0');
		$('#regular_price_shedule').show();
	});

	$('#variable_sale_price_shedule').click(function(){
		$('#variable_sale_price_range').show();
		$('input[name=is_variable_sale_price_shedule]').val('1');		
		$(this).hide();
	});

	$('#cancel_variable_regular_price_shedule').click(function(){
		$('#variable_sale_price_range').hide();
		$('input[name=is_variable_sale_price_shedule]').val('0');		
		$('#variable_sale_price_shedule').show();
	});

	$('#_variable_virtual').click(function(){
		if ($(this).is(':checked')){
			$('#variable_virtual').show();
			$('#variable_dimensions').hide();
		}
		else{
			$('#variable_virtual').hide();
			$('#variable_dimensions').show();
		}
	});

	$('#_variable_downloadable').click(function(){
		if ($(this).is(':checked')) $('#variable_downloadable').show(); else $('#variable_downloadable').hide();
	});	

	var variation_xpath = $('#variations_xpath').val();

	$('#variations_xpath').blur(function(){
		if (variation_xpath == ""){
			$(this).val($(this).val().replace(/(\[\d\]})$/, '[*]'));			
		}
		variation_xpath = $(this).val();
	});

	$('#variations_xpath').mousemove(function(){
		if (variation_xpath == ""){
			$(this).val($(this).val().replace(/(\[\d\]})$/, '[*]}'));			
		}
		variation_xpath = $(this).val();
	});

	$('#variations_xpath').each(function () {

		var $input = $('#variations_xpath');
		var $xml = $('#variations_xml');		
		var $next_element = $('#next_variation_element');
		var $prev_element = $('#prev_variation_element');		
		var $goto_element =  $('#goto_variation_element');
		var $variation_tagno = 0;
		
		// tag preview
		/*$.fn.variation_tag = function () {
			this.each(function () {
				var $tag = $(this);
				$tag.xml('dragable');
				var tagno = parseInt($tag.find('input[name="tagno"]').val());
				var parent_tagno = parseInt($parent_tag.find('input[name="tagno"]').val());
				console.log(parent_tagno);
				$tag.find('.navigation a').click(function () {
					tagno += '#variation_prev' == $(this).attr('href') ? -1 : 1;
					$tag.addClass('loading').css('opacity', 0.7);
										
					$('#variations_console').load('admin.php?page=pmxi-admin-import&action=evaluate_variations', {xpath: $input.val(), tagno: tagno, parent_tagno: parent_tagno}, function () {
						var $indicator = $('<span />').insertBefore($tag);						
						$xml.variation_tag();
					});

					$.post('admin.php?page=pmxi-admin-import&action=evaluate_variations', {xpath: $input.val(), tagno: tagno, parent_tagno: parent_tagno}, function (data) {
						var $indicator = $('<span />').insertBefore($tag);
						$tag.replaceWith(data);
						$indicator.next().tag().prevObject.remove();
					}, 'html');
					return false;
				});
			});
			return this;
		};	*/	
			
		var variationsXPathChanged = function () {
			
			if ($input.val() == $input.data('checkedValue')) return;					
			
			// request server to return elements which correspond to xpath entered
			$input.attr('readonly', true).unbind('change', variationsXPathChanged).data('checkedValue', $input.val());
			
			var parent_tagno = parseInt($('.tag').find('input[name="tagno"]').val());

			$('#variations_console').load('admin.php?page=pmxi-admin-import&action=evaluate_variations', {xpath: $input.val(), tagno: $variation_tagno, parent_tagno: parent_tagno}, function () {
				$input.attr('readonly', false);			
				$xml.xml('dragable');
				if ($('.error').length){ 
					$xml.html('');
					$('#close_xml_tree').hide();
				}
				else $('#close_xml_tree').show();
			});
		};

		$xml.find('.navigation a').live('click', function () {
			$variation_tagno += '#variation_prev' == $(this).attr('href') ? -1 : 1;
			$input.data('checkedValue', '');
			variationsXPathChanged();
		});

		$('#variations_xpath').change(function(){$variation_tagno = 0; variationsXPathChanged();});	
		
		$('#variations_xpath').blur(function(){$variation_tagno = 0; variationsXPathChanged();});

		$('#variations_xpath').keyup(function (e) {
			if (13 == e.keyCode) {$variation_tagno = 0;  $(this).change();}
		});

		if ($input.val() != "")			
			variationsXPathChanged();				

		$('#variations_xpath').mousemove(function(){
			variationsXPathChanged();				
		});
	});
    

	$('.variation_attributes, #woocommerce_attributes').find('label').live({
        mouseenter:
           function()
           {           	
           	if ( "" == $(this).attr('for')){
				var counter = $('.variation_attributes').find('.form-field').length;
				$(this).parents('span:first').find('input').attr('id', $(this).parents('span:first').find('input').attr('name') + '_' + counter);
				$(this).attr('for', $(this).parents('span:first').find('input').attr('id'));
			}
           },
        mouseleave:
           function()
           {

           }
    });

	$('#variations_tag').draggable({ containment: "#wpwrap", zIndex: 100 }).hide();	

	$('#toggle_xml_tree').click(function(){
		$('#variations_tag').show();
	});	     

	$('#close_xml_tree').click(function(){
		$('#variations_tag').hide();
	});

	var $unique_key = $('input[name=unique_key]:first').val();
	
	$('.auto_generate_unique_key').click(function(){
		
		var attrs = new Array();
		$('#attributes_table').find('textarea[name^=attribute_value]').each(function(){
			if ("" != $(this).val() && $(this).val() != undefined) attrs.push($(this).val());
		});
		if (attrs.length){
			$(this).parents('#product:first').find('input[name=unique_key]').val($unique_key + attrs.join('-'));
			alert('The unique key has been successfully generated');
		}
		else
			alert('At first, you should add minimum one attribute on the "Attributes" tab.');
	});

});})(jQuery);

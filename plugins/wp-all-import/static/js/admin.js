/**
 * plugin admin area javascript
 */
(function($){$(function () {
	if ( ! $('body.pmxi_plugin').length) return; // do not execute any code if we are not on plugin page

	// fix layout position
	setTimeout(function () {
		$('table.layout').length && $('table.layout td.left h2:first-child').css('margin-top',  $('.wrap').offset().top - $('table.layout').offset().top);
	}, 10);	
	
	// help icons
	$('a.help').tipsy({
		gravity: function() {
			var ver = 'n';
			if ($(document).scrollTop() < $(this).offset().top - $('.tipsy').height() - 2) {
				ver = 's';
			}
			var hor = '';
			if ($(this).offset().left + $('.tipsy').width() < $(window).width() + $(document).scrollLeft()) {
				hor = 'w';
			} else if ($(this).offset().left - $('.tipsy').width() > $(document).scrollLeft()) {
				hor = 'e';
			}
	        return ver + hor;
	    },
		live: true,
		html: true,
		opacity: 1
	}).live('click', function () {
		return false;
	}).each(function () { // fix tipsy title for IE
		$(this).attr('original-title', $(this).attr('title'));
		$(this).removeAttr('title');
	});

	if ($('#pmxi_tabs').length){ 		
		if ($('form.options').length){
			$('.nav-tab').removeClass('nav-tab-active');
			if ($('#selected_post_type').val() != ''){
				var post_type_founded = false;
				$('.pmxi_tab').hide();				
				$('input[name=custom_type]').each(function(i){					
					if ($(this).val() == $('#selected_post_type').val()) { 												
						$('.nav-tab[rel='+ $(this).val() +']').addClass('nav-tab-active');
						$(this).parents('.pmxi_tab:first').show(); 
						post_type_founded = true; 
					}
				});
				if ( ! post_type_founded){
					if ($('#selected_type').val() == 'post'){
						$('.nav-tab[rel=posts]').addClass('nav-tab-active');
						$('div#posts').show();				
					}	
					else{
						$('.nav-tab[rel=pages]').addClass('nav-tab-active');
						$('div#pages').show();
					}					
				}
			}
			else if ($('#selected_type').val() != ''){
				if ($('#selected_type').val() == 'post'){
					$('.nav-tab[rel=posts]').addClass('nav-tab-active');
					$('div#posts').show();				
				}	
				else{
					$('.nav-tab[rel=pages]').addClass('nav-tab-active');
					$('div#pages').show();
				}				
			}
			$('.nav-tab-wrapper').show();
		}
		else
			$('#pmxi_tabs').tabs().show();
	}

	$('.pmxi_plugin').find('.nav-tab').click(function(){
		$('.nav-tab').removeClass('nav-tab-active');
		$(this).addClass('nav-tab-active');
		$('.pmxi_tab').hide();
		$('div#' + $(this).attr('rel')).fadeIn();				

		if ( parseInt($('div#' + $(this).attr('rel')).find('.is_disabled').val()) ) {
			$('div#' + $(this).attr('rel')).find('input, select, textarea').attr('disabled','disabled'); 			
		} 
		/*else { 
			$('div#' + $(this).attr('rel')).find('input, select, textarea').removeAttr('readonly');			
		}*/

	});	
	
	// swither show/hide logic
	$('input.switcher').live('change', function (e) {	

		if ($(this).is(':radio:checked')) {
			$(this).parents('form').find('input.switcher:radio[name="' + $(this).attr('name') + '"]').not(this).change();
		}
		var $targets = $('.switcher-target-' + $(this).attr('id'));

		var is_show = $(this).is(':checked'); if ($(this).is('.switcher-reversed')) is_show = ! is_show;
		if (is_show) {
			$targets.fadeIn();
		} else {
			$targets.hide().find('.clear-on-switch').add($targets.filter('.clear-on-switch')).val('');
		}
	}).change();
	
	// autoselect input content on click
	$('input.selectable').live('click', function () {
		$(this).select();
	});
	
	// input tags with title
	$('input[title]').each(function () {
		var $this = $(this);
		$this.bind('focus', function () {
			if ('' == $(this).val() || $(this).val() == $(this).attr('title')) {
				$(this).removeClass('note').val('');
			}
		}).bind('blur', function () {
			if ('' == $(this).val() || $(this).val() == $(this).attr('title')) {
				$(this).addClass('note').val($(this).attr('title'));
			}
		}).blur();
		$this.parents('form').bind('submit', function () {
			if ($this.val() == $this.attr('title')) {
				$this.val('');
			}
		});
	});

	// datepicker
	$('input.datepicker').datepicker({
		dateFormat: 'yy-mm-dd',
		showOn: 'button',
		buttonText: '',
		constrainInput: false,
		showAnim: 'fadeIn',
		showOptions: 'fast'
	}).bind('change', function () {
		var selectedDate = $(this).val();
		var instance = $(this).data('datepicker');
		var date = null;
		if ('' != selectedDate) {
			try {
				date = $.datepicker.parseDate(instance.settings.dateFormat || $.datepicker._defaults.dateFormat, selectedDate, instance.settings);
			} catch (e) {
				date = null;
			}
		}
		if ($(this).hasClass('range-from')) {
			$(this).parent().find('.datepicker.range-to').datepicker("option", "minDate", date);
		}
		if ($(this).hasClass('range-to')) {
			$(this).parent().find('.datepicker.range-from').datepicker("option", "maxDate", date);
		}
	}).change();
	$('.ui-datepicker').hide(); // fix: make sure datepicker doesn't break wordpress layout upon initialization 
	
	// no-enter-submit forms
	$('form.no-enter-submit').find('input,select,textarea').not('*[type="submit"]').keydown(function (e) {
		if (13 == e.keyCode) e.preventDefault();
	});

	// enter-submit form on step 1
	if ($('.pmxi_step_1').length){
		$('body').keydown(function (e) {
			if (13 == e.keyCode){
				$('form.choose-file').submit();
			}
		});
	}
	
	// choose file form: option selection dynamic
	// options form: highlight options of selected post type
	$('form.choose-file input[name="type"]').click(function() {
		if ($(this).val() == 'upload' || $(this).val() == 'file' || $(this).val() == 'reimport' || $(this).val() == 'url') $('#large_import').slideDown(); else { $('#large_import').slideUp(); $('#large_import_toggle').removeAttr('checked'); $('#large_import_xpath').slideUp();}
		var $container = $(this).parents('.file-type-container');		
		$('.file-type-container').not($container).removeClass('selected').find('.file-type-options').hide();
		$container.addClass('selected').find('.file-type-options').show();
	}).filter(':checked').click();
	
	// template form: auto submit when `load template` list value is picked
	$('form.template, form.options').find('select[name="load_template"]').change(function () {
		$(this).parents('form').submit();
	});		

	// template form: preview button
	$('form.template').each(function () {
		var $form = $(this);
		var $preview = $('#post-preview');		
		var set_encoding = false;
		var $modal = $('<div></div>').dialog({
			autoOpen: false,
			modal: true,
			title: 'Preview Post',
			width: 760,
			maxHeight: 600,
			open: function(event, ui) {
				$(this).dialog('option', 'height', 'auto').css({'max-height': $(this).dialog('option', 'maxHeight') - $(this).prev().height() - 24, 'overflow-y': 'auto'}); 
	    	}
		});
		$form.find('.preview').click(function () {
			$modal.addClass('loading').empty().dialog('open').dialog('option', 'position', 'center');
			if (tinyMCE != undefined) tinyMCE.triggerSave(false, false);
			$.post('admin.php?page=pmxi-admin-import&action=preview', $form.serialize(), function (response) {
				$modal.removeClass('loading').html(response).dialog('option', 'position', 'center');
				var $tag = $('.tag');
				var tagno = parseInt($tag.find('input[name="tagno"]').val());
				$preview.find('.navigation a').live('click', function () {
					tagno += '#prev' == $(this).attr('href') ? -1 : 1;						
					$tag.addClass('loading').css('opacity', 0.7);
					$.post('admin.php?page=pmxi-admin-import&action=tag', {tagno: tagno}, function (data) {
						var $indicator = $('<span />').insertBefore($tag);
						$tag.replaceWith(data);
						$indicator.next().tag().prevObject.remove();
						if ($('#variations_xpath').length){						
							$('#variations_xpath').data('checkedValue', '').change();
						}
						if ($('.layout').length){
					    	var offset = $('.layout').offset();
					        if ($(document).scrollTop() > offset.top)
					            $('.tag').css({'top':(($(document).scrollTop() - offset.top) ? $(document).scrollTop() - offset.top : 0) + 'px'});        
					        else
					        	$('.tag').css({'top':''});
					    }
					    $preview.find('input[name="tagno"]').die();
					    $preview.find('.navigation a').die('click');
					    $form.find('.preview').click();
					}, 'html');
					return false;
				});
				$preview.find('input[name="tagno"]').live('change', function () {
					tagno = (parseInt($(this).val()) > parseInt($preview.find('.pmxi_count').html())) ? $preview.find('.pmxi_count').html() : ( (parseInt($(this).val())) ? $(this).val() : 1 );									
					$tag.addClass('loading').css('opacity', 0.7);
					$.post('admin.php?page=pmxi-admin-import&action=tag', {tagno: tagno}, function (data) {
						var $indicator = $('<span />').insertBefore($tag);
						$tag.replaceWith(data);
						$indicator.next().tag().prevObject.remove();
						if ($('#variations_xpath').length){						
							$('#variations_xpath').data('checkedValue', '').change();
						}
						if ($('.layout').length){
					    	var offset = $('.layout').offset();
					        if ($(document).scrollTop() > offset.top)
					            $('.tag').css({'top':(($(document).scrollTop() - offset.top) ? $(document).scrollTop() - offset.top : 0) + 'px'});        
					        else
					        	$('.tag').css({'top':''});
					    }
					    $preview.find('input[name="tagno"]').die();
					    $preview.find('.navigation a').die('click');
					    $form.find('.preview').click();
					}, 'html');
					return false;
				});

				if (set_encoding){
					var $tag = $('.tag');
					$tag.addClass('loading').css('opacity', 0.7);
					$.post('admin.php?page=pmxi-admin-import&action=tag', {tagno: 0}, function (data) {
						var $indicator = $('<span />').insertBefore($tag);
						$tag.replaceWith(data);
						$indicator.next().tag().prevObject.remove();					
						if ($('.layout').length){
					    	var offset = $('.layout').offset();
					        if ($(document).scrollTop() > offset.top)
					            $('.tag').css({'top':(($(document).scrollTop() - offset.top) ? $(document).scrollTop() - offset.top : 0) + 'px'});        
					        else
					        	$('.tag').css({'top':''});
					    }
					    set_encoding = false;
					}, 'html');
				}				
			});
			return false;
		});
		$form.find('.set_encoding').live('click', function(e){
			e.preventDefault();
			set_encoding = true;			
			$form.find('input[type="button"].preview').click();
		});
	});
	
	// options form: highlight options of selected post type
	$('form.options input[name="type"]').click(function() {
		var $container = $(this).parents('.post-type-container');
		$('.post-type-container').not($container).removeClass('selected').find('.post-type-options').hide();
		$container.addClass('selected').find('.post-type-options').show();
	}).filter(':checked').click();
	// options form: add / remove custom params
	$('.form-table a.action[href="#add"]').live('click', function () {
		var $template = $(this).parents('table').first().find('tr.template');
		$template.clone(true).insertBefore($template).css('display', 'none').removeClass('template').fadeIn();
		return false;
	});
	// options form: auto submit when `load options` checkbox is checked
	$('input[name="load_options"]').click(function () {		
		if ($(this).is(':checked')) $(this).parents('form').submit();
	});
	// options form: auto submit when `reset options` checkbox is checked
	$('form.options').find('input[name="reset_options"]').click(function () {		
		if ($(this).is(':checked')) $(this).parents('form').submit();
	});
	$('.form-table .action.remove a').live('click', function () {
		$(this).parents('tr').first().remove();
		return false;
	});
	
	var dblclickbuf = {
		'selected':false,
		'value':''
	};

	function insertxpath(){
		if (dblclickbuf.selected)
		{
			$(this).val($(this).val() + dblclickbuf.value);
			$('.xml-element[title*="/'+dblclickbuf.value.replace('{','').replace('}','')+'"]').removeClass('selected');
			dblclickbuf.value = '';
			dblclickbuf.selected = false;					
		}
	}

	// [xml representation dynamic]
	$.fn.xml = function (opt) {
		if ( ! this.length) return this;
		
		var $self = this;
		var opt = opt || {};
		var action = {};
		if ('object' == typeof opt) {
			action = opt;
		} else {
			action[opt] = true;
		}
		action = $.extend({init: ! this.data('initialized')}, action);
		
		if (action.init) {
			this.data('initialized', true);
			// add expander
			this.find('.xml-expander').live('click', function () {
				var method;
				if ('-' == $(this).text()) {
					$(this).text('+');
					method = 'addClass';
				} else {
					$(this).text('-');
					method = 'removeClass';
				}
				// for nested representation based on div
				$(this).parent().find('> .xml-content')[method]('collapsed');
				// for nested representation based on tr
				var $tr = $(this).parent().parent().filter('tr.xml-element').next()[method]('collapsed');
			});
		}
		if (action.dragable) { // drag & drop
			var _w; var _dbl = 0;
			var $drag = $('__drag'); $drag.length || ($drag = $('<input type="text" id="__drag" readonly="readonly" />'));

			$drag.css({
				position: 'absolute',
				background: 'transparent',
				top: -50,
				left: 0,
				margin: 0,
				border: 'none',
				lineHeight: 1,
				opacity: 0,
				cursor: 'pointer',
				borderRadius: 0,
				zIndex:99
			}).appendTo(document.body).mousedown(function (e) {
				if (_dbl) return;
				var _x = e.pageX - $drag.offset().left;
				var _y = e.pageY - $drag.offset().top;
				if (_x < 4 || _y < 4 || $drag.width() - _x < 0 || $drag.height() - _y < 0) {
					return;
				}
				$drag.width($(document.body).width() - $drag.offset().left - 5).css('opacity', 1);
				$drag.select();
				_dbl = true; setTimeout(function () {_dbl = false;}, 400);
			}).mouseup(function () {
				$drag.css('opacity', 0).css('width', _w);
				$drag.blur();
			}).dblclick(function(){
				if (dblclickbuf.selected)
				{
					$('.xml-element[title*="/'+dblclickbuf.value.replace('{','').replace('}','')+'"]').removeClass('selected');

					if ($(this).val() == dblclickbuf.value)
					{
						dblclickbuf.value = '';
						dblclickbuf.selected = false;
					}
					else
					{
						dblclickbuf.selected = true;
						dblclickbuf.value = $(this).val();
						$('.xml-element[title*="/'+$(this).val().replace('{','').replace('}','')+'"]').addClass('selected');
					}
				}
				else
				{
					dblclickbuf.selected = true;
					dblclickbuf.value = $(this).val();
					$('.xml-element[title*="/'+$(this).val().replace('{','').replace('}','')+'"]').addClass('selected');
				}
			});
			
			$('#title, #content, .widefat, input[name^=custom_name], textarea[name^=custom_value], input[name^=featured_image], input[name^=unique_key]').bind('focus', insertxpath );
			
			$(document).mousemove(function () {
				if (parseInt($drag.css('opacity')) != 0) {
					setTimeout(function () {
						$drag.css('opacity', 0);
					}, 50);
					setTimeout(function () {
						$drag.css('width', _w);
					}, 500);
				}
			});
			
			if ($('#content').length && window.tinymce != undefined) tinymce.dom.Event.add('wp-content-editor-container', 'click', function(e) {
				if (dblclickbuf.selected)
				{
					tinyMCE.activeEditor.selection.setContent(dblclickbuf.value);
					$('.xml-element[title*="'+dblclickbuf.value.replace('{','').replace('}','')+'"]').removeClass('selected');
					dblclickbuf.value = '';
					dblclickbuf.selected = false;					
				}				
			});

			this.find('.xml-tag.opening > .xml-tag-name, .xml-attr-name').each(function () {
				var $this = $(this);
				var xpath = '.';
				if ($this.is('.xml-attr-name'))
					xpath = '{' + ($this.parents('.xml-element:first').attr('title').replace(/^\/[^\/]+\/?/, '') || '.') + '/@' + $this.html().trim() + '}';
				else
					xpath = '{' + ($this.parent().parent().attr('title').replace(/^\/[^\/]+\/?/, '') || '.') + '}';

				$this.mouseover(function (e) {
					$drag.val(xpath).offset({left: $this.offset().left - 2, top: $this.offset().top - 2}).width(_w = $this.width() + 4).height($this.height() + 4);
				});
			}).eq(0).mouseover();
		}
		return this;
	};

	var go_to_template = false;

	// selection logic
	$('form.choose-elements').each(function () {
		var $form = $(this);
		$form.find('.xml').xml();
		var $input = $form.find('input[name="xpath"]');		
		var $next_element = $form.find('#next_element');
		var $prev_element = $form.find('#prev_element');		
		var $goto_element =  $form.find('#goto_element');
		var $get_default_xpath = $form.find('#get_default_xpath');	
		var $root_element = $form.find('#root_element');		
		var $submit = $form.find('input[type="submit"]');
		var $csv_delimiter = $form.find('input[name=delimiter]');
		var $apply_delimiter = $form.find('input[name=apply_delimiter]');

		var $xml = $('.xml');
		$form.find('.xml-tag.opening').live('mousedown', function () {return false;}).live('dblclick', function () {
			if ($form.hasClass('loading')) return; // do nothing if selecting operation is currently under way
			$input.val($(this).parents('.xml-element').first().attr('title').replace(/\[\d+\]$/, '')).change();
		});
		var xpathChanged = function () {
			if ($input.val() == $input.data('checkedValue')) return;			
			
			$form.addClass('loading');			
			$form.find('.xml-element.selected').removeClass('selected'); // clear current selection
			// request server to return elements which correspond to xpath entered
			$input.attr('readonly', true).unbind('change', xpathChanged).data('checkedValue', $input.val());
			$xml.css({'visibility':'hidden'});
			$xml.parents('fieldset:first').addClass('preload');
			go_to_template = false;
			$submit.hide();
			var evaluate = function(){
				$.post('admin.php?page=pmxi-admin-import&action=evaluate', {xpath: $input.val(), show_element: $goto_element.val(), root_element:$root_element.val(), delimiter:$csv_delimiter.val()}, function (response) {					
					if (response.result){
						$('.ajax-console').html(response.html);
						$input.attr('readonly', false).change(function(){$goto_element.val(1); xpathChanged();});
						$form.removeClass('loading');
						$xml.parents('fieldset:first').removeClass('preload');
						go_to_template = true;		
						$('#pmxi_xml_element').find('option').each(function(){
							if ($(this).val() != "") $(this).remove();
						});
						$('#pmxi_xml_element').append(response.render_element);
						$submit.show();
					}
				}, "json").fail(function() { 					
					
					$xml.parents('fieldset:first').removeClass('preload');
					$form.removeClass('loading');
					$('.ajax-console').html('<div class="error inline"><p>No matching elements found for XPath expression specified.</p></div>');

				});		
			}
			evaluate();
		};
		$next_element.live('click', function(){
			var matches_count = ($('.matches_count').length) ? parseInt($('.matches_count').html()) : 0;
			var show_element = Math.min((parseInt($goto_element.val()) + 1), matches_count);
			$goto_element.val(show_element).html( show_element ); $input.data('checkedValue', ''); xpathChanged();
		});
		$prev_element.live('click', function(){
			var show_element = Math.max((parseInt($goto_element.val()) - 1), 1);
			$goto_element.val(show_element).html( show_element ); $input.data('checkedValue', ''); xpathChanged();
		});
		$goto_element.change(function(){
			var matches_count = ($('.matches_count').length) ? parseInt($('.matches_count').html()) : 0;
			var show_element = Math.max(Math.min(parseInt($goto_element.val()), matches_count), 1);
			$goto_element.val(show_element); $input.data('checkedValue', ''); xpathChanged();			
		});

		var reset_filters = function(){
			$('#apply_filters').hide();
			$('.filtering_rules').empty();	
			$('#filtering_rules').find('p').show();	
		}

		$get_default_xpath.click(function(){
			$input.val($(this).attr('rel'));
			if ($input.val() == $input.data('checkedValue')) return;									
			reset_filters();
			$root_element.val($(this).attr('root')); $goto_element.val(1);  xpathChanged();
		});
		$('.change_root_element').click(function(){
			$input.val('/' + $(this).attr('rel'));
			if ($input.val() == $input.data('checkedValue')) return;						
			reset_filters();
			$root_element.val($(this).attr('rel')); $goto_element.val(1); xpathChanged();
		});
		$input.change(function(){$goto_element.val(1); xpathChanged();}).change();
		$input.keyup(function (e) {
			if (13 == e.keyCode) $(this).change();
		});

		$apply_delimiter.click(function(){			
			if ( ! $input.attr('readonly') ){										
				$('input[name="xpath"]').data('checkedValue','');
				xpathChanged();
			}
		});

		/* Advanced Filtering */

		$('.filtering_rules').nestedSortable({
	        handle: 'div',
	        items: 'li',
	        toleranceElement: '> div',
	        update: function () {	        
	        	$('.filtering_rules').find('.condition').show();
	        	$('.filtering_rules').find('.condition:last').hide();     								
		    }
	    });

	    $('#pmxi_add_rule').click(function(){    	

	    	var $el = $('#pmxi_xml_element');
	    	var $rule = $('#pmxi_rule');
	    	var $val = $('#pmxi_value');

	    	if ($el.val() == "" || $rule.val() == "") return;    	

	    	if ($rule.val() != 'is_empty' && $rule.val() != "is_not_empty" && $val.val() == "") return;

	    	var relunumber = $('.filtering_rules').find('li').length;

	    	var html = '<li><div class="drag-element">';
	    		html += '<input type="hidden" value="'+ $el.val() +'" class="pmxi_xml_element"/>';
	    		html += '<input type="hidden" value="'+ $rule.val() +'" class="pmxi_rule"/>';
	    		html += '<input type="hidden" value="'+ $val.val() +'" class="pmxi_value"/>';
	    		html += '<span>' + $el.val() + ' <strong>' + $rule.find('option:selected').html() + '</strong> "' + $val.val() +'"</span>';
	    		html += '<span class="condition"> <label for="rule_and_'+relunumber+'">AND</label><input id="rule_and_'+relunumber+'" type="radio" value="and" name="rule_'+relunumber+'" checked="checked" class="rule_condition"/><label for="rule_or_'+relunumber+'">OR</label><input id="rule_or_'+relunumber+'" type="radio" value="or" name="rule_'+relunumber+'" class="rule_condition"/> </span>';
	    		html += '</div><a href="javascript:void(0);" class="icon-item remove-ico"></a></li>';

	    	$('#apply_filters').show();
	    	$('#filtering_rules').find('p').hide();    	

	    	$('.filtering_rules').append(html);

	    	$('.filtering_rules').find('.condition').show();
	        $('.filtering_rules').find('.condition:last').hide();

	    	$el.prop('selectedIndex',0);	
	    	$rule.prop('selectedIndex',0);	
	    	$val.val('');	    	
	    	$('#pmxi_value').show();	    	

	    });

		$('.filtering_rules').find('.remove-ico').live('click', function(){
			$(this).parents('li:first').remove();
			if (!$('.filtering_rules').find('li').length){
				$('#apply_filters').hide();
	    		$('#filtering_rules').find('p').show();			
			}
		});

		$('#pmxi_rule').change(function(){
			if ($(this).val() == 'is_empty' || $(this).val() == 'is_not_empty')
				$('#pmxi_value').hide();
			else
				$('#pmxi_value').show();
		});

		var filter = '[';

		var xpath_builder = function(rules_box, lvl){						

			var rules = rules_box.children('li');			

			if (lvl && rules.length > 1) filter += ' (';

			rules.each(function(){
				
				var node = $(this).children('.drag-element').find('.pmxi_xml_element').val();
				var condition = $(this).children('.drag-element').find('.pmxi_rule').val();
				var value = $(this).children('.drag-element').find('.pmxi_value').val();

				var clause = ($(this).children('.drag-element').find('.condition').is(':visible')) ? $(this).children('.drag-element').find('input.rule_condition:checked').val() : false;				

				var is_attr = false;

				if (node.indexOf('@') != -1){
					is_attr = true;
					node_name = node.split('@')[0];
					attr_name = node.split('@')[1];
				}

				filter += (is_attr) ? node_name.replace(/->/g, '/') : node.replace(/->/g, '/');

				if (is_attr) filter += '[@' + attr_name;
 
				switch (condition){
					case 'equals':
						filter += ' = %s';
						break;
					case 'greater':
						filter += ' > %s';
						break;
					case 'equals_or_greater':
						filter += ' >= %s';
						break;
					case 'less':
						filter += ' < %s';
						break;
					case 'equals_or_less':
						filter += ' =< %s';
						break;
					case 'contains':
						filter += '[contains(.,"%s")]';
						break;
					case 'is_empty':
						filter += '[not(text())]';
						break;
					case 'is_not_empty':
						filter += '[text()]';
						break;
				}

				filter = filter.replace('%s', value);

				if (is_attr) filter += ']';

				if (clause) filter += ' ' + clause + ' ';				

				if ($(this).children('ol').length){
					$(this).children('ol').each(function(){						
						if ($(this).children('li').length) xpath_builder($(this), 1);
					});				
				}
			});
	
			if (lvl && rules.length > 1) filter += ') ';	

		}	

		$('#apply_filters').click(function(){

			var xpath = $('input[name=xpath]').val();

			filter = '[';

			xpath_builder($('.filtering_rules'), 0);

			filter += ']';

			$input.val( $input.val().split('[')[0] + filter);

			$input.data('checkedValue', ''); xpathChanged();

		});
	});
	
	$('form.choose-elements').find('input[type="submit"]').click(function(e){
		e.preventDefault();
		if (go_to_template) $(this).parents('form:first').submit();
	});

	// tag preview
	$.fn.tag = function () {
		this.each(function () {
			var $tag = $(this);
			$tag.xml('dragable');
			var tagno = parseInt($tag.find('input[name="tagno"]').val());
			$tag.find('.navigation a').live('click', function () {
				tagno += '#prev' == $(this).attr('href') ? -1 : 1;				
				$tag.addClass('loading').css('opacity', 0.7);
				$.post('admin.php?page=pmxi-admin-import&action=tag', {tagno: tagno}, function (data) {
					var $indicator = $('<span />').insertBefore($tag);
					$tag.replaceWith(data);
					$indicator.next().tag().prevObject.remove();
					if ($('#variations_xpath').length){						
						$('#variations_xpath').data('checkedValue', '').change();
					}
					if ($('.layout').length){
				    	var offset = $('.layout').offset();
				        if ($(document).scrollTop() > offset.top)
				            $('.tag').css({'top':(($(document).scrollTop() - offset.top) ? $(document).scrollTop() - offset.top : 0) + 'px'});        
				        else
				        	$('.tag').css({'top':''});
				    }
				}, 'html');
				return false;
			});
			$tag.find('input[name="tagno"]').live('change', function () {
				tagno = (parseInt($(this).val()) > parseInt($tag.find('.pmxi_count').html())) ? $tag.find('.pmxi_count').html() : ( (parseInt($(this).val())) ? $(this).val() : 1 );				
				$(this).val(tagno);
				$tag.addClass('loading').css('opacity', 0.7);
				$.post('admin.php?page=pmxi-admin-import&action=tag', {tagno: tagno}, function (data) {
					var $indicator = $('<span />').insertBefore($tag);
					$tag.replaceWith(data);
					$indicator.next().tag().prevObject.remove();
					if ($('#variations_xpath').length){						
						$('#variations_xpath').data('checkedValue', '').change();
					}
					if ($('.layout').length){
				    	var offset = $('.layout').offset();
				        if ($(document).scrollTop() > offset.top)
				            $('.tag').css({'top':(($(document).scrollTop() - offset.top) ? $(document).scrollTop() - offset.top : 0) + 'px'});        
				        else
				        	$('.tag').css({'top':''});
				    }
				}, 'html');
				return false;
			});
		});
		return this;
	};
	$('.tag').tag();
	// [/xml representation dynamic]
	
	$('input.autocomplete').each(function () {
		$(this).autocomplete({
			source: eval($(this).attr('id')),
			minLength: 0
		}).click(function () {
			$(this).autocomplete('search', '');
		});
	});

	/* Categories hierarchy */

	$('.sortable').nestedSortable({
        handle: 'div',
        items: 'li',
        toleranceElement: '> div',
        update: function () {	        
	       $(this).parents('td:first').find('.hierarhy-output').val(window.JSON.stringify($(this).nestedSortable('toArray', {startDepthCount: 0})));
	       if ($(this).parents('td:first').find('input:first').val() == '') $(this).parents('td:first').find('.hierarhy-output').val('');
	    }
    });

    $('.drag-element').find('input').live('blur', function(){    	
    	$(this).parents('td:first').find('.hierarhy-output').val(window.JSON.stringify($(this).parents('.sortable:first').nestedSortable('toArray', {startDepthCount: 0})));
    	if ($(this).parents('td:first').find('input:first').val() == '') $(this).parents('td:first').find('.hierarhy-output').val('');
    });

    $('.drag-element').find('input').live('change', function(){    	
    	$(this).parents('td:first').find('.hierarhy-output').val(window.JSON.stringify($(this).parents('.sortable:first').nestedSortable('toArray', {startDepthCount: 0})));
    	if ($(this).parents('td:first').find('input:first').val() == '') $(this).parents('td:first').find('.hierarhy-output').val('');
    });

    $('.drag-element').find('input').live('hover', function(){},function(){    	
    	$(this).parents('td:first').find('.hierarhy-output').val(window.JSON.stringify($(this).parents('.sortable:first').nestedSortable('toArray', {startDepthCount: 0})));
    	if ($(this).parents('td:first').find('input:first').val() == '') $(this).parents('td:first').find('.hierarhy-output').val('');
    });

    $('.taxonomy_auto_nested').live('click', function(){
    	$(this).parents('td:first').find('.hierarhy-output').val(window.JSON.stringify($(this).parents('td:first').find('.sortable:first').nestedSortable('toArray', {startDepthCount: 0})));
    	if ($(this).parents('td:first').find('input:first').val() == '') $(this).parents('td:first').find('.hierarhy-output').val('');
    });

	$('.sortable').find('.remove-ico').live('click', function(){
	 	
	 	var parent_td = $(this).parents('td:first');
	 
		$(this).parents('li:first').remove(); 			
		parent_td.find('ol.sortable:first').find('li').each(function(i, e){
			$(this).attr({'id':'item_'+ (i+1)});
		});
		parent_td.find('.hierarhy-output').val(window.JSON.stringify(parent_td.find('.sortable:first').nestedSortable('toArray', {startDepthCount: 0})));	         	
	 	if (parent_td.find('input:first').val() == '') parent_td.find('.hierarhy-output').val('');	 			 	
	});

	$('.add-new-ico').live('click', function(){		
		var count = $(this).parents('tr:first').find('ol.sortable').find('li').length + 1;
		$(this).parents('tr:first').find('ol.sortable').append('<li id="item_'+count+'"><div class="drag-element"><input type="checkbox" class="assign_post" checked="checked"/><input type="text" value="" class="widefat"></div><a class="icon-item remove-ico" href="javascript:void(0);"></a></li>');
		$(this).parents('td:first').find('.hierarhy-output').val(window.JSON.stringify($(this).parents('.sortable:first').nestedSortable('toArray', {startDepthCount: 0})));
    	if ($(this).parents('td:first').find('input:first').val() == '') $(this).parents('td:first').find('.hierarhy-output').val('');
		$('.widefat').bind('focus', insertxpath );
	});
	
	$('form.options').find('input[type=submit]').click(function(e){
		e.preventDefault();
		
		$('.hierarhy-output').each(function(){			
			$(this).val(window.JSON.stringify($(this).parents('td:first').find('.sortable:first').nestedSortable('toArray', {startDepthCount: 0})));			
			if ($(this).parents('td:first').find('input:first').val() == '') $(this).val('');
		});
		if ($(this).attr('name') == 'btn_save_only') $('.save_only').val('1');

		$('input[name^=in_variations], input[name^=is_visible], input[name^=is_taxonomy], input[name^=create_taxonomy_in_not_exists], input[name^=variable_create_taxonomy_in_not_exists], input[name^=variable_in_variations], input[name^=variable_is_visible], input[name^=variable_is_taxonomy]').each(function(){
	    	if ( ! $(this).is(':checked') && ! $(this).parents('.form-field:first').hasClass('template')){	    		
	    		$(this).val('0').attr('checked','checked');
	    	}
	    });		

		$(this).parents('form:first').submit();
	});

	/* END Categories hierarchy */	

	// manage screen: cron url
	$('.get_cron_url').each(function () {
		var $form = $(this);
		var $modal = $('<div></div>').dialog({
			autoOpen: false,
			modal: true,
			title: 'Cron URLs',
			width: 760,
			maxHeight: 600,
			open: function(event, ui) {
				$(this).dialog('option', 'height', 'auto').css({'max-height': $(this).dialog('option', 'maxHeight') - $(this).prev().height() - 24, 'overflow-y': 'auto'}); 
	    	}
		});	
		$form.find('a').click(function () {
			$modal.addClass('loading').empty().dialog('open').dialog('option', 'position', 'center');									
			$modal.removeClass('loading').html('<textarea style="width:100%; height:100%;">' + $form.find('a').attr('rel') + '</textarea>').dialog('option', 'position', 'center');
		});
	});
	
	// chunk files upload
	if ($('#plupload-ui').length)
	{
		$('#plupload-ui').show();
		$('#html-upload-ui').hide();	

		wplupload = $('#select-files').wplupload({
			runtimes : 'gears,browserplus,html5,flash,silverlight,html4',
			url : 'admin.php?page=pmxi-admin-settings&action=upload',
			container: 'plupload-ui',
			browse_button : 'select-files',
			file_data_name : 'async-upload',
			flash_swf_url : plugin_url + '/static/js/plupload/plupload.flash.swf',
			silverlight_xap_url : plugin_url + '/static/js/plupload/plupload.silverlight.xap',
		
			multipart: false,
			max_file_size: '1000mb',
			chunk_size: '1mb',			
			drop_element: 'plupload-ui'
		});
	}	

	/* END plupload scripts */

	if ($('#large_import_toggle').is(':checked')) $('#large_import_xpath').slideToggle();

	$('#large_import_toggle').click(function(){
		$('#large_import_xpath').slideToggle();
	});

	// Step 4 - custom meta keys helper
	$('.existing_meta_keys').change(function(){
		var parent_fieldset = $(this).parents('fieldset');
		var key = $(this).find('option:selected').val();
		
		if ("" != $(this).val()) {
			parent_fieldset.find('input[name^=custom_name]:visible').each(function(){
				if ("" == $(this).val()) $(this).parents('tr:first').remove();
			});
			parent_fieldset.find('a.action[href="#add"]').click();
			parent_fieldset.find('input[name^=custom_name]:visible:last').val($(this).val());						

			$(this).prop('selectedIndex',0);	

			parent_fieldset.addClass('loading');		
		
			$.post('admin.php?page=pmxi-admin-settings&action=meta_values', {key: key}, function (data) {
					
				parent_fieldset.find('input[name^=custom_name]:visible:last').after(data);

				parent_fieldset.removeClass('loading');		

			}, 'html');
		}
	});

	$('input[name^=custom_name]').live('change', function(){
		var $ths = $(this);
		$ths.parents('fieldset:first').addClass('loading');
		$.post('admin.php?page=pmxi-admin-settings&action=meta_values', {key: $ths.val()}, function (data) {
			$ths.nextAll().remove();
			$ths.after(data);
			$ths.parents('fieldset:first').removeClass('loading');
		}, 'html');

	});

	$('.existing_meta_values').live('change', function(){
		var parent_fieldset = $(this).parents('.form-field:first');
		if ($(this).val() != ""){
			parent_fieldset.find('textarea').val($(this).val());
			$(this).prop('selectedIndex', 0);
		}
	});	

	if ($('#upload_process').length){ 
		$('#upload_process').progressbar({ value: (($('#progressbar').html() != '') ? 100 : 0) });
		if ($('#progressbar').html() != '')
			$('.submit-buttons').show();
	}

	$('#view_log').live('click', function(){
		$('#import_finished').css({'visibility':'hidden'});
		$('#logwrapper').slideToggle(100, function(){
			$('#import_finished').css({'visibility':'visible'});
		});
	});			

    $(document).scroll(function() {    	    	
    	if ($('.layout').length){
	    	var offset = $('.layout').offset();
	        if ($(document).scrollTop() > offset.top)
	            $('.tag').css({'top':(($(document).scrollTop() - offset.top) ? $(document).scrollTop() - offset.top : 0) + 'px'});        
	        else
	        	$('.tag').css({'top':''});
	    }
	});       

	// Select Encoding
	$('#import_encoding').live('change', function(){
		if ($(this).val() == 'new'){
			$('#select_encoding').hide();
			$('#add_encoding').show();
		}
	});

	$('#cancel_new_encoding').live('click', function(){
		$('#add_encoding').hide();
		$('#select_encoding').show();		
		$('#new_encoding').val('');
		$('#import_encoding').prop('selectedIndex',0);	
	});

	$('#add_new_encoding').live('click', function(){
		var new_encoding = $('#new_encoding').val();
		if ("" != new_encoding){
			$('#import_encoding').prepend('<option value="'+new_encoding+'">' + new_encoding + '</option>');
			$('#cancel_new_encoding').click();
			$('#import_encoding').prop('selectedIndex',0);	
		}
		else alert('Please enter encoding.');
	});

	$('input[name=keep_custom_fields]').click(function(){
		$(this).parents('.input:first').find('.keep_except').slideToggle();
	});		
	
    $('.pmxi_choosen').each(function(){    	
    	$(this).find(".choosen_input").select2({tags: $(this).find('.choosen_values').html().split(',')});
    });

	$('.pmxi_tips_pointer').click(function(){		
		$(this).pointer({
            content: $('#record_matching_pointer').html(),
            position: {
                edge: 'right',
                align: 'center'                
            },
            pointerWidth: 715,
            close: function() {
                $.post( ajaxurl, {
                    pointer: 'pksn1',
                    action: 'dismiss-wp-pointer'
                });
            }
        }).pointer('open');
	});	

});})(jQuery);

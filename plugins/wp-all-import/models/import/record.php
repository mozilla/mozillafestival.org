<?php

class PMXI_Import_Record extends PMXI_Model_Record {
	
	/**
	 * Some pre-processing logic, such as removing control characters from xml to prevent parsing errors
	 * @param string $xml
	 */
	public static function preprocessXml( & $xml) {		
		
		$xml = str_replace("&", "&amp;", str_replace("&amp;","&", $xml));
		
	}

	/**
	 * Validate XML to be valid for improt
	 * @param string $xml
	 * @param WP_Error[optional] $errors
	 * @return bool Validation status
	 */
	public static function validateXml( & $xml, $errors = NULL) {
		if (FALSE === $xml or '' == $xml) {
			$errors and $errors->add('form-validation', __('XML file does not exist, not accessible or empty', 'pmxi_plugin'));
		} else {
						
			PMXI_Import_Record::preprocessXml($xml);																						

			if ( function_exists('simplexml_load_string')){
				libxml_use_internal_errors(true);
				libxml_clear_errors();
				$_x = @simplexml_load_string($xml);
				$xml_errors = libxml_get_errors();			
				libxml_clear_errors();
				if ($xml_errors) {								
					$error_msg = '<strong>' . __('Invalid XML', 'pmxi_plugin') . '</strong><ul>';
					foreach($xml_errors as $error) {
						$error_msg .= '<li>';
						$error_msg .= __('Line', 'pmxi_plugin') . ' ' . $error->line . ', ';
						$error_msg .= __('Column', 'pmxi_plugin') . ' ' . $error->column . ', ';
						$error_msg .= __('Code', 'pmxi_plugin') . ' ' . $error->code . ': ';
						$error_msg .= '<em>' . trim(esc_html($error->message)) . '</em>';
						$error_msg .= '</li>';
					}
					$error_msg .= '</ul>';
					$errors and $errors->add('form-validation', $error_msg);				
				} else {
					return true;
				}
			}
			else{
				$errors and $errors->add('form-validation', __('simplexml module is disabled on your server', 'pmxi_plugin'));				
			}
		}
		return false;
	}

	/**
	 * Initialize model instance
	 * @param array[optional] $data Array of record data to initialize object with
	 */
	public function __construct($data = array()) {
		parent::__construct($data);
		$this->setTable(PMXI_Plugin::getInstance()->getTablePrefix() . 'imports');
	}	
	
	/**
	 * Perform import operation
	 * @param string $xml XML string to import
	 * @param callback[optional] $logger Method where progress messages are submmitted
	 * @return PMXI_Import_Record
	 * @chainable
	 */
	public function process($xml, $logger = NULL, $chunk = false, $is_cron = false, $xpath_prefix = '') {
		add_filter('user_has_cap', array($this, '_filter_has_cap_unfiltered_html')); kses_init(); // do not perform special filtering for imported content
		
		$cxpath = $xpath_prefix . $this->xpath;		

		$this->options += PMXI_Plugin::get_default_import_options(); // make sure all options are defined
		
		$avoid_pingbacks = PMXI_Plugin::getInstance()->getOption('pingbacks');
		$legacy_handling = PMXI_Plugin::getInstance()->getOption('legacy_special_character_handling');

		if ( $avoid_pingbacks and ! defined( 'WP_IMPORTING' ) ) define( 'WP_IMPORTING', true );

		$postRecord = new PMXI_Post_Record();		
		
		$tmp_files = array();
		// compose records to import
		$records = array();
		
		try { 						
			
			$chunk == 1 and $logger and call_user_func($logger, __('Composing titles...', 'pmxi_plugin'));
			$titles = XmlImportParser::factory($xml, $cxpath, $this->template['title'], $file)->parse($records); $tmp_files[] = $file;							

			$chunk == 1 and $logger and call_user_func($logger, __('Composing excerpts...', 'pmxi_plugin'));			
			$post_excerpt = array();
			if (!empty($this->options['post_excerpt'])){
				$post_excerpt = XmlImportParser::factory($xml, $cxpath, $this->options['post_excerpt'], $file)->parse($records); $tmp_files[] = $file;
			}
			else{
				count($titles) and $post_excerpt = array_fill(0, count($titles), '');
			}

			if ( "xpath" == $this->options['status'] ){
				$chunk == 1 and $logger and call_user_func($logger, __('Composing statuses...', 'pmxi_plugin'));			
				$post_status = array();
				if (!empty($this->options['status_xpath'])){
					$post_status = XmlImportParser::factory($xml, $cxpath, $this->options['status_xpath'], $file)->parse($records); $tmp_files[] = $file;
				}
				else{
					count($titles) and $post_status = array_fill(0, count($titles), '');
				}
			}

			$chunk == 1 and $logger and call_user_func($logger, __('Composing authors...', 'pmxi_plugin'));			
			$post_author = array();
			$current_user = wp_get_current_user();

			if (!empty($this->options['author'])){
				$post_author = XmlImportParser::factory($xml, $cxpath, $this->options['author'], $file)->parse($records); $tmp_files[] = $file;
				foreach ($post_author as $key => $author) {
					$user = get_user_by('login', $author) or $user = get_user_by('slug', $author) or $user = get_user_by('email', $author) or ctype_digit($author) and $user = get_user_by('id', $author);
					$post_author[$key] = (!empty($user)) ? $user->ID : $current_user->ID;
				}
			}
			else{								
				count($titles) and $post_author = array_fill(0, count($titles), $current_user->ID);
			}			

			$chunk == 1 and $logger and call_user_func($logger, __('Composing slugs...', 'pmxi_plugin'));			
			$post_slug = array();
			if (!empty($this->options['post_slug'])){
				$post_slug = XmlImportParser::factory($xml, $cxpath, $this->options['post_slug'], $file)->parse($records); $tmp_files[] = $file;
			}
			else{
				count($titles) and $post_slug = array_fill(0, count($titles), '');
			}

			$chunk == 1 and $logger and call_user_func($logger, __('Composing contents...', 'pmxi_plugin'));			 						
			$contents = XmlImportParser::factory(
				(intval($this->template['is_keep_linebreaks']) ? $xml : preg_replace('%\r\n?|\n%', ' ', $xml)),
				$cxpath,
				$this->template['content'],
				$file)->parse($records
			); $tmp_files[] = $file;						
										
			$chunk == 1 and $logger and call_user_func($logger, __('Composing dates...', 'pmxi_plugin'));
			if ('specific' == $this->options['date_type']) {
				$dates = XmlImportParser::factory($xml, $cxpath, $this->options['date'], $file)->parse($records); $tmp_files[] = $file;
				$warned = array(); // used to prevent the same notice displaying several times
				foreach ($dates as $i => $d) {
					if ($d == 'now') $d = current_time('mysql'); // Replace 'now' with the WordPress local time to account for timezone offsets (WordPress references its local time during publishing rather than the server’s time so it should use that)
					$time = strtotime($d);
					if (FALSE === $time) {
						in_array($d, $warned) or $logger and call_user_func($logger, sprintf(__('<b>WARNING</b>: unrecognized date format `%s`, assigning current date', 'pmxi_plugin'), $warned[] = $d));
						$logger and PMXI_Plugin::$session['pmxi_import']['warnings'] = ++PMXI_Plugin::$session->data['pmxi_import']['warnings'];
						$time = time();
					}
					$dates[$i] = date('Y-m-d H:i:s', $time);
				}
			} else {
				$dates_start = XmlImportParser::factory($xml, $cxpath, $this->options['date_start'], $file)->parse($records); $tmp_files[] = $file;
				$dates_end = XmlImportParser::factory($xml, $cxpath, $this->options['date_end'], $file)->parse($records); $tmp_files[] = $file;
				$warned = array(); // used to prevent the same notice displaying several times
				foreach ($dates_start as $i => $d) {
					$time_start = strtotime($dates_start[$i]);
					if (FALSE === $time_start) {
						in_array($dates_start[$i], $warned) or $logger and call_user_func($logger, sprintf(__('<b>WARNING</b>: unrecognized date format `%s`, assigning current date', 'pmxi_plugin'), $warned[] = $dates_start[$i]));
						$logger and PMXI_Plugin::$session['pmxi_import']['warnings'] = ++PMXI_Plugin::$session->data['pmxi_import']['warnings'];
						$time_start = time();
					}
					$time_end = strtotime($dates_end[$i]);
					if (FALSE === $time_end) {
						in_array($dates_end[$i], $warned) or $logger and call_user_func($logger, sprintf(__('<b>WARNING</b>: unrecognized date format `%s`, assigning current date', 'pmxi_plugin'), $warned[] = $dates_end[$i]));
						$logger and PMXI_Plugin::$session['pmxi_import']['warnings'] = ++PMXI_Plugin::$session->data['pmxi_import']['warnings'];
						$time_end = time();
					}					
					$dates[$i] = date('Y-m-d H:i:s', mt_rand($time_start, $time_end));
				}
			}
			
			$tags = array();
			if ($this->options['tags']) {
				$chunk == 1 and $logger and call_user_func($logger, __('Composing tags...', 'pmxi_plugin'));
				$tags_raw = XmlImportParser::factory($xml, $cxpath, $this->options['tags'], $file)->parse($records); $tmp_files[] = $file;
				foreach ($tags_raw as $i => $t_raw) {
					$tags[$i] = '';
					if ('' != $t_raw) $tags[$i] = implode(', ', str_getcsv($t_raw, $this->options['tags_delim']));
				}
			} else {
				count($titles) and $tags = array_fill(0, count($titles), '');
			}

			// [posts categories]
			require_once(ABSPATH . 'wp-admin/includes/taxonomy.php');

			if ('post' == $this->options['type']) {				
								
				$cats = array();

				$categories_hierarchy = (!empty($this->options['categories'])) ?  json_decode($this->options['categories']) : array();

				if ((!empty($categories_hierarchy) and is_array($categories_hierarchy))){						

					$chunk == 1 and $logger and call_user_func($logger, __('Composing categories...', 'pmxi_plugin'));
					$categories = array();
					
					foreach ($categories_hierarchy as $k => $category): if ("" == $category->xpath) continue;							
						$cats_raw = XmlImportParser::factory($xml, $cxpath, str_replace('\'','"',$category->xpath), $file)->parse($records); $tmp_files[] = $file;										
						$warned = array(); // used to prevent the same notice displaying several times
						foreach ($cats_raw as $i => $c_raw) {
							if (empty($categories_hierarchy[$k]->cat_ids[$i])) $categories_hierarchy[$k]->cat_ids[$i] = array();
							if (empty($cats[$i])) $cats[$i] = array();
							$count_cats = count($cats[$i]);
							
							$delimeted_categories = explode($this->options['categories_delim'],  $c_raw);
							
							if ('' != $c_raw) foreach (explode($this->options['categories_delim'], $c_raw) as $j => $cc) if ('' != $cc) {																								
								$cat = get_term_by('name', trim($cc), 'category') or $cat = get_term_by('slug', trim($cc), 'category') or ctype_digit($cc) and $cat = get_term_by('id', trim($cc), 'category');									
								if ( !empty($category->parent_id) ) {
									foreach ($categories_hierarchy as $key => $value){
										if ($value->item_id == $category->parent_id and !empty($value->cat_ids[$i])){												
											foreach ($value->cat_ids[$i] as $parent) {		
												if (!$j or !$this->options['categories_auto_nested']){
													$cats[$i][] = array(
														'name' => trim($cc),
														'parent' => (is_array($parent)) ? $parent['name'] : $parent, // if parent taxonomy exists then return ID else return TITLE
														'assign' => $category->assign
													);
												}
												elseif($this->options['categories_auto_nested']){
													$cats[$i][] = array(
														'name' => trim($cc),
														'parent' => (!empty($delimeted_categories[$j - 1])) ? trim($delimeted_categories[$j - 1]) : false, // if parent taxonomy exists then return ID else return TITLE
														'assign' => $category->assign
													);	
												}													
											}
										}
									}
								}
								else {
									if (!$j or !$this->options['categories_auto_nested']){
										$cats[$i][] = array(
											'name' => trim($cc),
											'parent' => false,
											'assign' => $category->assign
										);
									}
									elseif ($this->options['categories_auto_nested']){
										$cats[$i][] = array(
											'name' => trim($cc),
											'parent' => (!empty($delimeted_categories[$j - 1])) ? trim($delimeted_categories[$j - 1]) : false,
											'assign' => $category->assign
										);
									}
									
								}									
							}
							if ($count_cats < count($cats[$i])) $categories_hierarchy[$k]->cat_ids[$i][] = $cats[$i][count($cats[$i]) - 1];
						}						
					endforeach;					
				} else{
					count($titles) and $cats = array_fill(0, count($titles), '');
				}
				
			}			
			// [/posts categories]
			
			// [custom taxonomies]
			$taxonomies = array();
			$taxonomies_param = $this->options['type'].'_taxonomies';
			if ('page' == $this->options['type']) {
				$taxonomies_object_type = 'page';
			} elseif ('' != $this->options['custom_type']) {
				$taxonomies_object_type = $this->options['custom_type'];
			} else {
				$taxonomies_object_type = 'post';
			}

			if (!empty($this->options[$taxonomies_param]) and is_array($this->options[$taxonomies_param])): foreach ($this->options[$taxonomies_param] as $tx_name => $tx_template) if ('' != $tx_template) {
				$tx = get_taxonomy($tx_name);		
				$taxonomies[$tx_name] = array();
				if (!empty($tx->object_type) and in_array($taxonomies_object_type, $tx->object_type)) {
					$chunk == 1 and $logger and call_user_func($logger, sprintf(__('Composing terms for `%s` taxonomy...', 'pmxi_plugin'), $tx->labels->name));
					$txes = array();
					
					$taxonomies_hierarchy = json_decode($tx_template);
					foreach ($taxonomies_hierarchy as $k => $taxonomy){	if ("" == $taxonomy->xpath) continue;								
						$txes_raw =  XmlImportParser::factory($xml, $cxpath, str_replace('\'','"',$taxonomy->xpath), $file)->parse($records); $tmp_files[] = $file;						
						$warned = array();
						foreach ($txes_raw as $i => $tx_raw) {
							if (empty($taxonomies_hierarchy[$k]->txn_names[$i])) $taxonomies_hierarchy[$k]->txn_names[$i] = array();
							if (empty($taxonomies[$tx_name][$i])) $taxonomies[$tx_name][$i] = array();
							$count_cats = count($taxonomies[$tx_name][$i]);
							
							$delimeted_taxonomies = explode((!empty($taxonomy->delim)) ? $taxonomy->delim : ',', $tx_raw);

							if ('' != $tx_raw) foreach (explode((!empty($taxonomy->delim)) ? $taxonomy->delim : ',', $tx_raw) as $j => $cc) if ('' != $cc) {										
																																
								$cat = get_term_by('name', trim($cc), $tx_name) or $cat = get_term_by('slug', trim($cc), $tx_name) or ctype_digit($cc) and $cat = get_term_by('id', $cc, $tx_name);
								if (!empty($taxonomy->parent_id)) {																			
									foreach ($taxonomies_hierarchy as $key => $value){
										if ($value->item_id == $taxonomy->parent_id and !empty($value->txn_names[$i])){													
											foreach ($value->txn_names[$i] as $parent) {	
												if (!$j or !$taxonomy->auto_nested){																																																																
													$taxonomies[$tx_name][$i][] = array(
														'name' => trim($cc),
														'parent' => $parent,
														'assign' => $taxonomy->assign
													);
												}
												elseif ($taxonomy->auto_nested){
													$taxonomies[$tx_name][$i][] = array(
														'name' => trim($cc),
														'parent' => (!empty($delimeted_taxonomies[$j - 1])) ? trim($delimeted_taxonomies[$j - 1]) : false,
														'assign' => $taxonomy->assign
													);
												}																	
											}											
										}
									}
									
								}
								else {	
									if (!$j or !$taxonomy->auto_nested){
										$taxonomies[$tx_name][$i][] = array(
											'name' => trim($cc),
											'parent' => false,
											'assign' => $taxonomy->assign
										);
									}
									elseif ($taxonomy->auto_nested) {
										$taxonomies[$tx_name][$i][] = array(
											'name' => trim($cc),
											'parent' => (!empty($delimeted_taxonomies[$j - 1])) ? trim($delimeted_taxonomies[$j - 1]) : false,
											'assign' => $taxonomy->assign
										);
									}
								}								
							}
							if ($count_cats < count($taxonomies[$tx_name][$i])) $taxonomies_hierarchy[$k]->txn_names[$i][] = $taxonomies[$tx_name][$i][count($taxonomies[$tx_name][$i]) - 1];
						}
					}
				}
			}; endif;
			// [/custom taxonomies]				

			// serialized featured images
			if ( ! (($uploads = wp_upload_dir()) && false === $uploads['error'])) {
				$logger and call_user_func($logger, __('<b>WARNING</b>', 'pmxi_plugin') . ': ' . $uploads['error']);
				$logger and call_user_func($logger, __('<b>WARNING</b>: No featured images will be created', 'pmxi_plugin'));				
				PMXI_Plugin::$session['pmxi_import']['warnings'] = ++PMXI_Plugin::$session->data['pmxi_import']['warnings'];				
			} else {
				$chunk == 1 and $logger and call_user_func($logger, __('Composing URLs for featured images...', 'pmxi_plugin'));
				$featured_images = array();				
				if ($this->options['featured_image']) {
					// Detect if images is separated by comma										
					$imgs = ( "" == $this->options['featured_delim']) ? explode("\n", $this->options['featured_image']) : explode(',',$this->options['featured_image']);
					if (!empty($imgs)){
						$parse_multiple = true;
						foreach($imgs as $img) if (!preg_match("/{.*}/", trim($img))) $parse_multiple = false;			

						if ($parse_multiple)
						{
							foreach($imgs as $img) 
							{								
								$posts_images = XmlImportParser::factory($xml, $cxpath, trim($img), $file)->parse($records); $tmp_files[] = $file;								
								foreach($posts_images as $i => $val) $featured_images[$i][] = $val;																
							}
						}
						else
						{
							$featured_images = XmlImportParser::factory($xml, $cxpath, $this->options['featured_image'], $file)->parse($records); $tmp_files[] = $file;															
						}
					}
					
				} else {
					count($titles) and $featured_images = array_fill(0, count($titles), '');
				}
			}	

			// serialized images meta data
			if ( $this->options['set_image_meta_data'] ){
				$uploads = wp_upload_dir();
									
				// serialized images meta titles
				$chunk == 1 and $logger and call_user_func($logger, __('Composing image meta data (titles)...', 'pmxi_plugin'));
				$image_meta_titles = array();				

				if ($this->options['image_meta_title']) {
					// Detect if images is separated by comma
					$imgs = ( "" == $this->options['image_meta_title_delim']) ? explode("\n",$this->options['image_meta_title']) : explode(',',$this->options['image_meta_title']);
					
					if (!empty($imgs)){
						$parse_multiple = true;
						foreach($imgs as $img) if (!preg_match("/{.*}/", trim($img))) $parse_multiple = false;			

						if ($parse_multiple)
						{
							foreach($imgs as $img) 
							{								
								$posts_images = XmlImportParser::factory($xml, $cxpath, trim($img), $file)->parse($records); $tmp_files[] = $file;								
								foreach($posts_images as $i => $val) $image_meta_titles[$i][] = $val;								
							}
						}
						else
						{
							$image_meta_titles = XmlImportParser::factory($xml, $cxpath, $this->options['image_meta_title'], $file)->parse($records); $tmp_files[] = $file;								
						}
					}
					
				} else {
					count($titles) and $image_meta_titles = array_fill(0, count($titles), '');
				}

				// serialized images meta captions
				$chunk == 1 and $logger and call_user_func($logger, __('Composing image meta data (captions)...', 'pmxi_plugin'));
				$image_meta_captions = array();				
				if ($this->options['image_meta_caption']) {
					// Detect if images is separated by comma
					$imgs = ( "" == $this->options['image_meta_caption_delim']) ? explode("\n",$this->options['image_meta_caption']) : explode(',',$this->options['image_meta_caption']);
					if (!empty($imgs)){
						$parse_multiple = true;
						foreach($imgs as $img) if (!preg_match("/{.*}/", trim($img))) $parse_multiple = false;			

						if ($parse_multiple)
						{
							foreach($imgs as $img) 
							{								
								$posts_images = XmlImportParser::factory($xml, $cxpath, trim($img), $file)->parse($records); $tmp_files[] = $file;								
								foreach($posts_images as $i => $val) $image_meta_captions[$i][] = $val;								
							}
						}
						else
						{
							$image_meta_captions = XmlImportParser::factory($xml, $cxpath, $this->options['image_meta_caption'], $file)->parse($records); $tmp_files[] = $file;								
						}
					}
					
				} else {
					count($titles) and $image_meta_captions = array_fill(0, count($titles), '');
				}
				// serialized images meta alt text
				$chunk == 1 and $logger and call_user_func($logger, __('Composing image meta data (alt text)...', 'pmxi_plugin'));
				$image_meta_alts = array();				
				if ($this->options['image_meta_alt']) {
					// Detect if images is separated by comma
					$imgs = ( "" == $this->options['image_meta_alt_delim']) ? explode("\n",$this->options['image_meta_alt']) : explode(',',$this->options['image_meta_alt']);
					if (!empty($imgs)){
						$parse_multiple = true;
						foreach($imgs as $img) if (!preg_match("/{.*}/", trim($img))) $parse_multiple = false;			

						if ($parse_multiple)
						{
							foreach($imgs as $img) 
							{								
								$posts_images = XmlImportParser::factory($xml, $cxpath, trim($img), $file)->parse($records); $tmp_files[] = $file;								
								foreach($posts_images as $i => $val) $image_meta_alts[$i][] = $val;								
							}
						}
						else
						{
							$image_meta_alts = XmlImportParser::factory($xml, $cxpath, $this->options['image_meta_alt'], $file)->parse($records); $tmp_files[] = $file;								
						}
					}
					
				} else {
					count($titles) and $image_meta_alts = array_fill(0, count($titles), '');
				}
				// serialized images meta description
				$chunk == 1 and $logger and call_user_func($logger, __('Composing image meta data (description)...', 'pmxi_plugin'));
				$image_meta_descriptions = array();				
				if ($this->options['image_meta_description']) {
					// Detect if images is separated by comma
					$imgs = ( "" == $this->options['image_meta_description_delim']) ? explode("\n",$this->options['image_meta_description']) : explode(',',$this->options['image_meta_description']);
					if (!empty($imgs)){
						$parse_multiple = true;
						foreach($imgs as $img) if (!preg_match("/{.*}/", trim($img))) $parse_multiple = false;			

						if ($parse_multiple)
						{
							foreach($imgs as $img) 
							{								
								$posts_images = XmlImportParser::factory($xml, $cxpath, trim($img), $file)->parse($records); $tmp_files[] = $file;								
								foreach($posts_images as $i => $val) $image_meta_descriptions[$i][] = $val;								
							}
						}
						else
						{
							$image_meta_descriptions = XmlImportParser::factory($xml, $cxpath, $this->options['image_meta_description'], $file)->parse($records); $tmp_files[] = $file;								
						}
					}
					
				} else {
					count($titles) and $image_meta_descriptions = array_fill(0, count($titles), '');
				}								
			}

			// Composing images suffix
			$chunk == 1 and $this->options['auto_rename_images'] and $logger and call_user_func($logger, __('Composing images suffix...', 'pmxi_plugin'));			
			$auto_rename_images = array();
			if ( $this->options['auto_rename_images'] and ! empty($this->options['auto_rename_images_suffix'])){
				$auto_rename_images = XmlImportParser::factory($xml, $cxpath, $this->options['auto_rename_images_suffix'], $file)->parse($records); $tmp_files[] = $file;
			}
			else{
				count($titles) and $auto_rename_images = array_fill(0, count($titles), '');
			}

			// serialized attachments
			if ( ! (($uploads = wp_upload_dir()) && false === $uploads['error'])) {
				$logger and call_user_func($logger, __('<b>WARNING</b>', 'pmxi_plugin') . ': ' . $uploads['error']);				
				$logger and call_user_func($logger, __('<b>WARNING</b>: No attachments will be created', 'pmxi_plugin')); 				
				$logger and PMXI_Plugin::$session['pmxi_import']['warnings'] = ++PMXI_Plugin::$session['pmxi_import']['warnings'];
			} else {
				$chunk == 1 and $logger and call_user_func($logger, __('Composing URLs for attachments files...', 'pmxi_plugin'));
				$attachments = array();

				if ($this->options['attachments']) {
					// Detect if attachments is separated by comma
					$atchs = explode(',', $this->options['attachments']);					
					if (!empty($atchs)){
						$parse_multiple = true;
						foreach($atchs as $atch) if (!preg_match("/{.*}/", trim($atch))) $parse_multiple = false;			

						if ($parse_multiple)
						{							
							foreach($atchs as $atch) 
							{								
								$posts_attachments = XmlImportParser::factory($xml, $cxpath, trim($atch), $file)->parse($records); $tmp_files[] = $file;																
								foreach($posts_attachments as $i => $val) $attachments[$i][] = $val;								
							}
						}
						else
						{
							$attachments = XmlImportParser::factory($xml, $cxpath, $this->options['attachments'], $file)->parse($records); $tmp_files[] = $file;								
						}
					}
					
				} else {
					count($titles) and $attachments = array_fill(0, count($titles), '');
				}
			}				

			$chunk == 1 and $logger and call_user_func($logger, __('Composing unique keys...', 'pmxi_plugin'));
			$unique_keys = XmlImportParser::factory($xml, $cxpath, $this->options['unique_key'], $file)->parse($records); $tmp_files[] = $file;
			
			$chunk == 1 and $logger and call_user_func($logger, __('Processing posts...', 'pmxi_plugin'));
			
			if ('post' == $this->options['type'] and '' != $this->options['custom_type']) {
				$post_type = ($this->options['custom_type'] == 'product' and class_exists('PMWI_Plugin')) ? $this->options['custom_type'] : 'post';
			} else {
				$post_type = $this->options['type'];
			}					
			
			$addons = array();
			$addons_data = array();

			// data parsing for WP All Import add-ons
			foreach (PMXI_Admin_Addons::get_active_addons() as $class) {				
				// prepare data to parsing
				$parsingData = array(
					'import' => $this,
					'count'  => count($titles),
					'xml'    => $xml,
					'logger' => $logger,
					'chunk'  => $chunk,
					'xpath_prefix' => $xpath_prefix
				);
				$model_class = str_replace("_Plugin", "_Import_Record", $class);					
				$addons[$class] = new $model_class();
				$addons_data[$class] = $addons[$class]->parse($parsingData);								
			}

			// save current import state to variables before import
			$current_post_ids = (!empty($this->current_post_ids)) ? json_decode($this->current_post_ids, true) : array();
			$created = $this->created;
			$updated = $this->updated;
			$skipped = $this->skipped;			
			
			$specified_records = array();

			if ($this->options['is_import_specified']) {
			
				foreach (preg_split('% *, *%', $this->options['import_specified'], -1, PREG_SPLIT_NO_EMPTY) as $chank) {
					if (preg_match('%^(\d+)-(\d+)$%', $chank, $mtch)) {
						$specified_records = array_merge($specified_records, range(intval($mtch[1]), intval($mtch[2])));
					} else {
						$specified_records = array_merge($specified_records, array(intval($chank)));
					}
				}

			}

			foreach ($titles as $i => $void) {						

				wp_cache_flush();

				do_action('pmxi_before_post_import', $this->id);															

				if (empty($titles[$i])) {
					if ($addons_data['PMWI_Plugin'] and !empty($addons_data['PMWI_Plugin']['single_product_parent_ID'][$i])){
						$titles[$i] = $addons_data['PMWI_Plugin']['single_product_parent_ID'][$i] . ' Product Variation';
					}
					else{
						$skipped++;
						$logger and call_user_func($logger, __('<b>SKIPPED</b>: by empty title', 'pmxi_plugin'));						
						PMXI_Plugin::$session['pmxi_import']['chunk_number'] = ++PMXI_Plugin::$session->data['pmxi_import']['chunk_number'];	
						PMXI_Plugin::$session['pmxi_import']['warnings'] = ++PMXI_Plugin::$session->data['pmxi_import']['warnings'];												
						pmxi_session_commit();	
						continue;										
					}
				}				
					
				$articleData = array(
					'post_type' => $post_type,
					'post_status' => ("xpath" == $this->options['status']) ? $post_status[$i] : $this->options['status'],
					'comment_status' => $this->options['comment_status'],
					'ping_status' => $this->options['ping_status'],
					'post_title' => ($this->template['is_leave_html']) ? html_entity_decode($titles[$i]) : $titles[$i], 
					'post_excerpt' => ($this->template['is_leave_html']) ? html_entity_decode($post_excerpt[$i]) : $post_excerpt[$i],
					'post_name' => $post_slug[$i],
					'post_content' => ($this->template['is_leave_html']) ? html_entity_decode($contents[$i]) : $contents[$i],
					'post_date' => $dates[$i],
					'post_date_gmt' => get_gmt_from_date($dates[$i]),
					'post_author' => $post_author[$i],
					'tags_input' => $tags[$i]
				);				

				if ('post' != $articleData['post_type']){					
					$articleData += array(
						'menu_order' => $this->options['order'],
						'post_parent' => $this->options['parent'],
					);
				}				
				
				// Re-import Records Matching
				$post_to_update = false; $post_to_update_id = false;
				
				// if Auto Matching re-import option selected
				if ("manual" != $this->options['duplicate_matching']){
					
					// find corresponding article among previously imported
					
					$postRecord->clear();
					$postRecord->getBy(array(
						'unique_key' => $unique_keys[$i],
						'import_id' => $this->id,
					));
					if ( ! $postRecord->isEmpty() ) 
						$post_to_update = get_post($post_to_update_id = $postRecord->post_id);
																
				// if Manual Matching re-import option seleted
				} else {
					
					$postRecord->clear();
					// find corresponding article among previously imported
					$postRecord->getBy(array(
						'unique_key' => $unique_keys[$i],
						'import_id' => $this->id,
					));
					
					if ('custom field' == $this->options['duplicate_indicator']) {
						$custom_duplicate_value = XmlImportParser::factory($xml, $cxpath, $this->options['custom_duplicate_value'], $file)->parse($records); $tmp_files[] = $file;
						$custom_duplicate_name = XmlImportParser::factory($xml, $cxpath, $this->options['custom_duplicate_name'], $file)->parse($records); $tmp_files[] = $file;
					}
					else{
						count($titles) and $custom_duplicate_name = $custom_duplicate_value = array_fill(0, count($titles), '');					
					}

					// handle duplicates according to import settings
					if ($duplicates = pmxi_findDuplicates($articleData, $custom_duplicate_name[$i], $custom_duplicate_value[$i], $this->options['duplicate_indicator'])) {															
						$duplicate_id = array_shift($duplicates);
						if ($duplicate_id) {														
							$post_to_update = get_post($post_to_update_id = $duplicate_id);
						}						
					}
				}

				if (!empty($specified_records)){
					if ( ! in_array($created + $updated + $skipped + 1, $specified_records) ){
						$skipped++;			
						if ( ! empty($post_to_update_id) and ! in_array($post_to_update_id, $current_post_ids) ) $current_post_ids[] = $post_to_update_id;				
						$logger and call_user_func($logger, __('<b>SKIPPED</b>: by specified records option', 'pmxi_plugin'));
						$logger and PMXI_Plugin::$session['pmxi_import']['warnings'] = ++PMXI_Plugin::$session->data['pmxi_import']['warnings'];					
						$logger and PMXI_Plugin::$session['pmxi_import']['chunk_number'] = ++PMXI_Plugin::$session->data['pmxi_import']['chunk_number'];
						pmxi_session_commit();						
						continue;
					}										
				}				

				// Duplicate record is founded
				if ($post_to_update){

					// Do not update already existing records option selected
					if ("yes" == $this->options['is_keep_former_posts']) {																	
						
						if ( ! in_array($post_to_update_id, $current_post_ids) ) $current_post_ids[] = $post_to_update_id;													
											
						// Do not update product variations
						$current_post_ids = apply_filters('pmxi_do_not_update_existing', $current_post_ids, $post_to_update_id);
																
						$skipped++;
						$logger and call_user_func($logger, sprintf(__('<b>SKIPPED</b>: Previously imported record found for `%s`', 'pmxi_plugin'), $articleData['post_title']));
						$logger and PMXI_Plugin::$session['pmxi_import']['warnings'] = ++PMXI_Plugin::$session->data['pmxi_import']['warnings'];								
						$logger and PMXI_Plugin::$session['pmxi_import']['chunk_number'] = ++PMXI_Plugin::$session->data['pmxi_import']['chunk_number'];	
						pmxi_session_commit();	
						continue;
					}					

					$articleData['ID'] = $post_to_update_id;
					// Choose which data to update
					if ( $this->options['update_all_data'] == 'no' ){
						// preserve date of already existing article when duplicate is found					
						if ( ! $this->options['is_update_categories'] or ($this->options['is_update_categories'] and $this->options['update_categories_logic'] != "full_update")) { 
							// preserve categories and tags of already existing article if corresponding setting is specified
							$cats_list = get_the_category($articleData['ID']);
							$existing_cats = array();
							if (is_wp_error($cats_list)) {
								$logger and call_user_func($logger, sprintf(__('<b>WARNING</b>: Unable to get current categories for article #%d, updating with those read from XML file', 'pmxi_plugin'), $articleData['ID']));
								$logger and PMXI_Plugin::$session['pmxi_import']['warnings'] = ++PMXI_Plugin::$session->data['pmxi_import']['warnings'];
							} else {
								$cats_new = array();
								foreach ($cats_list as $c) {
									$cats_new[] = $c->slug;
								}
								$existing_cats[$i] = $cats_new;							
							}
							
							// Re-import Post Tags
							if ( ! $this->options['is_update_categories'] or $this->options['update_categories_logic'] == 'add_new' 
								or ($this->options['update_categories_logic'] == 'only' and empty($this->options['taxonomies_list']))
								or (! empty($this->options['taxonomies_list']) and is_array($this->options['taxonomies_list']) and ((in_array('post_tag', $this->options['taxonomies_list']) and $this->options['update_categories_logic'] == 'all_except') or (!in_array('post_tag', $this->options['taxonomies_list']) and $this->options['update_categories_logic'] == 'only')))) {

									$tags_list = get_the_tags($articleData['ID']);
									if (is_wp_error($tags_list)) {
										$logger and call_user_func($logger, sprintf(__('<b>WARNING</b>: Unable to get current tags for article #%d, updating with those read from XML file', 'pmxi_plugin'), $articleData['ID']));
										$logger and PMXI_Plugin::$session['pmxi_import']['warnings'] = ++PMXI_Plugin::$session->data['pmxi_import']['warnings'];
									} else {
										$tags_new = ($this->options['is_update_categories'] and $this->options['update_categories_logic'] == 'add_new') ? array_filter(explode(",", $tags[$i])) : array();									
										if ($tags_list) foreach ($tags_list as $t) {
											if ( ! in_array($t->name, $tags_new) ) $tags_new[] = $t->name;
										}
										$articleData['tags_input'] = implode(', ', $tags_new);
									}
							}

							$existing_taxonomies = array();
							foreach (array_keys($taxonomies) as $tx_name) {
								$txes_list = get_the_terms($articleData['ID'], $tx_name);
								if (is_wp_error($txes_list)) {
									$logger and call_user_func($logger, sprintf(__('<b>WARNING</b>: Unable to get current taxonomies for article #%d, updating with those read from XML file', 'pmxi_plugin'), $articleData['ID']));
									$logger and PMXI_Plugin::$session['pmxi_import']['warnings'] = ++PMXI_Plugin::$session->data['pmxi_import']['warnings'];
								} else {
									$txes_new = array();
									if (!empty($txes_list)):
										foreach ($txes_list as $t) {
											$txes_new[] = $t->slug;
										}
									endif;
									$existing_taxonomies[$tx_name][$i] = $txes_new;								
								}
							}							
						}	
						else{							
							//Remove existing taxonomies
							foreach (array_keys($taxonomies) as $tx_name) wp_set_object_terms($articleData['ID'], NULL, $tx_name); 									
						}						
						if ( ! $this->options['is_update_dates']) { // preserve date of already existing article when duplicate is found
							$articleData['post_date'] = $post_to_update->post_date;
							$articleData['post_date_gmt'] = $post_to_update->post_date_gmt;
						}
						if ( ! $this->options['is_update_status']) { // preserve status and trashed flag
							$articleData['post_status'] = $post_to_update->post_status;
						}
						if ( ! $this->options['is_update_content']){ 
							$articleData['post_content'] = $post_to_update->post_content;
						}
						if ( ! $this->options['is_update_title']){ 
							$articleData['post_title'] = $post_to_update->post_title;												
						}
						if ( ! $this->options['is_update_slug']){ 
							$articleData['post_name'] = $post_to_update->post_name;												
						}
						if ( ! $this->options['is_update_excerpt']){ 
							$articleData['post_excerpt'] = $post_to_update->post_excerpt;												
						}										
						if ( ! $this->options['is_update_menu_order']){ 
							$articleData['menu_order'] = $post_to_update->menu_order;
						}
						if ( ! $this->options['is_update_parent']){ 
							$articleData['post_parent'] = $post_to_update->post_parent;
						}						
					}
					if ( $this->options['update_all_data'] == 'yes' or ( $this->options['update_all_data'] == 'no' and $this->options['is_update_attachments'])) {
						wp_delete_attachments($articleData['ID'], true, 'files');
					}
					// handle obsolete attachments (i.e. delete or keep) according to import settings
					if ( $this->options['update_all_data'] == 'yes' or ( $this->options['update_all_data'] == 'no' and $this->options['is_update_images'] and $this->options['update_images_logic'] == "full_update")){
						wp_delete_attachments($articleData['ID'], $this->options['download_images'], 'images');
					}
				}
				elseif ( ! $postRecord->isEmpty() ){
					
					// existing post not found though it's track was found... clear the leftover, plugin will continue to treat record as new
					$postRecord->delete();
					
				}					
				
				// no new records are created. it will only update posts it finds matching duplicates for
				if ( ! $this->options['create_new_records'] and empty($articleData['ID'])){ 
					$logger and PMXI_Plugin::$session['pmxi_import']['warnings'] = ++PMXI_Plugin::$session->data['pmxi_import']['warnings'];										
					$logger and PMXI_Plugin::$session['pmxi_import']['chunk_number'] = ++PMXI_Plugin::$session->data['pmxi_import']['chunk_number'];			
					$skipped++;		
					pmxi_session_commit();	
					continue;
				}

				// cloak urls with `WP Wizard Cloak` if corresponding option is set
				if ( ! empty($this->options['is_cloak']) and class_exists('PMLC_Plugin')) {
					if (preg_match_all('%<a\s[^>]*href=(?(?=")"([^"]*)"|(?(?=\')\'([^\']*)\'|([^\s>]*)))%is', $articleData['post_content'], $matches, PREG_PATTERN_ORDER)) {
						$hrefs = array_unique(array_merge(array_filter($matches[1]), array_filter($matches[2]), array_filter($matches[3])));
						foreach ($hrefs as $url) {
							if (preg_match('%^\w+://%i', $url)) { // mask only links having protocol
								// try to find matching cloaked link among already registered ones
								$list = new PMLC_Link_List(); $linkTable = $list->getTable();
								$rule = new PMLC_Rule_Record(); $ruleTable = $rule->getTable();
								$dest = new PMLC_Destination_Record(); $destTable = $dest->getTable();
								$list->join($ruleTable, "$ruleTable.link_id = $linkTable.id")
									->join($destTable, "$destTable.rule_id = $ruleTable.id")
									->setColumns("$linkTable.*")
									->getBy(array(
										"$linkTable.destination_type =" => 'ONE_SET',
										"$linkTable.is_trashed =" => 0,
										"$linkTable.preset =" => '',
										"$linkTable.expire_on =" => '0000-00-00',
										"$ruleTable.type =" => 'ONE_SET',
										"$destTable.weight =" => 100,
										"$destTable.url LIKE" => $url,
									), NULL, 1, 1)->convertRecords();
								if ($list->count()) { // matching link found
									$link = $list[0];
								} else { // register new cloaked link
									global $wpdb;
									$slug = max(
										intval($wpdb->get_var("SELECT MAX(CONVERT(name, SIGNED)) FROM $linkTable")),
										intval($wpdb->get_var("SELECT MAX(CONVERT(slug, SIGNED)) FROM $linkTable")),
										0
									);
									$i = 0; do {
										is_int(++$slug) and $slug > 0 or $slug = 1;
										$is_slug_found = ! intval($wpdb->get_var("SELECT COUNT(*) FROM $linkTable WHERE name = '$slug' OR slug = '$slug'"));
									} while( ! $is_slug_found and $i++ < 100000);
									if ($is_slug_found) {
										$link = new PMLC_Link_Record(array(
											'name' => strval($slug),
											'slug' => strval($slug),
											'header_tracking_code' => '',
											'footer_tracking_code' => '',
											'redirect_type' => '301',
											'destination_type' => 'ONE_SET',
											'preset' => '',
											'forward_url_params' => 1,
											'no_global_tracking_code' => 0,
											'expire_on' => '0000-00-00',
											'created_on' => date('Y-m-d H:i:s'),
											'is_trashed' => 0,
										));
										$link->insert();
										$rule = new PMLC_Rule_Record(array(
											'link_id' => $link->id,
											'type' => 'ONE_SET',
											'rule' => '',
										));
										$rule->insert();
										$dest = new PMLC_Destination_Record(array(
											'rule_id' => $rule->id,
											'url' => $url,
											'weight' => 100,
										));
										$dest->insert();
									} else {
										$logger and call_user_func($logger, sprintf(__('<b>WARNING</b>: Unable to create cloaked link for %s', 'pmxi_plugin'), $url));
										$logger and PMXI_Plugin::$session['pmxi_import']['warnings'] = ++PMXI_Plugin::$session->data['pmxi_import']['warnings'];
										$link = NULL;
									}
								}
								if ($link) { // cloaked link is found or created for url
									$articleData['post_content'] = preg_replace('%' . preg_quote($url, '%') . '(?=([\s\'"]|$))%i', $link->getUrl(), $articleData['post_content']);
								}
							}
						}
					}
				}															

				// insert article being imported								
				$pid = ($this->options['is_fast_mode']) ? pmxi_insert_post($articleData, true) : wp_insert_post($articleData, true);

				if (is_wp_error($pid)) {
					$logger and call_user_func($logger, __('<b>ERROR</b>', 'pmxi_plugin') . ': ' . $pid->get_error_message());
					$logger and PMXI_Plugin::$session['pmxi_import']['errors'] = ++PMXI_Plugin::$session->data['pmxi_import']['errors'];
				} else {										
										
					if ( ! in_array($pid, $current_post_ids) ) $current_post_ids[] = $pid;													
					
					if ("manual" != $this->options['duplicate_matching'] or empty($articleData['ID'])){						
						// associate post with import
						$postRecord->isEmpty() and $postRecord->set(array(
							'post_id' => $pid,
							'import_id' => $this->id,
							'unique_key' => $unique_keys[$i],
							'product_key' => ($post_type == "product" and PMXI_Admin_Addons::get_addon('PMWI_Plugin')) ? $addons_data['PMWI_Plugin']['single_product_ID'][$i] : ''
						))->insert();
					}

					// [post format]
					if ( current_theme_supports( 'post-formats' ) && post_type_supports( $post_type, 'post-formats' ) ){						
						set_post_format($pid, $this->options['post_format'] ); 
					}
					// [/post format]
					
					// [custom fields]
					if (empty($articleData['ID']) or $this->options['update_all_data'] == 'yes' or ($this->options['update_all_data'] == 'no' and $this->options['is_update_custom_fields']) or ($this->options['update_all_data'] == 'no' and $this->options['is_update_attributes'])) {
												
						// Delete all meta keys 
						if ( ! empty($articleData['ID']) ) {

							// Get all existing meta keys						
							$existing_meta_keys = array(); foreach (get_post_meta($pid, '') as $cur_meta_key => $cur_meta_val) $existing_meta_keys[] = $cur_meta_key;

							// delete keys which are no longer correspond to import settings																
							foreach ($existing_meta_keys as $cur_meta_key) { 
								
								// Do not delete post meta for features image 
								if ( in_array($cur_meta_key, array('_thumbnail_id','_product_image_gallery')) ) continue;																

								$field_to_delete = true;								

								// apply addons filters
								if ( ! apply_filters('pmxi_custom_field_to_delete', $field_to_delete, $pid, $post_type, $this->options, $cur_meta_key) ) continue;

								// Update all Custom Fields is defined
								if ($this->options['update_all_data'] == 'yes' or ($this->options['update_all_data'] == 'no' and $this->options['is_update_custom_fields'] and $this->options['update_custom_fields_logic'] == "full_update")) {
									delete_post_meta($pid, $cur_meta_key);								
								}
								// Update only these Custom Fields, leave the rest alone
								elseif ($this->options['update_all_data'] == 'no' and $this->options['is_update_custom_fields'] and $this->options['update_custom_fields_logic'] == "only"){
									if (!empty($this->options['custom_fields_list']) and is_array($this->options['custom_fields_list']) and in_array($cur_meta_key, $this->options['custom_fields_list'])) delete_post_meta($pid, $cur_meta_key);
								}
								// Leave these fields alone, update all other Custom Fields
								elseif ($this->options['update_all_data'] == 'no' and $this->options['is_update_custom_fields'] and $this->options['update_custom_fields_logic'] == "all_except"){
									if ( empty($this->options['custom_fields_list']) or ! in_array($cur_meta_key, $this->options['custom_fields_list'])) delete_post_meta($pid, $cur_meta_key);
								}
								
							}
						}						
						
					}
					// [/custom fields]

					// [addons import]

					// prepare data for import
					$importData = array(
						'pid' => $pid,
						'i' => $i,
						'import' => $this,
						'articleData' => $articleData,
						'xml' => $xml,
						'is_cron' => $is_cron,
						'logger' => $logger,
						'xpath_prefix' => $xpath_prefix
					);

					// deligate operation to addons
					foreach (PMXI_Admin_Addons::get_active_addons() as $class) $addons[$class]->import($importData);	
					
					// [/addons import]

					// Page Template
					if ('post' != $articleData['post_type'] and !empty($this->options['page_template'])) update_post_meta($pid, '_wp_page_template', $this->options['page_template']);
					
					// [featured image]
					if ( ! empty($uploads) and false === $uploads['error'] and !empty($featured_images[$i]) and (empty($articleData['ID']) or $this->options['update_all_data'] == "yes" or ( $this->options['update_all_data'] == "no" and $this->options['is_update_images']))) {
						
						require_once(ABSPATH . 'wp-admin/includes/image.php');
						
						if ( ! is_array($featured_images[$i]) ) $featured_images[$i] = array($featured_images[$i]);
						if ( ! is_array($image_meta_titles[$i]) ) $image_meta_titles[$i] = array($image_meta_titles[$i]);
						if ( ! is_array($image_meta_captions[$i]) ) $image_meta_captions[$i] = array($image_meta_captions[$i]);
						if ( ! is_array($image_meta_descriptions[$i]) ) $image_meta_descriptions[$i] = array($image_meta_descriptions[$i]);
						
						$success_images = false;	
						$gallery_attachment_ids = array();											

						$_pmxi_images = array();

						foreach ($featured_images[$i] as $k => $featured_image)
						{							
							$imgs = ( ! empty($this->options['featured_delim']) ) ? str_getcsv($featured_image, $this->options['featured_delim']) : explode("\n", $featured_image);
							if ( $this->options['set_image_meta_data'] ){								
								$img_titles = ( ! empty($this->options['image_meta_title_delim']) ) ? str_getcsv($image_meta_titles[$i][$k], $this->options['image_meta_title_delim']) : array($image_meta_titles[$i][$k]);
								$img_captions = ( ! empty($this->options['image_meta_caption_delim']) ) ? str_getcsv($image_meta_captions[$i][$k], $this->options['image_meta_caption_delim']) : array($image_meta_captions[$i][$k]);
								$img_alts = ( ! empty($this->options['image_meta_alt_delim']) ) ? str_getcsv($image_meta_alts[$i][$k], $this->options['image_meta_alt_delim']) : array($image_meta_alts[$i][$k]);
								$img_descriptions = ( ! empty($this->options['image_meta_description_delim']) ) ? str_getcsv($image_meta_descriptions[$i][$k], $this->options['image_meta_description_delim']) : array($image_meta_descriptions[$i][$k]);
							}
							if (!empty($imgs)) {											

								foreach ($imgs as $img_key => $img_url) { if (empty($img_url)) continue;																		

									$url = str_replace(" ", "%20", trim(pmxi_convert_encoding($img_url)));
									$img_ext = pmxi_getExtensionFromStr($url);
									if ($img_ext == "") $img_ext = pmxi_get_remote_image_ext($url);

									$image_name = (($this->options['auto_rename_images'] and "" != $auto_rename_images[$i]) ? url_title($auto_rename_images[$i] . '_' . str_replace("." . $img_ext, "", array_shift(explode('?', basename($url))))) : str_replace("." . $img_ext, "", array_shift(explode('?', basename($url))))) . (("" != $img_ext) ? '.' . $img_ext : '');

									// if wizard store image data to custom field									
									$create_image = false;
									$download_image = true;

									if (base64_decode($url, true) !== false){
										$img = @imagecreatefromstring(base64_decode($url));									    
									    if($img)
									    {	
									    	$image_filename = md5(time()) . '.jpg';
									    	$image_filepath = $uploads['path'] . '/' . $image_filename;
									    	imagejpeg($img, $image_filepath);
									    	if( ! ($image_info = @getimagesize($image_filepath)) or ! in_array($image_info[2], array(IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG))) {
												$logger and call_user_func($logger, sprintf(__('<b>WARNING</b>: File %s is not a valid image and cannot be set as featured one', 'pmxi_plugin'), $image_filepath));
												$logger and PMXI_Plugin::$session['pmxi_import']['warnings'] = ++PMXI_Plugin::$session->data['pmxi_import']['warnings'];
											} else {
												$create_image = true;											
											}
									    } 
									} 
									else {										
										
										$image_filename = wp_unique_filename($uploads['path'], $image_name);
										$image_filepath = $uploads['path'] . '/' . url_title($image_filename);

										// keep existing and add newest images
										if ( ! empty($articleData['ID']) and $this->options['is_update_images'] and $this->options['update_images_logic'] == "add_new" and $this->options['update_all_data'] == "no"){ 																																											
											
											$attachment_imgs = get_posts( array(
												'post_type' => 'attachment',
												'posts_per_page' => -1,
												'post_parent' => $pid,												
											) );

											if ( $attachment_imgs ) {
												foreach ( $attachment_imgs as $attachment_img ) {													
													if ($attachment_img->guid == $uploads['url'] . '/' . $image_name){
														$download_image = false;
														$success_images = true;
														if ( ! has_post_thumbnail($pid) ) 
															set_post_thumbnail($pid, $attachment_img->ID);
														else 
															$gallery_attachment_ids[] = $attachment_img->ID;	

														$logger and call_user_func($logger, sprintf(__('<b>Image SKIPPED</b>: The image %s is always exists for the %s', 'pmxi_plugin'), basename($attachment_img->guid), $articleData['post_title']));							
													}
												}												
											}

										}

										if ($download_image){											

											// do not download images
											if ( ! $this->options['download_images'] ){ 		

												$image_filename = $image_name;
												$image_filepath = $uploads['path'] . '/' . url_title( $image_filename );																																																				
												
												$existing_attachment = $this->wpdb->get_row( $this->wpdb->prepare( "SELECT * FROM " . $this->wpdb->prefix ."posts WHERE guid = '%s'", $image_filepath ) );

												if ( ! empty($existing_attachment->ID) ){

													$download_image = false;	
													$create_image = false;	

													if ( ! has_post_thumbnail($pid) ) 
														set_post_thumbnail($pid, $existing_attachment->ID); 											
													else 
														$gallery_attachment_ids[] = $existing_attachment->ID;	

												}
												else{													
													
													if ( @file_exists($image_filepath) ){
														$download_image = false;																				
														if( ! ($image_info = @getimagesize($image_filepath)) or ! in_array($image_info[2], array(IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG))) {
															$logger and call_user_func($logger, sprintf(__('<b>WARNING</b>: File %s is not a valid image and cannot be set as featured one', 'pmxi_plugin'), $image_filepath));
															$logger and PMXI_Plugin::$session['pmxi_import']['warnings'] = ++PMXI_Plugin::$session->data['pmxi_import']['warnings'];
															@unlink($image_filepath);
														} else {
															$create_image = true;											
														}
													}
												}
											}	

											if ($download_image){

												if ( ! get_file_curl($url, $image_filepath) and ! @file_put_contents($image_filepath, @file_get_contents($url))) {
													@unlink($image_filepath); // delete file since failed upload may result in empty file created
												} elseif( ($image_info = @getimagesize($image_filepath)) and in_array($image_info[2], array(IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG))) {
													$create_image = true;											
												}												
												
												if ( ! $create_image ){

													$url = str_replace(" ", "%20", trim($img_url));
													
													if ( ! get_file_curl($url, $image_filepath) and ! @file_put_contents($image_filepath, @file_get_contents($url))) {
														$logger and call_user_func($logger, sprintf(__('<b>WARNING</b>: File %s cannot be saved locally as %s', 'pmxi_plugin'), $url, $image_filepath));
														$logger and PMXI_Plugin::$session['pmxi_import']['warnings'] = ++PMXI_Plugin::$session->data['pmxi_import']['warnings'];
														@unlink($image_filepath); // delete file since failed upload may result in empty file created										
													} elseif( ! ($image_info = @getimagesize($image_filepath)) or ! in_array($image_info[2], array(IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG))) {
														$logger and call_user_func($logger, sprintf(__('<b>WARNING</b>: File %s is not a valid image and cannot be set as featured one', 'pmxi_plugin'), $url));
														$logger and PMXI_Plugin::$session['pmxi_import']['warnings'] = ++PMXI_Plugin::$session->data['pmxi_import']['warnings'];
														@unlink($image_filepath);
													} else {
														$create_image = true;											
													}
												}
											}
										}
									}

									if ($create_image){

										$attachment = array(
											'post_mime_type' => image_type_to_mime_type($image_info[2]),
											'guid' => $uploads['url'] . '/' . $image_filename,
											'post_title' => $image_filename,
											'post_content' => '',
										);
										if (($image_meta = wp_read_image_metadata($image_filepath))) {
											if (trim($image_meta['title']) && ! is_numeric(sanitize_title($image_meta['title'])))
												$attachment['post_title'] = $image_meta['title'];
											if (trim($image_meta['caption']))
												$attachment['post_content'] = $image_meta['caption'];
										}

										$attid = ($this->options['is_fast_mode']) ? pmxi_insert_attachment($attachment, $image_filepath, $pid) : wp_insert_attachment($attachment, $image_filepath, $pid);										

										if (is_wp_error($attid)) {
											$logger and call_user_func($logger, __('<b>WARNING</b>', 'pmxi_plugin') . ': ' . $attid->get_error_message());
											$logger and PMXI_Plugin::$session['pmxi_import']['warnings'] = ++PMXI_Plugin::$session->data['pmxi_import']['warnings'];
										} else {
											// you must first include the image.php file
											// for the function wp_generate_attachment_metadata() to work
											require_once(ABSPATH . 'wp-admin/includes/image.php');
											wp_update_attachment_metadata($attid, wp_generate_attachment_metadata($attid, $image_filepath));																							
											
											if ( $this->options['set_image_meta_data'] ){											
												$update_attachment_meta = array();
												if ( ! empty($img_titles[$img_key]) )       $update_attachment_meta['post_title'] = $img_titles[$img_key];
												if ( ! empty($img_captions[$img_key]) )     $update_attachment_meta['post_excerpt'] = $img_captions[$img_key];											
												if ( ! empty($img_descriptions[$img_key]) ) $update_attachment_meta['post_content'] = $img_descriptions[$img_key];
												if ( ! empty($img_alts[$img_key]) ) update_post_meta($attid, '_wp_attachment_image_alt', $img_alts[$img_key]);
												
												if ( ! empty($update_attachment_meta)) $this->wpdb->update( $this->wpdb->posts, $update_attachment_meta, array('ID' => $attid) );																
												
											}

											do_action( 'pmxi_gallery_image', $pid, $attid, $image_filepath); 

											$success_images = true;
											if ( ! has_post_thumbnail($pid) ) 
												set_post_thumbnail($pid, $attid); 											
											else 
												$gallery_attachment_ids[] = $attid;												
										}
									}																		
								}									
							}
						}							
						// Set product gallery images
						if ( $post_type == "product" and !empty($gallery_attachment_ids) )
							update_post_meta($pid, '_product_image_gallery', implode(',', $gallery_attachment_ids));
						// Create entry as Draft if no images are downloaded successfully
						if ( ! $success_images and "yes" == $this->options['create_draft'] ) $this->wpdb->update( $this->wpdb->posts, array('post_status' => 'draft'), array('ID' => $pid) );
					}
					// [/featured image]

					// [attachments]
					if ( ! empty($uploads) and false === $uploads['error'] and !empty($attachments[$i]) and (empty($articleData['ID']) or $this->options['update_all_data'] == "yes" or ($this->options['update_all_data'] == "no" and $this->options['is_update_attachments']))) {

						// you must first include the image.php file
						// for the function wp_generate_attachment_metadata() to work
						require_once(ABSPATH . 'wp-admin/includes/image.php');

						if ( ! is_array($attachments[$i]) ) $attachments[$i] = array($attachments[$i]);

						foreach ($attachments[$i] as $attachment) { if ("" == $attachment) continue;
							
							$atchs = str_getcsv($attachment, $this->options['atch_delim']);

							if (!empty($atchs)) {
								foreach ($atchs as $atch_url) {	if (empty($atch_url)) continue;									

									$attachment_filename = wp_unique_filename($uploads['path'], basename(parse_url(trim($atch_url), PHP_URL_PATH)));										
									$attachment_filepath = $uploads['path'] . '/' . url_title($attachment_filename);
																		
									if ( ! get_file_curl(trim($atch_url), $attachment_filepath) and ! @file_put_contents($attachment_filepath, @file_get_contents(trim($atch_url)))) {												
										$logger and call_user_func($logger, sprintf(__('<b>WARNING</b>: Attachment file %s cannot be saved locally as %s', 'pmxi_plugin'), trim($atch_url), $attachment_filepath));
										$logger and PMXI_Plugin::$session['pmxi_import']['warnings'] = ++PMXI_Plugin::$session->data['pmxi_import']['warnings'];
										unlink($attachment_filepath); // delete file since failed upload may result in empty file created												
									} elseif( ! $wp_filetype = wp_check_filetype(basename($attachment_filename), null )) {
										$logger and call_user_func($logger, sprintf(__('<b>WARNING</b>: Can\'t detect attachment file type %s', 'pmxi_plugin'), trim($atch_url)));
										$logger and PMXI_Plugin::$session['pmxi_import']['warnings'] = ++PMXI_Plugin::$session->data['pmxi_import']['warnings'];
									} else {
										
										$attachment_data = array(
										    'guid' => $uploads['baseurl'] . '/' . _wp_relative_upload_path( $attachment_filepath ), 
										    'post_mime_type' => $wp_filetype['type'],
										    'post_title' => preg_replace('/\.[^.]+$/', '', basename($attachment_filepath)),
										    'post_content' => '',
										    'post_status' => 'inherit'
										);
										$attach_id = ($this->options['is_fast_mode']) ? pmxi_insert_attachment( $attachment_data, $attachment_filepath, $pid ) : wp_insert_attachment( $attachment_data, $attachment_filepath, $pid );												

										if (is_wp_error($attach_id)) {
											$logger and call_user_func($logger, __('<b>WARNING</b>', 'pmxi_plugin') . ': ' . $pid->get_error_message());
											$logger and PMXI_Plugin::$session['pmxi_import']['warnings'] = ++PMXI_Plugin::$session->data['pmxi_import']['warnings'];
										} else {											
											wp_update_attachment_metadata($attach_id, wp_generate_attachment_metadata($attach_id, $attachment_filepath));											
											do_action( 'pmxi_attachment_uploaded', $pid, $attid, $image_filepath);
										}										
									}																
								}
							}
						}
					}
					// [/attachments]
					
					// [custom taxonomies]
					if (!empty($taxonomies)){	

						foreach ($taxonomies as $tx_name => $txes) {														

							// Skip updating product attributes
							if ( PMXI_Admin_Addons::get_addon('PMWI_Plugin') and strpos($tx_name, "pa_") === 0 ) continue;

							if ( empty($articleData['ID']) or $this->options['update_all_data'] == "yes" or ( $this->options['update_all_data'] == "no" and $this->options['is_update_categories'] )) {

								if (!empty($articleData['ID'])){
									if ($this->options['update_all_data'] == "no" and $this->options['update_categories_logic'] == "all_except" and !empty($this->options['taxonomies_list']) 
										and is_array($this->options['taxonomies_list']) and in_array($tx_name, $this->options['taxonomies_list'])) continue;
									if ($this->options['update_all_data'] == "no" and $this->options['update_categories_logic'] == "only" and ((!empty($this->options['taxonomies_list']) 
										and is_array($this->options['taxonomies_list']) and ! in_array($tx_name, $this->options['taxonomies_list'])) or empty($this->options['taxonomies_list']))) continue;
								}

								$assign_taxes = array();

								if ($this->options['update_categories_logic'] == "add_new" and !empty($existing_taxonomies[$tx_name][$i])){
									$assign_taxes = $existing_taxonomies[$tx_name][$i];	
									unset($existing_taxonomies[$tx_name][$i]);
								}
								elseif(!empty($existing_taxonomies[$tx_name][$i])){
									unset($existing_taxonomies[$tx_name][$i]);
								}

								// create term if not exists
								if (!empty($txes[$i])):
									foreach ($txes[$i] as $key => $single_tax) {
										if (is_array($single_tax)){																														

											$parent_id = (!empty($single_tax['parent'])) ? pmxi_recursion_taxes($single_tax['parent'], $tx_name, $txes[$i], $key) : '';
											
											$term = is_exists_term($tx_name, $single_tax['name'], (int)$parent_id);		
											
											if ( empty($term) and !is_wp_error($term) ){
												$term_attr = array('parent'=> (!empty($parent_id)) ? $parent_id : 0);
												$term = wp_insert_term(
													$single_tax['name'], // the term 
												  	$tx_name, // the taxonomy
												  	$term_attr
												);
											}
											
											if ( is_wp_error($term) ){									
												$logger and call_user_func($logger, sprintf(__('<b>WARNING</b>: `%s`', 'pmxi_plugin'), $term->get_error_message()));
												$logger and PMXI_Plugin::$session['pmxi_import']['warnings'] = ++PMXI_Plugin::$session->data['pmxi_import']['warnings'];
											}
											elseif (!empty($term)) {
												$cat_id = $term['term_id'];
												if ($cat_id and $single_tax['assign']) 
												{
													$term = get_term_by('id', $cat_id, $tx_name);
													if (!in_array($term->slug, $assign_taxes)) $assign_taxes[] = $term->slug;		
												}									
											}									
										}
									}				
								endif;										
								if (!empty($assign_taxes)){
									// associate taxes with post
									$term_ids = wp_set_object_terms($pid, $assign_taxes, $tx_name);									
									if (is_wp_error($term_ids)) {
										$logger and call_user_func($logger, __('<b>WARNING</b>', 'pmxi_plugin') . ': '.$term_ids->get_error_message());
										$logger and PMXI_Plugin::$session['pmxi_import']['warnings'] = ++PMXI_Plugin::$session->data['pmxi_import']['warnings'];
									}
								}
							}
						}
						if (!empty($existing_taxonomies) and $this->options['update_all_data'] == "no" and $this->options['is_update_categories'] and $this->options['update_categories_logic'] != 'full_update') {
							foreach ($existing_taxonomies as $tx_name => $txes) {
								// Skip updating product attributes
								if ( PMXI_Admin_Addons::get_addon('PMWI_Plugin') and strpos($tx_name, "pa_") === 0 ) continue;

								if (!empty($txes[$i])){
									$term_ids = wp_set_object_terms($pid, $txes[$i], $tx_name);
									if (is_wp_error($term_ids)) {
										$logger and call_user_func($logger, __('<b>WARNING</b>', 'pmxi_plugin') . ': '.$term_ids->get_error_message());
										$logger and PMXI_Plugin::$session['pmxi_import']['warnings'] = ++PMXI_Plugin::$session->data['pmxi_import']['warnings'];
									}
								}
							}
						}
					}					
					// [/custom taxonomies]
					
					// [categories]
					if (!empty($cats[$i])) {
					
						if ( empty($articleData['ID']) or $this->options['update_all_data'] == "yes" or ( $this->options['update_all_data'] == "no" and $this->options['is_update_categories'] )) {							

							wp_set_object_terms( $pid, NULL, 'category' );							

							$is_update_cats = true;

							if (!empty($articleData['ID'])){
								if ($this->options['update_all_data'] == "no" and $this->options['update_categories_logic'] == "all_except" and !empty($this->options['taxonomies_list']) 
										and is_array($this->options['taxonomies_list']) and in_array('category', $this->options['taxonomies_list'])) $is_update_cats = false;
								if ($this->options['update_all_data'] == "no" and $this->options['update_categories_logic'] == "only" and ((!empty($this->options['taxonomies_list']) 
										and is_array($this->options['taxonomies_list']) and ! in_array('category', $this->options['taxonomies_list'])) or empty($this->options['taxonomies_list']))) $is_update_cats = false;
							}

							if ($is_update_cats){
								$assign_cats = array();

								if ($this->options['update_categories_logic'] == "add_new" and !empty($existing_cats[$i])){
									$assign_cats = $existing_cats[$i];	
									unset($existing_cats[$i]);
								}
								elseif(!empty($existing_cats[$i])){
									unset($existing_cats[$i]);
								}

								// create categories if it's doesn't exists						
								foreach ($cats[$i] as $key => $single_cat) {												

									if (is_array($single_cat)){								

										$parent_id = (!empty($single_cat['parent'])) ? pmxi_recursion_taxes($single_cat['parent'], 'category', $cats[$i], $key) : '';
																																
										$term = is_exists_term('category', $single_cat['name'], (int)$parent_id);
										
										if ( empty($term) and !is_wp_error($term) ){																								
											$term_attr = array('parent'=> (!empty($parent_id)) ? $parent_id : 0);									
											$term = wp_insert_term(
												$single_cat['name'], // the term 
											  	'category', // the taxonomy
											  	$term_attr
											);									
										}
										
										if ( is_wp_error($term) ){									
											$logger and call_user_func($logger, sprintf(__('<b>WARNING</b>: `%s`', 'pmxi_plugin'), $term->get_error_message()));
											$logger and PMXI_Plugin::$session['pmxi_import']['warnings'] = ++PMXI_Plugin::$session->data['pmxi_import']['warnings'];
										}
										elseif ( ! empty($term) ) {
											$cat_id = $term['term_id'];
											if ($cat_id and $single_cat['assign']) 
											{
												$term = get_term_by('id', $cat_id, 'category');
												if ( ! in_array($term->slug, $assign_cats)) $assign_cats[] = $term->slug;		
											}									
										}									
									}
								}	

								// associate categories with post
								$cats_ids = wp_set_object_terms($pid, $assign_cats, 'category');													
								if (is_wp_error($cats_ids)) {
									$logger and call_user_func($logger, __('<b>WARNING</b>', 'pmxi_plugin') . ': '.$cats_ids->get_error_message());
									$logger and PMXI_Plugin::$session['pmxi_import']['warnings'] = ++PMXI_Plugin::$session->data['pmxi_import']['warnings'];
								}
							}							
						}
					}
					
					if (!empty($existing_cats[$i]) and $this->options['update_all_data'] == "no" and (!$this->options['is_update_categories'] or ($this->options['is_update_categories'] and $this->options['update_categories_logic'] != 'full_update'))) {
						$cats_ids = wp_set_object_terms($pid, $existing_cats[$i], 'category');						
						if (is_wp_error($cats_ids)) {
							$logger and call_user_func($logger, __('<b>WARNING</b>', 'pmxi_plugin') . ': '.$cats_ids->get_error_message());
							$logger and PMXI_Plugin::$session['pmxi_import']['warnings'] = ++PMXI_Plugin::$session->data['pmxi_import']['warnings'];
						}
					}
					// [/categories]

					if (empty($articleData['ID'])) {																												
						$logger and call_user_func($logger, sprintf(__('`%s` post created successfully', 'pmxi_plugin'), $articleData['post_title']));
					} else {						
						$logger and call_user_func($logger, sprintf(__('`%s` post updated successfully', 'pmxi_plugin'), $articleData['post_title']));
					}

					// [addons import]

					// prepare data for import
					$importData = array(
						'pid' => $pid,						
						'import' => $this,						
						'logger' => $logger						
					);

					// deligate operation to addons
					foreach (PMXI_Admin_Addons::get_active_addons() as $class) if ( method_exists($addons[$class], 'saved_post') ) $addons[$class]->saved_post($importData);	
					
					// [/addons import]										

					do_action( 'pmxi_saved_post', $pid); // hook that was triggered immediately after post saved
					
					if (empty($articleData['ID'])) $created++; else $updated++;						

					if ( ! $is_cron and "default" == $this->options['import_processing'] ){
						$processed_records = $created + $updated + $skipped + PMXI_Plugin::$session->data['pmxi_import']['errors'];
						$logger and call_user_func($logger, sprintf(__('<span class="processing_info"><span class="created_count">%s</span><span class="updated_count">%s</span><span class="percents_count">%s</span></span>', 'pmxi_plugin'), $created, $updated, ceil(($processed_records/$this->count) * 100)));
					}
																					
				}				

				do_action('pmxi_after_post_import', $this->id);

				$logger and PMXI_Plugin::$session['pmxi_import']['chunk_number'] = ++PMXI_Plugin::$session->data['pmxi_import']['chunk_number'];
			}

			wp_cache_flush();			

			$current_ids = (empty($this->current_post_ids)) ? array() : json_decode($this->current_post_ids, true);

			$this->set(array(		
				'imported' => $created + $updated,	
				'created'  => $created,
				'updated'  => $updated,
				'skipped'  => $skipped,
				'queue_chunk_number' => $created + $updated + $skipped,			
				'current_post_ids' => json_encode(array_unique(array_merge($current_ids, $current_post_ids)))
			))->update();
			
			if ( ! $is_cron ){

				pmxi_session_commit();	

				$records_count = $this->created + $this->updated + $this->skipped + PMXI_Plugin::$session->data['pmxi_import']['errors'];

				$is_import_complete = ($records_count == $this->count);						

				// Delete posts that are no longer present in your file
				if ( $is_import_complete and ! empty($this->options['is_delete_missing'])) { 

					$logger and call_user_func($logger, 'Removing previously imported posts which are no longer actual...');
					$postList = new PMXI_Post_List();				
					$current_post_ids = (empty($this->current_post_ids)) ? array() : json_decode($this->current_post_ids, true);	

					$missing_ids = array();
					foreach ($postList->getBy(array('import_id' => $this->id, 'post_id NOT IN' => $current_post_ids)) as $missingPost) {
						
						$missing_ids[] = $missingPost['post_id'];

						// Instead of deletion, set Custom Field
						if ($this->options['is_update_missing_cf']){
							update_post_meta( $missingPost['post_id'], $this->options['update_missing_cf_name'], $this->options['update_missing_cf_value'] );
						}

						// Instead of deletion, change post status to Draft
						if ($this->options['set_missing_to_draft']) $this->wpdb->update( $this->wpdb->posts, array('post_status' => 'draft'), array('ID' => $missingPost['post_id']) );								

						// Delete posts that are no longer present in your file
						if ( ! $this->options['is_update_missing_cf'] and ! $this->options['set_missing_to_draft']){

							// Remove attachments
							empty($this->options['is_keep_attachments']) and wp_delete_attachments($missingPost['post_id'], true, 'files');						
							// Remove images
							empty($this->options['is_keep_imgs']) and wp_delete_attachments($missingPost['post_id'], $this->options['download_images']);

							// Delete record form pmxi_posts						
							$missingRecord = new PMXI_Post_Record();
							$missingRecord->getById($missingPost['id'])->delete();						

							// Clear post's relationships
							wp_delete_object_term_relationships($missingPost['post_id'], get_object_taxonomies('' != $this->options['custom_type'] ? $this->options['custom_type'] : 'post'));

						}
														
					}								

					// Delete posts from database
					if (!empty($missing_ids) && is_array($missing_ids) and ! $this->options['is_update_missing_cf'] and ! $this->options['set_missing_to_draft']){																	
						
						$sql = "delete a,b,c
						FROM ".$this->wpdb->posts." a
						LEFT JOIN ".$this->wpdb->term_relationships." b ON ( a.ID = b.object_id )
						LEFT JOIN ".$this->wpdb->postmeta." c ON ( a.ID = c.post_id )										
						WHERE a.ID IN (".implode(',', $missing_ids).")";					

						$this->wpdb->query( 
							$this->wpdb->prepare($sql, '')
						);			

						do_action('pmxi_delete_post', $missing_ids);		
					}								

				}

				// Set out of stock status for missing records [Woocommerce add-on option]
				if ( $is_import_complete and empty($this->options['is_delete_missing']) and $post_type == "product" and class_exists('PMWI_Plugin') and !empty($this->options['missing_records_stock_status'])) {

					$logger and call_user_func($logger, 'Update stock status previously imported posts which are no longer actual...');
					$postList = new PMXI_Post_List();				
					$current_post_ids = (empty($this->current_post_ids)) ? array() : json_decode($this->current_post_ids, true);	
					foreach ($postList->getBy(array('import_id' => $this->id, 'post_id NOT IN' => $current_post_ids)) as $missingPost) {
						update_post_meta( $missingPost['post_id'], '_stock_status', 'outofstock' );
						update_post_meta( $missingPost['post_id'], '_stock', 0 );
					}

				}	
			}		
			
		} catch (XmlImportException $e) {
			$logger and call_user_func($logger, __('<b>ERROR</b>', 'pmxi_plugin') . ': ' . $e->getMessage());
			PMXI_Plugin::$session['pmxi_import']['errors'] = ++PMXI_Plugin::$session->data['pmxi_import']['errors'];	
		}				
		
		$logger and $is_import_complete and call_user_func($logger, __('Cleaning temporary data...', 'pmxi_plugin'));
		foreach ($tmp_files as $file) { // remove all temporary files created
			unlink($file);
		}
		
		if (($is_cron or $is_import_complete) and $this->options['is_delete_source']) {
			$logger and call_user_func($logger, __('Deleting source XML file...', 'pmxi_plugin'));			

			// Delete chunks
			foreach (PMXI_Helper::safe_glob($uploads['path'] . '/pmxi_chunk_*', PMXI_Helper::GLOB_RECURSE | PMXI_Helper::GLOB_PATH) as $filePath) {
				@file_exists($filePath) and @unlink($filePath);		
			}

			if ($this->type != "ftp"){
				if ( ! @unlink($this->path)) {
					$logger and call_user_func($logger, sprintf(__('<b>WARNING</b>: Unable to remove %s', 'pmxi_plugin'), $this->path));
				}
			}
			else{
				$file_path_array = PMXI_Helper::safe_glob($this->path, PMXI_Helper::GLOB_NODIR | PMXI_Helper::GLOB_PATH);
				if (!empty($file_path_array)){
					foreach ($file_path_array as $path) {
						if ( ! @unlink($path)) {
							$logger and call_user_func($logger, sprintf(__('<b>WARNING</b>: Unable to remove %s', 'pmxi_plugin'), $path));
						}
					}
				}
			}
		}

		if ( ! $is_cron and $is_import_complete ){

			$this->set(array(
				'processing' => 0, // unlock cron requests	
				'triggered' => 0,
				'queue_chunk_number' => 0,
				'current_post_ids' => '',
				'registered_on' => date('Y-m-d H:i:s')
			))->update();

			$logger and call_user_func($logger, 'Done');			
		}
		
		remove_filter('user_has_cap', array($this, '_filter_has_cap_unfiltered_html')); kses_init(); // return any filtering rules back if they has been disabled for import procedure
		
		return $this;
	}	
	
	public function _filter_has_cap_unfiltered_html($caps)
	{
		$caps['unfiltered_html'] = true;
		return $caps;
	}		
	
	/**
	 * Clear associations with posts
	 * @param bool[optional] $keepPosts When set to false associated wordpress posts will be deleted as well
	 * @return PMXI_Import_Record
	 * @chainable
	 */
	public function deletePosts($keepPosts = TRUE) {
		$post = new PMXI_Post_List();		
		if ( ! $keepPosts) {								
			$ids = array();
			foreach ($post->getBy('import_id', $this->id)->convertRecords() as $p) {
				// Remove attachments
				empty($this->options['is_keep_attachments']) and wp_delete_attachments($p->post_id, true, 'files');
				// Remove images
				empty($this->options['is_keep_imgs']) and wp_delete_attachments($p->post_id, $this->options['download_images']);
				$ids[] = $p->post_id;								
			}
			if (!empty($ids)){				

				foreach ($ids as $id) {
					do_action('pmxi_delete_post', $id);
					wp_delete_object_term_relationships($id, get_object_taxonomies('' != $this->options['custom_type'] ? $this->options['custom_type'] : 'post'));
				}

				$sql = "delete a,b,c
				FROM ".$this->wpdb->posts." a
				LEFT JOIN ".$this->wpdb->term_relationships." b ON ( a.ID = b.object_id )
				LEFT JOIN ".$this->wpdb->postmeta." c ON ( a.ID = c.post_id )
				LEFT JOIN ".$this->wpdb->posts." d ON ( a.ID = d.post_parent )
				WHERE a.ID IN (".implode(',', $ids).");";

				$this->wpdb->query( 
					$this->wpdb->prepare($sql, '')
				);				
				
			}			
		}
		
		$this->wpdb->query($this->wpdb->prepare('DELETE FROM ' . $post->getTable() . ' WHERE import_id = %s', $this->id));

		return $this;
	}
	/**
	 * Delete associated files
	 * @return PMXI_Import_Record
	 * @chainable
	 */
	public function deleteFiles() {
		$fileList = new PMXI_File_List();
		foreach($fileList->getBy('import_id', $this->id)->convertRecords() as $f) {
			if ( @file_exists($f->path) ) @unlink($f->path);
			$f->delete();
		}
		return $this;
	}
	
	/**
	 * @see parent::delete()
	 * @param bool[optional] $keepPosts When set to false associated wordpress posts will be deleted as well
	 */
	public function delete($keepPosts = TRUE) {
		$this->deletePosts($keepPosts)->deleteFiles();
		
		return parent::delete();
	}
	
}

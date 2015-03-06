<?php 
/**
 * Import configuration wizard
 * 
 * @author Pavel Kulbakin <p.kulbakin@gmail.com>
 */

class PMXI_Admin_Import extends PMXI_Controller_Admin {
	protected $isWizard = true; // indicates whether controller is in wizard mode (otherwize it called to be deligated an edit action)
	protected $isTemplateEdit = false; // indicates whether controlled is deligated by manage imports controller	

	protected function init() {
		parent::init();							
		
		if ('PMXI_Admin_Manage' == PMXI_Plugin::getInstance()->getAdminCurrentScreen()->base) { // prereqisites are not checked when flow control is deligated
			$id = $this->input->get('id');
			$this->data['import'] = $import = new PMXI_Import_Record();			
			if ( ! $id or $import->getById($id)->isEmpty()) { // specified import is not found
				wp_redirect(add_query_arg('page', 'pmxi-admin-manage', admin_url('admin.php'))); die();
			}
			$this->isWizard = false;
			
		} else {						
			$action = PMXI_Plugin::getInstance()->getAdminCurrentScreen()->action; 
			$this->_step_ready($action);
			$this->isInline = 'process' == $action;
		}		
		
		XmlImportConfig::getInstance()->setCacheDirectory(sys_get_temp_dir());
		
		// preserve id parameter as part of baseUrl
		$id = $this->input->get('id') and $this->baseUrl = add_query_arg('id', $id, $this->baseUrl);

		$this->baseUrl = apply_filters('pmxi_base_url', $this->baseUrl);
	}

	public function set($var, $val)
	{
		$this->{$var} = $val;
	}
	public function get($var)
	{
		return $this->{$var};
	} 

	/**
	 * Checks whether corresponding step of wizard is complete
	 * @param string $action
	 */
	protected function _step_ready($action) {		
		// step #1: xml selction - has no prerequisites
		if ('index' == $action) return true;
		
		// step #2: element selection
		$this->data['dom'] = $dom = new DOMDocument('1.0', PMXI_Plugin::$session->data['pmxi_import']['encoding']);
		$this->data['update_previous'] = $update_previous = new PMXI_Import_Record();
		$old = libxml_use_internal_errors(true);				
		
		$xml = $this->get_xml();
		
		if (empty($xml) and in_array($action, array('process')) ){ 
			! empty(PMXI_Plugin::$session->data['pmxi_import']['update_previous']) and $update_previous->getById(PMXI_Plugin::$session->data['pmxi_import']['update_previous']);
			return true;
		}				

		if (empty(PMXI_Plugin::$session->data['pmxi_import'])
			or ! @$dom->loadXML($xml)// FIX: libxml xpath doesn't handle default namespace properly, so remove it upon XML load
			//or empty(PMXI_Plugin::$session['pmxi_import']['source'])
			or ! empty(PMXI_Plugin::$session->data['pmxi_import']['update_previous']) and $update_previous->getById(PMXI_Plugin::$session->data['pmxi_import']['update_previous'])->isEmpty()			
		) {					
			if (!PMXI_Plugin::is_ajax()){
				$this->errors->add('form-validation', __('Can not create DOM object for provided feed.', 'pmxi_plugin')); 
				wp_redirect_or_javascript($this->baseUrl); die();
			}
		}

		libxml_use_internal_errors($old);			
		if ('element' == $action) return true;
		if ('evaluate' == $action) return true;
		if ('evaluate_variations' == $action) return true;

		// step #3: template
		$xpath = new DOMXPath($dom);
		$this->data['elements'] = $elements = @$xpath->query(PMXI_Plugin::$session->data['pmxi_import']['xpath']);
		
		if ('preview' == $action or 'tag' == $action) return true;

		if (empty(PMXI_Plugin::$session->data['pmxi_import']['xpath']) or empty($elements) or ! $elements->length) {
			$this->errors->add('form-validation', __('No matching elements found.', 'pmxi_plugin')); 
			wp_redirect_or_javascript(add_query_arg('action', 'element', $this->baseUrl)); die();
		}

		if ('template' == $action or 'preview' == $action or 'tag' == $action) return true;
		
		// step #4: options
		if (empty(PMXI_Plugin::$session->data['pmxi_import']['template']) or empty(PMXI_Plugin::$session->data['pmxi_import']['template']['title']) or empty(PMXI_Plugin::$session->data['pmxi_import']['template']['content'])) {
			wp_redirect_or_javascript(add_query_arg('action', 'template', $this->baseUrl)); die();
		}
		if ('options' == $action) return true;
		
		if (empty(PMXI_Plugin::$session->data['pmxi_import']['options'])) {
			wp_redirect(add_query_arg('action', 'options', $this->baseUrl)); die();
		}
	}
	
	/**
	 * Step #1: Choose File
	 */
	public function index() {
		
		$this->data['reimported_import'] = $import = new PMXI_Import_Record();
		$this->data['id'] = $id = $this->input->get('id');
		$this->data['parent_import'] = $parent_import = $this->input->get('parent_import', 0);
		if ($id and $import->getById($id)->isEmpty()) { // update requested but corresponding import is not found
			wp_redirect(remove_query_arg('id', $this->baseUrl)); die();
		}
		
		$this->data['post'] = $post = $this->input->post(array(
			'type' => 'upload',
			'feed_type' => '',
			'url' => 'http://',
			'ftp' => array('url' => 'ftp://'),
			'file' => '',
			'reimport' => '',
			'is_update_previous' => $id ? 1 : 0,
			'update_previous' => $id,
			'xpath' => '/',			
			'filepath' => '',
			'root_element' => ''
		));			

		if ($this->input->post('is_submitted_continue')) { 
			if ( ! empty(PMXI_Plugin::$session->data['pmxi_import']['local_paths'])) {
				wp_redirect(add_query_arg('action', 'element', $this->baseUrl)); die();
			}
		} elseif ('upload' == $this->input->post('type')) { 						
			
			$uploads = wp_upload_dir();

			if (empty($post['filepath'])) {
				$this->errors->add('form-validation', __('XML/CSV file must be specified', 'pmxi_plugin'));
			} elseif (!is_file($post['filepath'])) {
				$this->errors->add('form-validation', __('Uploaded file is empty', 'pmxi_plugin'));
			} elseif ( ! preg_match('%\W(xml|gzip|zip|csv|gz)$%i', trim(basename($post['filepath'])))) {				
				$this->errors->add('form-validation', __('Uploaded file must be XML, CSV or ZIP, GZIP', 'pmxi_plugin'));
			} elseif (preg_match('%\W(zip)$%i', trim(basename($post['filepath'])))) {
										
				include_once(PMXI_Plugin::ROOT_DIR.'/libraries/pclzip.lib.php');

				$archive = new PclZip($post['filepath']);
			    if (($v_result_list = $archive->extract(PCLZIP_OPT_PATH, $uploads['path'], PCLZIP_OPT_REPLACE_NEWER)) == 0) {
			    	$this->errors->add('form-validation', 'Failed to open uploaded ZIP archive : '.$archive->errorInfo(true));			    	
			   	}
				else {
					
					$filePath = '';

					if (!empty($v_result_list)){
						foreach ($v_result_list as $unzipped_file) {							
							if ($unzipped_file['status'] == 'ok' and preg_match('%\W(xml|csv|txt|dat|psv)$%i', trim($unzipped_file['stored_filename']))) { $filePath = $unzipped_file['filename']; break; }
						}
					}
			    	if($uploads['error']){
						 $this->errors->add('form-validation', __('Can not create upload folder. Permision denied', 'pmxi_plugin'));
					}

					if(empty($filePath)){						
						$zip = zip_open(trim($post['filepath']));
						if (is_resource($zip)) {														
							while ($zip_entry = zip_read($zip)) {
								$filePath = zip_entry_name($zip_entry);												
							    $fp = fopen($uploads['path']."/".$filePath, "w");
							    if (zip_entry_open($zip, $zip_entry, "r")) {
							      $buf = zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
							      fwrite($fp,"$buf");
							      zip_entry_close($zip_entry);
							      fclose($fp);
							    }
							    break;
							}
							zip_close($zip);							

						} else {
					        $this->errors->add('form-validation', __('Failed to open uploaded ZIP archive. Can\'t extract files.', 'pmxi_plugin'));
					    }						
					}																

					// Detect if file is very large					
					$source = array(
						'name' => basename($post['filepath']),
						'type' => 'upload',							
						'path' => $post['filepath'],					
					); 

					if (preg_match('%\W(csv|txt|dat|psv)$%i', trim($filePath))){ // If CSV file found in archieve						

						if($uploads['error']){
							 $this->errors->add('form-validation', __('Can not create upload folder. Permision denied', 'pmxi_plugin'));
						}																								
						
						include_once(PMXI_Plugin::ROOT_DIR.'/libraries/XmlImportCsvParse.php');
						$csv = new PMXI_CsvParser($filePath, true); // create chunks
						$filePath = $csv->xml_path;
						$post['root_element'] = 'node';		
						
					}					
				}

			} elseif ( preg_match('%\W(csv|txt|dat|psv)$%i', trim($post['filepath']))) { // If CSV file uploaded
				
				// Detect if file is very large				
				if($uploads['error']){
					 $this->errors->add('form-validation', __('Can not create upload folder. Permision denied', 'pmxi_plugin'));
				}									
    			$filePath = $post['filepath'];
				$source = array(
					'name' => basename($post['filepath']),
					'type' => 'upload',
					'path' => $filePath,
				);								
				include_once(PMXI_Plugin::ROOT_DIR.'/libraries/XmlImportCsvParse.php');					
				$csv = new PMXI_CsvParser($post['filepath'], true);					
				$filePath = $csv->xml_path;
				$post['root_element'] = 'node';
						
			} elseif(preg_match('%\W(gz)$%i', trim($post['filepath']))){ // If gz file uploaded
				$fileInfo = pmxi_gzfile_get_contents($post['filepath']);
				$filePath = $fileInfo['localPath'];				
				
				// Detect if file is very large				
				$source = array(
					'name' => basename($post['filepath']),
					'type' => 'upload',
					'path' => $post['filepath'],					
				);

				// detect CSV or XML 
				if ( $fileInfo['type'] == 'csv') { // it is CSV file									
					
					include_once(PMXI_Plugin::ROOT_DIR.'/libraries/XmlImportCsvParse.php');					
					$csv = new PMXI_CsvParser($filePath, true); // create chunks
					$filePath = $csv->xml_path;
					$post['root_element'] = 'node';						
				
				}
			} else { // If XML file uploaded				

				// Detect if file is very large
				$filePath = $post['filepath'];
				$source = array(
					'name' => basename($post['filepath']),
					'type' => 'upload',
					'path' => $filePath,
				);
			}		
		}
		elseif ($this->input->post('is_submitted')){  

			$this->errors->add('form-validation', __('Upgrade to the paid edition of WP All Import to use this feature.', 'pmxi_plugin'));
		} 

		if ($post['is_update_previous'] and empty($post['update_previous'])) {
			$this->errors->add('form-validation', __('Previous import for update must be selected to proceed with a new one', 'pmxi_plugin'));
		}

		$this->data['detection_feed_extension'] = false;		

		if ( ! class_exists('DOMDocument') ) {
			$this->errors->add('form-validation', __('Class \'DOMDocument\' not found.', 'pmxi_plugin')); 						
		}
		if ( ! class_exists('XMLReader') ) {
			$this->errors->add('form-validation', __('Class \'XMLReader\' not found.', 'pmxi_plugin')); 						
		}
		
		if ($this->input->post('is_submitted') and ! $this->errors->get_error_codes()) {			
				
			check_admin_referer('choose-file', '_wpnonce_choose-file');					 														
			$elements_cloud = array();																					
			@set_time_limit(0);																	
			$local_paths = !empty($local_paths) ? $local_paths : array($filePath);
			
			foreach ($local_paths as $key => $path) {						
													
				if ( @file_exists($path) ){
					
					$file = new PMXI_Chunk($path, array('element' => $post['root_element']));										    					    					   							

					if ( ! empty($file->options['element']) ) {						
						$xpath = "/".$file->options['element'];
						$elements_cloud = $file->cloud;								
						break;																				    		   
					}
					else $this->errors->add('form-validation', __('Unable to find root element for this feed. Please open the feed in your browser or a text editor and ensure it is a valid feed.', 'pmxi_plugin')); 
					
				}
				else $this->errors->add('form-validation', __('Unable to download feed resource.', 'pmxi_plugin')); 
			}			
			
			if ( ! $this->errors->get_error_codes() ) {
				
				// xml is valid				
				$source['root_element'] = $file->options['element'];				
				$source['first_import'] = date("Y-m-d H:i:s");				
				pmxi_session_unset();														

				PMXI_Plugin::$session['pmxi_import'] = array(						
					'filePath' => $filePath,
					'parent_import_id' => $parent_import,
					'xpath' => (!empty($xpath)) ? $xpath : '',
					'feed_type' => $post['feed_type'],
					'source' => $source,										
					'encoding' => 'UTF-8',//$file->options['encoding'],
					'is_csv' => PMXI_Plugin::$is_csv,
					'csv_path' => PMXI_Plugin::$csv_path,
					'chunk_number' => 1,
					'log' => '',					
					'processing' => 0,
					'queue_chunk_number' => 0,
					'count' => (isset($chunks)) ? $chunks : 0,					
					'warnings' => 0,
					'errors' => 0,
					'start_time' => 0,
					'local_paths' => (!empty($local_paths)) ? $local_paths : array(), // ftp import local copies of remote files
					'csv_paths' => (!empty($csv_paths)) ? $csv_paths : array(PMXI_Plugin::$csv_path), // ftp import local copies of remote CSV files
					'action' => 'import',
					'elements_cloud' => (!empty($elements_cloud)) ? $elements_cloud : array(),
					'pointer' => 1
				);								
										
				$update_previous = new PMXI_Import_Record();
				if ($post['is_update_previous'] and ! $update_previous->getById($post['update_previous'])->isEmpty()) {
					PMXI_Plugin::$session['pmxi_import']['update_previous'] = $update_previous->id;
					PMXI_Plugin::$session['pmxi_import']['xpath'] = $update_previous->xpath;
					PMXI_Plugin::$session['pmxi_import']['template'] = $update_previous->template;
					PMXI_Plugin::$session['pmxi_import']['options'] = $update_previous->options;
				} else {
					PMXI_Plugin::$session['pmxi_import']['update_previous'] = '';
				}		

				pmxi_session_commit(); 						
				
				$xml = $this->get_xml();
				if (empty($xml))
				{
					$this->errors->add('form-validation', __('Please confirm you are importing a valid feed.<br/> Often, feed providers distribute feeds with invalid data, improperly wrapped HTML, line breaks where they should not be, faulty character encodings, syntax errors in the XML, and other issues.<br/><br/>WP All Import has checks in place to automatically fix some of the most common problems, but we can’t catch every single one.<br/><br/>It is also possible that there is a bug in WP All Import, and the problem is not with the feed.<br/><br/>If you need assistance, please contact support – <a href="mailto:support@soflyy.com">support@soflyy.com</a> – with your XML/CSV file. We will identify the problem and release a bug fix if necessary.', 'pmxi_plugin')); 
					if ( "" != PMXI_Plugin::$is_csv) $this->errors->add('form-validation', __('Probably your CSV feed contains HTML code. In this case, you can enable the <strong>"My CSV feed contains HTML code"</strong> option on the settings screen.', 'pmxi_plugin')); 
				}
				else{
					wp_redirect(add_query_arg('action', 'element', $this->baseUrl)); die();
				}				

			} else {
				$this->errors->add('form-validation', __('Please confirm you are importing a valid feed.<br/> Often, feed providers distribute feeds with invalid data, improperly wrapped HTML, line breaks where they should not be, faulty character encodings, syntax errors in the XML, and other issues.<br/><br/>WP All Import has checks in place to automatically fix some of the most common problems, but we can’t catch every single one.<br/><br/>It is also possible that there is a bug in WP All Import, and the problem is not with the feed.<br/><br/>If you need assistance, please contact support – <a href="mailto:support@soflyy.com">support@soflyy.com</a> – with your XML/CSV file. We will identify the problem and release a bug fix if necessary.', 'pmxi_plugin')); 
				if ( "" != PMXI_Plugin::$is_csv) $this->errors->add('form-validation', __('Probably your CSV feed contains HTML code. In this case, you can enable the <strong>"My CSV feed contains HTML code"</strong> option on the settings screen.', 'pmxi_plugin')); 
			}

			do_action("pmxi_get_file", $filePath);
		}
		
		$this->render();
	}
	
	/**
	 * Step #2: Choose elements
	 */
	public function element()
	{
					
		$xpath = new DOMXPath($this->data['dom']);		
		$post = $this->input->post(array('xpath' => ''));
		$this->data['post'] =& $post;
		$this->data['elements_cloud'] = PMXI_Plugin::$session->data['pmxi_import']['elements_cloud'];
		$this->data['is_csv'] = PMXI_Plugin::$session->data['pmxi_import']['is_csv'];

		$wp_uploads = wp_upload_dir();
		
		if ($this->input->post('is_submitted')) {			
			check_admin_referer('choose-elements', '_wpnonce_choose-elements');
			if ('' == $post['xpath']) {
				$this->errors->add('form-validation', __('No elements selected', 'pmxi_plugin'));
			} else {
				$node_list = @ $xpath->query($post['xpath']); // make sure only element selection is allowed; prevent parsing warning to be displayed
			
				if (FALSE === $node_list) {
					$this->errors->add('form-validation', __('Invalid XPath expression', 'pmxi_plugin'));
				/*} elseif ( ! $node_list->length) {
					$this->errors->add('form-validation', __('No matching elements found for XPath expression specified', 'pmxi_plugin'));*/
				} else {
					foreach ($node_list as $el) {
						if ( ! $el instanceof DOMElement) {
							$this->errors->add('form-validation', __('XPath must match only elements', 'pmxi_plugin'));
							break;
						};
					}
				}
			}

			if ( ! $this->errors->get_error_codes()) {
				
				wp_redirect(add_query_arg('action', 'template', $this->baseUrl)); die();
				
			}
			
		} else {
			
			if (isset(PMXI_Plugin::$session->data['pmxi_import']['xpath'])) {
				$post['xpath'] = PMXI_Plugin::$session->data['pmxi_import']['xpath'];
				$this->data['elements'] = $elements = $xpath->query($post['xpath']);
				if ( ! $elements->length and ! empty(PMXI_Plugin::$session->data['pmxi_import']['update_previous'])) {
					$_GET['pmxi_nt'] = __('<b>Warning</b>: No matching elements found for XPath expression from the import being updated. It probably means that new XML file has different format. Though you can update XPath, procceed only if you sure about update operation being valid.', 'pmxi_plugin');
				}
			} else {
				// suggest 1st repeating element as default selection
				$post['xpath'] = $this->xml_find_repeating($this->data['dom']->documentElement);
				if (!empty($post['xpath'])){
					$this->data['elements'] = $elements = $xpath->query($post['xpath']);
				}
			}

		}
		
		// workaround to prevent rendered XML representation to eat memory since it has to be stored in momory when output is bufferred
		$this->render();
		//add_action('pmxi_action_after', array($this, 'element_after'));
	}
	public function element_after()
	{
		$this->render();
	}
	
	/**
	 * Helper to evaluate xpath and return matching elements as direct paths for javascript side to highlight them
	 */
	public function evaluate()
	{
		if ( ! PMXI_Plugin::getInstance()->getAdminCurrentScreen()->is_ajax) { // call is only valid when send with ajax
			wp_redirect(add_query_arg('action', 'element', $this->baseUrl)); die();
		}				

		// HTTP headers for no cache etc
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
		
		$xpath = new DOMXPath($this->data['dom']);
		$post = $this->input->post(array('xpath' => '', 'show_element' => 1, 'root_element' => PMXI_Plugin::$session->data['pmxi_import']['source']['root_element'], 'delimiter' => ''));
		$wp_uploads = wp_upload_dir();

		if ('' == $post['xpath']) {
			$this->errors->add('form-validation', __('No elements selected', 'pmxi_plugin'));
		} else {		
			// counting selected elements			
			if ('' != $post['delimiter'] and $post['delimiter'] != PMXI_Plugin::$session->data['pmxi_import']['is_csv']) {
				include_once(PMXI_Plugin::ROOT_DIR.'/libraries/XmlImportCsvParse.php');						
				
				PMXI_Plugin::$session['pmxi_import']['is_csv'] = $post['delimiter'];

				if (PMXI_Plugin::$session->data['pmxi_import']['source']['type'] != 'ftp'){
					$csv = new PMXI_CsvParser(PMXI_Plugin::$session->data['pmxi_import']['csv_path'], true, '', $post['delimiter']); // create chunks
					$filePath = $csv->xml_path;										
					PMXI_Plugin::$session['pmxi_import']['filePath'] = $filePath;
					PMXI_Plugin::$session['pmxi_import']['local_paths'] = array($filePath);
				}
				else{
					$local_paths = array();
					foreach (PMXI_Plugin::$session->data['pmxi_import']['csv_paths'] as $key => $path) {
						$csv = new PMXI_CsvParser($path, true, '', $post['delimiter']); // create chunks
						$filePath = $csv->xml_path;										
						if (!$key) PMXI_Plugin::$session['pmxi_import']['filePath'] = $filePath;
						$local_paths[] = $filePath;							
					}
					PMXI_Plugin::$session['pmxi_import']['local_paths'] = $local_paths;
				}
			}

			// counting selected elements			
			PMXI_Plugin::$session['pmxi_import']['xpath'] = $post['xpath'];

			$current_xpath = '';

			if ($post['show_element'] == 1) {
				PMXI_Plugin::$session['pmxi_import']['count'] = $this->data['node_list_count'] = 0;					 														
			}else{				
				$this->data['node_list_count'] = PMXI_Plugin::$session->data['pmxi_import']['count'];	
				//$post['xpath'] .= '[' . $post['show_element'] . ']';									
			}				
						
			$xpath_elements = explode('[', $post['xpath']);
			$xpath_parts    = explode('/', $xpath_elements[0]);				
			
			PMXI_Plugin::$session['pmxi_import']['source']['root_element'] = $xpath_parts[1];				

			pmxi_session_commit();									

			$loop = 0;			

			foreach (PMXI_Plugin::$session->data['pmxi_import']['local_paths'] as $key => $path) {		

				$file = new PMXI_Chunk($path, array('element' => PMXI_Plugin::$session->data['pmxi_import']['source']['root_element'], 'encoding' => PMXI_Plugin::$session->data['pmxi_import']['encoding']));

			    // loop through the file until all lines are read				    				    			   				    
			    while ($xml = $file->read()) {

			    	if (!empty($xml))
			      	{
			      		PMXI_Import_Record::preprocessXml($xml);
			      		$xml = "<?xml version=\"1.0\" encoding=\"". PMXI_Plugin::$session->data['pmxi_import']['encoding'] ."\"?>" . "\n" . $xml;					      		
				      	
				      	$dom = new DOMDocument('1.0', PMXI_Plugin::$session->data['pmxi_import']['encoding']);
						$old = libxml_use_internal_errors(true);
						$dom->loadXML($xml);
						libxml_use_internal_errors($old);
						$xpath = new DOMXPath($dom);
						
						if (($elements = @$xpath->query($post['xpath'])) and $elements->length){
						
							if ( $post['show_element'] == 1 ){
								$this->data['node_list_count'] += $elements->length;
								PMXI_Plugin::$session['pmxi_import']['count'] = $this->data['node_list_count'];
								if (!$loop) $this->data['dom'] = $dom;
							}
							
							$loop += $elements->length;							

							if ( $post['show_element'] > 1 and $loop == $post['show_element']) {
								$this->data['dom'] = $dom;
								break(2);								
							}

							unset($dom, $xpath, $elements);		
						}
				    }
				}
				unset($file);					
			}
			if ( ! $this->data['node_list_count']) {
				$this->errors->add('form-validation', __('No matching elements found for XPath expression specified', 'pmxi_plugin'));
			}					
		}
		
		pmxi_session_commit();
		
		ob_start();		
		if ( ! $this->errors->get_error_codes()) {			
			$xpath = new DOMXPath($this->data['dom']);
			$this->data['elements'] = $elements = @ $xpath->query($post['xpath']); // prevent parsing warning to be displayed			
			$paths = array(); $this->data['paths'] =& $paths;
			if (PMXI_Plugin::getInstance()->getOption('highlight_limit') and $elements->length <= PMXI_Plugin::getInstance()->getOption('highlight_limit')) {
				foreach ($elements as $el) {
					if ( ! $el instanceof DOMElement) continue;					
					$p = $this->get_xml_path($el, $xpath) and $paths[] = $p;
				}
			}									
			$this->render();									
		} else {
			$this->error();
		}		

		$html = ob_get_clean();

		ob_start();

		if ( ! empty($elements->length) ) $this->render_xml_elements_for_filtring($elements->item(0));

		$render_element = ob_get_clean();

		exit( json_encode( array('result' => true, 'html' => $html, 'root_element' =>  PMXI_Plugin::$session['pmxi_import']['source']['root_element'], 'render_element' => $render_element )));
	}

	/**
	 * Helper to evaluate xpath and return matching elements as direct paths for javascript side to highlight them
	 */
	public function evaluate_variations()
	{
		if ( ! PMXI_Plugin::getInstance()->getAdminCurrentScreen()->is_ajax) { // call is only valid when send with ajax
			wp_redirect(add_query_arg('action', 'element', $this->baseUrl)); die();
		}		

		$post = $this->input->post(array('xpath' => '', 'show_element' => 1, 'root_element' => PMXI_Plugin::$session->data['pmxi_import']['source']['root_element'], 'tagno' => 0, 'parent_tagno' => 1));
		$wp_uploads = wp_upload_dir();

		$this->get_xml( $post['parent_tagno'] );

		$xpath = new DOMXPath($this->data['dom']);

		$this->data['tagno'] = max(intval($this->input->getpost('tagno', 1)), 0);

		if ('' == $post['xpath']) {
			$this->errors->add('form-validation', __('No elements selected', 'pmxi_plugin'));
		} else {			
			$post['xpath'] = '/' . ((!empty($this->data['update_previous']->root_element)) ? $this->data['update_previous']->root_element : PMXI_Plugin::$session->data['pmxi_import']['source']['root_element']) .'/'.  ltrim(trim(str_replace("[*]","",$post['xpath']),'{}'), '/');					

			// in default mode
			$this->data['variation_elements'] = $elements = @ $xpath->query($post['xpath']); // prevent parsing warning to be displayed
			$this->data['variation_list_count'] = $elements->length;
			if (FALSE === $elements) {
				$this->errors->add('form-validation', __('Invalid XPath expression', 'pmxi_plugin'));
			} elseif ( ! $elements->length) {
				$this->errors->add('form-validation', __('No matching variations found for XPath specified', 'pmxi_plugin'));
			} else {
				foreach ($elements as $el) {
					if ( ! $el instanceof DOMElement) {
						$this->errors->add('form-validation', __('XPath must match only elements', 'pmxi_plugin'));
						break;
					};
				}
			}			
		}
		if ( ! $this->errors->get_error_codes()) {
								
			$paths = array(); $this->data['paths'] =& $paths;
			if (PMXI_Plugin::getInstance()->getOption('highlight_limit') and $elements->length <= PMXI_Plugin::getInstance()->getOption('highlight_limit')) {
				foreach ($elements as $el) {
					if ( ! $el instanceof DOMElement) continue;
					
					$p = $this->get_xml_path($el, $xpath) and $paths[] = $p;
				}
			}

			$this->render();
		} else {
			$this->error();
		}
	}
	
	/**
	 * Step #3: Choose template
	 */
	public function template()
	{
		
		$template = new PMXI_Template_Record();
		$default = array(
			'title' => '',
			'content' => '',
			'name' => '',
			'is_keep_linebreaks' => 0,
			'is_leave_html' => 0,
			'fix_characters' => 0
		);		

		if ($this->isWizard) {			
			$this->data['post'] = $post = $this->input->post(
				apply_filters('pmxi_template_options', (isset(PMXI_Plugin::$session->data['pmxi_import']['template']) ? PMXI_Plugin::$session->data['pmxi_import']['template'] : array())
				+ $default, $this->isWizard)
			);						
		} else {			
			$this->data['post'] = $post = $this->input->post(
				apply_filters('pmxi_template_options', $this->data['import']->template
				+ $default, $this->isWizard)
			);			
		}	
		
		if (($load_template = $this->input->post('load_template'))) { // init form with template selected
			if ( ! $template->getById($load_template)->isEmpty()) {
				$this->data['post'] = array(
					'title' => $template->title,
					'content' => $template->content,
					'is_keep_linebreaks' => $template->is_keep_linebreaks,	
					'is_leave_html' => $template->is_leave_html,
					'fix_characters' => $template->fix_characters,				
					'name' => '', // template is always empty
				);
				PMXI_Plugin::$session['pmxi_import']['is_loaded_template'] = $load_template;
			}

		} elseif ($this->input->post('is_submitted')) { // save template submission
			check_admin_referer('template', '_wpnonce_template');
			
			if (empty($post['title'])) {
				$this->errors->add('form-validation', __('Post title is empty', 'pmxi_plugin'));
			} else {
				$this->_validate_template($post['title'], 'Post title');
			}

			if (empty($post['content'])) {
				$this->errors->add('form-validation', __('Post content is empty', 'pmxi_plugin'));
			} else {
				$this->_validate_template($post['content'], 'Post content');
			}							
			
			if ( ! $this->errors->get_error_codes()) {						
				if ( ! empty($post['name'])) { // save template in database
					$template->getByName($post['name'])->set($post)->save();
					PMXI_Plugin::$session['pmxi_import']['saved_template'] = $template->id;				
				}
				if ($this->isWizard) {
					PMXI_Plugin::$session['pmxi_import']['template'] = $post;
					pmxi_session_commit();
					wp_redirect(add_query_arg('action', 'options', $this->baseUrl)); die();
				} else {					
					$this->data['import']->set('template', $post)->save();
					if ( ! empty($_POST['import_encoding'])){
						$options = $this->data['import']->options;
						$options['encoding'] = $_POST['import_encoding'];
						$this->data['import']->set('options', $options)->save();					
					}
					wp_redirect(add_query_arg(array('page' => 'pmxi-admin-manage', 'pmlc_nt' => urlencode(__('Template updated', 'pmxi_plugin'))) + array_intersect_key($_GET, array_flip($this->baseUrlParamNames)), admin_url('admin.php'))); die();
				}

			}
			else $this->errors->add('form-validation', __('Make sure the shortcodes are escaped.', 'pmxi_plugin'));
		}
		
		pmxi_session_commit();

		if (user_can_richedit()) {
			wp_enqueue_script('editor');
		}
		wp_enqueue_script('word-count');
		add_thickbox();
		wp_enqueue_script('media-upload');		
		wp_enqueue_script('quicktags');
		$this->render();
	}

	protected function _validate_template($text, $field_title)
	{
		try {
			$scanner = new XmlImportTemplateScanner();
			$tokens = $scanner->scan(new XmlImportStringReader($text));
			$parser = new XmlImportTemplateParser($tokens);
			$tree = $parser->parse();
		} catch (XmlImportException $e) {
			$this->errors->add('form-validation', sprintf(__('%s template is invalid: %s', 'pmxi_plugin'), $field_title, $e->getMessage()));
		}
	}
	
	/**
	 * Preview selected xml tag (called with ajax from `template` step)
	 */
	public function tag()
	{					

		$wp_uploads = wp_upload_dir();

		if (empty($this->data['elements']->length))
		{
			$update_previous = new PMXI_Import_Record();
			$id = $this->input->get('id');
			if ($id and $update_previous->getById($id)) {				
				PMXI_Plugin::$session['pmxi_import'] = array(
					'update_previous' => $update_previous->id,
					'xpath' => $update_previous->xpath,
					'template' => $update_previous->template,
					'options' => $update_previous->options,					
				);
				$history = new PMXI_File_List();
				$history->setColumns('id', 'name', 'registered_on', 'path')->getBy(array('import_id' => $update_previous->id), 'id DESC');				
				
				if ($history->count()){
					$history_file = new PMXI_File_Record();
					$history_file->getBy('id', $history[0]['id']);

					if (empty(PMXI_Plugin::$session->data['pmxi_import'])){
						PMXI_Plugin::$session['pmxi_import']['filePath'] = $history_file->path;						
						if (!@file_exists($history_file->path)) PMXI_Plugin::$session['pmxi_import']['filePath'] = $wp_uploads['basedir']  . '/wpallimport_history/' . $history_file->id;						
						PMXI_Plugin::$session['pmxi_import']['count'] = $update_previous->count;
						PMXI_Plugin::$session['pmxi_import']['encoding'] = (!empty($update_previous->options['encoding'])) ? $update_previous->options['encoding'] : 'UTF-8';						
						pmxi_session_commit();
					}					
				}	

			} else {
				PMXI_Plugin::$session['pmxi_import']['update_previous'] = '';
			}					
		}
		
		$this->data['tagno'] = max(intval($this->input->getpost('tagno', 1)), 1);
		
		if ($this->data['tagno']){	
			
			PMXI_Plugin::$session['pmxi_import']['local_paths'] = $local_paths = (!empty(PMXI_Plugin::$session->data['pmxi_import']['local_paths'])) ? PMXI_Plugin::$session->data['pmxi_import']['local_paths'] : array(PMXI_Plugin::$session->data['pmxi_import']['filePath']);						
			
			$loop = 0;
			
			foreach ($local_paths as $key => $path) {	

				if (@file_exists($path)){				
					
					$file = new PMXI_Chunk($path, array('element' => (!empty($update_previous->root_element)) ? $update_previous->root_element : ((!empty($this->data['update_previous']->root_element)) ? $this->data['update_previous']->root_element : PMXI_Plugin::$session->data['pmxi_import']['source']['root_element']), 'encoding' => PMXI_Plugin::$session->data['pmxi_import']['encoding']));								   
				    // loop through the file until all lines are read				    				    			   			    
				    while ($xml = $file->read()) {					      						    					    					    			    					    	
				    
				    	if (!empty($xml))
				      	{						      			
				      		PMXI_Import_Record::preprocessXml($xml);
				      		$xml = "<?xml version=\"1.0\" encoding=\"". PMXI_Plugin::$session->data['pmxi_import']['encoding'] ."\"?>" . "\n" . $xml;						      			      						      							      					      		
					      					      		
					      	$dom = new DOMDocument('1.0', PMXI_Plugin::$session->data['pmxi_import']['encoding']);
							$old = libxml_use_internal_errors(true);
							$dom->loadXML($xml);				
							libxml_use_internal_errors($old);
							$xpath = new DOMXPath($dom);
							if (($elements = @$xpath->query(PMXI_Plugin::$session->data['pmxi_import']['xpath'])) and $elements->length){ 														
								$this->data['elements'] = $elements;
								$loop += $elements->length;
								unset($dom, $xpath, $elements);								
								if ($loop == $this->data['tagno'] or $loop == PMXI_Plugin::$session->data['pmxi_import']['count'])
									break(2);
							}											  					 
					    }
					}	
					unset($file);
				}
			}
		}		

		$this->render();
	}
	
	/**
	 * Preview future post based on current template and tag (called with ajax from `template` step)
	 */
	public function preview()
	{
		$post = $this->input->post(array(
			'title' => '',
			'content' => '',
			'is_keep_linebreaks' => 0,
			'is_leave_html' => 0,
			'fix_characters' => 0,
			'import_encoding' => 'UTF-8',
			'tagno' => 0
		));		
		$wp_uploads = wp_upload_dir();		

		$legacy_handling = PMXI_Plugin::getInstance()->getOption('legacy_special_character_handling');
		$this->data['tagno'] = $tagno = min(max(intval($this->input->getpost('tagno', 1)), 1), PMXI_Plugin::$session->data['pmxi_import']['count']);

		$xml = '';
		
		$local_paths = (!empty(PMXI_Plugin::$session->data['pmxi_import']['local_paths'])) ? PMXI_Plugin::$session->data['pmxi_import']['local_paths'] : array(PMXI_Plugin::$session->data['pmxi_import']['filePath']);						

		$loop = 1; 
		foreach ($local_paths as $key => $path) {

			if (PMXI_Plugin::$session->data['pmxi_import']['encoding'] != $post['import_encoding'] and ! empty(PMXI_Plugin::$session->data['pmxi_import']['csv_paths'][$key])){
				include_once(PMXI_Plugin::ROOT_DIR.'/libraries/XmlImportCsvParse.php');	
				$csv = new PMXI_CsvParser(PMXI_Plugin::$session->data['pmxi_import']['csv_paths'][$key], true, '', PMXI_Plugin::$is_csv, $post['import_encoding'], $path); // conver CSV to XML with selected encoding
			}

			$file = new PMXI_Chunk($path, array('element' => (!empty($this->data['update_previous']->root_element)) ? $this->data['update_previous']->root_element : PMXI_Plugin::$session->data['pmxi_import']['source']['root_element'], 'encoding' => $post['import_encoding']));
		    // loop through the file until all lines are read				    				    			   			    
		    while ($xml = $file->read()) {					      						    					    					    			    	
		    	if (!empty($xml))
		      	{			
		      		PMXI_Import_Record::preprocessXml($xml);	      						      							      					      						      	
		      		$xml = "<?xml version=\"1.0\" encoding=\"". $post['import_encoding'] ."\"?>" . "\n" . $xml;			
		      		
			      	$dom = new DOMDocument('1.0', $post['import_encoding']);															
					$old = libxml_use_internal_errors(true);
					$dom->loadXML($xml); // FIX: libxml xpath doesn't handle default namespace properly, so remove it upon XML load							
					libxml_use_internal_errors($old);
					$xpath = new DOMXPath($dom);						
					if (($this->data['elements'] = $elements = @$xpath->query(PMXI_Plugin::$session->data['pmxi_import']['xpath'])) and $elements->length){ 						
						unset($dom, $xpath, $elements);
						if ( $loop == $tagno )
							break(2); 																											
						$loop++;
					}											  					 
			    }
			}
			unset($file);				
		}
		//$this->data['tagno'] = $tagno = 1;			
		
		$xpath = "(" . PMXI_Plugin::$session->data['pmxi_import']['xpath'] . ")[1]";		
		
		PMXI_Plugin::$session['pmxi_import']['encoding'] = $post['import_encoding'];
		pmxi_session_commit();
		
		// validate
		try {
			if (empty($xml)){
				$this->errors->add('form-validation', __('Error parsing title: String could not be parsed as XML', 'pmxi_plugin'));
			} elseif (empty($post['title'])) {
				$this->errors->add('form-validation', __('Post title is empty', 'pmxi_plugin'));
			} else {				
				list($this->data['title']) = XmlImportParser::factory($xml, $xpath, $post['title'], $file)->parse(); unlink($file);				
				if ( ! isset($this->data['title']) or '' == strval(trim(strip_tags($this->data['title'], '<img><input><textarea><iframe><object><embed>')))) {
					$this->errors->add('xml-parsing', __('<strong>Warning</strong>: resulting post title is empty', 'pmxi_plugin'));
				}
				else $this->data['title'] = ($post['is_leave_html']) ? html_entity_decode($this->data['title']) : $this->data['title']; 
			}
		} catch (XmlImportException $e) {
			$this->errors->add('form-validation', sprintf(__('Error parsing title: %s', 'pmxi_plugin'), $e->getMessage()));
		}
		try {	
			if (empty($xml)){
				$this->errors->add('form-validation', __('Error parsing content: String could not be parsed as XML', 'pmxi_plugin'));
			} elseif (empty($post['content'])) {
				$this->errors->add('form-validation', __('Post content is empty', 'pmxi_plugin'));
			} else {
				list($this->data['content']) = XmlImportParser::factory($post['is_keep_linebreaks'] ? $xml : preg_replace('%\r\n?|\n%', ' ', $xml), $xpath, $post['content'], $file)->parse(); unlink($file);				
				if ( ! isset($this->data['content']) or '' == strval(trim(strip_tags($this->data['content'], '<img><input><textarea><iframe><object><embed>')))) {
					$this->errors->add('xml-parsing', __('<strong>Warning</strong>: resulting post content is empty', 'pmxi_plugin'));
				}
				else $this->data['content'] = ($post['is_leave_html']) ? html_entity_decode($this->data['content']) : $this->data['content'];
			}
		} catch (XmlImportException $e) {
			$this->errors->add('form-validation', sprintf(__('Error parsing content: %s', 'pmxi_plugin'), $e->getMessage()));
		}
		
		$this->render();
	}
	
	/**
	 * Step #4: Options
	 */
	public function options()
	{		

		$default = PMXI_Plugin::get_default_import_options();
		
		if ($this->isWizard) {			
			$this->data['source_type'] = PMXI_Plugin::$session->data['pmxi_import']['source']['type'];
			$default['unique_key'] = PMXI_Plugin::$session->data['pmxi_import']['template']['title'];
			
			$keys_black_list = array('programurl');

			// auto searching ID element
			if (!empty($this->data['dom'])){
				$this->find_unique_key($this->data['dom']->documentElement);
				if (!empty($this->_unique_key)){
					foreach ($keys_black_list as $key => $value) {
						$default['unique_key'] = str_replace('{' . $value . '[1]}', "", $default['unique_key']);
					}					
					foreach ($this->_unique_key as $key) {
						if (stripos($key, 'id') !== false) { 
							$default['unique_key'] .= ' - {'.$key.'[1]}';							
							break;
						}
					}					
					foreach ($this->_unique_key as $key) {
						if (stripos($key, 'url') !== false or stripos($key, 'sku') !== false or stripos($key, 'ref') !== false) { 
							if ( ! in_array($key, $keys_black_list) ){
								$default['unique_key'] .= ' - {'.$key.'[1]}';								
								break;
							}							
						}
					}					
				}
			}
			
			$DefaultOptions = (isset(PMXI_Plugin::$session->data['pmxi_import']['options']) ? PMXI_Plugin::$session->data['pmxi_import']['options'] : array()) + $default;
			foreach (PMXI_Admin_Addons::get_active_addons() as $class) 
				$DefaultOptions += call_user_func(array($class, "get_default_import_options"));			

			$post = $this->input->post( apply_filters('pmxi_options_options', $DefaultOptions, $this->isWizard) );
	
		} else {
			$this->data['source_type'] = $this->data['import']->type;	
			$DefaultOptions = $this->data['import']->options + $default;
			foreach (PMXI_Admin_Addons::get_active_addons() as $class) 
				$DefaultOptions += call_user_func(array($class, "get_default_import_options"));			

			$post = $this->input->post( apply_filters( 'pmxi_options_options', $DefaultOptions, $this->isWizard) );	
		}		

		$this->data['post'] =& $post;				

		// Get All meta keys in the system
		$this->data['meta_keys'] = $keys = new PMXI_Model_List();
		$keys->setTable(PMXI_Plugin::getInstance()->getWPPrefix() . 'postmeta');
		$keys->setColumns('meta_id', 'meta_key')->getBy(NULL, "meta_id", NULL, NULL, "meta_key");			

		global $wpdb;

		$existing_attributes = $wpdb->get_results("SELECT meta_value FROM $wpdb->postmeta WHERE meta_key = '_product_attributes'" );

		$this->data['existing_attributes'] = array();

		if ( ! empty($existing_attributes)){
			foreach ($existing_attributes as $key => $existing_attribute) {				
				$existing_attribute = maybe_unserialize($existing_attribute->meta_value);				
				if (!empty($existing_attribute) and is_array($existing_attribute)): 
					foreach ($existing_attribute as $key => $value) {
						if (strpos($key, "pa_") === false and ! in_array($key, $this->data['existing_attributes'])) $this->data['existing_attributes'][] = $key;
					} 
				endif;
			}
		}				

		$load_template = $this->input->post('load_template');
		if ($load_template) { // init form with template selected			 			
			PMXI_Plugin::$session['pmxi_import']['is_loaded_template'] = $load_template;
			$template = new PMXI_Template_Record();
			if ( ! $template->getById($load_template)->isEmpty()) {					
				$post = (!empty($template->options) ? $template->options : array()) + $default;							
			}
		} elseif ($load_template == -1){
			PMXI_Plugin::$session['pmxi_import']['is_loaded_template'] = 0;

			$post = $default;							

		} elseif ($this->input->post('is_submitted')) {							

			check_admin_referer('options', '_wpnonce_options');						

			// Categories/taxonomies logic
			if ($post['update_categories_logic'] == 'only'){
				$post['taxonomies_list'] = explode(",", $post['taxonomies_only_list']); 
			}
			elseif ($post['update_categories_logic'] == 'all_except'){
				$post['taxonomies_list'] = explode(",", $post['taxonomies_except_list']); 	
			}			

			// Custom fields logic
			if ($post['update_custom_fields_logic'] == 'only'){
				$post['custom_fields_list'] = explode(",", $post['custom_fields_only_list']); 
			}
			elseif ($post['update_custom_fields_logic'] == 'all_except'){
				$post['custom_fields_list'] = explode(",", $post['custom_fields_except_list']); 	
			}

			// Attributes fields logic
			$post = apply_filters('pmxi_save_options', $post);					
						
			// remove entires where both custom_name and custom_value are empty 
			$not_empty = array_flip(array_values(array_merge(array_keys(array_filter($post['custom_name'], 'strlen')), array_keys(array_filter($post['custom_value'], 'strlen')))));

			$post['custom_name'] = array_intersect_key($post['custom_name'], $not_empty);
			$post['custom_value'] = array_intersect_key($post['custom_value'], $not_empty);
			
			// validate
			if (array_keys(array_filter($post['custom_name'], 'strlen')) != array_keys(array_filter($post['custom_value'], 'strlen'))) {
				$this->errors->add('form-validation', __('Both name and value must be set for all custom parameters', 'pmxi_plugin'));
			} else {
				foreach ($post['custom_name'] as $custom_name) {
					$this->_validate_template($custom_name, __('Custom Field Name', 'pmxi_plugin'));
				}
				foreach ($post['custom_value'] as $custom_value) {
					$this->_validate_template($custom_value, __('Custom Field Value', 'pmxi_plugin'));
				}
			}
			
			if ( $post['type'] == "post" and $post['custom_type'] == "product" and class_exists('PMWI_Plugin')){
				// remove entires where both custom_name and custom_value are empty 
				$not_empty = array_flip(array_values(array_merge(array_keys(array_filter($post['attribute_name'], 'strlen')), array_keys(array_filter($post['attribute_value'], 'strlen')))));

				$post['attribute_name'] = array_intersect_key($post['attribute_name'], $not_empty);
				$post['attribute_value'] = array_intersect_key($post['attribute_value'], $not_empty);

				// validate
				if (array_keys(array_filter($post['attribute_name'], 'strlen')) != array_keys(array_filter($post['attribute_value'], 'strlen'))) {
					$this->errors->add('form-validation', __('Both name and value must be set for all woocommerce attributes', 'pmxi_plugin'));
				} else {
					foreach ($post['attribute_name'] as $attribute_name) {
						$this->_validate_template($attribute_name, __('Attribute Field Name', 'pmxi_plugin'));
					}
					foreach ($post['attribute_value'] as $custom_value) {
						$this->_validate_template($custom_value, __('Attribute Field Value', 'pmxi_plugin'));
					}
				}
				
			}

			if ('page' == $post['type'] and ! preg_match('%^(-?\d+)?$%', $post['order'])) {
				$this->errors->add('form-validation', __('Order must be an integer number', 'pmxi_plugin'));
			}
			if ('post' == $post['type']) {
				/*'' == $post['categories'] or $this->_validate_template($post['categories'], __('Categories', 'pmxi_plugin'));*/
				'' == $post['tags'] or $this->_validate_template($post['tags'], __('Tags', 'pmxi_plugin'));
				if ( "" != $post['custom_type']) {
					if ($post['custom_type'] != 'product'){
						$this->_validate_template($post['custom_type'], __('Custom post type is not supported', 'pmxi_plugin'));
					}
					elseif ( ! class_exists('PMWI_Plugin') ){
						$this->_validate_template($post['custom_type'], __('Custom post type is not supported', 'pmxi_plugin'));
					}
				}
			}
			if ('specific' == $post['date_type']) {
				'' == $post['date'] or $this->_validate_template($post['date'], __('Date', 'pmxi_plugin'));
			} else {
				'' == $post['date_start'] or $this->_validate_template($post['date_start'], __('Start Date', 'pmxi_plugin'));
				'' == $post['date_end'] or $this->_validate_template($post['date_end'], __('Start Date', 'pmxi_plugin'));
			}			
			if ('' == $post['tags_delim']) {
				$this->errors->add('form-validation', __('Tag list delimiter must cannot be empty', 'pmxi_plugin'));
			}
			if ($post['is_import_specified']) {
				if (empty($post['import_specified'])) {
					$this->errors->add('form-validation', __('Records to import must be specified or uncheck `Import only specified records` option to process all records', 'pmxi_plugin'));
				} else {
					$chanks = preg_split('% *, *%', $post['import_specified']);
					foreach ($chanks as $chank) {
						if ( ! preg_match('%^([1-9]\d*)( *- *([1-9]\d*))?$%', $chank, $mtch)) {
							$this->errors->add('form-validation', __('Wrong format of `Import only specified records` value', 'pmxi_plugin'));
							break;
						} elseif (isset($mtch[3]) and intval($mtch[3]) > PMXI_Plugin::$session->data['pmxi_import']['count']) {
							$this->errors->add('form-validation', __('One of the numbers in `Import only specified records` value exceeds record quantity in XML file', 'pmxi_plugin'));
							break;
						}
					}
				}
			}
			if ('' == $post['unique_key']) {
				$this->errors->add('form-validation', __('Expression for `Post Unique Key` must be set, use the same expression as specified for post title if you are not sure what to put there', 'pmxi_plugin'));
			} else {
				$this->_validate_template($post['unique_key'], __('Post Unique Key', 'pmxi_plugin'));
			}			
			if ( 'manual' == $post['duplicate_matching'] and 'custom field' == $post['duplicate_indicator']){
				if ('' == $post['custom_duplicate_name'])
					$this->errors->add('form-validation', __('Custom field name must be specified.', 'pmxi_plugin'));
				if ('' == $post['custom_duplicate_value'])
					$this->errors->add('form-validation', __('Custom field value must be specified.', 'pmxi_plugin'));
			}

			apply_filters('pmxi_options_validation', $this->errors, $post, $this->data['import']);
			
			if ( ! $this->errors->get_error_codes()) { // no validation errors found
				// assign some defaults
				'' !== $post['date'] or $post['date'] = 'now';
				'' !== $post['date_start'] or $post['date_start'] = 'now';
				'' !== $post['date_end'] or $post['date_end'] = 'now';
				
				if ( $this->input->post('name')) { // save template in database
					$template = new PMXI_Template_Record();
					
					$template->getByName($this->input->post('name'))->set(array(
						'name' => $this->input->post('name'),
						'options' => $post,						
					))->save();						
				}

				if ($this->isWizard) {
					PMXI_Plugin::$session['pmxi_import']['options'] = $post;

					pmxi_session_commit();

					if ( ! $this->input->post('save_only')) { 						
						wp_redirect(add_query_arg('action', 'process', $this->baseUrl)); die();
					} else {
						$import = $this->data['update_previous'];
						$is_update = ! $import->isEmpty();
						$import->set(
							PMXI_Plugin::$session->data['pmxi_import']['source']
							+ array(
								'xpath' => PMXI_Plugin::$session->data['pmxi_import']['xpath'],
								'template' => PMXI_Plugin::$session->data['pmxi_import']['template'],
								'options' => PMXI_Plugin::$session->data['pmxi_import']['options'],
								//'scheduled' => PMXI_Plugin::$session->data['pmxi_import']['scheduled'],
								'count' => PMXI_Plugin::$session->data['pmxi_import']['count'],
								'friendly_name' => $this->data['post']['friendly_name'],
							)
						)->save();
						
						$history_file = new PMXI_File_Record();
						$history_file->set(array(
							'name' => $import->name,
							'import_id' => $import->id,
							'path' => PMXI_Plugin::$session->data['pmxi_import']['filePath'],
							'contents' => $this->get_xml(), 
							'registered_on' => date('Y-m-d H:i:s'),
						))->save();	

						pmxi_session_unset();												

						wp_redirect(add_query_arg(array('page' => 'pmxi-admin-manage', 'pmlc_nt' => urlencode($is_update ? __('Import updated', 'pmxi_plugin') : __('Import created', 'pmxi_plugin'))), admin_url('admin.php'))); die();
					}
				} else {

					$this->data['import']->set('options', $post)->set( array( 'scheduled' => '', 'friendly_name' => $this->data['post']['friendly_name'] ) )->save();
					
					wp_redirect(add_query_arg(array('page' => 'pmxi-admin-manage', 'pmlc_nt' => urlencode(__('Options updated', 'pmxi_plugin'))) + array_intersect_key($_GET, array_flip($this->baseUrlParamNames)), admin_url('admin.php'))); die();
				} 
			}
		}
		
		! empty($post['custom_name']) or $post['custom_name'] = array('') and $post['custom_value'] = array('');

		if ( $post['type'] == "product" and class_exists('PMWI_Plugin'))
		{
			! empty($post['attribute_name']) or $post['attribute_name'] = array('') and $post['attribute_value'] = array('');
		}

		pmxi_session_commit();
		
		$this->render();
	}	

	/**
	 * Import processing step (status console)
	 */
	public function process($save_history = true)
	{
		$wp_uploads = wp_upload_dir();		
													
		$import = $this->data['update_previous'];				
		$logger = create_function('$m', 'echo "<div class=\\"progress-msg\\">$m</div>\\n"; if ( "" != strip_tags(pmxi_strip_tags_content($m))) { PMXI_Plugin::$session[\'pmxi_import\'][\'log\'] .= "<p>".strip_tags(pmxi_strip_tags_content($m))."</p>"; flush(); }');								

		if ( ! PMXI_Plugin::is_ajax() ) {																

			$import->set(
				(empty(PMXI_Plugin::$session->data['pmxi_import']['source']) ? array() : PMXI_Plugin::$session->data['pmxi_import']['source'])
				+ array(
					'xpath' => PMXI_Plugin::$session->data['pmxi_import']['xpath'],
					'template' => PMXI_Plugin::$session->data['pmxi_import']['template'],
					'options' => PMXI_Plugin::$session->data['pmxi_import']['options'],									
					'count' => PMXI_Plugin::$session->data['pmxi_import']['count'],
					'friendly_name' => PMXI_Plugin::$session->data['pmxi_import']['options']['friendly_name'],
					'feed_type' => PMXI_Plugin::$session->data['pmxi_import']['feed_type'],
					'parent_import_id' => ($this->data['update_previous']->isEmpty()) ? PMXI_Plugin::$session->data['pmxi_import']['parent_import_id'] : $this->data['update_previous']->parent_import_id,
					'queue_chunk_number' => 0,
					'triggered' => 0,
					'processing' => 0,					
				)
			)->save();

			if ( PMXI_Plugin::$session->data['pmxi_import']['action'] != 'continue' ){
				// store import info in database
				$import->set(array(
					'imported' => 0,
					'created' => 0,
					'updated' => 0,
					'skipped' => 0,		
					'current_post_ids' => ''	
				))->update();			
			}								

			foreach ( get_taxonomies() as $tax ) 
				delete_transient("pmxi_{$tax}_terms");

			do_action( 'pmxi_before_xml_import', $import->id );	

			PMXI_Plugin::$session['pmxi_import']['update_previous'] = $import->id;

			// unlick previous files
			$history = new PMXI_File_List();
			$history->setColumns('id', 'name', 'registered_on', 'path')->getBy(array('import_id' => $import->id), 'id DESC');				
			if ($history->count()){
				foreach ($history as $file){						
					if (@file_exists($file['path']) and $file['path'] != PMXI_Plugin::$session->data['pmxi_import']['filePath']) @unlink($file['path']);
					$history_file = new PMXI_File_Record();
					$history_file->getBy('id', $file['id']);
					if ( ! $history_file->isEmpty()) $history_file->delete();
				}
			}

			if ($save_history){
				$history_file = new PMXI_File_Record();
				$history_file->set(array(
					'name' => $import->name,
					'import_id' => $import->id,
					'path' => PMXI_Plugin::$session->data['pmxi_import']['filePath'],
					'contents' => $this->get_xml(),
					'registered_on' => date('Y-m-d H:i:s')
				))->save();
			}																	

			/*
				Split file up into 1000 record chunks.			
				This option will decrease the amount of slowdown experienced at the end of large imports. 
				The slowdown is partially caused by the need for WP All Import to read deeper and deeper into the file on each successive iteration. 
				Splitting the file into pieces means that, for example, instead of having to read 19000 records into a 20000 record file when importing the last 1000 records, 
				WP All Import will just split it into 20 chunks, and then read the last chunk from the beginning.
			*/
			if ( "ajax" == $import->options['import_processing'] and $import->count > PMXI_Plugin::getInstance()->getOption('large_feed_limit') and $import->options['chuncking'] ){ 

				$chunk_files = array();

				if (!empty(PMXI_Plugin::$session->data['pmxi_import']['local_paths'])){

					$records_count = 0;
					$chunk_records_count = 0;

					$feed = "<?xml version=\"1.0\" encoding=\"". $import->options['encoding'] ."\"?>"  . "\n" . "<pmxi_records>";

					foreach (PMXI_Plugin::$session->data['pmxi_import']['local_paths'] as $key => $path) {

						$file = new PMXI_Chunk($path, array('element' => $import->root_element, 'encoding' => $import->options['encoding']));												

					    // loop through the file until all lines are read				    				    			   			   	    			    			    
					    while ($xml = $file->read()) {				    	

					    	if ( ! empty($xml) )
					      	{
					      		PMXI_Import_Record::preprocessXml($xml);
					      		$chunk = "<?xml version=\"1.0\" encoding=\"". $import->options['encoding'] ."\"?>"  . "\n" . $xml;					      		
						      					      		
						      	$dom = new DOMDocument('1.0', $import->options['encoding']);
								$old = libxml_use_internal_errors(true);
								$dom->loadXML($chunk); // FIX: libxml xpath doesn't handle default namespace properly, so remove it upon XML load											
								libxml_use_internal_errors($old);
								$xpath = new DOMXPath($dom);								

								if ($elements = @$xpath->query($import->xpath) and $elements->length){		
									$records_count += $elements->length;
									$chunk_records_count += $elements->length;
									$feed .= $xml;
								}
							}

							if ( $chunk_records_count == PMXI_Plugin::getInstance()->getOption('large_feed_limit') or $records_count == $import->count ){
								$feed .= "</pmxi_records>";
								$chunk_file_path = $wp_uploads['path'] . "/pmxi_chunk_" . count($chunk_files) . "_" . basename($path);
								file_put_contents($chunk_file_path, $feed);
								$chunk_files[] = $chunk_file_path;
								$chunk_records_count = 0;
								$feed = "<?xml version=\"1.0\" encoding=\"". $import->options['encoding'] ."\"?>"  . "\n" . "<pmxi_records>";
							}
						}						
					}
					PMXI_Plugin::$session['pmxi_import']['local_paths'] = $chunk_files;
				}								
			}

			pmxi_session_commit();

			$this->render();
			wp_ob_end_flush_all(); flush();
			@set_time_limit(0);	

			if ( "ajax" == $import->options['import_processing'] ) die();
		}		
		elseif (empty($import->id)){
			$import = new PMXI_Import_Record();
			$import->getById(PMXI_Plugin::$session->data['pmxi_import']['update_previous']);
		}
		
		$ajax_processing = ("ajax" == $import->options['import_processing']) ? true : false;

		PMXI_Plugin::$session['pmxi_import']['start_time'] = (empty(PMXI_Plugin::$session->data['pmxi_import']['start_time'])) ? time() : PMXI_Plugin::$session->data['pmxi_import']['start_time'];								

		wp_cache_flush();

		if ( PMXI_Plugin::is_ajax() or ! $ajax_processing ) {			

			if ( "ajax" == $import->options['import_processing'] ) {
				// HTTP headers for no cache etc
				header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
				header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
				header("Cache-Control: no-store, no-cache, must-revalidate");
				header("Cache-Control: post-check=0, pre-check=0", false);
				header("Pragma: no-cache");									
			}

			$loop = 0;	
			$pointer = 0;			
			$records = array();

			if ($import->options['is_import_specified']) {								
				foreach (preg_split('% *, *%', $import->options['import_specified'], -1, PREG_SPLIT_NO_EMPTY) as $chank) {
					if (preg_match('%^(\d+)-(\d+)$%', $chank, $mtch)) {
						$records = array_merge($records, range(intval($mtch[1]), intval($mtch[2])));
					} else {
						$records = array_merge($records, array(intval($chank)));
					}
				}
			}												

			$records_to_import = (empty($records)) ? $import->count : $records[count($records) -1];

			$records_per_request = ( ! $ajax_processing and $import->options['records_per_request'] < 50 ) ? 50 : $import->options['records_per_request'];

			if (!empty(PMXI_Plugin::$session->data['pmxi_import']['local_paths'])){

				$feed = "<?xml version=\"1.0\" encoding=\"". $import->options['encoding'] ."\"?>"  . "\n" . "<pmxi_records>";

				foreach (PMXI_Plugin::$session->data['pmxi_import']['local_paths'] as $key => $path) {

					$import_done = ($import->imported + $import->skipped + PMXI_Plugin::$session->data['pmxi_import']['errors'] == $records_to_import ) ? true : false;

			    	if ( $import_done ) {
			    		if (strpos($path, "pmxi_chunk_") !== false and @file_exists($path)) @unlink($path);			    		
				    	PMXI_Plugin::$session['pmxi_import']['local_paths'] = array();
				    	pmxi_session_commit();
				    	break;
				    }
						
					$file = new PMXI_Chunk($path, array('element' => $import->root_element, 'encoding' => $import->options['encoding'], 'pointer' => PMXI_Plugin::$session->data['pmxi_import']['pointer']));												
				    // loop through the file until all lines are read				    				    			   			   	    			    			    
				    while ($xml = $file->read()) {				    	

				    	if ( ! empty($xml) )
				      	{
				      		PMXI_Import_Record::preprocessXml($xml);
				      		$chunk = "<?xml version=\"1.0\" encoding=\"". $import->options['encoding'] ."\"?>"  . "\n" . $xml;				      		
					      					      		
					      	$dom = new DOMDocument('1.0', $import->options['encoding']);
							$old = libxml_use_internal_errors(true);							
							$dom->loadXML($chunk); // FIX: libxml xpath doesn't handle default namespace properly, so remove it upon XML load	
							libxml_use_internal_errors($old);
							$xpath = new DOMXPath($dom);

							$pointer++;

							if (($this->data['elements'] = $elements = @$xpath->query($import->xpath)) and $elements->length){
									
								// continue action
								if ( $import->imported + $import->skipped >= PMXI_Plugin::$session->data['pmxi_import']['chunk_number'] + $elements->length - 1 ){
									PMXI_Plugin::$session['pmxi_import']['chunk_number'] = PMXI_Plugin::$session->data['pmxi_import']['chunk_number'] + $elements->length;									
									pmxi_session_commit();
									continue;
								}	

								if ( ! $loop and $ajax_processing ) ob_start();								

								$feed .= $xml; $loop += $elements->length;

								$processed_records = $import->imported + $import->skipped + PMXI_Plugin::$session->data['pmxi_import']['errors'];

								if ( $loop == $records_per_request or $processed_records + $loop == $records_to_import or $processed_records == $records_to_import) {

									$feed .= "</pmxi_records>";																														
									$import->process($feed, $logger, PMXI_Plugin::$session->data['pmxi_import']['chunk_number'], false, '/pmxi_records');																											
									unset($dom, $xpath);									

									if ( ! $ajax_processing ){
										$feed = "<?xml version=\"1.0\" encoding=\"". $import->options['encoding'] ."\"?>"  . "\n" . "<pmxi_records>";
										$loop = 0;	
									} else {
										unset($file);
										PMXI_Plugin::$session['pmxi_import']['pointer'] = PMXI_Plugin::$session->data['pmxi_import']['pointer'] + $pointer;
										pmxi_session_commit();
										wp_send_json(array(
											'created' => $import->created,
											'updated' => $import->updated,										
											'percentage' => ceil(($processed_records/$import->count) * 100),
											'warnings' => PMXI_Plugin::$session->data['pmxi_import']['warnings'],
											'errors' => PMXI_Plugin::$session->data['pmxi_import']['errors'],
											'log' => ob_get_clean(),
											'done' => false 
										));														
									}
								}								
							}
					    }
					}						

					// Move to the next file, set pointer to first element
					if ( $ajax_processing ) {
						
						if (strpos($path, "pmxi_chunk_") !== false and @file_exists($path)) @unlink($path);

						PMXI_Plugin::$session['pmxi_import']['pointer'] = 1;
						$pointer = 0;
				    	array_shift(PMXI_Plugin::$session->data['pmxi_import']['local_paths']);
				    	PMXI_Plugin::$session['pmxi_import']['local_paths'] = PMXI_Plugin::$session->data['pmxi_import']['local_paths'];				    	
				    	pmxi_session_commit();
				    } 					    				    																		
				}
			}								
		}			
		
		if ( ( PMXI_Plugin::is_ajax() and empty(PMXI_Plugin::$session->data['pmxi_import']['local_paths']) ) or ! $ajax_processing ){
			
			// Save import process log
			$log_file = $wp_uploads['basedir'] . '/wpallimport_logs/' . $import->id . '.html';
			if (file_exists($log_file)) unlink($log_file);
			@file_put_contents($log_file, PMXI_Plugin::$session->data['pmxi_import']['log']);

			if ( ! empty(PMXI_Plugin::$session->data['pmxi_import']) ) do_action( 'pmxi_after_xml_import', $import->id );									
			
			wp_cache_flush();
			foreach ( get_taxonomies() as $tax ) {				
				delete_option( "{$tax}_children" );
				_get_term_hierarchy( $tax );
			}
			
			$import->set('registered_on', date('Y-m-d H:i:s'))->update();				

			// clear import session
			pmxi_session_unset(); // clear session data (prevent from reimporting the same data on page refresh)

			// [indicate in header process is complete]
			$msg = addcslashes(__('Complete', 'pmxi_plugin'), "\n\r");	

			if ( $ajax_processing ) ob_start();			

			$import->options['is_import_specified'] and $logger and call_user_func($logger, 'Done');	

echo <<<COMPLETE
<script type="text/javascript">
//<![CDATA[
(function($){	
	$('#status').html('$msg');
	$('#import_finished').show();
	window.onbeforeunload = false;
})(jQuery);
//]]>
</script>
COMPLETE;
// [/indicate in header process is complete]	
			
			if ( $ajax_processing ) {

				wp_send_json(array(
					'created' => $import->created,
					'updated' => $import->updated,				
					'percentage' => 100,
					'warnings' => PMXI_Plugin::$session->data['pmxi_import']['warnings'],
					'errors' => PMXI_Plugin::$session->data['pmxi_import']['errors'],
					'log' => ob_get_clean(),					
					'done' => true
				));			

			}
		}		
	}	

	protected $_sibling_limit = 20;
	protected function get_xml_path(DOMElement $el, DOMXPath $xpath)
	{
		for($p = '', $doc = $el; $doc and ! $doc instanceof DOMDocument; $doc = $doc->parentNode) {
			if (($ind = $xpath->query('preceding-sibling::' . $doc->nodeName, $doc)->length)) {
				$p = '[' . ++$ind . ']' . $p;
			} elseif ( ! $doc->parentNode instanceof DOMDocument) {
				$p = '[' . ($ind = 1) . ']' . $p;
			}
			$p = '/' . $doc->nodeName . $p;
		}
		return $p;
	}
	
	protected function shrink_xml_element(DOMElement $el)
	{
		$prev = null; $sub_ind = null;
		for ($i = 0; $i < $el->childNodes->length; $i++) {
			$child = $el->childNodes->item($i);
			if ($child instanceof DOMText) {
				if ('' == trim($child->wholeText)) {
					$el->removeChild($child);
					$i--;
					continue;
				}
			}
			if ($child instanceof DOMComment) {
				continue;
			}
			if ($prev instanceof $child and $prev->nodeName == $child->nodeName) {
				$sub_ind++;
			} else {
				if ($sub_ind > $this->_sibling_limit) {
					$el->insertBefore(new DOMComment('[pmxi_more:' . ($sub_ind - $this->_sibling_limit) . ']'), $child);
					$i++;
				}
				$sub_ind = 1;
				$prev = null;
			}
			if ($child instanceof DOMElement) {
				$prev = $child;
				if ($sub_ind <= $this->_sibling_limit) {
					$this->shrink_xml_element($child); 
				} else {
					$el->removeChild($child);
					$i--;
				}
			}
		}
		if ($sub_ind > $this->_sibling_limit) {
			$el->appendChild(new DOMComment('[pmxi_more:' . ($sub_ind - $this->_sibling_limit) . ']'));
		}
		return $el;
	}
	protected function render_xml_element(DOMElement $el, $shorten = false, $path = '/', $ind = 1, $lvl = 0)
	{
		$path .= $el->nodeName;		
		if ( ! $el->parentNode instanceof DOMDocument and $ind > 0) {
			$path .= "[$ind]";
		}		
		
		echo '<div class="xml-element lvl-' . $lvl . ' lvl-mod4-' . ($lvl % 4) . '" title="' . $path . '">';
		if ($el->hasChildNodes()) {
			$is_render_collapsed = $ind > 1;
			if ($el->childNodes->length > 1 or ! $el->childNodes->item(0) instanceof DOMText or strlen(trim($el->childNodes->item(0)->wholeText)) > 40) {
				echo '<div class="xml-expander">' . ($is_render_collapsed ? '+' : '-') . '</div>';
			}
			echo '<div class="xml-tag opening">&lt;<span class="xml-tag-name">' . $el->nodeName . '</span>'; $this->render_xml_attributes($el, $path . '/'); echo '&gt;</div>';
			if (1 == $el->childNodes->length and $el->childNodes->item(0) instanceof DOMText) {
				$this->render_xml_text(trim($el->childNodes->item(0)->wholeText), $shorten, $is_render_collapsed);
			} else {
				echo '<div class="xml-content' . ($is_render_collapsed ? ' collapsed' : '') . '">';
				$indexes = array();
				foreach ($el->childNodes as $child) {
					if ($child instanceof DOMElement) {
						empty($indexes[$child->nodeName]) and $indexes[$child->nodeName] = 0; $indexes[$child->nodeName]++;
						$this->render_xml_element($child, $shorten, $path . '/', $indexes[$child->nodeName], $lvl + 1); 
					} elseif ($child instanceof DOMText) {
						$this->render_xml_text(trim($child->wholeText), $shorten); 
					} elseif ($child instanceof DOMComment) {
						if (preg_match('%\[pmxi_more:(\d+)\]%', $child->nodeValue, $mtch)) {
							$no = intval($mtch[1]);
							echo '<div class="xml-more">[ &dArr; ' . sprintf(__('<strong>%s</strong> %s more', 'pmxi_plugin'), $no, _n('element', 'elements', $no, 'pmxi_plugin')) . ' &dArr; ]</div>';
						}
					}
				}
				echo '</div>';
			}
			echo '<div class="xml-tag closing">&lt;/<span class="xml-tag-name">' . $el->nodeName . '</span>&gt;</div>';
		} else {
			echo '<div class="xml-tag opening empty">&lt;<span class="xml-tag-name">' . $el->nodeName . '</span>'; $this->render_xml_attributes($el); echo '/&gt;</div>';
		}
		echo '</div>';
	}
	protected function render_xml_elements_for_filtring(DOMElement $el, $path ='', $lvl = 0){		
		if ("" != $path){ 
			if ($lvl > 1) $path .= "->" . $el->nodeName; else $path = $el->nodeName; 
			echo '<option value="'.$path.'">' .$path . '</option>';
		}
		else $path = $el->nodeName;		
				
		foreach ($el->attributes as $attr) {
			echo '<option value="'.$path . '@' . $attr->nodeName.'">'. $path . '@' . $attr->nodeName . '</option>';
		}
		if ($el->hasChildNodes()) {
			foreach ($el->childNodes as $child) {
				if ($child instanceof DOMElement) 
					$this->render_xml_elements_for_filtring($child, $path, $lvl + 1);
			}
		}		
	}
	protected $_unique_key = array();
	protected function find_unique_key(DOMElement $el){
		if ($el->hasChildNodes()) {
			if ($el->childNodes->length) {
				foreach ($el->childNodes as $child) {
					if ($child instanceof DOMElement) {
						if (!in_array($child->nodeName, $this->_unique_key)) $this->_unique_key[] = $child->nodeName;						
						$this->find_unique_key($child); 
					} 
				}
			}
		}
	}
	protected function render_xml_text($text, $shorten = false, $is_render_collapsed = false)
	{
		if (empty($text)) {
			return; // do not display empty text nodes
		}
		if (preg_match('%\[more:(\d+)\]%', $text, $mtch)) {
			$no = intval($mtch[1]);
			echo '<div class="xml-more">[ &dArr; ' . sprintf(__('<strong>%s</strong> %s more', 'pmxi_plugin'), $no, _n('element', 'elements', $no, 'pmxi_plugin')) . ' &dArr; ]</div>';
			return;
		}
		$more = '';
		if ($shorten and preg_match('%^(.*?\s+){20}(?=\S)%', $text, $mtch)) {
			$text = $mtch[0];
			$more = '<span class="xml-more">[' . __('more', 'pmxi_plugin') . ']</span>';
		}
		$is_short = strlen($text) <= 40;
		$text = esc_html($text); 
		$text = preg_replace('%(?<!\s)\b(?!\s|\W[\w\s])|\w{20}%', '$0&#8203;', $text); // put explicit breaks for xml content to wrap
		echo '<div class="xml-content textonly' . ($is_short ? ' short' : '') . ($is_render_collapsed ? ' collapsed' : '') . '">' . $text . $more . '</div>';
	}
	protected function render_xml_attributes(DOMElement $el, $path = '/')
	{
		foreach ($el->attributes as $attr) {
			echo ' <span class="xml-attr" title="' . $path . '@' . $attr->nodeName . '"><span class="xml-attr-name">' . $attr->nodeName . '</span>=<span class="xml-attr-value">"' . esc_attr($attr->value) . '"</span></span>';
		}
	}
	
	protected function render_xml_element_table(DOMElement $el, $shorten = false, $path = '/', $ind = 0, $lvl = 0)
	{
		$path .= $el->nodeName;
		if ($ind > 0) {
			$path .= "[$ind]";
		}
		
		$is_render_collapsed = $ind > 1;
		echo '<tr class="xml-element lvl-' . $lvl . ($is_render_collapsed ? ' collapsed' : '') . '" title="' . $path . '">';
			echo '<td style="padding-left:' . ($lvl + 1) * 15 . 'px">';
				$is_inline = true;
				if ( ! (0 == $el->attributes->length and 1 == $el->childNodes->length and $el->childNodes->item(0) instanceof DOMText and strlen($el->childNodes->item(0)->wholeText) <= 40)) {
					$is_inline = false;
					echo '<div class="xml-expander">' . ($is_render_collapsed ? '+' : '-') . '</div>';
				}
				echo '<div class="xml-tag opening"><span class="xml-tag-name">' . $el->nodeName . '</span></div>';
			echo '</td>';
			echo '<td>';
				$is_inline and $this->render_xml_text_table(trim($el->childNodes->item(0)->wholeText), $shorten, NULL, NULL, $is_inline = true);
			echo '</td>';
		echo '</tr>';
		if ( ! $is_inline) {
			echo '<tr class="xml-content' . ($is_render_collapsed ? ' collapsed' : '') . '">';
				echo '<td colspan="2">';
					echo '<table>';
						$this->render_xml_attributes_table($el, $path . '/', $lvl + 1);
						$indexes = array();
						foreach ($el->childNodes as $child) {
							if ($child instanceof DOMElement) {
								empty($indexes[$child->nodeName]) and $indexes[$child->nodeName] = 1;
								$this->render_xml_element_table($child, $shorten, $path . '/', $indexes[$child->nodeName]++, $lvl + 1);
							} elseif ($child instanceof DOMText) {
								$this->render_xml_text_table(trim($child->wholeText), $shorten, $path . '/', $lvl + 1);
							}
						}
					echo '</table>';
				echo '</td>';
			echo '</tr>';
		}
	}
	protected function render_xml_text_table($text, $shorten = false, $path = '/', $lvl = 0, $is_inline = false)
	{
		if (empty($text)) {
			return; // do not display empty text nodes
		}
		$more = '';
		if ($shorten and preg_match('%^(.*?\s+){20}(?=\S)%', $text, $mtch)) {
			$text = $mtch[0];
			$more = '<span class="xml-more">[' . __('more', 'pmxi_plugin') . ']</span>';
		}
		$is_short = strlen($text) <= 40;
		$text = esc_html($text); 
		$text = preg_replace('%(?<!\s)\b(?!\s|\W[\w\s])|\w{20}%', '$0&#8203;', $text); // put explicit breaks for xml content to wrap
		if ($is_inline) {
			echo $text . $more;
		} else {
			echo '<tr class="xml-content-tr textonly lvl-' . $lvl . ($is_short ? ' short' : '') . '" title="' . $path . 'text()">';
				echo '<td style="padding-left:' . ($lvl + 1) * 15 . 'px"><span class="xml-attr-name">text</span></td>';
				echo '<td>' . $text . $more . '</td>';
			echo '</tr>';
		}
	}
	protected function render_xml_attributes_table(DOMElement $el, $path = '/', $lvl = 0)
	{
		foreach ($el->attributes as $attr) {
			echo '<tr class="xml-attr lvl-' . $lvl . '" title="' . $path . '@' . $attr->nodeName . '">';
				echo '<td style="padding-left:' . ($lvl + 1) * 15 . 'px"><span class="xml-attr-name">@' . $attr->nodeName . '</span></td>';
				echo '<td><span class="xml-attr-value">' . esc_attr($attr->value) . '</span></td>';
			echo '</tr>';
		}
	}
	
	protected function xml_find_repeating(DOMElement $el, $path = '/')
	{
		$path .= $el->nodeName;
		if ( ! $el->parentNode instanceof DOMDocument) {
			$path .= '[1]';
		}
		$children = array();
		foreach ($el->childNodes as $child) {
			if ($child instanceof DOMElement) {
				if ( ! empty($children[$child->nodeName])) {
					return $path . '/' . $child->nodeName;
				} else {
					$children[$child->nodeName] = true;
				}
			}
		}
		// reaching this point means we didn't find anything among current element children, so recursively ask children to find something in them
		foreach ($el->childNodes as $child) {
			if ($child instanceof DOMElement) {
				$result = $this->xml_find_repeating($child, $path . '/');
				if ($result) {
					return $result;
				}
			}
		}
		// reaching this point means we didn't find anything, so return element itself if the function was called for it
		if ('/' . $el->nodeName == $path) {
			return $path;
		}
		
		return NULL;		
	}	

	protected function sxml_append(SimpleXMLElement $to, SimpleXMLElement $from) {
	    $toDom = dom_import_simplexml($to);
	    $fromDom = dom_import_simplexml($from);
	    $toDom->appendChild($toDom->ownerDocument->importNode($fromDom, true));
	}
	
	protected function get_xml($tagno = 0){
		$xml = '';
		$customXpath = PMXI_Plugin::$session->data['pmxi_import']['xpath'];
		$wp_uploads = wp_upload_dir();			
		$update_previous = new PMXI_Import_Record();

		if ( ! empty(PMXI_Plugin::$session->data['pmxi_import']['update_previous'])) $update_previous->getById(PMXI_Plugin::$session->data['pmxi_import']['update_previous']);

		if ( ! empty(PMXI_Plugin::$session->data['pmxi_import']['local_paths'])) {

			$loop = 0;
			foreach (PMXI_Plugin::$session->data['pmxi_import']['local_paths'] as $key => $path) {																						

				if ( @file_exists($path) ){								
					
					$root_element = ( ! $update_previous->isEmpty() ) ? $update_previous->root_element : PMXI_Plugin::$session->data['pmxi_import']['source']['root_element'];

					$file = new PMXI_Chunk($path, array('element' => $root_element, 'encoding' => PMXI_Plugin::$session->data['pmxi_import']['encoding']));															

				    while ($xml = $file->read()) {					      						    					    					    					    					    					    				    					    	

				    	if (!empty($xml))
				      	{								      		
				      		PMXI_Import_Record::preprocessXml($xml);
				      		$xml = "<?xml version=\"1.0\" encoding=\"". PMXI_Plugin::$session->data['pmxi_import']['encoding'] ."\"?>" . "\n" . $xml;				      						      						      		
				    					    	
					      	if ( '' != $customXpath){
						      	$dom = new DOMDocument('1.0', PMXI_Plugin::$session->data['pmxi_import']['encoding']);
								$old = libxml_use_internal_errors(true);
								$dom->loadXML($xml);
								libxml_use_internal_errors($old);
								$xpath = new DOMXPath($dom);									
								if (($elements = $xpath->query($customXpath)) and $elements->length){
									$this->data['dom'] = $dom;
									$loop++;
									if ( ! $tagno or $loop == $tagno ) break;
								}										
							}
							else break;
					    }
					}
					unset($file);		

				}
			}			
		}			
		return $xml;
	}
}
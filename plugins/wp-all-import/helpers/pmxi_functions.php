<?php
	/**
	* IF statement		
	*
	* @access	public
	* @param	string
	* @param	string
	* @param	string
	* @param	string
	* @param	string
	* @return   string
	*/
	if (!function_exists('pmxi_if')){		
		function pmxi_if($left_condition, $operand = '', $right_condition = '', $then, $else = ''){			
			$str = trim(implode(' ', array($left_condition, html_entity_decode($operand), $right_condition)));												
			return (eval ("return ($str);")) ? $then : $else;
		}		
	}

	if (!function_exists('is_empty')){	
		function is_empty($var)
		{ 
		 	return empty($var);
		}
	}

	/**
	 * Word Limiter
	 *
	 * Limits a string to X number of words.
	 *
	 * @access	public
	 * @param	string
	 * @param	string
	 * @param	string	the end character. Usually an ellipsis
	 * @return	string
	 */
	if ( ! function_exists('pmxi_word_limiter'))
	{
		function pmxi_word_limiter($str, $limit = 100, $end_char = '&#8230;')
		{
			if (trim($str) == '')
			{
				return $str;
			}

			preg_match('/^\s*+(?:\S++\s*+){1,'.(int) $limit.'}/', $str, $matches);

			if (strlen($str) == strlen($matches[0]))
			{
				$end_char = '';
			}

			return rtrim($matches[0]).$end_char;
		}
	}

	/**
	 * Character Limiter
	 *
	 * Limits the string based on the character count.  Preserves complete words
	 * so the character count may not be exactly as specified.
	 *
	 * @access	public
	 * @param	string
	 * @param	string
	 * @param	string	the end character. Usually an ellipsis
	 * @return	string
	 */
	if ( ! function_exists('pmxi_character_limiter'))
	{
		function pmxi_character_limiter($str, $n = 500, $end_char = '&#8230;')
		{
			if (strlen($str) < $n)
			{
				return $str;
			}

			$str = preg_replace("/\s+/", ' ', str_replace(array("\r\n", "\r", "\n"), ' ', $str));

			if (strlen($str) <= $n)
			{
				return $str;
			}

			$out = "";
			foreach (explode(' ', trim($str)) as $val)
			{
				$out .= $val.' ';

				if (strlen($out) >= $n)
				{
					$out = trim($out);
					return (strlen($out) == strlen($str)) ? $out : $out.$end_char;
				}
			}
		}
	}

	if ( ! function_exists('url_title')){

		function url_title($str, $separator = 'dash', $lowercase = FALSE)
		{
			if ($separator == 'dash')
			{
				$search		= '_';
				$replace	= '-';
			}
			else
			{
				$search		= '-';
				$replace	= '_';
			}

			$trans = array(
				'&\#\d+?;'				=> '',
				'&\S+?;'				=> '',
				'\s+'					=> $replace,
				'[^a-z0-9\-\._]'		=> '',
				$replace.'+'			=> $replace,
				$replace.'$'			=> $replace,
				'^'.$replace			=> $replace,
				'\.+$'					=> ''
			);

			$str = strip_tags($str);

			foreach ($trans as $key => $val)
			{
				$str = preg_replace("#".$key."#i", $val, $str);
			}

			if ($lowercase === TRUE)
			{
				$str = strtolower($str);
			}

			return trim(stripslashes($str));
		}
	}

	if ( ! function_exists('rand_char')){

		function rand_char($length) {
		  $random = '';
		  for ($i = 0; $i < $length; $i++) {
		    $random .= chr(mt_rand(33, 126));
		  }
		  return $random;
		}
	}

	if ( ! function_exists('pmxi_get_remote_file_name')){

		function pmxi_get_remote_file_name($filePath){
			$type = (preg_match('%\W(csv|txt|dat|psv)$%i', basename($filePath))) ? 'csv' : false;
			if (!$type) $type = (preg_match('%\W(xml)$%i', basename($filePath))) ? 'xml' : false;
			if (!$type) $type = (preg_match('%\W(zip)$%i', basename($filePath))) ? 'zip' : false;
			if (!$type) $type = (preg_match('%\W(gz)$%i', basename($filePath))) ? 'gz' : false;

			if (!$type){
				$response = wp_remote_get($filePath);
				$headers = wp_remote_retrieve_headers( $response );
				
				if (preg_match("/filename=\".*\"/i", $headers['content-disposition'], $matches)){
					$remote_file_name = str_replace(array('filename=','"'), '', $matches[0]);
					if (!empty($remote_file_name)){
						$type = (preg_match('%\W(csv)$%i', basename($remote_file_name))) ? 'csv' : false;
						if (!$type) $type = (preg_match('%\W(xml)$%i', basename($remote_file_name))) ? 'xml' : false;
						if (!$type) $type = (preg_match('%\W(zip)$%i', basename($remote_file_name))) ? 'zip' : false;
						if (!$type) $type = (preg_match('%\W(gz)$%i', basename($remote_file_name))) ? 'gz' : false;					
					};
				};						
			}

			return ($type) ? $type : '';
		}
	}

	if ( ! function_exists('pmxi_get_remote_image_ext')){

		function pmxi_get_remote_image_ext($filePath){
			$ext = pmxi_getExtension($filePath);

			if ("" != $ext) return $ext;
			$response = wp_remote_get($filePath);
			$headers = wp_remote_retrieve_headers( $response );
			$content_type = explode('/',$headers['content-type']);		

			return (!empty($content_type[1])) ? $content_type[1] : '';
		}
	}

	if ( ! function_exists('pmxi_getExtension')){
		function pmxi_getExtension($str) 
	    {
	        $i = strrpos($str,".");        
	        if (!$i) return "";
	        $l = strlen($str) - $i;        
	        $ext = substr($str,$i+1,$l);
	        return ($l <= 4) ? $ext : "";
		}
	}

	if ( ! function_exists('getExtensionFromStr')){
		function pmxi_getExtensionFromStr($str) 
	    {
	        $i = strrpos($str,".");
	        if (!$i) return "";
	        $l = strlen($str) - $i;
	        $ext = substr($str,$i+1,$l);
	        return (preg_match('%\W(jpg|jpeg|gif|png)$%i', basename($ext)) and strlen($ext) <= 4) ? $ext : "";
		}
	}

	/**
	 * Reading large files from remote server
	 * @ $filePath - file URL
	 * return local path of copied file
	 */
	if ( ! function_exists('pmxi_copy_url_file')){

		function pmxi_copy_url_file($filePath, $detect = false){
			/*$ctx = stream_context_create();
			stream_context_set_params($ctx, array("notification" => "stream_notification_callback"));*/
			
			$type = (preg_match('%\W(csv|txt|dat|psv)$%i', basename($filePath))) ? 'csv' : false;
			if (!$type) $type = (preg_match('%\W(xml)$%i', basename($filePath))) ? 'xml' : false;

			$uploads = wp_upload_dir();
			$tmpname = wp_unique_filename($uploads['path'], ($type and strlen(basename($filePath)) < 30) ? basename($filePath) : time());	
			$localPath = $uploads['path']  .'/'. url_title($tmpname) . ((!$type) ? '.tmp' : '');

			$file = @fopen($filePath, "rb");

	   		if (is_resource($file)){   			
	   			$fp = @fopen($localPath, 'w');
			   	$first_chunk = true;
				while (!feof($file)) {
					$chunk = @fread($file, 1024);				
					if (!$type and $first_chunk and strpos($chunk, "<") !== false) $type = 'xml'; elseif (!$type and $first_chunk) $type = 'csv'; // if it's a 1st chunk, then chunk <? symbols to detect XML file
					$first_chunk = false;
				 	@fwrite($fp, $chunk);		 	
				}
				@fclose($file);
				@fclose($fp); 	   	
			}						

		   	if (!file_exists($localPath)) {
		   		
		   		get_file_curl($filePath, $localPath);

		   		if (!$type){	   			
			   		$file = @fopen($localPath, "rb");	   		
					while (!feof($file)) {
						$chunk = @fread($file, 1024);					
						if (strpos($chunk, "<?") !== false) $type = 'xml'; else $type = 'csv'; // if it's a 1st chunk, then chunk <? symbols to detect XML file					
					 	break;		 	
					}
					@fclose($file);	
				}
		   		
		   	} 			   	

			$newpath = str_replace("." . $type, "", $localPath) . '.' . $type;

			if (@rename($localPath, $newpath)) $localPath = $newpath;				
			
			return ($detect) ? array('type' => $type, 'localPath' => $localPath) : $localPath;
		}
	}

	if ( ! function_exists('pmxi_gzfile_get_contents')){
		function pmxi_gzfile_get_contents($filename, $use_include_path = 0) {

			$type = 'csv';
			$uploads = wp_upload_dir();
			$tmpname = wp_unique_filename($uploads['path'], (strlen(basename($filename)) < 30) ? basename($filename) : time());	
			$localPath = $uploads['path']  .'/'. url_title($tmpname);

			$fp = @fopen($localPath, 'w');

		    $file = @gzopen($filename, 'rb', $use_include_path);
		    if ($file) {
		        $first_chunk = true;
		        while (!gzeof($file)) {
		            $chunk = gzread($file, 1024);
		            if ($first_chunk and strpos($chunk, "<?") !== false) { $type = 'xml'; $first_chunk = false; } // if it's a 1st chunk, then chunk <? symbols to detect XML file
		            @fwrite($fp, $chunk);
		        }
		        gzclose($file);
		    }
		    @fclose($fp);
		   
		    return array('type' => $type, 'localPath' => $localPath);
		}
	}	

	if ( ! function_exists('pmxi_strip_tags_content')){

		function pmxi_strip_tags_content($text, $tags = '', $invert = FALSE) {

		  preg_match_all('/<(.+?)[\s]*\/?[\s]*>/si', trim($tags), $tags);
		  $tags = array_unique($tags[1]);
		   
		  if(is_array($tags) AND count($tags) > 0) {
		    if($invert == FALSE) {
		      return preg_replace('@<(?!(?:'. implode('|', $tags) .')\b)(\w+)\b.*?>.*?</\1>@si', '', $text);
		    }
		    else {
		      return preg_replace('@<('. implode('|', $tags) .')\b.*?>.*?</\1>@si', '', $text);
		    }
		  }
		  elseif($invert == FALSE) {
		    return preg_replace('@<(\w+)\b.*?>.*?</\1>@si', '', $text);
		  }
		  return $text;
		} 
	}

	/*	
	* $value = {property/type[1]}
	* Would return Rent if $value is 1, $Buy if $value is 2, Unavailable if $value is 3.
	*/
	if ( ! function_exists('wpai_util_map')){

		function wpai_util_map($orig, $change, $value) {
			
			$orig_array = explode(',', $orig);

			if ( empty($orig_array) ) return "";

			$change_array = explode(',', $change);

			if ( empty($change_array) or count($orig_array) != count($change_array)) return "";					

			return str_replace(array_map('trim', $orig_array), array_map('trim', $change_array), $value); 
			
		}
	}

	if ( ! function_exists('pmxi_convert_encoding')){
		
		function pmxi_convert_encoding ( $source, $target_encoding = 'ASCII' )
		{		   

			if ( function_exists('mb_detect_encoding') ){
			    
			    // detect the character encoding of the incoming file
			    $encoding = mb_detect_encoding( $source, "auto" );
			      
			    // escape all of the question marks so we can remove artifacts from
			    // the unicode conversion process
			    $target = str_replace( "?", "[question_mark]", $source );
			    
			    // convert the string to the target encoding
			    $target = mb_convert_encoding( $target, $target_encoding, $encoding);
			      
			    // remove any question marks that have been introduced because of illegal characters
			    $target = str_replace( "?", "", $target );
			      
			    // replace the token string "[question_mark]" with the symbol "?"
			    $target = str_replace( "[question_mark]", "?", $target );
			  	
			    return html_entity_decode($target, ENT_COMPAT, 'UTF-8');

			}

			return $source;
		}
	}

	if ( ! function_exists('pmxi_imageurlencode')){

		function pmxi_imageurlencode($url){

		    $urlArray = parse_url($url);

		    $url = ($urlArray['scheme'].'://'.$urlArray['host'].str_replace('%2F', '/', urlencode($urlArray['path'])));
		    $url .= isset($urlArray['query']) ? '?'.$urlArray['query'] : '';

		    return $url;
		}
	}

	/* Session */

	/**
	 * Return the current session status.
	 *
	 * @return int
	 */
	function pmxi_session_status() {
		
		PMXI_Plugin::$session = PMXI_Session::get_instance();

		if ( PMXI_Plugin::$session->session_started() ) {
			return PHP_SESSION_ACTIVE;
		}

		return PHP_SESSION_NONE;
	}

	/**
	 * Unset all session variables.
	 */
	function pmxi_session_unset() {
		PMXI_Plugin::$session = PMXI_Session::get_instance();

		PMXI_Plugin::$session->reset();
	}

	/**
	 * Alias of wp_session_write_close()
	 */
	function pmxi_session_commit() {		
		PMXI_Plugin::$session = PMXI_Session::get_instance();
		PMXI_Plugin::$session->write_data();	
		do_action( 'pmxi_session_commit' );
	}

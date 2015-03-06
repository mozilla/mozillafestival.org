<?php
/**
 * Chunk
 * 
 * Reads a large file in as chunks for easier parsing.
 * 
 * 
 * @package default
 * @author Max Tsiplyakov
 */
class PMXI_Chunk {
  /**
   * options
   *
   * @var array Contains all major options
   * @access public
   */
  public $options = array(
    'path' => './',       // string The path to check for $file in
    'element' => '',      // string The XML element to return    
    'type' => 'upload',
    'encoding' => 'UTF-8',
    'pointer' => 1,
    'chunkSize' => 1024
  );
  
  /**
   * file
   *
   * @var string The filename being read
   * @access public
   */
  public $file = '';
  /**
   * pointer
   *
   * @var integer The current position the file is being read from
   * @access public
   */
  public $reader;  
  public $cloud = array();      
  public $loop = 1;
    
  /**
   * handle
   *
   * @var resource The fopen() resource
   * @access private
   */
  private $handle = null;
  /**
   * reading
   *
   * @var boolean Whether the script is currently reading the file
   * @access private
   */  
  
  /**
   * __construct
   * 
   * Builds the Chunk object
   *
   * @param string $file The filename to work with
   * @param array $options The options with which to parse the file
   * @author Dom Hastings
   * @access public
   */
  public function __construct($file, $options = array()) {
    
    // merge the options together
    $this->options = array_merge($this->options, (is_array($options) ? $options : array()));                       

    $this->options['chunkSize'] *= PMXI_Plugin::getInstance()->getOption('chunk_size');      

    // set the filename
    $this->file = $file;     

    if (empty($this->options['element'])){
      $founded_tags = array();     

      $reader = new XMLReader();
      $reader->open($this->file);
      $reader->setParserProperty(XMLReader::VALIDATE, false);
      while ( @$reader->read()) {
         switch ($reader->nodeType) {
           case (XMLREADER::ELEMENT):              
              array_push($founded_tags, str_replace(":", "_", $reader->localName));
              if (count($founded_tags) > 100) break(2);
              break;
            default:

              break;
         }
      }
      unset($reader);

      if (!empty($founded_tags)) {            
        $element_counts = array_count_values($founded_tags);                          
        if (!empty($element_counts)){
          foreach ($element_counts as $tag => $count)
            if (strpos($tag, ":") === false)
                $this->cloud[$tag] = $count;                
          
          arsort($element_counts);           
        }              
      }        
     
      if (!empty($this->cloud)){      
        
        $main_elements = array('node', 'product', 'job', 'deal', 'entry', 'item', 'property', 'listing', 'hotel', 'record', 'article');

        foreach ($this->cloud as $element_name => $value) {          
          if ( in_array(strtolower($element_name), $main_elements) ){
            $this->options['element'] = $element_name;
            break;    
          }
        }
        if (empty($this->options['element'])){
          //if (count($element_counts) > 1) array_shift($element_counts);          
          foreach ($element_counts as $el => $count) {                        
              $this->options['element'] = $el;
              break;            
          }          
        }             
      }
    }                           

    // we must be looking for a specific element        
    /*if (empty($this->options['encoding'])) { 

        // open the file
        $this->handle = @fopen($this->file, 'rb'); 

        fseek($this->handle, 0);
        $this->reading = true;    
        // read in the whole doc, cos we don't know what's wanted      
        while ($this->reading) {
            $c = @fread($this->handle, $this->options['chunkSize']);          
            
            $enc = @preg_match("/<\?xml[^<]*\?>/i", $c, $enc_matches);
            if ($enc)
                $this->options['encoding'] = $enc_matches[0];
            
            $this->reading = false;
        }
        if ($this->handle) @fclose($this->handle);
    }      

    $encoding = '';

    if (empty($this->options['encoding']) or strpos($this->options['encoding'], 'encoding') === false) 
      $encoding = "UTF-8";    
    else
      preg_match('~encoding=["|\']{1}([-a-z0-9_]+)["|\']{1}~i', $this->options['encoding'], $encoding);  

    $this->options['encoding'] = (is_array($encoding)) ? $encoding[1] : $encoding;    */

    /*stream_filter_register("removecolons", "removecolons_filter");    

    $path = 'php://filter/read=removecolons/resource=' . $this->file;*/

    $this->reader = new XMLReader();    
    $this->reader->open($this->file);    
    $this->reader->setParserProperty(XMLReader::VALIDATE, false);

  }  

  /**
   * __destruct
   * 
   * Cleans up
   *
   * @return void
   * @author Dom Hastings
   * @access public
   */
  public function __destruct() {
    // close the file resource
    unset($this->reader);
  }
  
  /**
   * read
   * 
   * Reads the first available occurence of the XML element $this->options['element']
   *
   * @return string The XML string from $this->file
   * @author Dom Hastings
   * @access public
   */
  public function read($debug = false) {

    // trim it
    $element = trim($this->options['element']);
                  
    $xml = '';    

    try { 
      while ( @$this->reader->read() ) {        
          switch ($this->reader->nodeType) {
           case (XMLREADER::ELEMENT):            
              if ( strtolower(str_replace(":", "_", $this->reader->localName)) == strtolower($element) ) {                

                  if ($this->loop < $this->options['pointer']){
                    $this->loop++;                  
                    continue;
                  }                
                  
                  $xml = @$this->reader->readOuterXML();                  

                  break(2);                                
              }            
              break;
            default:
              // code ...
              break;
          }               
      }
    } catch (XmlImportException $e) {
      $xml = false;
    }    
    
    return ( ! empty($xml) ) ? $this->removeColonsFromRSS(preg_replace('%xmlns.*=\s*([\'"]).*\1%sU', '', $xml)) : false;

  }  

  function removeColonsFromRSS($feed) {
                  
        // pull out colons from start tags
        // (<\w+):(\w+>)
        $pattern = '/(<\w+):(\w+[ |>]{1})/i';
        $replacement = '<$2';
        $feed = preg_replace($pattern, $replacement, $feed);
        // pull out colons from end tags
        // (<\/\w+):(\w+>)
        $pattern = '/(<\/\w+):(\w+>)/i';
        $replacement = '</$2';
        $feed = preg_replace($pattern, $replacement, $feed);
        // pull out colons from attributes
        $pattern = '/(\s+\w+):(\w+[=]{1})/i';
        $replacement = '$1_$2';
        $feed = preg_replace($pattern, $replacement, $feed);
      
        return $feed;

    }

}

class removecolons_filter extends php_user_filter {

    function filter($in, $out, &$consumed, $closing)
    {
      while ($bucket = stream_bucket_make_writeable($in)) {
        $bucket->data = $this->removeColonsFromRSS(preg_replace('%xmlns.*=\s*([\'"]).*\1%sU', '', $bucket->data));              //preg_replace ('/[^\x{0009}\x{000a}\x{000d}\x{0020}-\x{D7FF}\x{E000}-\x{FFFD}]+/u', ' ', $bucket->data);
        $consumed += $bucket->datalen;        
        stream_bucket_append($out, $bucket);
      }      
      return PSFS_PASS_ON;
    }

}

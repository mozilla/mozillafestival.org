<?php 

class PMXI_CsvParser
{
    public

    /**
     * csv parsing default-settings
     *
     * @var array
     * @access public
     */
    $settings = array(
        'delimiter' => ',',
        'eol' => '',
        'length' => 999999,
        'escape' => '"'
    ),

    $tmp_files = array(),

    $xpath = '',

    $delimiter = '',

    $large_import = false,    

    $htmlentities = false,

    $xml_path = '',

    $iteration = 0,

    $csv_encoding = 'UTF-8',

    $auto_encoding = true;

    protected

    /**
     * imported data from csv
     *
     * @var array
     * @access protected
     */
    $rows = array(),

    /**
     * csv file to parse
     *
     * @var string
     * @access protected
     */
    $_filename = '',

    /**
     * csv headers to parse
     *
     * @var array
     * @access protected
     */
    $headers = array();

    /**
     * data load initialize
     *
     * @param mixed $filename please look at the load() method
     *
     * @access public
     * @see load()
     * @return void
     */
    public function __construct($filename = null, $large_import = false, $xpath = '', $delimiter = '', $encoding = '', $xml_path = '')
    {
        PMXI_Plugin::$csv_path = $filename;

        $this->large_import = $large_import;
        $this->xpath = (!empty($xpath) ? $xpath : ((!empty($_POST['xpath'])) ? $_POST['xpath'] : '/node'));        
        $this->delimiter = $delimiter;
        if ('' != $encoding){ 
            $this->csv_encoding = $encoding;
            $this->auto_encoding = false;
        }
        if ('' != $xml_path) $this->xml_path = $xml_path;

        @ini_set( "display_errors", 0);
        @ini_set('auto_detect_line_endings', true);        
        
        $file_params = self::analyse_file($filename, 1);

        $this->set_settings(array('delimiter' => $file_params['delimiter']['value'], 'eol' => $file_params['line_ending']['value']));        

        unset($file_params);

        $this->load($filename);
    }

    /**
     * csv file loader
     *
     * indicates the object which file is to be loaded
     *
     *
     * @param string $filename the csv filename to load
     *
     * @access public
     * @return boolean true if file was loaded successfully
     * @see isSymmetric(), getAsymmetricRows(), symmetrize()
     */
    public function load($filename)
    {
        $this->_filename = $filename;
        $this->flush();
        return $this->parse();
    }

    /**
     * settings alterator
     *
     * lets you define different settings for scanning
     *
     *
     * @param mixed $array containing settings to use
     *
     * @access public
     * @return boolean true if changes where applyed successfully
     * @see $settings
     */
    public function set_settings($array)
    {
        $this->settings = array_merge($this->settings, $array);
    }

    /**
     * header fetcher
     *
     * gets csv headers into an array
     *
     * @access public
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * header counter
     *
     * retrives the total number of loaded headers
     *
     * @access public
     * @return integer gets the length of headers
     */
    public function countHeaders()
    {
        return count($this->headers);
    }

    /**
     * header and row relationship builder
     *
     * Attempts to create a relationship for every single cell that
     * was captured and its corresponding header. The sample below shows
     * how a connection/relationship is built.    
     *
     * @param array $columns the columns to connect, if nothing
     * is given all headers will be used to create a connection
     *
     * @access public
     * @return array If the data is not symmetric an empty array
     * will be returned instead
     * @see isSymmetric(), getAsymmetricRows(), symmetrize(), getHeaders()
     */
    public function connect($columns = array())
    {
        if (!$this->isSymmetric()) {
            return array();
        }
        if (!is_array($columns)) {
            return array();
        }
        if ($columns === array()) {
            $columns = $this->headers;
        }

        $ret_arr = array();

        foreach ($this->rows as $record) {
            $item_array = array();
            foreach ($record as $column => $value) {
                $header = $this->headers[$column];
                if (in_array($header, $columns)) {
                    $item_array[$header] = $value;
                }
            }

            // do not append empty results
            if ($item_array !== array()) {
                array_push($ret_arr, $item_array);
            }
        }

        return $ret_arr;
    }

    /**
     * data length/symmetry checker
     *
     * tells if the headers and all of the contents length match.
     * Note: there is a lot of methods that won't work if data is not
     * symmetric this method is very important!
     *
     * @access public
     * @return boolean
     * @see symmetrize(), getAsymmetricRows(), isSymmetric()
     */
    public function isSymmetric()
    {
        $hc = count($this->headers);
        foreach ($this->rows as $row) {
            if (count($row) != $hc) {
                return false;
            }
        }
        return true;
    }

    /**
     * asymmetric data fetcher
     *
     * finds the rows that do not match the headers length
     *
     * lets assume that we add one more row to our csv file.
     * that has only two values. Something like
     *
     * @access public
     * @return array filled with rows that do not match headers
     * @see getHeaders(), symmetrize(), isSymmetric(),
     * getAsymmetricRows()
     */
    public function getAsymmetricRows()
    {
        $ret_arr = array();
        $hc      = count($this->headers);
        foreach ($this->rows as $row) {
            if (count($row) != $hc) {
                $ret_arr[] = $row;
            }
        }
        return $ret_arr;
    }

    /**
     * all rows length equalizer
     *
     * makes the length of all rows and headers the same. If no $value is given
     * all unexistent cells will be filled with empty spaces
     *
     * @param mixed $value the value to fill the unexistent cells
     *
     * @access public
     * @return array
     * @see isSymmetric(), getAsymmetricRows(), symmetrize()
     */
    public function symmetrize($value = '')
    {
        $max_length = 0;
        $headers_length = count($this->headers);

        foreach ($this->rows as $row) {
            $row_length = count($row);
            if ($max_length < $row_length) {
                $max_length = $row_length;
            }
        }

        if ($max_length < $headers_length) {
            $max_length = $headers_length;
        }

        foreach ($this->rows as $key => $row) {
            $this->rows[$key] = array_pad($row, $max_length, $value);
        }

        $this->headers = array_pad($this->headers, $max_length, $value);
    }

    /**
     * grid walker
     *
     * travels through the whole dataset executing a callback per each
     * cell
     *
     * Note: callback functions get the value of the cell as an
     * argument, and whatever that callback returns will be used to
     * replace the current value of that cell.
     *
     * @param string $callback the callback function to be called per
     * each cell in the dataset.
     *
     * @access public
     * @return void
     * @see walkColumn(), walkRow(), fillColumn(), fillRow(), fillCell()
     */
    public function walkGrid($callback)
    {
        foreach (array_keys($this->getRows()) as $key) {
            if (!$this->walkRow($key, $callback)) {
                return false;
            }
        }
        return true;
    }

    /**
     * column fetcher
     *
     * gets all the data for a specific column identified by $name
     *
     * Note $name is the same as the items returned by getHeaders()
     *
     * @param string $name the name of the column to fetch
     *
     * @access public
     * @return array filled with values of a column
     * @see getHeaders(), fillColumn(), appendColumn(), getCell(), getRows(),
     * getRow(), hasColumn()
     */
    public function getColumn($name)
    {
        if (!in_array($name, $this->headers)) {
            return array();
        }
        $ret_arr = array();
        $key     = array_search($name, $this->headers, true);
        foreach ($this->rows as $data) {
            $ret_arr[] = $data[$key];
        }
        return $ret_arr;
    }

    /**
     * column existance checker
     *
     * checks if a column exists, columns are identified by their
     * header name.
     *
     * @param string $string an item returned by getHeaders()
     *
     * @access public
     * @return boolean
     * @see getHeaders()
     */
    public function hasColumn($string)
    {
        return in_array($string, $this->headers);
    }

    /**
     * column appender
     *
     * Appends a column and each or all values in it can be
     *
     * @param string $column an item returned by getHeaders()
     * @param mixed  $values same as fillColumn()
     *
     * @access public
     * @return boolean
     * @see getHeaders(), fillColumn(), fillCell(), createHeaders(),
     * setHeaders()
     */
    public function appendColumn($column, $values = null)
    {
        if ($this->hasColumn($column)) {
            return false;
        }
        $this->headers[] = $column;
        $length          = $this->countHeaders();
        $rows            = array();

        foreach ($this->rows as $row) {
            $rows[] = array_pad($row, $length, '');
        }

        $this->rows = $rows;

        if ($values === null) {
            $values = '';
        }

        return $this->fillColumn($column, $values);
    }

    /**
     * collumn data injector
     *
     * fills alll the data in the given column with $values
     *
     * @param mixed $column the column identified by a string
     * @param mixed $values ither one of the following
     *  - (Number) will fill the whole column with the value of number
     *  - (String) will fill the whole column with the value of string
     *  - (Array) will fill the while column with the values of array
     *    the array gets ignored if it does not match the length of rows
     *
     * @access public
     * @return void
     */
    public function fillColumn($column, $values = null)
    {
        if (!$this->hasColumn($column)) {
            return false;
        }

        if ($values === null) {
            return false;
        }

        if (!$this->isSymmetric()) {
            return false;
        }

        $y = array_search($column, $this->headers);

        if (is_numeric($values) || is_string($values)) {
            foreach (range(0, $this->countRows() -1) as $x) {
                $this->fillCell($x, $y, $values);
            }
            return true;
        }

        if ($values === array()) {
            return false;
        }

        $length = $this->countRows();
        if (is_array($values) && $length == count($values)) {
            for ($x = 0; $x < $length; $x++) {
                $this->fillCell($x, $y, $values[$x]);
            }
            return true;
        }

        return false;
    }

    /**
     * column remover
     *
     * Completly removes a whole column identified by $name    
     *
     * @param string $name same as the ones returned by getHeaders();
     *
     * @access public
     * @return boolean
     * @see hasColumn(), getHeaders(), createHeaders(), setHeaders(),
     * isSymmetric(), getAsymmetricRows()
     */
    public function removeColumn($name)
    {
        if (!in_array($name, $this->headers)) {
            return false;
        }

        if (!$this->isSymmetric()) {
            return false;
        }

        $key = array_search($name, $this->headers);
        unset($this->headers[$key]);
        $this->resetKeys($this->headers);

        foreach ($this->rows as $target => $row) {
            unset($this->rows[$target][$key]);
            $this->resetKeys($this->rows[$target]);
        }

        return $this->isSymmetric();
    }

    /**
     * column walker
     *
     * goes through the whole column and executes a callback for each
     * one of the cells in it.
     *
     * Note: callback functions get the value of the cell as an
     * argument, and whatever that callback returns will be used to
     * replace the current value of that cell.
     *
     * @param string $name     the header name used to identify the column
     * @param string $callback the callback function to be called per
     * each cell value
     *
     * @access public
     * @return boolean
     * @see getHeaders(), fillColumn(), appendColumn()
     */
    public function walkColumn($name, $callback)
    {
        if (!$this->isSymmetric()) {
            return false;
        }

        if (!$this->hasColumn($name)) {
            return false;
        }

        if (!function_exists($callback)) {
            return false;
        }

        $column = $this->getColumn($name);
        foreach ($column as $key => $cell) {
            $column[$key] = $callback($cell);
        }
        return $this->fillColumn($name, $column);
    }

    /**
     * cell fetcher
     *
     * gets the value of a specific cell by given coordinates     
     *
     * @param integer $x the row to fetch
     * @param integer $y the column to fetch
     *
     * @access public
     * @return mixed|false the value of the cell or false if the cell does
     * not exist
     * @see getHeaders(), hasCell(), getRow(), getRows(), getColumn()
     */
    public function getCell($x, $y)
    {
        if ($this->hasCell($x, $y)) {
            $row = $this->getRow($x);
            return $row[$y];
        }
        return false;
    }

    /**
     * cell value filler
     *
     * replaces the value of a specific cell      
     *
     * @param integer $x     the row to fetch
     * @param integer $y     the column to fetch
     * @param mixed   $value the value to fill the cell with
     *
     * @access public
     * @return boolean
     * @see hasCell(), getRow(), getRows(), getColumn()
     */
    public function fillCell($x, $y, $value)
    {
        if (!$this->hasCell($x, $y)) {
            return false;
        }
        $row            = $this->getRow($x);
        $row[$y]        = $value;
        $this->rows[$x] = $row;
        return true;
    }

    /**
     * checks if a coordinate is valid   
     *
     * @param mixed $x the row to fetch
     * @param mixed $y the column to fetch
     *
     * @access public
     * @return void
     */
    public function hasCell($x, $y)
    {
        $has_x = array_key_exists($x, $this->rows);
        $has_y = array_key_exists($y, $this->headers);
        return ($has_x && $has_y);
    }

    /**
     * row fetcher
     *
     * Note: first row is zero       
     *
     * @param integer $number the row number to fetch
     *
     * @access public
     * @return array the row identified by number, if $number does
     * not exist an empty array is returned instead
     */
    public function getRow($number)
    {
        $raw = $this->rows;
        if (array_key_exists($number, $raw)) {
            return $raw[$number];
        }
        return array();
    }

    /**
     * multiple row fetcher
     *
     * Extracts a rows in the following fashion
     *   - all rows if no $range argument is given
     *   - a range of rows identified by their key
     *   - if rows in range are not found nothing is retrived instead
     *   - if no rows were found an empty array is returned  
     *
     * @param array $range a list of rows to retrive
     *
     * @access public
     * @return array
     */
    public function getRows($range = array())
    {
        if (is_array($range) && ($range === array())) {
            return $this->rows;
        }

        if (!is_array($range)) {
            return $this->rows;
        }

        $ret_arr = array();
        foreach ($this->rows as $key => $row) {
            if (in_array($key, $range)) {
                $ret_arr[] = $row;
            }
        }
        return $ret_arr;
    }

    /**
     * row counter
     *
     * This function will exclude the headers
     *
     * @access public
     * @return integer
     */
    public function countRows()
    {
        return count($this->rows);
    }

    /**
     * row appender
     *
     * Aggregates one more row to the currently loaded dataset
     *
     * @param array $values the values to be appended to the row
     *
     * @access public
     * @return boolean
     */
    public function appendRow($values)
    {
        $this->rows[] = array();
        $this->symmetrize();
        return $this->fillRow($this->countRows() - 1, $values);
    }

    /**
     * fillRow
     *
     * Replaces the contents of cells in one given row with $values.       
     *
     * @param integer $row    the row to fill identified by its key
     * @param mixed   $values the value to use, if a string or number
     * is given the whole row will be replaced with this value.
     * if an array is given instead the values will be used to fill
     * the row. Only when the currently loaded dataset is symmetric
     *
     * @access public
     * @return boolean
     * @see isSymmetric(), getAsymmetricRows(), symmetrize(), fillColumn(),
     * fillCell(), appendRow()
     */
    public function fillRow($row, $values)
    {
        if (!$this->hasRow($row)) {
            return false;
        }

        if (is_string($values) || is_numeric($values)) {
            foreach ($this->rows[$row] as $key => $cell) {
                 $this->rows[$row][$key] = $values;
            }
            return true;
        }

        $eql_to_headers = ($this->countHeaders() == count($values));
        if (is_array($values) && $this->isSymmetric() && $eql_to_headers) {
            $this->rows[$row] = $values;
            return true;
        }

        return false;
    }

    /**
     * row existance checker
     *
     * Scans currently loaded dataset and
     * checks if a given row identified by $number exists       
     *
     * @param mixed $number a numeric value that identifies the row
     * you are trying to fetch.
     *
     * @access public
     * @return boolean
     * @see getRow(), getRows(), appendRow(), fillRow()
     */
    public function hasRow($number)
    {
        return (in_array($number, array_keys($this->rows)));
    }

    /**
     * row remover
     *
     * removes one row from the current data set.
     *  
     *
     * @param mixed $number the key that identifies that row
     *
     * @access public
     * @return boolean
     * @see hasColumn(), getHeaders(), createHeaders(), setHeaders(),
     * isSymmetric(), getAsymmetricRows()
     */
    public function removeRow($number)
    {
        $cnt = $this->countRows();
        $row = $this->getRow($number);
        if (is_array($row) && ($row != array())) {
            unset($this->rows[$number]);
        } else {
            return false;
        }
        $this->resetKeys($this->rows);
        return ($cnt == ($this->countRows() + 1));
    }

    /**
     * row walker
     *
     * goes through one full row of data and executes a callback
     * function per each cell in that row.
     *
     * Note: callback functions get the value of the cell as an
     * argument, and whatever that callback returns will be used to
     * replace the current value of that cell.
     *
     * @param string|integer $row      anything that is numeric is a valid row
     * identificator. As long as it is within the range of the currently
     * loaded dataset
     *
     * @param string         $callback the callback function to be executed
     * per each cell in a row
     *
     * @access public
     * @return boolean
     *  - false if callback does not exist
     *  - false if row does not exits
     */
    public function walkRow($row, $callback)
    {
        if (!function_exists($callback)) {
            return false;
        }
        if ($this->hasRow($row)) {
            foreach ($this->getRow($row) as $key => $value) {
                $this->rows[$row][$key] = $callback($value);
            }
            return true;
        }
        return false;
    }

    /**
     * raw data as array
     *
     * Gets the data that was retrived from the csv file as an array
     *
     * Note: that changes and alterations made to rows, columns and
     * values will also reflect on what this function retrives.
     *
     * @access public
     * @return array
     * @see connect(), getHeaders(), getRows(), isSymmetric(), getAsymmetricRows(),
     * symmetrize()
     */
    public function getRawArray()
    {
        $ret_arr   = array();
        
        foreach ($this->rows as $key => $row) {
            $item = array();
            foreach ($this->headers as $col => $value) {
                $item[$value] = $this->fixEncoding($row[$col]);
            }
            array_push($ret_arr, $item);
            unset($item);
        }
        return $ret_arr;
    }    

    // Fixes the encoding to uf8
    function fixEncoding($in_str)
    {
        if (function_exists('mb_detect_encoding') and function_exists('mb_check_encoding')){
          $cur_encoding = mb_detect_encoding($in_str) ;
          if($cur_encoding == "UTF-8" && mb_check_encoding($in_str,"UTF-8"))
            return $in_str;
          else
            return utf8_encode($in_str);
        }

        return $in_str;        

        
    } // fixEncoding 

    /**
     * header creator
     *
     * uses prefix and creates a header for each column suffixed by a
     * numeric value  
     *
     * @param string $prefix string to use as prefix for each
     * independent header
     *
     * @access public
     * @return boolean fails if data is not symmetric
     * @see isSymmetric(), getAsymmetricRows()
     */
    public function createHeaders($prefix)
    {
        if (!$this->isSymmetric()) {
            return false;
        }

        $length = count($this->headers) + 1;
        $this->moveHeadersToRows();

        $ret_arr = array();
        for ($i = 1; $i < $length; $i ++) {
            $ret_arr[] = $prefix . "_$i";
        }
        $this->headers = $ret_arr;
        return $this->isSymmetric();
    }

    /**
     * header injector
     *
     * uses a $list of values which wil be used to replace current
     * headers.
     *
     *
     * @param array $list a collection of names to use as headers,
     *
     * @access public
     * @return boolean fails if data is not symmetric
     * @see isSymmetric(), getAsymmetricRows(), getHeaders(), createHeaders()
     */
    public function setHeaders($list)
    {
        if (!$this->isSymmetric()) {
            return false;
        }
        if (!is_array($list)) {
            return false;
        }
        if (count($list) != count($this->headers)) {
            return false;
        }
        $this->moveHeadersToRows();
        $this->headers = $list;
        return true;
    }

    /**
     * csv parser
     *
     * reads csv data and transforms it into php-data
     *
     * @access protected
     * @return boolean
     */
    protected function parse()
    {
        if (!$this->validates()) {            
            return false;
        }

        $c = 0;
        $d = ( "" != $this->delimiter ) ? $this->delimiter : $this->settings['delimiter'];
        $e = $this->settings['escape'];
        $l = $this->settings['length'];       

        PMXI_Plugin::$is_csv = $d;         

        $res = fopen($this->_filename, 'rb');
        
        $wp_uploads = wp_upload_dir();
        if ($this->large_import){
            $tmpname = wp_unique_filename($wp_uploads['path'], str_replace("csv", "xml", basename($this->_filename)));
            if ('' == $this->xml_path) $this->xml_path = $wp_uploads['path']  .'/'. url_title($tmpname);
            $fp = fopen($this->xml_path, 'w');
            fwrite($fp, "<?xml version=\"1.0\" encoding=\"".$this->csv_encoding."\"?><data>");
        }
        $create_new_headers = false;
        
        $legacy_special_character_handling = PMXI_Plugin::getInstance()->getOption('legacy_special_character_handling');

        while ($keys = fgetcsv($res, $l, $d, $e)) {

            if ($c == 0) {
                $buf_keys = $keys;
                foreach ($keys as $key => $value) {    
                    if (!$create_new_headers and (preg_match('%\W(http:|https:|ftp:)$%i', $value) or is_numeric($value))) $create_new_headers = true;                                                                    
                    $value = trim(strtolower(preg_replace('/^[0-9]{1}/','el_', preg_replace('/[^a-z0-9_]/i', '', $value))));                    
                    $keys[$key] = (!empty($value)) ? $value : 'undefined'.$key;                    
                }            
                $this->headers = $keys;                                
                if ($create_new_headers){ 
                    $this->createHeaders('column');      
                    $keys = $buf_keys;
                }
            } 

            if ($c or $create_new_headers) {                                            
               if (!empty($keys)){                                   

                    $chunk = array();
                    
                    foreach ($this->headers as $key => $header) {                                                
                        if ($this->auto_encoding)
                            $chunk[$header] = $this->fixEncoding( ($legacy_special_character_handling) ? htmlspecialchars($keys[$key], ENT_COMPAT, $this->csv_encoding) : $keys[$key] );                            
                        else
                            $chunk[$header] = ($legacy_special_character_handling) ? htmlspecialchars($keys[$key], ENT_COMPAT, $this->csv_encoding) : $keys[$key];
                    }

                    if (!empty($chunk))
                    {                                                                
                        fwrite($fp, "<node>"); 
                        foreach ($chunk as $header => $value) fwrite($fp, "<".$header.">".$value."</".$header.">");                                                                        
                        fwrite($fp, "</node>");
                    }                                        
                }
            }

            $c ++;
        }
        fwrite($fp, '</data>');
        fclose($fp);
        fclose($res);
        $this->removeEmpty();             

        return true;       
    }

    /**
     * empty row remover
     *
     * removes all records that have been defined but have no data.
     *
     * @access protected
     * @return array containing only the rows that have data
     */
    protected function removeEmpty()
    {
        $ret_arr = array();
        foreach ($this->rows as $row) {
            $line = trim(join('', $row));
            if (!empty($line)) {
                $ret_arr[] = $row;
            }
        }
        $this->rows = $ret_arr;
    }

    /**
     * csv file validator
     *
     * checks wheather if the given csv file is valid or not
     *
     * @access protected
     * @return boolean
     */
    protected function validates()
    {        
        // file existance
        if (!file_exists($this->_filename)) {
            return false;
        }

        // file readability
        if (!is_readable($this->_filename)) {
            return false;
        }

        return true;
    }

    /**
     * header relocator
     *
     * @access protected
     * @return void
     */
    protected function moveHeadersToRows()
    {
        $arr   = array();
        $arr[] = $this->headers;
        foreach ($this->rows as $row) {
            $arr[] = $row;
        }
        $this->rows    = $arr;
        $this->headers = array();
    }

    /**
     * array key reseter
     *
     * makes sure that an array's keys are setted in a correct numerical order
     *
     * Note: that this function does not return anything, all changes
     * are made to the original array as a reference
     *
     * @param array &$array any array, if keys are strings they will
     * be replaced with numeric values
     *
     * @access protected
     * @return void
     */
    protected function resetKeys(&$array)
    {
        $arr = array();
        foreach ($array as $item) {
            $arr[] = $item;
        }
        $array = $arr;
    }

    /**
     * object data flusher
     *
     * tells this object to forget all data loaded and start from
     * scratch
     *
     * @access protected
     * @return void
     */
    protected function flush()
    {
        $this->rows    = array();
        $this->headers = array();
    }

    public function toXml() {          
        $this->symmetrize();     
        return ArrayToXML::toXml($this->getRawArray());
    }

    function analyse_file($file, $capture_limit_in_kb = 10) {
        // capture starting memory usage
        $output['peak_mem']['start']    = memory_get_peak_usage(true);

        // log the limit how much of the file was sampled (in Kb)
        $output['read_kb']                 = $capture_limit_in_kb;
       
        // read in file
        $fh = fopen($file, 'r');
            $contents = fgets($fh);
        fclose($fh);
       
        // specify allowed field delimiters
        $delimiters = array(
            'comma'     => ',',
            'semicolon' => ';',            
            'pipe'         => '|',
            'tabulation' => "\t"
        );
       
        // specify allowed line endings
        $line_endings = array(
            'rn'         => "\r\n",
            'n'         => "\n",
            'r'         => "\r",
            'nr'         => "\n\r"
        );
       
        // loop and count each line ending instance
        foreach ($line_endings as $key => $value) {
            $line_result[$key] = substr_count($contents, $value);
        }
       
        // sort by largest array value
        asort($line_result);
       
        // log to output array
        $output['line_ending']['results']   = $line_result;
        $output['line_ending']['count']     = end($line_result);
        $output['line_ending']['key']       = key($line_result);
        $output['line_ending']['value']     = $line_endings[$output['line_ending']['key']];
        $lines = explode($output['line_ending']['value'], $contents);
       
        // remove last line of array, as this maybe incomplete?
        array_pop($lines);
       
        // create a string from the legal lines
        $complete_lines = implode(' ', $lines);
       
        // log statistics to output array
        $output['lines']['count']     = count($lines);
        $output['lines']['length']     = strlen($complete_lines);
       
        // loop and count each delimiter instance
        foreach ($delimiters as $delimiter_key => $delimiter) {
            $delimiter_result[$delimiter_key] = substr_count($complete_lines, $delimiter);
        }
       
        // sort by largest array value
        asort($delimiter_result);
       
        // log statistics to output array with largest counts as the value
        $output['delimiter']['results']     = $delimiter_result;
        $output['delimiter']['count']         = end($delimiter_result);
        $output['delimiter']['key']         = key($delimiter_result);
        $output['delimiter']['value']         = $delimiters[$output['delimiter']['key']];
       
        // capture ending memory usage
        $output['peak_mem']['end'] = memory_get_peak_usage(true);
        return $output;
    }

}

class ArrayToXML
    {
    /**
    * The main function for converting to an XML document.
    * Pass in a multi dimensional array and this recrusively loops through and builds up an XML document.
    *
    * @param array $data
    * @param string $rootNodeName - what you want the root node to be - defaultsto data.
    * @param SimpleXMLElement $xml - should only be used recursively
    * @return string XML
    */
    public static function toXml($data, $rootNodeName = 'data', $xml=null)
    {                
        // turn off compatibility mode as simple xml throws a wobbly if you don't.
        if (ini_get('zend.ze1_compatibility_mode') == 1)
        {
            ini_set ('zend.ze1_compatibility_mode', 0);
        }
     
        if ($xml == null)
        {
            $xml = simplexml_load_string('<?xml version="1.0" encoding="utf-8"?><'.$rootNodeName .'/>');
        }
     
        // loop through the data passed in.
        foreach($data as $key => $value)
        {
            // no numeric keys in our xml please!
            if (is_numeric($key))
            {
                // make string key...
                $key = "node";
            }
            
            // replace anything not alpha numeric
            $key = preg_replace('/[^a-z0-9_]/i', '', $key);
             
            // if there is another array found recrusively call this function
            if (is_array($value) or is_object($value))
            {
                $node = $xml->addChild($key);
                // recrusive call.
                ArrayToXML::toXml($value, $rootNodeName, $node);
            }
            else
            {                
                // add single node.
                $value =  htmlspecialchars($value);
                $xml->addChild($key,$value);
            }
            
        }
        // pass back as string. or simple xml object if you want!
        return $xml->asXML();
    }


}

?>

<?php
/**
 * Introduce special type for controllers which render pages inside admin area
 * 
 * @author Pavel Kulbakin <p.kulbakin@gmail.com>
 */
abstract class PMXI_Controller_Admin extends PMXI_Controller {
	/**
	 * Admin page base url (request url without all get parameters but `page`)
	 * @var string
	 */
	public $baseUrl;
	/**
	 * Parameters which is left when baseUrl is detected
	 * @var array
	 */
	public $baseUrlParamNames = array('page', 'pagenum', 'order', 'order_by', 'type', 's', 'f');
	/**
	 * Whether controller is rendered inside wordpress page
	 * @var bool
	 */
	public $isInline = false;
	/**
	 * Constructor
	 */
	public function __construct() {
		$remove = array_diff(array_keys($_GET), $this->baseUrlParamNames);
		if ($remove) {
			$this->baseUrl = remove_query_arg($remove);
		} else {
			$this->baseUrl = $_SERVER['REQUEST_URI'];
		}
		parent::__construct();
		
		// add special filter for url fields
		$this->input->addFilter(create_function('$str', 'return "http://" == $str || "ftp://" == $str ? "" : $str;'));
		
		// enqueue required sripts and styles
		global $wp_styles;
		if ( ! is_a($wp_styles, 'WP_Styles'))
			$wp_styles = new WP_Styles();
		
		wp_enqueue_style('jquery-ui', PMXI_ROOT_URL . '/static/js/jquery/css/redmond/jquery-ui.css');
		wp_enqueue_style('jquery-tipsy', PMXI_ROOT_URL . '/static/js/jquery/css/smoothness/jquery.tipsy.css');
		wp_enqueue_style('pmxi-admin-style', PMXI_ROOT_URL . '/static/css/admin.css');
		wp_enqueue_style('pmxi-admin-style-ie', PMXI_ROOT_URL . '/static/css/admin-ie.css');		
		wp_enqueue_style('jquery-select2', PMXI_ROOT_URL . '/static/js/jquery/css/select2/select2.css');
		wp_enqueue_style('jquery-select2', PMXI_ROOT_URL . '/static/js/jquery/css/select2/select2-bootstrap.css');
		$wp_styles->add_data('pmxi-admin-style-ie', 'conditional', 'lte IE 7');
		wp_enqueue_style('wp-pointer');		
		
		if ( version_compare(get_bloginfo('version'), '3.8-RC1') >= 0 ){			
			wp_enqueue_style('pmxi-admin-style-wp-3.8', PMXI_ROOT_URL . '/static/css/admin-wp-3.8.css');
		}

		$scheme_color = get_user_option('admin_color') and is_file(PMXI_Plugin::ROOT_DIR . '/static/css/admin-colors-' . $scheme_color . '.css') or $scheme_color = 'fresh';
		if (is_file(PMXI_Plugin::ROOT_DIR . '/static/css/admin-colors-' . $scheme_color . '.css')) {
			wp_enqueue_style('pmxi-admin-style-color', PMXI_ROOT_URL . '/static/css/admin-colors-' . $scheme_color . '.css');
		}
	
		wp_enqueue_script('jquery-ui-datepicker', PMXI_ROOT_URL . '/static/js/jquery/ui.datepicker.js', 'jquery-ui-core');
		wp_enqueue_script('jquery-ui-autocomplete', PMXI_ROOT_URL . '/static/js/jquery/ui.autocomplete.js', array('jquery-ui-core', 'jquery-ui-widget', 'jquery-ui-position'));
		wp_enqueue_script('jquery-tipsy', PMXI_ROOT_URL . '/static/js/jquery/jquery.tipsy.js', 'jquery');
		wp_enqueue_script('jquery-nestable', PMXI_ROOT_URL . '/static/js/jquery/jquery.mjs.nestedSortable.js', array('jquery', 'jquery-ui-dialog', 'jquery-ui-sortable', 'jquery-ui-draggable', 'jquery-ui-droppable', 'jquery-ui-tabs', 'jquery-ui-progressbar'));
		wp_enqueue_script('jquery-moment', PMXI_ROOT_URL . '/static/js/jquery/moment.js', 'jquery');		
		wp_enqueue_script('jquery-select2', PMXI_ROOT_URL . '/static/js/jquery/select2.min.js', 'jquery');
		wp_enqueue_script('wp-pointer');

		/* load plupload scripts */
		wp_deregister_script('swfupload-all');
		wp_deregister_script('swfupload-handlers');
		wp_enqueue_script('swfupload-handlers', get_option('siteurl') . "/wp-includes/js/swfupload/handlers.js", array('jquery'), '2201-20100523');

		wp_enqueue_script('jquery-browserplus-min', 'http://bp.yahooapis.com/2.4.21/browserplus-min.js', array('jquery'));
		wp_enqueue_script('full-plupload', PMXI_ROOT_URL . '/static/js/plupload/plupload.full.js', array('jquery-browserplus-min'));
		wp_enqueue_script('jquery-plupload', PMXI_ROOT_URL . '/static/js/plupload/wplupload.js', array('full-plupload', 'jquery'));		

		wp_enqueue_script('pmxi-admin-script', PMXI_ROOT_URL . '/static/js/admin.js', array('jquery', 'jquery-ui-dialog', 'jquery-ui-datepicker', 'jquery-ui-draggable', 'jquery-ui-droppable'));
				
	}	
	
	/**
	 * @see Controller::render()
	 */
	protected function render($viewPath = NULL)
	{
		// assume template file name depending on calling function
		if (is_null($viewPath)) {
			$trace = debug_backtrace();
			$viewPath = str_replace('_', '/', preg_replace('%^' . preg_quote(PMXI_Plugin::PREFIX, '%') . '%', '', strtolower($trace[1]['class']))) . '/' . $trace[1]['function'];
		}
		
		// render contextual help automatically
		$viewHelpPath = $viewPath;
		// append file extension if not specified
		if ( ! preg_match('%\.php$%', $viewHelpPath)) {
			$viewHelpPath .= '.php';
		}
		$viewHelpPath = preg_replace('%\.php$%', '-help.php', $viewHelpPath);
		$fileHelpPath = PMXI_Plugin::ROOT_DIR . '/views/' . $viewHelpPath;
				
		if (is_file($fileHelpPath)) { // there is help file defined
			ob_start();
			include $fileHelpPath;
			add_contextual_help(PMXI_Plugin::getInstance()->getAdminCurrentScreen()->id, ob_get_clean());
		}
		
		parent::render($viewPath);
	}
	
}
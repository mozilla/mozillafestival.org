<?php 
/**
 * Admin Add-ons page
 * 
 * @author Max Tsiplyakov <makstsiplyakov@gmail.com>
 */
class PMXI_Admin_Addons extends PMXI_Controller_Admin {		

	public static $addons = array('PMWI_Plugin' => 0, 'PMAI_Plugin' => 0, 'PMWITabs_Plugin' => 0, 'PMLI_Plugin' => 0); // inactive by default

	public function __construct() {

		parent::__construct();		

	}	

	public function index() {

		$this->data['premium'] = array();
		$this->data['premium']['PMWI_Plugin'] = array(
			'title' => __("WooCommerce Addon",'pmxi_plugin'),
			'description' => __("Import Products from any XML or CSV to WooCommerce",'pmxi_plugin'),
			'thumbnail' => 'http://placehold.it/220x220',
			'active' => (class_exists('PMWI_Plugin') and PMWI_EDITION == 'paid'),
			'free_installed' => (class_exists('PMWI_Plugin') and PMWI_EDITION == 'free'),
			'required_plugins' => false,
			'url' => 'http://www.wpallimport.com/woocommerce-product-import'
		);
		$this->data['premium']['PMAI_Plugin'] = array(
			'title' => __("ACF Addon",'pmxi_plugin'),
			'description' => __("Import to advanced custom fields",'pmxi_plugin'),
			'thumbnail' => 'http://placehold.it/220x220',
			'active' => class_exists('PMAI_Plugin'),
			'free_installed' => (class_exists('PMAI_Plugin') and PMAI_EDITION == 'free'),
			'required_plugins' => array('Advanced Custom Fields' => class_exists('acf')),
			'url' => 'http://www.wpallimport.com'
		);		
		$this->data['premium']['PMLI_Plugin'] = array(
			'title' => __("WPML Addon",'pmxi_plugin'),
			'description' => __("Import to WPML",'pmxi_plugin'),
			'thumbnail' => 'http://placehold.it/220x220',
			'active' => class_exists('PMLI_Plugin'),
			'free_installed' => (class_exists('PMLI_Plugin') and PMLI_EDITION == 'free'),
			'required_plugins' => array('WPML' => class_exists('SitePress')),
			'url' => 'http://www.wpallimport.com'
		);				
		
		$this->data['free'] = array();						
		$this->data['free']['PMWI_Plugin'] = array(
			'title' => __("WooCommerce Addon - free edition",'pmxi_plugin'),
			'description' => __("Import Products from any XML or CSV to WooCommerce",'pmxi_plugin'),
			'thumbnail' => 'http://placehold.it/220x220',
			'active' => (class_exists('PMWI_Plugin') and PMWI_EDITION == 'free'),
			'paid_installed' => (class_exists('PMWI_Plugin') and PMWI_EDITION == 'paid'),
			'required_plugins' => false,
			'url' => 'http://wordpress.org/plugins/woocommerce-xml-csv-product-import'
		);		

		$this->data['free']['PMWITabs_Plugin'] = array(
			'title' => __("WooCommerce Tabs Addon",'pmxi_plugin'),
			'description' => __("Import data to WooCommerce tabs",'pmxi_plugin'),
			'thumbnail' => 'http://placehold.it/220x220',
			'active' => class_exists('PMWITabs_Plugin'),
			'paid_installed' => false,
			'required_plugins' => array('WooCommerce Addon' => class_exists('PMWI_Plugin')),
			'url' => 'http://www.wpallimport.com'
		);

		$this->render();
	}

	protected static function set_addons_status(){
		foreach (self::$addons as $class => $active) 
			self::$addons[$class] = class_exists($class);
	}

	public static function get_all_addons(){
		
		self::set_addons_status();

		return self::$addons;
	}

	public static function get_addon($addon = false){
		
		self::set_addons_status();

		return ($addon) ? self::$addons[$addon] : false;
	}

	public static function get_active_addons(){
		
		self::set_addons_status();
		$active_addons = array();
		foreach (self::$addons as $class => $active) if ($active) $active_addons[] = $class;		

		return $active_addons;
	}
	
}
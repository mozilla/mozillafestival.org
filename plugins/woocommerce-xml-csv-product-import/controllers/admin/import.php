<?php 
/**
 * Import configuration wizard
 * 
 * @author Pavel Kulbakin <p.kulbakin@gmail.com>
 */

class PMWI_Admin_Import extends PMWI_Controller_Admin {		
	
	/**
	 * Step #1: Choose File
	 */
	public function index() {	

		$default = PMWI_Plugin::get_default_import_options();

		$this->data['id'] = $id = $this->input->get('id');

		$this->data['import'] = $import = new PMXI_Import_Record();			
		if ( ! $id or $import->getById($id)->isEmpty()) { // specified import is not found
			$post = $this->input->post(			
				$default			
			);
		}
		else 
			$post = $this->input->post(
				$this->data['import']->options
				+ $default			
			);		

		$this->data['is_loaded_template'] = PMXI_Plugin::$session->data['pmxi_import']['is_loaded_template'];

		$load_options = $this->input->post('load_template');

		if ($load_options) { // init form with template selected
			
			$template = new PMXI_Template_Record();
			if ( ! $template->getById($this->data['is_loaded_template'])->isEmpty()) {	
				$post = (!empty($template->options) ? $template->options : array()) + $default;				
			}
			
		} elseif ($load_options == -1){
			
			$post = $default;
							
		}
		
		$this->data['woo_commerce_attributes'] = $woo_commerce_attributes = new PMXI_Model_List();
		$woo_commerce_attributes->setTable(PMXI_Plugin::getInstance()->getWPPrefix() . 'woocommerce_attribute_taxonomies');
		$woo_commerce_attributes->setColumns('attribute_name', 'attribute_id', 'attribute_label')->getBy(NULL, "attribute_id", NULL, NULL, "attribute_name");				

		$this->data['post'] =& $post;

		$this->render();

	}	
		
}

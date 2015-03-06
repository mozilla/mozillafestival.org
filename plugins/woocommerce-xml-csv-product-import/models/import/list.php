<?php

class PMWI_Import_List extends PMWI_Model_List {
	public function __construct() {
		parent::__construct();
		$this->setTable(PMWI_Plugin::getInstance()->getTablePrefix() . 'imports');
	}
}
<?php
function pmwi_pmxi_extend_options_main($entry){

	if ($entry != 'product') return;

	$woo_controller = new PMWI_Admin_Import();										
	$woo_controller->index();

}
?>
<?php
	
function pmxi_admin_init(){
	wp_enqueue_script('pmxi-script', PMXI_ROOT_URL . '/static/js/pmxi.js', array('jquery'), PMXI_VERSION);     
}
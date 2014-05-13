<?php 

function pmxi_plugins_loaded() {
	
	PMXI_Plugin::$session = PMXI_Session::get_instance();
	do_action( 'pmxi_session_start' );

	return PMXI_Plugin::$session->session_started();
}
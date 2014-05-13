<?php 

function pmxi_wp() {		
	if ( ! wp_next_scheduled( 'wp_session_garbage_collection' ) ) {
		wp_schedule_event( time(), 'twicedaily', 'wp_session_garbage_collection' );
	}
}